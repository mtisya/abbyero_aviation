<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Services\AI\AdmissionsAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdmissionsAssistantController extends Controller
{
    public function __construct(
        private AdmissionsAssistantService $assistantService
    ) {
    }

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:2000'],
            'history' => ['nullable', 'array', 'max:10'],
            'history.*.role' => ['required', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $answer = $this->assistantService->reply(
                $validated['message'],
                $validated['history'] ?? []
            );

            return response()->json([
                'success' => true,
                'reply' => $answer,
            ]);
        } catch (Throwable $exception) {
            Log::error('Admissions assistant error', [
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'The admissions assistant is temporarily unavailable. Please try again later or contact Abbyero Aviation.',
            ], 503);
        }
    }
}