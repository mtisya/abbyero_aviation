<?php

namespace App\Http\Controllers;

use App\Models\AircraftPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AircraftPartController extends Controller
{
    public function index()
    {
        $parts = AircraftPart::latest()->paginate(10);
        return view('aircraft_parts.index', compact('parts'));
    }
    public function partsSale()
    {
        $parts = AircraftPart::where('status', 'available')->get();

        return view('aircraft_parts.parts_sale', compact('parts'));
    }




    public function create()
    {
        return view('aircraft_parts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number' => 'required|unique:aircraft_parts',
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('aircraft_parts', 'public');
        }

        // Create aircraft part record
        AircraftPart::create($validated);

        return redirect()->route('aircraft_parts.list')
            ->with('success', 'Aircraft part created successfully.');
    }


    public function show(AircraftPart $aircraftPart)
    {
        return view('aircraft_parts.show', compact('aircraftPart'));
    }
    public function list()
    {
        $parts = AircraftPart::paginate(10); // adjust pagination if needed
        return view('aircraft_parts.aircraftdetails', compact('parts'));
    }


    public function edit(AircraftPart $aircraftPart)
    {
        return view('aircraft_parts.edit', compact('aircraftPart'));
    }

    public function update(Request $request, AircraftPart $aircraftPart)
    {
        $validated = $request->validate([
            'part_number' => 'required|unique:aircraft_parts,part_number,' . $aircraftPart->id,
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('aircraft_parts', 'public');
        }

        // Update aircraft part record
        $aircraftPart->update($validated);

        // Redirect to list page
        return redirect()->route('aircraft_parts.list')
            ->with('success', 'Aircraft part updated successfully.');
    }


    public function destroy(AircraftPart $aircraftPart)
    {
        // Delete image if exists
        if ($aircraftPart->image && \Storage::disk('public')->exists($aircraftPart->image)) {
            \Storage::disk('public')->delete($aircraftPart->image);
        }

        // Delete the part
        $aircraftPart->delete();

        // Redirect back to list page
        return redirect()->route('aircraft_parts.list')
            ->with('success', 'Aircraft part deleted successfully.');
    }

}
