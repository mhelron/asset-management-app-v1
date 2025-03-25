<?php

namespace App\Http\Controllers;

use App\Models\Components;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function index()
    {
        $components = Components::all();
        return view('components.index', compact('components'));
    }

    public function create()
    {
        return view('components.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'serial_no' => 'required|unique:components,serial_no',
            'model_no' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'assigned' => 'nullable|string|max:255',
            'date_purchased' => 'required|date',
            'purchased_from' => 'required|string|max:255',
            'log_note' => 'nullable|string'
        ]);

        Components::create($validated);

        return redirect()->route('components.index')
            ->with('success', 'Component created successfully.');
    }

    public function show($id)
    {
        $component = Components::findOrFail($id);

        // Ensure date_purchased is converted to a Carbon instance if it's not already
        if ($component->date_purchased && !$component->date_purchased instanceof \Carbon\Carbon) {
            $component->date_purchased = \Carbon\Carbon::parse($component->date_purchased);
        }

        return view('components.show', ['component' => $component]);
    }

    public function edit($id)
    {
        $component = Components::findOrFail($id);
        
        // If date_purchased is a Carbon object, convert to string
        if ($component->date_purchased instanceof \Carbon\Carbon) {
            $component->date_purchased = $component->date_purchased->format('Y-m-d');
        }

        return view('components.edit', ['component' => $component]);
    }

    public function update(Request $request, Components $component)
    {
        $validated = $request->validate([
            'component_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'serial_no' => 'required|unique:components,serial_no,' . $component->id,
            'model_no' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'assigned' => 'nullable|string|max:255',
            'date_purchased' => 'required|date',
            'purchased_from' => 'required|string|max:255',
            'log_note' => 'nullable|string'
        ]);

        $component->update($validated);

        return redirect()->route('components.index')
            ->with('success', 'Component updated successfully');
    }

    public function archive($id)
    {
        $component = Components::find($id);
        
        if (!$component) {
            return back()->with('error', 'Component not found');
        }

        $component->delete();

        return redirect()->route('components.index')->with('success', 'Component archived successfully.');
    }
}