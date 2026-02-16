<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, int $id, string $hash)
    {
        $user = User::findOrFail($id);

        // Validate hash
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid or expired verification link.');
        }

        // Already verified
        if ($user->hasVerifiedEmail()) {

            // Redirect based on approval
            if ($user->status !== 'approved') {
                return redirect('/login')->with('message', 'Email verified, but your account is awaiting approval.');
            }

            return redirect('/login')->with('message', 'Email already verified. Please log in.');
        }

        // Mark email as verified
        $user->markEmailAsVerified();
        $user->touch();

        // Auto-login (optional)
        Auth::login($user);

        // Check if user is approved
        if ($user->status !== 'approved') {
            Auth::logout(); // logout immediately
            return redirect('/login')->with('message', 'Email verified successfully! Your account is awaiting admin approval.');
        }

        // Approved + verified → normal login
        return redirect('/dashboard')->with('verified', true)->with('message', 'Your email has been verified successfully!');
    }
}
