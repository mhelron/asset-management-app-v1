<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::with('category')->get();
        $categories = Category::all();

        return view('inventory.index', compact('inventory', 'categories'));
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
            
            // If custom_fields is stored as JSON in the database
            $customFields = is_string($item->custom_fields) 
                ? json_decode($item->custom_fields, true) 
                : $item->custom_fields;
                
            // Transform to the format expected by your modal
            $formattedFields = [];
            if (is_array($customFields)) {
                foreach ($customFields as $name => $value) {
                    $formattedFields[] = [
                        'name' => $name,
                        'type' => is_array($value) ? 'File' : gettype($value),
                        'is_required' => false // Set based on your requirements
                    ];
                }
            }
            
            return response()->json($formattedFields);
        } catch (\Exception $e) {
            Log::error('Error fetching custom fields: ' . $e->getMessage());
            return response()->json([], 404);
        }
    }
    

    public function create() {
        $categories = Category::all();
        return view('inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $customFields = $request->custom_fields ?? [];
            
            if ($request->hasFile('custom_fields_files')) {
                foreach ($request->file('custom_fields_files') as $field => $file) {
                    $path = $file->store('uploads/inventory');
                    $customFields[$field] = ['path' => $path, 'original_name' => $file->getClientOriginalName()];
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
            return redirect()->route('inventory.create')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $inventoryItem = Inventory::with('category')->findOrFail($id);
        $categories = Category::pluck('category', 'id'); // Changed from 'name' to 'category'
        
        return view('inventory.edit', compact('inventoryItem', 'categories', 'id'));
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
