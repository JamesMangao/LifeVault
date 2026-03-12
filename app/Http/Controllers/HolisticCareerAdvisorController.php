<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;

class HolisticCareerAdvisorController extends Controller
{
    private const OPENROUTER_URL = 'https://openrouter.ai/api/v1/chat/completions';

    /**
     * Models tried in order. Free models first; paid models as final fallback.
     * OpenRouter free models end with ":free".
     */
    private const MODELS = [
        'mistralai/mistral-7b-instruct:free',
        'google/gemma-3-12b-it:free',
        'microsoft/phi-3-mini-128k-instruct:free',
        'qwen/qwen3-8b:free',
        'deepseek/deepseek-r1-0528:free',
        'openai/gpt-4o-mini',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    //  POST /ai/holistic-career/analyze
    // ──────────────────────────────────────────────────────────────────────────
    public function analyze(Request $request)
    {
        // ── 1. Validate ───────────────────────────────────────────────────────
        $validator = \Validator::make($request->all(), [
            'resume_text'                 => 'required|string|min:100|max:8000',
            'job_title_target'            => 'nullable|string|max:200',
            'journal_entries'             => 'required|array|min:3|max:30',
            'journal_entries.*.title'     => 'nullable|string|max:255',
            'journal_entries.*.content'   => 'nullable|string|max:2000',
            'journal_entries.*.mood'      => 'nullable|integer|min:1|max:5',
            'journal_entries.*.createdAt' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ── 2. Get API key ────────────────────────────────────────────────────
        $apiKey = config('services.openrouter.key') ?? env('OPENROUTER_API_KEY');

        if (empty($apiKey)) {
            Log::error('[HCA] No OpenRouter API key configured.');
            return response()->json([
                'success' => false,
                'message' => 'AI service not configured. Please set OPENROUTER_API_KEY in your .env file.',
            ], 500);
        }

        // ── 3. Prepare inputs ─────────────────────────────────────────────────
        $resumeText     = $this->cleanResumeText($request->input('resume_text'));
        $jobTarget      = trim($request->input('job_title_target', '')) ?: 'Not specified';
        $journalEntries = $request->input('journal_entries', []);
        $journalText    = $this->formatJournalEntries($journalEntries);

        // ── 4. Build prompts ──────────────────────────────────────────────────
        $systemPrompt = 'You are a holistic career coach and Jungian shadow-work practitioner. '
                      . 'You combine career psychology, resume strategy, and personal development. '
                      . 'You identify surface-level professional gaps AND deep psychological patterns '
                      . 'that affect career choices. Always respond in well-structured Markdown. '
                      . 'Be compassionate, specific, and actionable. Never quote journal entries directly '
                      . '— always paraphrase themes. '
                      . 'CRITICAL: In the ## Alignment Score section, the very first line MUST be: '
                      . '**Score: XX/100** (replace XX with an integer 0-100, nothing else on that line).';

        $userPrompt = $this->buildPrompt($resumeText, $jobTarget, $journalText, count($journalEntries));

        // ── 5. Try each model until one succeeds ──────────────────────────────
        $markdown  = null;
        $usedModel = null;
        $lastError = null;

        foreach (self::MODELS as $model) {
            try {
                Log::info("[HCA] Trying model: {$model}");

                $response = Http::timeout(120)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type'  => 'application/json',
                        'HTTP-Referer'  => config('app.url', 'http://localhost'),
                        'X-Title'       => config('app.name', 'LifeVault'),
                    ])
                    ->post(self::OPENROUTER_URL, [
                        'model'    => $model,
                        'messages' => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user',   'content' => $userPrompt],
                        ],
                        'max_tokens'  => 4000,
                        'temperature' => 0.7,
                    ]);

                $data   = $response->json();
                $status = $response->status();

                Log::info("[HCA] {$model} → HTTP {$status}");

                // Rate limited or provider error — try next model
                if ($status === 429 || $status === 503) {
                    $lastError = data_get($data, 'error.message', "HTTP {$status} on {$model}");
                    Log::warning("[HCA] {$model} rate-limited/unavailable, trying next...");
                    continue;
                }

                // Other HTTP error
                if (!$response->successful()) {
                    $lastError = data_get($data, 'error.message')
                               ?? data_get($data, 'message')
                               ?? "HTTP {$status} on {$model}";
                    Log::warning("[HCA] {$model} failed: {$lastError}");
                    continue;
                }

                // Extract content
                $text = trim(data_get($data, 'choices.0.message.content', ''));

                if (empty($text)) {
                    $lastError = "Empty response from {$model} (finish: " . data_get($data, 'choices.0.finish_reason', '?') . ")";
                    Log::warning("[HCA] {$lastError}");
                    continue;
                }

                // Success
                $markdown  = $text;
                $usedModel = $model;
                Log::info("[HCA] Success with {$model}. Length: " . strlen($markdown));
                break;

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $lastError = "Connection error on {$model}: " . $e->getMessage();
                Log::warning("[HCA] {$lastError}");
                continue;
            } catch (\Exception $e) {
                $lastError = "Exception on {$model}: " . $e->getMessage();
                Log::warning("[HCA] {$lastError}");
                continue;
            }
        }

        // ── 6. All models failed ──────────────────────────────────────────────
        if (empty($markdown)) {
            Log::error("[HCA] All models failed. Last: {$lastError}");
            return response()->json([
                'success' => false,
                'message' => 'All AI models are currently busy or unavailable. Please try again in a few minutes.',
            ], 500);
        }

        // ── 7. Extract score & render HTML ────────────────────────────────────
        $alignmentScore = $this->extractScore($markdown);
        Log::info('[HCA] Score: ' . ($alignmentScore ?? 'null') . ' | Model: ' . $usedModel);

        try {
            $converter  = new CommonMarkConverter([
                'html_input'         => 'strip',
                'allow_unsafe_links' => false,
            ]);
            $reportHtml = $converter->convert($markdown)->getContent();
        } catch (\Exception $e) {
            Log::warning('[HCA] CommonMark fallback: ' . $e->getMessage());
            $reportHtml = '<pre style="white-space:pre-wrap">' . htmlspecialchars($markdown) . '</pre>';
        }

        // ── 8. Return ─────────────────────────────────────────────────────────
        return response()->json([
            'success' => true,
            'report'  => [
                'report_html'     => $reportHtml,
                'report_markdown' => $markdown,
                'alignment_score' => $alignmentScore,
                'job_target'      => $jobTarget,
                'entries_count'   => count($journalEntries),
                'generated_at'    => now()->toISOString(),
                'model_used'      => $usedModel,
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Build the holistic prompt
    // ──────────────────────────────────────────────────────────────────────────
    private function buildPrompt(
        string $resumeText,
        string $jobTarget,
        string $journalText,
        int    $entryCount
    ): string {
        return <<<PROMPT
You are a Holistic Career Advisor bridging professional identity with authentic self.

TARGET ROLE / DIRECTION: {$jobTarget}

═══════════════════════════════════════
RESUME
═══════════════════════════════════════
{$resumeText}

═══════════════════════════════════════
JOURNAL ENTRIES ({$entryCount} entries)
═══════════════════════════════════════
{$journalText}

═══════════════════════════════════════
INSTRUCTIONS
═══════════════════════════════════════
Respond ONLY in Markdown using EXACTLY these ## section headings. No other sections.

## Alignment Score
First line MUST be: **Score: XX/100**
Then 2-3 sentences interpreting the score.

## Holistic Profile
3–4 sentences synthesizing professional identity vs authentic self from journals.

## Career Paths That Honor Your Whole Self
3–4 roles matching both skills and journal values. For each: skills that transfer, values honored, one first step.

## Shadow Patterns Affecting Your Career
3–4 psychological patterns from journals affecting career. For each:
- **Pattern Name 🔍**
- How it shows up (1–2 sentences)
- Career behavior it causes
- The hidden strength / reframe

## Authentic Resume Narrative
3–4 suggestions for rewriting Summary, Experience bullets, and Skills more authentically.

## Cover Letter Compass
3-paragraph framework using [PLACEHOLDER] tags. Hook from journal theme → skills bridge → values call-to-action.

## Values-Career Gap Analysis
Bullet list of misalignments between journal values and resume narrative.

## 30-Day Authentic Career Action Plan
8–10 numbered steps mixing practical tasks and inner-work tasks.
PROMPT;
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Format journal entries
    // ──────────────────────────────────────────────────────────────────────────
    private function formatJournalEntries(array $entries): string
    {
        return collect($entries)
            ->take(20)
            ->map(function ($e, $i) {
                $date    = !empty($e['createdAt']) ? date('M j, Y', strtotime($e['createdAt'])) : 'Unknown date';
                $mood    = $e['mood']  ?? 3;
                $title   = $e['title'] ?? 'Untitled';
                $content = mb_substr(trim($e['content'] ?? ''), 0, 500);
                return "[Entry " . ($i + 1) . " | {$date} | Mood {$mood}/5]\nTitle: {$title}\n{$content}";
            })
            ->implode("\n\n---\n\n");
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Clean resume text
    // ──────────────────────────────────────────────────────────────────────────
    private function cleanResumeText(string $rawText): string
    {
        $text = html_entity_decode($rawText, ENT_QUOTES | ENT_HTML5);
        $text = preg_replace('/@page\s+[^{]+\{[^}]*\}/i', '', $text);
        $text = preg_replace('/\.[a-zA-Z0-9_-]+\s*\{[^}]*\}/', '', $text);
        $text = strip_tags($text);
        $text = preg_replace('/\r\n|\r/', "\n", $text);
        $text = preg_replace('/[ \t]{2,}/', ' ', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        return mb_substr(trim($text), 0, 6000);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Extract alignment score
    // ──────────────────────────────────────────────────────────────────────────
    private function extractScore(string $markdown): ?int
    {
        $patterns = [
            '/\*{1,2}Score:\s*(\d{1,3})\s*\/\s*100\*{0,2}/i',
            '/Score:\s*\*{0,2}(\d{1,3})\*{0,2}\s*\/\s*100/i',
            '/\*{1,2}(\d{1,3})\s*\/\s*100\*{0,2}/',
            '/(\d{1,3})\s*out\s*of\s*100/i',
            '/score[^\d]{0,20}(\d{1,3})\s*\/\s*100/i',
            '/(\d{1,3})\s*\/\s*100/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $markdown, $m)) {
                $val = (int) $m[1];
                if ($val >= 0 && $val <= 100) {
                    return $val;
                }
            }
        }

        return null;
    }
}