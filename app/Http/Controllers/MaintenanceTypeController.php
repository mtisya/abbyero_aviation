<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceType;
use Illuminate\Http\Request;

class MaintenanceTypeController extends Controller
{
    /**
     * Display all maintenance types.
     */
    public function index()
    {
        $types = MaintenanceType::orderBy('name')->paginate(5);

        return view('admin.index_types', compact('types'));
    }

    /**
     * Store a new maintenance type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255|unique:maintenance_types,name',

            'code' => 'nullable|string|max:20|unique:maintenance_types,code',

            'category' => 'nullable|string|max:100',

            'default_interval_hours' => 'nullable|numeric|min:0',

            'default_interval_days' => 'nullable|integer|min:0',

            'description' => 'nullable|string',

            'is_active' => 'required|boolean',

        ]);

        MaintenanceType::create($validated);

        return redirect()
            ->route('maintenance-types.index')
            ->with('success', 'Maintenance type created successfully.');
    }

    /**
     * Show one maintenance type.
     */
    public function show(MaintenanceType $maintenanceType)
    {
        return response()->json($maintenanceType);
    }

    /**
     * Update maintenance type.
     */
    public function update(Request $request, MaintenanceType $maintenanceType)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255|unique:maintenance_types,name,' . $maintenanceType->id,

            'code' => 'nullable|string|max:20|unique:maintenance_types,code,' . $maintenanceType->id,

            'category' => 'nullable|string|max:100',

            'default_interval_hours' => 'nullable|numeric|min:0',

            'default_interval_days' => 'nullable|integer|min:0',

            'description' => 'nullable|string',

            'is_active' => 'required|boolean',

        ]);

        $maintenanceType->update($validated);

        return redirect()
            ->route('maintenance-types.index')
            ->with('success', 'Maintenance type updated successfully.');
    }

    /**
     * Delete maintenance type.
     */
    public function destroy(MaintenanceType $maintenanceType)
    {
        if ($maintenanceType->schedules()->exists()) {

            return back()->with(
                'error',
                'Cannot delete a maintenance type that is in use.'
            );

        }

        $maintenanceType->delete();

        return back()->with(
            'success',
            'Maintenance type deleted successfully.'
        );
    }
}