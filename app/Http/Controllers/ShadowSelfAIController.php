<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShadowSelfAIController extends Controller
{
    // ── Groq API config ───────────────────────────────────────────────────────
    private const GROQ_MODEL = 'llama-3.3-70b-versatile';
    private const GROQ_API   = 'https://api.groq.com/openai/v1/chat/completions';

    // ──────────────────────────────────────────────────────────────────────────
    //  POST /ai/shadow-self/analyze
    //  Finds recurring negative patterns & reframes them compassionately
    // ──────────────────────────────────────────────────────────────────────────
    public function analyzeShadowSelf(Request $request)
    {
        $request->validate([
            'entries'             => 'required|array|min:3|max:30',
            'entries.*.title'     => 'nullable|string|max:255',
            'entries.*.content'   => 'nullable|string|max:2000',
            'entries.*.mood'      => 'nullable|integer|min:1|max:5',
            'entries.*.createdAt' => 'nullable|string',
        ]);

        $apiKey = config('services.groq.key');

        if (! $apiKey) {
            return response()->json(
                ['error' => 'Groq API key is not configured. Add GROQ_API_KEY to your .env file.'],
                500
            );
        }

        $entries = $request->input('entries', []);

        $entriesText = collect($entries)
            ->take(20)
            ->map(function ($e) {
                $date    = ! empty($e['createdAt'])
                    ? date('M j, Y', strtotime($e['createdAt']))
                    : 'Unknown date';
                $mood    = $e['mood']  ?? 3;
                $title   = $e['title'] ?? 'Untitled';
                $content = mb_substr($e['content'] ?? '', 0, 400);
                return "[{$date}] Mood {$mood}/5 | {$title}\n{$content}";
            })
            ->implode("\n\n---\n\n");

        try {
            $patternsResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post(self::GROQ_API, [
                'model'       => self::GROQ_MODEL,
                'max_tokens'  => 1024,
                'temperature' => 0.4, // lower = more consistent JSON output
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'You are a compassionate psychological pattern analyst. '
                                   . 'You respond ONLY with valid JSON. No markdown fences, no preamble, no explanation. '
                                   . 'Never quote journal entries directly — always paraphrase.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => "Analyze these journal entries for recurring shadow patterns — "
                                   . "negative thought loops, self-limiting beliefs, fears, and blind spots.\n\n"
                                   . "JOURNAL ENTRIES:\n{$entriesText}\n\n"
                                   . "Return ONLY this JSON with no extra text:\n"
                                   . "{\n"
                                   . "  \"awarenessScore\": <integer 1-100>,\n"
                                   . "  \"summaryTitle\": \"<2-5 words>\",\n"
                                   . "  \"summaryText\": \"<2-3 compassionate sentences>\",\n"
                                   . "  \"patterns\": [\n"
                                   . "    {\"name\": \"<2-4 words>\", \"emoji\": \"<emoji>\", \"severity\": <1-5>, "
                                   . "\"color\": \"<rose|amber|lavender|teal|accent>\", "
                                   . "\"description\": \"<1-2 sentences>\", \"evidence\": \"<paraphrased theme>\"}\n"
                                   . "  ],\n"
                                   . "  \"reframes\": [\n"
                                   . "    {\"shadow\": \"<negative belief>\", \"reframe\": \"<compassionate truth>\"}\n"
                                   . "  ],\n"
                                   . "  \"growthActions\": [\"<specific action>\"],\n"
                                   . "  \"hiddenStrengths\": [\"<strength found in journals>\"]\n"
                                   . "}\n\n"
                                   . "Rules: 3-5 patterns, 3-4 reframes, 3-4 growthActions, 4-6 hiddenStrengths. "
                                   . "Be specific to the journals. awarenessScore = how self-aware the writer already is.",
                    ],
                ],
            ]);

            if ($patternsResponse->failed()) {
                $status = $patternsResponse->status();
                Log::error('LifeStoryAI [Groq] shadowSelf error', [
                    'status' => $status,
                    'body'   => $patternsResponse->body(),
                ]);
                return response()->json(
                    ['error' => "Groq API error ({$status}). Please try again."],
                    $status
                );
            }

            $raw   = $patternsResponse->json()['choices'][0]['message']['content'] ?? '';
            $clean = trim(preg_replace('/^```json\s*|^```\s*|```$/m', '', trim($raw)));

            if (empty($clean)) {
                return response()->json(
                    ['error' => 'The AI returned an empty response. Please try again.'],
                    500
                );
            }

            $data = json_decode($clean, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('LifeStoryAI [Groq] shadow JSON parse failed', [
                    'raw'   => $raw,
                    'error' => json_last_error_msg(),
                ]);
                return response()->json(
                    ['error' => 'Could not parse the AI response. Please try again.'],
                    500
                );
            }

            return response()->json([
                'success'    => true,
                'data'       => $this->sanitizeShadowResponse($data),
                'analyzedAt' => now()->toISOString(),
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('LifeStoryAI [Groq] shadow connection error', ['msg' => $e->getMessage()]);
            return response()->json(
                ['error' => 'Could not reach Groq. Please check your connection and try again.'],
                504
            );
        } catch (\Exception $e) {
            Log::error('LifeStoryAI [Groq] shadow unexpected error', ['msg' => $e->getMessage()]);
            return response()->json(
                ['error' => 'An unexpected error occurred: ' . $e->getMessage()],
                500
            );
        }
    }

    private function sanitizeShadowResponse(array $data): array
    {
        $allowedColors = ['rose', 'amber', 'lavender', 'teal', 'accent'];

        return [
            'awarenessScore' => max(1, min(100, (int) ($data['awarenessScore'] ?? 50))),
            'summaryTitle'   => mb_substr((string) ($data['summaryTitle'] ?? 'Your Inner Landscape'), 0, 80),
            'summaryText'    => mb_substr((string) ($data['summaryText']  ?? ''), 0, 600),

            'patterns' => collect($data['patterns'] ?? [])
                ->take(5)
                ->map(fn ($p) => [
                    'name'        => mb_substr((string) ($p['name']        ?? 'Pattern'), 0, 60),
                    'emoji'       => mb_substr((string) ($p['emoji']       ?? '😔'),      0, 8),
                    'severity'    => max(1, min(5, (int) ($p['severity']   ?? 3))),
                    'color'       => in_array($p['color'] ?? '', $allowedColors, true) ? $p['color'] : 'rose',
                    'description' => mb_substr((string) ($p['description'] ?? ''), 0, 300),
                    'evidence'    => mb_substr((string) ($p['evidence']    ?? ''), 0, 300),
                ])
                ->values()->all(),

            'reframes' => collect($data['reframes'] ?? [])
                ->take(4)
                ->map(fn ($r) => [
                    'shadow'  => mb_substr((string) ($r['shadow']  ?? ''), 0, 200),
                    'reframe' => mb_substr((string) ($r['reframe'] ?? ''), 0, 300),
                ])
                ->values()->all(),

            'growthActions' => collect($data['growthActions'] ?? [])
                ->take(4)
                ->map(fn ($a) => mb_substr((string) $a, 0, 200))
                ->values()->all(),

            'hiddenStrengths' => collect($data['hiddenStrengths'] ?? [])
                ->take(6)
                ->map(fn ($s) => mb_substr((string) $s, 0, 100))
                ->values()->all(),
        ];
    }
}
