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
            'is_required' => 'required|boolean',
            'applies_to' => 'required|array',
            'desc' => 'required|string',
            'type' => 'required|string|in:Text,List,Checkbox,Radio,Select',
            'text_type' => 'required_if:type,Text|string|in:Text,Email,Number,Image,Password,Date',
            'options' => 'required_if:type,List,Checkbox,Radio,Select|array|min:1',
            'options.*' => 'required|max:255|distinct', // Ensure no duplicates
        ],[
            'text_type.required_if' => 'The text input type is required.',
            'text_type.in' => 'Invalid text type selected. Choose from Text, Email, Number, Image, Password, or Date.',
            'options.required_if' => 'You must add at least one option for List, Checkbox, Radio, or Select fields.',
            'options.*.required' => 'The option field is required.',
            'options.*.distinct' => 'Each option must be unique.',
            'options.*.max' => 'Each option cannot exceed 255 characters.',
        ]);

        $options = in_array($request->type, ['List', 'Checkbox', 'Radio', 'Select']) 
            ? json_encode(array_filter($request->options)) 
            : null;

        CustomField::create([
            'name' => $request->name,
            'type' => $request->type,
            'desc' => $request->desc,
            'text_type' => $request->text_type,
            'is_required' => $request->is_required,
            'options' => in_array($request->type, ['List', 'Checkbox', 'Radio', 'Select']) 
                ? json_encode(array_filter($request->options)) 
                : null,
            'applies_to' => $request->applies_to,
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
            'applies_to' => 'required|array|min:1', // Require at least one selection
            'applies_to.*' => 'in:Category,Asset,Accessory,Component',
        ]);

        $options = in_array($request->type, ['List', 'Checkbox', 'Radio', 'Select']) ? json_encode($request->options) : null;

        $customField->update([
            'name' => $request->name,
            'type' => $request->type,
            'desc' => $request->desc,
            'text_type' => $request->text_type,
            'is_required' => $request->is_required,
            'options' => $options,
            'applies_to' => $request->applies_to,
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
