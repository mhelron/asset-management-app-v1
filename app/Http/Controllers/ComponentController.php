<?php

namespace App\Http\Controllers;

use App\Models\Components;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function index()
    {
        $components = Components::with(['inventory', 'user'])->get();
        return view('components.index', compact('components'));
    }

    public function create()
    {
        // Get all active inventory items (assets) for the dropdown
        $assets = Inventory::where('status', 'Active')->get();
        
        // Get all users for the assigned dropdown
        $users = User::all();
        
        // Get categories for the dropdown
        $categories = Category::pluck('category', 'id');
        
        return view('components.create', compact('assets', 'users', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'serial_no' => 'required|unique:components,serial_no',
            'model_no' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'users_id' => 'nullable|exists:users,id',
            'date_purchased' => 'required|date',
            'purchased_from' => 'required|string|max:255',
            'log_note' => 'nullable|string',
            'inventory_id' => 'nullable|exists:inventories,id'
        ]);

        Components::create($validated);

        return redirect()->route('components.index')
            ->with('success', 'Component created successfully.');
    }

    public function show($id)
    {
        $component = Components::with(['inventory', 'user'])->findOrFail($id);

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

        // Get all active inventory items (assets) for the dropdown
        $assets = Inventory::where('status', 'Active')->get();
        
        // Get all users for the assigned dropdown
        $users = User::all();
        
        // Get categories for the dropdown
        $categories = Category::pluck('category', 'id');

        return view('components.edit', compact('component', 'assets', 'users', 'categories'));
    }

    public function update(Request $request, Components $component)
    {
        $validated = $request->validate([
            'component_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'serial_no' => 'required|unique:components,serial_no,' . $component->id,
            'model_no' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'users_id' => 'nullable|exists:users,id',
            'date_purchased' => 'required|date',
            'purchased_from' => 'required|string|max:255',
            'log_note' => 'nullable|string',
            'inventory_id' => 'nullable|exists:inventories,id'
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