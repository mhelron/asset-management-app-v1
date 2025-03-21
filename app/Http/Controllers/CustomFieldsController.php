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
            'type' => 'required|string|in:text,list,checkbox,radio,select',
            'text_type' => 'nullable|string|in:text,email,number,image,password,date',
            'is_required' => 'required|in:0,1',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        // Only store options if the field type requires it
        $options = in_array($request->type, ['list', 'checkbox', 'radio', 'select']) 
            ? json_encode(array_filter($request->options)) 
            : null;

        CustomField::create([
            'name' => $request->name,
            'type' => $request->type,
            'text_type' => $request->text_type,
            'is_required' => $request->is_required,
            'options' => $options,
        ]);

        return redirect()->route('customfields.index')->with('success', 'Custom Field added successfully!');
    }


    /**
     * Show the form for editing the specified custom field.
     */
    public function edit(CustomField $customField)
    {
        return view('customfields.edit', compact('customField'));
    }

    /**
     * Update the specified custom field in storage.
     */
    public function update(Request $request, CustomField $customField)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,list,checkbox,radio,select',
            'text_type' => 'nullable|string|in:text,email,number,image,password,date',
            'is_required' => 'required|boolean',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
        ]);

        // Convert options to JSON if applicable
        $options = in_array($request->type, ['list', 'checkbox', 'radio', 'select']) ? json_encode($request->options) : null;

        $customField->update([
            'name' => $request->name,
            'type' => $request->type,
            'text_type' => $request->text_type,
            'is_required' => $request->is_required,
            'options' => $options,
        ]);

        return redirect()->route('customfields.index')->with('success', 'Custom Field updated successfully!');
    }

    /**
     * Remove the specified custom field from storage.
     */
    public function destroy(CustomField $customField)
    {
        $customField->delete();
        return redirect()->route('customfields.index')->with('success', 'Custom Field deleted successfully!');
    }
}
