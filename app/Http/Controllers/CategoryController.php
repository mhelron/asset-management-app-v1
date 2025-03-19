<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    

    public function create()
    {
        return view('categories.create');
    }

    public function getCustomFields($id)
    {
        $category = Category::find($id);
        return response()->json($category ? json_decode($category->custom_fields, true) : []);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category' => 'required',
            'desc' => 'required',
        ]);
    
        $customFields = [];
        if ($request->has('field_names') && is_array($request->field_names)) {
            foreach ($request->field_names as $index => $fieldName) {
                if (!empty($fieldName)) {
                    $customFields[] = [
                        'name' => $fieldName,
                        'type' => $request->field_types[$index] ?? 'text',
                        'is_required' => isset($request->field_required[$index]),
                        'order' => $index
                    ];
                }
            }
        }
    
        Category::create([
            'category' => $validatedData['category'],
            'desc' => $validatedData['desc'],
            'status' => 'Active',
            'custom_fields' => json_encode($customFields)
        ]);
    
        return redirect('categories')->with('success', 'Category Added Successfully');
    }

    public function edit($id)
    {
        $editdata = Category::find($id);

        if ($editdata) {
            // Decode the JSON custom fields to an array
            $editdata->custom_fields = json_decode($editdata->custom_fields, true) ?? [];

            return view('categories.edit', compact('editdata'));
        }

        return redirect('categories')->with('error', 'Item ID Not Found');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category' => 'required',
            'desc' => 'required',
            'status' => 'required'
        ]);
    
        $customFields = [];
        if ($request->has('field_names') && is_array($request->field_names)) {
            foreach ($request->field_names as $index => $fieldName) {
                if (!empty($fieldName)) {
                    $customFields[] = [
                        'name' => $fieldName,
                        'type' => $request->field_types[$index] ?? 'text',
                        'is_required' => isset($request->field_required[$index]),
                        'order' => $index
                    ];
                }
            }
        }
    
        $category = Category::find($id);
        if ($category) {
            $category->update([
                'category' => $validatedData['category'],
                'desc' => $validatedData['desc'],
                'status' => $validatedData['status'],
                'custom_fields' => json_encode($customFields)
            ]);
            return redirect('categories')->with('success', 'Category Updated Successfully');
        }
        return redirect('categories')->with('error', 'Category Not Updated');
    }

    public function archive($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete(); // Soft delete
            return redirect()->route('categories.index')->with('success', 'Category Archived Successfully');
        }

        return redirect()->route('categories.index')->with('error', 'Category Not Archived');
    }
}
