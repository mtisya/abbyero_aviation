<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    // Show the contact page
    public function index()
    {
        return view('contact');
    }

    // Handle form submission
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        try {
            // Example: Send email (optional)
            /*
            Mail::raw($request->message, function ($msg) use ($request) {
                $msg->to('support@abbyeroaviation.com')
                    ->subject('New Contact Message from ' . $request->name)
                    ->replyTo($request->email);
            });
            */

            return response()->json([
                'success' => true,
                'message' => 'Thank you, your message has been sent successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
