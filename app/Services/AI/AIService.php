<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function analyzeLogbook($log)
    {
        $prompt = "
            Analyze this flight log:

            Aircraft: {$log->aircraft}
            Route: {$log->route}
            Duration: {$log->hours}
            Remarks: {$log->remarks}

            Return JSON in this format:

            {
            \"summary\": \"...\",
            \"feedback\": \"...\",
            \"flags\": [\"...\"]
            }
            ";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ]);

        return $response->json();
    }
}