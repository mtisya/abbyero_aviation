<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\FlightSchedule;
use Carbon\Carbon;



class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flights = Flight::all();
        return view('flights.index', compact('flights'));
    }
    public function schedule()
    {
        $flights = Flight::all();
        return view('flights.schedule', compact('flights'));
    }

public function calendar(){
    $flights = Flight::all();
    return view('flights.calendar', compact('flights'));
}



/* Aircraft rows */
public function aircraftResources()
{
    $flights = Flight::all();
    return response()->json(
        $flights->map(fn($f) => [
            'id' => $f->id,
            'title' => $f->registration_number
        ])
    );
}



/* Scheduled blocks */
public function calendarEvents()
{
    $schedules = FlightSchedule::with('flight')->get();

    return response()->json(
        $schedules->map(fn($s) => [
            'id' => $s->id,
            'resourceId' => $s->flight_id,
            'title' => $s->flight->registration_number,
            // Convert to ISO 8601 format for FullCalendar
            'start' => Carbon::parse($s->start_time)->toIso8601String(),
            'end' => Carbon::parse($s->end_time)->toIso8601String(),
            'color' => match($s->status) {
                'scheduled' => '#0d6efd',
                'completed' => '#198754',
                'cancelled' => '#dc3545',
                default => '#6c757d'
            }
        ])
    );
}



/* Drag Update */
public function updateFromCalendar(Request $request,$id){

$schedule = FlightSchedule::findOrFail($id);

/* CONFLICT CHECK */
$conflict = FlightSchedule::where('flight_id',$schedule->flight_id)
->where('id','!=',$id)
->where(function($q) use($request){
$q->whereBetween('start_time',[$request->start,$request->end])
->orWhereBetween('end_time',[$request->start,$request->end])
->orWhere(function($q2) use($request){
$q2->where('start_time','<=',$request->start)
->where('end_time','>=',$request->end);
});
})
->exists();

if($conflict){
return response()->json([
'success'=>false,
'message'=>'Time conflict detected'
]);
}

$schedule->update([
'start_time'=>$request->start,
'end_time'=>$request->end
]);

return response()->json(['success'=>true]);
}


    public function schedules()
    {
        $flights = Flight::all();
        $schedules = FlightSchedule::with('flight')->latest()->get();

        return view('flights.schedule-dashboard',
            compact('flights','schedules')
        );
    }

    public function editSchedule($id)
    {
        $schedule = FlightSchedule::findOrFail($id);
        $flights  = Flight::all();

        return view('flights.schedule_edit', compact('schedule','flights'));
    }

    public function updateSchedule(Request $request,$id)
    {
        $request->validate([
            'flight_id'=>'required',
            'start_time'=>'required|date',
            'end_time'=>'required|date|after:start_time'
        ]);

        $schedule = FlightSchedule::findOrFail($id);

        // conflict check
        $conflict = FlightSchedule::where('flight_id',$request->flight_id)
            ->where('id','!=',$id)
            ->where('status','scheduled')
            ->where(function($q) use ($request){
                $q->whereBetween('start_time', [$request->start_time,$request->end_time])
                ->orWhereBetween('end_time', [$request->start_time,$request->end_time])
                ->orWhere(function($q2) use ($request){
                    $q2->where('start_time','<=',$request->start_time)
                    ->where('end_time','>=',$request->end_time);
                });
            })->exists();

        if($conflict){
            return back()->with('error','Time slot already taken.');
        }

        $schedule->update($request->only('flight_id','start_time','end_time'));

        return redirect()->route('flights.schedules')
            ->with('success','Schedule updated.');
    }

    public function cancelSchedule($id)
    {
        $schedule = FlightSchedule::findOrFail($id);
        $schedule->status = 'cancelled';
        $schedule->save();

        return back()->with('success','Schedule cancelled.');
    }



    public function storeSchedule(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Check conflict
        $conflict = FlightSchedule::where('flight_id',$request->flight_id)
            ->where('status','scheduled')
            ->where(function($q) use ($request){
                $q->whereBetween('start_time', [$request->start_time,$request->end_time])
                ->orWhereBetween('end_time', [$request->start_time,$request->end_time])
                ->orWhere(function($q2) use ($request){
                    $q2->where('start_time','<=',$request->start_time)
                        ->where('end_time','>=',$request->end_time);
                });
            })->exists();

        if($conflict){
            return back()->with('error','This flight is already scheduled at that time.');
        }

        FlightSchedule::create($request->all());

        return back()->with('success','Flight scheduled successfully.');
    }


    /**
     * Show the form for creating a new resource.
     */
    // Show form
    public function create()
    {
        $instructors = \App\Models\Instructor::where('active', true)->get();
        return view('flights.create', compact('instructors'));
    }


    // Store flight
    public function store(Request $request)
    {
        $request->validate([
            'aircraft_model' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',
            'departure_location' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_location' => 'required|string|max:255',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,Cancelled,Completed,Available',
            'instructor_id' => 'nullable|exists:instructors,id',
        ]);

        Flight::create($request->all());

        return redirect()->route('flights.available')->with('success', 'Flight created successfully.');
    }

    public function available()
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            // Admin sees all flights
            $flights = Flight::all();
        } else {
            // Users see only available flights
            $flights = Flight::where('status', 'available')->get();
        }

        return view('flights.available', compact('flights'));
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $flight = Flight::findOrFail($id);
        return view('flights.show', compact('flight'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $flight = Flight::findOrFail($id);
        return view('flights.edit', compact('flight'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'aircraft_model' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',
            'departure_location' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_location' => 'required|string|max:255',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,Cancelled,Completed,Available',
            'instructor_id' => 'nullable|exists:instructors,id',
        ]);

        $flight = Flight::findOrFail($id);
        $flight->update($request->all());

        return redirect()->route('flights.available')->with('success', 'Flight updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flight = Flight::findOrFail($id);
        $flight->delete();

        return redirect()->route('flights.available')->with('success', 'Flight deleted successfully.');
    }

}
