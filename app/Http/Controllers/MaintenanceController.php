<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;


class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index()
{
    $maintenances = Maintenance::latest()->paginate(10);
    return view('maintenances.index', compact('maintenances'));
}

public function maintenance_show()
{
    $maintenances = Maintenance::latest()->paginate(10);
    return view('maintenances.maintenance', compact('maintenances'));
}


public function maintenance($id)
{
    $user = Auth::user();

    if ($user->role === 'admin') {
        // Admins can view any maintenance record
        $maintenance = Maintenance::findOrFail($id);
    } else {
        // Normal users can only view their own records
        $maintenance = $user->maintenances()->where('id', $id)->firstOrFail();
    }

    return view('user.maintenance_show', compact('maintenance'));
}

public function maintenanceIndex()
{
    $user = Auth::user();

    if ($user->role === 'admin') {
        // Admin sees all records
        $maintenances = Maintenance::all();
    } else {
        // Normal users see only their own
        $maintenances = $user->maintenances;
    }

    return view('maintenances.maintenance', compact('maintenances'));
}




public function create()
{
    return view('maintenances.create');
}

// public function store(Request $request)
// {
//     $request->validate([
//         'aircraft_model' => 'required|string|max:255',
//         'registration_number' => 'required|string|max:100',
//         'manufacturer' => 'nullable|string|max:255',
//         'serial_number' => 'nullable|string|max:255',
//         'engine_type' => 'nullable|string|max:255',
//         'last_maintenance_hours' => 'nullable|numeric',
//         'maintenance_date' => 'required|date',
//         'next_due_date' => 'nullable|date',
//         'issue_description' => 'required|string',
//         'remarks' => 'nullable|string',
//     ]);

//     Maintenance::create($request->all());

//     return redirect()
//         ->route('maintenances.maintenance_show')
//         ->with('success', 'Maintenance record added.');
// }

public function store(Request $request)
{
    $request->validate([
        'aircraft_model' => 'required|string|max:255',
        'registration_number' => 'required|string|max:100',
        'maintenance_date' => 'required|date',
        'issue_description' => 'required|string',
        'status' => 'nullable|string',
    ]);

    Maintenance::create([
        'user_id' => Auth::id(),
        'aircraft_model' => $request->aircraft_model,
        'registration_number' => $request->registration_number,
        'manufacturer' => $request->manufacturer,
        'serial_number' => $request->serial_number,
        'engine_type' => $request->engine_type,
        'last_maintenance_hours' => $request->last_maintenance_hours,
        'maintenance_date' => $request->maintenance_date,
        'next_due_date' => $request->next_due_date,
        'issue_description' => $request->issue_description,
        'remarks' => $request->remarks,
        'status' => $request->status ?? 'Pending',
    ]);

    return redirect()
        ->route('maintenances.maintenance_show')
        ->with('success', 'Maintenance record added.');
}


public function edit($id)
{
    $maintenance = Auth::user()
        ->maintenances()
        ->where('id', $id)
        ->firstOrFail();

    return view('maintenances.edit', compact('maintenance'));
}


public function update(Request $request, Maintenance $maintenance)
{
    $request->validate([
        'status' => 'required|string',
        'remarks' => 'nullable|string',
    ]);

    $maintenance->update($request->all());
    return redirect()->route('maintenances.maintenance_show')->with('success', 'Maintenance updated.');
}

public function show($id)
{
    $maintenance = Auth::user()
        ->maintenances()
        ->where('id', $id)
        ->firstOrFail();

    return view('maintenances.show', compact('maintenance'));
}


public function destroy(Maintenance $maintenance)
{
    $maintenance->delete();
    return redirect()->route('maintenances.maintenance_show')->with('success', 'Maintenance record deleted.');
}

}
