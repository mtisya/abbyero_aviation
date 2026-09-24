<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\InstructorRequest;
use App\Notifications\InstructorRequestApprovedNotification;
use App\Models\Student;
use App\Models\Logbook;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::all();
        return view('instructors.index', compact('instructors'));
    }

    public function acceptRequest1(Request $request, $id)
    {
        // If coming from email → validate signature
        if ($request->has('signature') && !$request->hasValidSignature()) {
            abort(403, 'Invalid or expired link');
        }

        $req = InstructorRequest::findOrFail($id);

        if ($req->status !== 'pending') {
            return "Already processed";
        }

        $req->update(['status' => 'accepted']);

        Student::where('id', $req->student_id)
            ->update(['instructor_id' => $req->instructor_id]);


        $request->update([
            'status' => 'approved'
        ]);

        $studentUser = $request->student->user;

        if ($studentUser) {
            $studentUser->notify(new InstructorRequestApprovedNotification($request));
        }

        return redirect('/instructor/dashboard')->with('success', 'Request accepted');
    }

    public function rejectRequest1($id)
    {
        $req = InstructorRequest::findOrFail($id);

        $req->update(['status' => 'rejected']);

        return "Request rejected";
    }
    public function acceptRequest($id)
    {
        $req = InstructorRequest::findOrFail($id);

        $req->update(['status' => 'accepted']);

        // 🔥 assign instructor to student
        $student = $req->student;
        $student->update([
            'instructor_id' => auth()->id()
        ]);

        // 🔥 notify student
        $student->user->notify(new InstructorRequestApprovedNotification($req));

        return back()->with('success', 'Request accepted');
    }
    public function rejectRequest($id)
    {
        $req = InstructorRequest::findOrFail($id);

        $req->update(['status' => 'rejected']);

        return back()->with('info', 'Request rejected');
    }
    
    public function showLogbook($id)
    {
        $log = Logbook::with('student.user')->findOrFail($id);

        return view('instructor.logbook', compact('log'));
    }

    public function dashboard()
    {
        $instructorId = auth()->id();

        // Students assigned to this instructor
        $students = Student::with('user')
            ->where('instructor_id', $instructorId)
            ->get();
        $bookedFlights = Booking::where('user_id', Auth::id())
            ->with('flight')
            ->get();

        // Pending requests
        $requests = InstructorRequest::with('student.user')
            ->where('instructor_id', $instructorId)
            ->where('status', 'pending')
            ->get();

        return view('instructor.dashboard', compact('students', 'requests', 'bookedFlights'));
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
