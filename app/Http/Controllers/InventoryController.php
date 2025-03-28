<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\CustomField;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::with('category')->get();
        $categories = Category::all();
        
        // Fetch custom fields that apply to Assets
        $assetCustomFields = CustomField::whereJsonContains('applies_to', 'Asset')->get();

        return view('inventory.index', compact('inventory', 'categories', 'assetCustomFields'));
    }

    public function getCategoryFields($id)
    {
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }

            $customFields = json_decode($category->custom_fields, true) ?? [];

            return response()->json($customFields);
        } catch (\Exception $e) {
            Log::error('Error fetching category fields: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    

    public function getItemDetails($id)
    {
        try {
            $inventoryItem = Inventory::with('category')->findOrFail($id);

            return response()->json([
                'item_name' => $inventoryItem->item_name,
                'category' => $inventoryItem->category->category ?? 'N/A',
                'status' => $inventoryItem->status,
                'custom_fields' => $inventoryItem->custom_fields ?? [],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching inventory details: ' . $e->getMessage());
            return response()->json(['error' => 'Item not found'], 404);
        }
    }

    public function getCustomFields($id) {
        try {
            $item = Inventory::findOrFail($id);
            
            // Get the custom fields that apply to Asset
            $assetCustomFields = CustomField::whereJsonContains('applies_to', 'Asset')->get();
            
            // If custom_fields is stored as JSON in the database
            $itemCustomFields = is_string($item->custom_fields) 
                ? json_decode($item->custom_fields, true) 
                : $item->custom_fields;
            
            // Format for display
            $formattedFields = [];
            foreach ($assetCustomFields as $field) {
                $fieldName = $field->name;
                $fieldValue = $itemCustomFields[$fieldName] ?? '-';
                
                $formattedFields[] = [
                    'name' => $fieldName,
                    'type' => $field->type,
                    'is_required' => $field->is_required,
                    'value' => is_array($fieldValue) ? 
                        (isset($fieldValue['original_name']) ? $fieldValue['original_name'] : json_encode($fieldValue)) : 
                        $fieldValue
                ];
            }
            
            return response()->json($formattedFields);
        } catch (\Exception $e) {
            Log::error('Error fetching custom fields: ' . $e->getMessage());
            return response()->json([], 404);
        }
    }
        
    public function create() {
        $categories = Category::all();
        
        // Filter custom fields that apply to Asset
        $assetCustomFields = CustomField::whereJsonContains('applies_to', 'Asset')->get();
        
        return view('inventory.create', compact('categories', 'assetCustomFields'));
    }

    public function store(Request $request)
    {
        // Basic validation for main fields
        $request->validateWithBag('inventoryForm', [
            'item_name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        // Fetch the category to get its custom fields
        $category = Category::findOrFail($request->category_id);
        $categoryCustomFields = CustomField::whereJsonContains('applies_to', 'Asset')->get();
    
        // Prepare dynamic validation rules
        $customValidationRules = [];
        $customValidationMessages = [];
    
        foreach ($categoryCustomFields as $field) {
            $fieldName = $field->name;
            $rules = [];
            $messages = [];
    
            // Required validation
            if ($field->is_required) {
                $rules[] = 'required';
                $messages["custom_fields.{$fieldName}.required"] = 
                    "The {$fieldName} field is required.";
            }
    
            // Type-specific validations
            switch ($field->type) {
                case 'Text':
                    // Add specific text type validations
                    switch ($field->text_type) {
                        case 'Email':
                            $rules[] = 'email';
                            $messages["custom_fields.{$fieldName}.email"] = 
                                "The {$fieldName} must be a valid email address.";
                            break;
                        case 'Numeric':
                            $rules[] = 'numeric';
                            $messages["custom_fields.{$fieldName}.numeric"] = 
                                "The {$fieldName} must be a number.";
                            break;
                        case 'Date':
                            $rules[] = 'date';
                            $messages["custom_fields.{$fieldName}.date"] = 
                                "The {$fieldName} must be a valid date.";
                            break;
                        case 'Custom':
                            if ($field->custom_regex) {
                                $rules[] = "regex:{$field->custom_regex}";
                                $messages["custom_fields.{$fieldName}.regex"] = 
                                    "The {$fieldName} format is invalid.";
                            }
                            break;
                    }
                    break;
    
                case 'Select':
                    $options = json_decode($field->options, true);
                    if ($options) {
                        $rules[] = 'in:' . implode(',', $options);
                        $messages["custom_fields.{$fieldName}.in"] = 
                            "The selected {$fieldName} is invalid.";
                    }
                    break;
    
                case 'Checkbox':
                    $rules[] = 'array';
                    $messages["custom_fields.{$fieldName}.array"] = 
                        "The {$fieldName} must be a valid selection.";
                    break;
            }
    
            // Add rules if not empty
            if (!empty($rules)) {
                $customValidationRules["custom_fields.{$fieldName}"] = $rules;
            }
            
            // Add corresponding error messages
            if (!empty($messages)) {
                $customValidationMessages = array_merge($customValidationMessages, $messages);
            }
        }
    
        // Perform dynamic validation
        if (!empty($customValidationRules)) {
            $validator = Validator::make(
                $request->all(), 
                $customValidationRules, 
                $customValidationMessages
            );
    
            // If validation fails, redirect back with errors
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator, 'customFields')
                    ->withInput();
            }
        }
    
        try {
            // Process and save the inventory item
            $customFields = $request->input('custom_fields', []);
            
            // Handle file uploads
            if ($request->hasFile('custom_fields_files')) {
                foreach ($request->file('custom_fields_files') as $field => $file) {
                    $path = $file->store('uploads/inventory');
                    $customFields[$field] = [
                        'path' => $path, 
                        'original_name' => $file->getClientOriginalName()
                    ];
                }
            }
            
            Inventory::create([
                'item_name' => $request->item_name,
                'category_id' => $request->category_id,
                'custom_fields' => $customFields,
                'status' => 'Active',
            ]);
    
            return redirect()->route('inventory.index')->with('success', 'Item added successfully');
        } catch (\Exception $e) {
            Log::error('Error adding inventory: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $inventoryItem = Inventory::with('category')->findOrFail($id);
        $categories = Category::pluck('category', 'id');
        $assetCustomFields = CustomField::whereJsonContains('applies_to', 'Asset')->get();
        
        return view('inventory.edit', compact('inventoryItem', 'categories', 'assetCustomFields', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $inventoryItem = Inventory::findOrFail($id);
            $customFields = $inventoryItem->custom_fields ?? [];

            // Update with new form values
            if ($request->has('custom_fields')) {
                foreach ($request->input('custom_fields') as $key => $value) {
                    $customFields[$key] = $value;
                }
            }

            // Handle file uploads
            if ($request->hasFile('custom_fields_files')) {
                foreach ($request->file('custom_fields_files') as $field => $file) {
                    $path = $file->store('uploads/inventory');
                    $customFields[$field] = [
                        'path' => $path, 
                        'original_name' => $file->getClientOriginalName()
                    ];
                }
            }
            
            // Update the inventory item
            $inventoryItem->update([
                'item_name' => $request->item_name,
                'category_id' => $request->category_id,
                'custom_fields' => $customFields,
            ]);

            return redirect()->route('inventory.index')->with('success', 'Item updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('inventory.edit', $id)->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function archive($id)
    {
        $item = Inventory::find($id);

        if ($item) {
            $item->delete(); // Soft delete
            return redirect()->route('inventory.index')->with('success', 'Inventory item archived successfully.');
        }

        return redirect()->route('inventory.index')->with('error', 'Inventory item not found.');
    }

}
