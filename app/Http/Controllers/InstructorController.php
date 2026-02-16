<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::all();
        return view('instructors.index', compact('instructors'));
    }

    public function dashboard()
    {
        $bookedFlights = Booking::where('user_id', Auth::id())
            ->with('flight')
            ->get();

        return view('instructor.dashboard', compact('bookedFlights'));
    }


    public function create()
    {
        return view('instructors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:instructors',
            'specialization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('instructors', 'public');
        }

        Instructor::create($validated);

        return redirect()->route('instructors.index')->with('success', 'Instructor added successfully.');
    }

    public function show($id)
    {
        $instructor = Instructor::findOrFail($id); // assuming you have Instructor model
        return view('instructors.show', compact('instructor'));
    }


    public function edit(Instructor $instructor)
    {
        return view('instructors.edit', compact('instructor'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:instructors,license_number,' . $instructor->id,
            'specialization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('instructors', 'public');
        }

        $instructor->update($validated);

        return redirect()->route('instructors.index')->with('success', 'Instructor updated successfully.');
    }

    public function destroy(Instructor $instructor)
    {
        $instructor->delete();
        return redirect()->route('instructors.index')->with('success', 'Instructor deleted successfully.');
    }
}
