<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function index()
    {
        $accessory = Accessory::all();
        return view('accessory.index', compact('accessory'));
    }

    public function create()
    {
        return view('accessory.create');
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

        Accessory::create($validated);

        return redirect()->route('accessory.index')
            ->with('success', 'Component created successfully.');
    }

    public function show($id)
    {
        $accessory = Accessory::findOrFail($id);

        // Ensure date_purchased is converted to a Carbon instance if it's not already
        if ($accessory->date_purchased && !$accessory->date_purchased instanceof \Carbon\Carbon) {
            $accessory->date_purchased = \Carbon\Carbon::parse($accessory->date_purchased);
        }

        return view('accessory.show', ['accessory' => $accessory]);
    }

    public function edit($id)
    {
        $accessory = Accessory::findOrFail($id);
        
        // If date_purchased is a Carbon object, convert to string
        if ($accessory->date_purchased instanceof \Carbon\Carbon) {
            $accessory->date_purchased = $accessory->date_purchased->format('Y-m-d');
        }

        return view('accessory.edit', ['component' => $accessory]);
    }

    public function update(Request $request, Accessory $accessory)
    {
        $validated = $request->validate([
            'component_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'serial_no' => 'required|unique:components,serial_no,' . $accessory->id,
            'model_no' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'assigned' => 'nullable|string|max:255',
            'date_purchased' => 'required|date',
            'purchased_from' => 'required|string|max:255',
            'log_note' => 'nullable|string'
        ]);

        $accessory->update($validated);

        return redirect()->route('accessory.index')
            ->with('success', 'Component updated successfully');
    }

    public function archive($id)
    {
        $accessory = Accessory::find($id);
        
        if (!$accessory) {
            return back()->with('error', 'Component not found');
        }

        $accessory->delete();

        return redirect()->route('components.index')->with('success', 'Component archived successfully.');
    }
}
