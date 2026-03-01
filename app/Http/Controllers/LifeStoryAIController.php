<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ============================================================
 *  LifeStoryAIController — Powered by OpenRouter (Auto Free)
 * ============================================================
 *
 *  Route : POST /ai/life-story/generate  (web.php)
 *  Named : ai.life-story.generate
 *
 *  SETUP
 *  ─────────────────────────────────────────────────────────
 *  1. Get free API key: https://openrouter.ai/keys
 *
 *  2. Add to .env:
 *       OPENROUTER_API_KEY=sk-or-v1-your_key_here
 *
 *  3. Add to config/services.php inside the return array:
 *       'openrouter' => [
 *           'key' => env('OPENROUTER_API_KEY'),
 *       ],
 *
 *  4. php artisan config:clear
 *
 *  NOTE: Uses "openrouter/free" — OpenRouter automatically picks
 *  the best available free model for each request. No model
 *  selection needed from the user.
 * ============================================================
 */
class LifeStoryAIController extends Controller
{
    // Hardcoded — OpenRouter auto-selects the best free model
    private const MODEL = 'openrouter/free';

    public function generate(Request $request)
    {
        $request->validate([
            'prompt'     => 'required|string|max:30000',
            'max_tokens' => 'nullable|integer|min:100|max:4000',
        ]);

        $apiKey    = config('services.openrouter.key');
        $maxTokens = $request->integer('max_tokens', 1400);
        $prompt    = (string) $request->string('prompt');

        if (empty($apiKey)) {
            return response()->json([
                'error'   => true,
                'message' => 'OPENROUTER_API_KEY is not set. Add it to your .env and run: php artisan config:clear',
            ], 500);
        }

        try {
            $response = Http::timeout(90)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'HTTP-Referer'  => config('app.url', 'http://localhost'),
                    'X-Title'       => config('app.name', 'Life Story Generator'),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model'       => self::MODEL,
                    'max_tokens'  => $maxTokens,
                    'temperature' => 0.88,
                    'top_p'       => 0.95,
                    'messages'    => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                ]);

            // ── 429: Rate limited ─────────────────────────────────
            if ($response->status() === 429) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Daily free limit reached. The limit resets at midnight UTC. Please try again later.',
                ], 429);
            }

            // ── 402: No credits ───────────────────────────────────
            if ($response->status() === 402) {
                return response()->json([
                    'error'   => true,
                    'message' => 'OpenRouter free limit reached. Please try again tomorrow or contact support.',
                ], 402);
            }

            // ── Other HTTP errors ─────────────────────────────────
            if (!$response->successful()) {
                $errorMsg = $response->json('error.message')
                    ?? $response->json('message')
                    ?? $response->body();

                Log::error('OpenRouter API error', [
                    'status'  => $response->status(),
                    'message' => $errorMsg,
                ]);

                return response()->json([
                    'error'   => true,
                    'message' => 'AI service error (' . $response->status() . '): ' . $errorMsg,
                ], $response->status());
            }

            // ── Extract text ──────────────────────────────────────
            $data = $response->json();
            $text = data_get($data, 'choices.0.message.content');

            if (empty($text)) {
                $finishReason = data_get($data, 'choices.0.finish_reason');
                return response()->json([
                    'error'   => true,
                    'message' => $finishReason === 'content_filter'
                        ? 'Content was filtered by the AI. Try adjusting your journal content.'
                        : 'The AI returned an empty response. Please try again.',
                ], 500);
            }

            return response()->json([
                'content' => $text,
                'usage'   => [
                    'input_tokens'  => data_get($data, 'usage.prompt_tokens'),
                    'output_tokens' => data_get($data, 'usage.completion_tokens'),
                ],
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('OpenRouter connection failed', ['error' => $e->getMessage()]);
            return response()->json([
                'error'   => true,
                'message' => 'Could not connect to the AI service. Check your internet connection.',
            ], 503);

        } catch (\Exception $e) {
            Log::error('LifeStoryAI exception', ['error' => $e->getMessage()]);
            return response()->json([
                'error'   => true,
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }
    }
}