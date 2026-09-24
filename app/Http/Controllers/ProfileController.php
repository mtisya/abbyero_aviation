<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        if ($user->profile_image &&
            Storage::disk('public')->exists($user->profile_image)) {

            Storage::disk('public')->delete($user->profile_image);
        }


        $path = $request->file('profile_image')
                        ->store('profile-images','public');


        $user->update([
            'profile_image' => $path
        ]);


        return back()->with('success','Profile photo updated successfully.');
    }
}