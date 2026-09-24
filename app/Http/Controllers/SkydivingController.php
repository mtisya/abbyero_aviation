<?php

// app/Http/Controllers/SkydivingController.php
namespace App\Http\Controllers;

use App\Models\Skydiving;
use Illuminate\Http\Request;

class SkydivingController extends Controller
{
    public function index()
    {
        $skydiving = Skydiving::latest()->paginate(10);

        if (auth()->check() && auth()->user()->role === 'admin') {
            // Show admin version
            return view('skydiving.adminindex', compact('skydiving'));
        }

        // Default for regular users
        return view('skydiving.index', compact('skydiving'));
    }


    public function create()
    {
        return view('skydiving.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable|string',   // ✅ add this
            'price' => 'required|numeric',
            'location' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'available_slots' => 'required|integer',
            'image' => 'nullable|image|max:10048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('skydiving_images', 'public');
        }

        Skydiving::create($validated);

        return redirect()->route('skydiving.index')->with('success', 'Skydiving event created successfully.');
    }


    public function show(Skydiving $skydiving)
    {
        return view('skydiving.show', compact('skydiving'));
    }

    public function edit(Skydiving $skydiving)
    {
        return view('skydiving.edit', compact('skydiving'));
    }

    public function update(Request $request, Skydiving $skydiving)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable|string',   // ✅ add this
            'price' => 'required|numeric',
            'location' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'available_slots' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('skydiving_images', 'public');
        }

        $skydiving->update($validated);

        return redirect()->route('skydiving.index')->with('success', 'Skydiving event updated successfully.');
    }

    public function destroy(Skydiving $skydiving)
    {
        $skydiving->delete();
        return redirect()->route('skydiving.index')->with('success', 'Skydiving event deleted successfully.');
    }
}
