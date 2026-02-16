<?php

namespace App\Http\Controllers;

use App\Models\Glider;
use Illuminate\Http\Request;

class GliderController extends Controller
{
    public function index()
    {
        $gliders = Glider::latest()->paginate(10);

        if (auth()->check() && auth()->user()->role === 'admin') {
            // Show admin version
            return view('gliders.adminindex', compact('gliders'));
        }

        // Default for regular users
        return view('gliders.index', compact('gliders'));
    }


    public function create()
    {
        return view('gliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'registration' => 'required|string|unique:gliders',
            'capacity' => 'required|integer|min:1',
            'rental_price' => 'required|numeric',
            'status' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gliders', 'public');
        }

        Glider::create($validated);

        return redirect()->route('gliders.index')->with('success', 'Glider added successfully.');
    }

    public function show(Glider $glider)
    {
        return view('gliders.show', compact('glider'));
    }

    public function edit(Glider $glider)
    {
        return view('gliders.edit', compact('glider'));
    }

    public function update(Request $request, Glider $glider)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'registration' => 'required|string|unique:gliders,registration,' . $glider->id,
            'capacity' => 'required|integer|min:1',
            'rental_price' => 'required|numeric',
            'status' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gliders', 'public');
        }

        $glider->update($validated);

        return redirect()->route('gliders.index')->with('success', 'Glider updated successfully.');
    }

    public function destroy(Glider $glider)
    {
        $glider->delete();
        return redirect()->route('gliders.index')->with('success', 'Glider deleted successfully.');
    }
}
