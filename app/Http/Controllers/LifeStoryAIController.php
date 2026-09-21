<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class LifeStoryAIController extends Controller
{
    private const OPENROUTER_URL = 'https://openrouter.ai/api/v1/chat/completions';

    /**
     * Free models tried in order; paid model as final fallback.
     */
    private const MODELS = [
        'nex-agi/nex-n2.5-pro:free',
        'nvidia/nemotron-3-super-120b-a12b:free',
        'nvidia/nemotron-3-ultra-550b-a55b:free',
        'google/gemma-4-31b-it:free',
        'qwen/qwen3.8-27b:free',
        'openai/gpt-4o-mini',
    ];

    public function generate(Request $request)
    {
        $request->validate([
            'prompt'     => 'required|string|max:30000',
            'max_tokens' => 'nullable|integer|min:100|max:6000',
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

        $lastError = null;

        foreach (self::MODELS as $model) {
            try {
                Log::info("[LifeStoryAI] Trying model: {$model}");

                $response = Http::timeout(90)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'HTTP-Referer'  => config('app.url', 'http://localhost'),
                        'X-Title'       => config('app.name', 'Life Story Generator'),
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                    ])
                    ->post(self::OPENROUTER_URL, [
                        'model'       => $model,
                        'max_tokens'  => $maxTokens,
                        'temperature' => 0.88,
                        'top_p'       => 0.95,
                        'messages'    => [
                            ['role' => 'user', 'content' => $prompt]
                        ],
                    ]);

                $status = $response->status();
                $data   = $response->json();

                // ── Rate limited or unavailable — try next ──────────
                if ($status === 429 || $status === 503) {
                    $lastError = data_get($data, 'error.message', "HTTP {$status} on {$model}");
                    Log::warning("[LifeStoryAI] {$model} rate-limited/unavailable, trying next...");
                    continue;
                }

                // ── 402: No credits ─────────────────────────────────
                if ($status === 402) {
                    $lastError = 'OpenRouter free limit reached.';
                    Log::warning("[LifeStoryAI] {$model} returned 402, trying next...");
                    continue;
                }

                // ── 404: Model removed ──────────────────────────────
                if ($status === 404) {
                    $lastError = data_get($data, 'error.message', "Model {$model} not found");
                    Log::warning("[LifeStoryAI] {$model} not found, trying next...");
                    continue;
                }

                // ── Other HTTP errors ───────────────────────────────
                if (!$response->successful()) {
                    $lastError = data_get($data, 'error.message')
                        ?? data_get($data, 'message')
                        ?? "HTTP {$status} on {$model}";
                    Log::warning("[LifeStoryAI] {$model} failed: {$lastError}");
                    continue;
                }

                // ── Extract text ────────────────────────────────────
                $text = trim(data_get($data, 'choices.0.message.content', ''));

                if (empty($text)) {
                    $finishReason = data_get($data, 'choices.0.finish_reason', '?');
                    if ($finishReason === 'content_filter') {
                        return response()->json([
                            'error'   => true,
                            'message' => 'Content was filtered by the AI. Try adjusting your journal content.',
                        ], 500);
                    }
                    $lastError = "Empty response from {$model} (finish: {$finishReason})";
                    Log::warning("[LifeStoryAI] {$lastError}");
                    continue;
                }

                // ── Success ─────────────────────────────────────────
                Log::info("[LifeStoryAI] Success with {$model}. Length: " . strlen($text));

                return response()->json([
                    'content' => $text,
                    'usage'   => [
                        'input_tokens'  => data_get($data, 'usage.prompt_tokens'),
                        'output_tokens' => data_get($data, 'usage.completion_tokens'),
                    ],
                ]);

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $lastError = "Connection error on {$model}: " . $e->getMessage();
                Log::warning("[LifeStoryAI] {$lastError}");
                continue;
            } catch (\Exception $e) {
                $lastError = "Exception on {$model}: " . $e->getMessage();
                Log::warning("[LifeStoryAI] {$lastError}");
                continue;
            }
        }

        // All models failed
        Log::error("[LifeStoryAI] All models exhausted. Last error: {$lastError}");
        return response()->json([
            'error'   => true,
            'message' => 'All AI models are currently unavailable. Last error: ' . ($lastError ?? 'Unknown'),
        ], 503);
    }
}