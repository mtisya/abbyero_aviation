<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Message;

class AIWebhookController extends Controller
{
   public function handle(Request $request)
{
    $input = $request->input('message');

    // 🔥 FAKE AI (for testing)
    $category = 'Complaint';

    if (str_contains(strtolower($input), 'help')) {
        $category = 'Inquiry';
    } elseif (str_contains(strtolower($input), 'thank')) {
        $category = 'Feedback';
    }

    $cleanMessage = ucfirst($input);
    $cleanMessage = str_replace(['bad', 'hate'], ['unsatisfactory', 'dislike'], $cleanMessage);

    // Save to DB
    $saved = Message::create([
        'original_message' => $input,
        'ai_response' => $cleanMessage,
        'category' => $category,
    ]);

    return response()->json([
        'status' => 'success (mocked AI)',
        'data' => $saved
    ]);
}
}
