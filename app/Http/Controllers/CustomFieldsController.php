<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldsController extends Controller
{
    /**
     * Display a listing of the custom fields.
     */
    public function index()
    {
        $customFields = CustomField::all(); // Fetch all custom fields
        return view('customfields.index', compact('customFields'));
    }

    /**
     * Show the form for creating a new custom field.
     */
    public function create()
    {
        return view('customfields.create');
    }

    /**
     * Store a newly created custom field in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Text,List,Checkbox,Radio,Select',
            'text_type' => 'nullable|string|in:Text,Email,Number,Image,Password,Date',
            'is_required' => 'required|in:0,1',
            'desc' => 'required|string|max:1000',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        // Only store options if the field type requires it
        $options = in_array($request->type, ['List', 'Checkbox', 'Radio', 'Select']) 
            ? json_encode(array_filter($request->options)) 
            : null;

        CustomField::create([
            'name' => $request->name,
            'type' => $request->type,
            'desc' => $request->desc,
            'text_type' => $request->text_type,
            'is_required' => $request->is_required,
            'options' => $options,
        ]);

        return redirect()->route('customfields.index')->with('success', 'Custom Field added successfully!');
    }


    /**
     * Show the form for editing the specified custom field.
     */
    public function edit($id)
    {
        $customField = CustomField::findOrFail($id);
        return view('customfields.edit', compact('customField'));
    }

    /**
     * Update the specified custom field in storage.
     */
    public function update(Request $request, $id)
    {
        $customField = CustomField::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Text,List,Checkbox,Radio,Select',
            'text_type' => 'nullable|string|in:Text,Email,Number,Image,Password,Date',
            'is_required' => 'required|in:0,1',
            'desc' => 'required|string|max:1000',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        $options = in_array($request->type, ['List', 'Checkbox', 'Radio', 'Select']) ? json_encode($request->options) : null;

        $customField->update([
            'name' => $request->name,
            'type' => $request->type,
            'desc' => $request->desc,
            'text_type' => $request->text_type,
            'is_required' => $request->is_required,
            'options' => $options,
        ]);

        return redirect()->route('customfields.index')->with('success', 'Custom field updated successfully'); 
    }

    /**
     * Remove the specified custom field from storage.
     */
    public function archive($id)
{
    try {
        $customField = CustomField::findOrFail($id);
        
        $customField->delete();
        
        return redirect()->route('customfields.index')->with('success', 'Custom field archived successfully');
    } catch (\Exception $e) {
        return redirect()->route('customfields.index')->with('error', 'Failed to archive custom field: ' . $e->getMessage());
    }
}
}
