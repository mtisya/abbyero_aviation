<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserTimezoneController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'timezone' => ['required', 'string', 'max:64'],
        ]);

        $timezone = $validated['timezone'];

        if (!in_array($timezone, timezone_identifiers_list(), true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid timezone.',
            ], 422);
        }

        $request->user()->forceFill([
            'timezone' => $timezone,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Timezone updated successfully.',
        ]);
    }
}