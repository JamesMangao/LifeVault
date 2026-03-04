<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\CommonMark\CommonMarkConverter;
use PhpOffice\PhpWord\IOFactory;
use App\Services\ResumeDocxService;
use Smalot\PdfParser\Parser as PdfParser;

class ResumeAnalysisController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    //  AJAX  ·  POST /analyze/resume/ajax
    // ─────────────────────────────────────────────────────────────────────────
    public function storeAjax(Request $request)
    {
        try {
            // ── 1. Validate ───────────────────────────────────────────────────
            $validator = \Validator::make($request->all(), [
                'resume_file' => [
                    'required',
                    'mimetypes:application/pdf,'
                        . 'application/msword,'
                        . 'application/vnd.openxmlformats-officedocument.wordprocessingml.document,'
                        . 'text/plain',
                    'max:5120',
                ],
                'job_description' => 'required|string|min:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // ── 2. Parse uploaded file ────────────────────────────────────────
            $file      = $request->file('resume_file');
            $extension = strtolower($file->getClientOriginalExtension());
            $tempPath  = sys_get_temp_dir() . '/' . uniqid('resume_') . '.' . $extension;
            $file->move(dirname($tempPath), basename($tempPath));

            $resumeHtml = '';

            try {
                switch ($extension) {
                    case 'txt':
                        $resumeHtml = self::formatExtractedTextAsHtml(
                            file_get_contents($tempPath)
                        );
                        break;

                    case 'doc':
                    case 'docx':
                        $phpWord = IOFactory::load($tempPath);
                        $writer  = IOFactory::createWriter($phpWord, 'HTML');
                        ob_start();
                        $writer->save('php://output');
                        $resumeHtml = ob_get_clean();
                        break;

                    case 'pdf':
                        $resumeHtml = self::extractPdf($tempPath);
                        break;

                    default:
                        throw new \Exception('Unsupported file type: ' . $extension);
                }
            } catch (\Exception $e) {
                @unlink($tempPath);
                return response()->json([
                    'success' => false,
                    'message' => 'Could not read the resume file: ' . $e->getMessage(),
                ], 500);
            }

            @unlink($tempPath);

            // ── 3. Strip HTML → plain text for AI prompt ──────────────────────
            $plainText = html_entity_decode($resumeHtml, ENT_QUOTES | ENT_HTML5);
            $plainText = preg_replace('/@page\b[^{]*\{[^}]*\}/i', '', $plainText);
            $plainText = preg_replace('/\.[a-zA-Z0-9_-]+\s*\{[^}]*\}/', '', $plainText);
            $plainText = strip_tags($plainText);
            $plainText = preg_replace('/(Hyperlink|WordSection\d+|NormalTable)/i', '', $plainText);
            $plainText = preg_replace('/\r\n|\r/', "\n", $plainText);
            $plainText = preg_replace('/\n{3,}/', "\n\n", $plainText);
            $plainText = preg_replace('/[ \t]{2,}/', ' ', $plainText);
            $plainText = trim($plainText);

            $resumeForPrompt = mb_substr($plainText, 0, 4000);
            $jobForPrompt    = mb_substr(trim($request->job_description), 0, 2000);

            // ── 4. Persist to DB (optional) ───────────────────────────────────
            try {
                DB::select('CALL usp_insert_resume(?, ?, ?)', [
                    1,
                    $resumeHtml,
                    $request->job_description,
                ]);
            } catch (\Exception $dbEx) {
                \Log::warning('[ResumeAnalyzer] DB insert skipped: ' . $dbEx->getMessage());
            }

            // ── 5. Build AI prompt ────────────────────────────────────────────
            $prompt = <<<PROMPT
You are an expert resume consultant and career coach with 15+ years of experience helping candidates land their dream jobs.

Carefully analyse the resume below against the provided job description, then produce a thorough, actionable improvement report.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
RESUME
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{$resumeForPrompt}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
JOB DESCRIPTION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{$jobForPrompt}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
YOUR REPORT — respond in clean Markdown with EXACTLY these sections:

## Match Score
Give a single integer 0–100 showing how well the resume matches the role.
Format the first line exactly as: **Score: XX/100**
Follow with 2 sentences of honest reasoning.

## Executive Summary
3 concise sentences: overall fit, biggest strength, and the single most critical gap.

## Key Strengths
- Bullet list of 3–5 things the resume does well for THIS specific role.

## Critical Gaps
- Bullet list of the top 3–5 missing skills, keywords, or experiences the job requires.

## Keyword Optimisation
List 8–12 specific keywords/phrases from the job description that must be added or emphasised in the resume.

## Section-by-Section Improvements
For each major section present in the resume (Summary/Objective, Work Experience, Skills, Education, Projects) give 2–3 concrete, specific rewrites or additions. Quote existing text where helpful, then show the improved version.

## ATS Formatting Tips
3–5 bullet points on formatting changes to improve Applicant Tracking System (ATS) compatibility.

## Quick Wins
The 3 changes the candidate can make in under 30 minutes that will have the biggest positive impact.

Important: Be specific and use evidence from the resume. Do not invent experience the candidate does not have. Keep tone professional yet encouraging.
PROMPT;

            // ── 6. Call Cerebras API ──────────────────────────────────────────
            $suggestionsHtml = '<p>No suggestions available.</p>';
            $markdown        = '';
            $matchScore      = null;

            try {
                $client = new \GuzzleHttp\Client(['timeout' => 90]);
                $apiKey = env('CEREBRAS_API_KEY');

                if (empty($apiKey)) {
                    throw new \Exception('CEREBRAS_API_KEY is not set. Add it to your .env file.');
                }

                $response = $client->post('https://api.cerebras.ai/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'model'       => 'llama3.1-8b',
                        'max_tokens'  => 2048,
                        'temperature' => 0.3,
                        'top_p'       => 0.9,
                        'messages'    => [
                            [
                                'role'    => 'system',
                                'content' => 'You are an expert resume consultant. Always respond in well-structured Markdown. Be specific, actionable, and professional.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $prompt,
                            ],
                        ],
                    ],
                ]);

                $body = json_decode($response->getBody()->getContents(), true);

                if (!empty($body['choices'][0]['message']['content'])) {
                    $markdown = trim($body['choices'][0]['message']['content']);

                    if (preg_match('/\*\*Score:\s*(\d{1,3})\/100\*\*/i', $markdown, $m)) {
                        $matchScore = min(100, max(0, (int) $m[1]));
                    } elseif (preg_match('/Score:\s*(\d{1,3})\s*\/\s*100/i', $markdown, $m)) {
                        $matchScore = min(100, max(0, (int) $m[1]));
                    }

                    $converter = new CommonMarkConverter([
                        'html_input'         => 'strip',
                        'allow_unsafe_links' => false,
                    ]);
                    $suggestionsHtml = $converter->convert($markdown)->getContent();
                }

            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $errBody = $e->getResponse()->getBody()->getContents();
                $errData = json_decode($errBody, true);
                $errMsg  = $errData['error']['message'] ?? $errBody;
                \Log::error('[ResumeAnalyzer] Cerebras 4xx: ' . $errMsg);
                $suggestionsHtml = '<p style="color:var(--rose)">⚠️ Cerebras API error: ' . htmlspecialchars($errMsg) . '</p>';

            } catch (\GuzzleHttp\Exception\ServerException $e) {
                \Log::error('[ResumeAnalyzer] Cerebras 5xx: ' . $e->getMessage());
                $suggestionsHtml = '<p style="color:var(--rose)">⚠️ Cerebras server error — please try again.</p>';

            } catch (\GuzzleHttp\Exception\ConnectException $e) {
                \Log::error('[ResumeAnalyzer] Cerebras connection error: ' . $e->getMessage());
                $suggestionsHtml = '<p style="color:var(--rose)">⚠️ Could not connect to Cerebras API.</p>';

            } catch (\Exception $e) {
                \Log::error('[ResumeAnalyzer] AI call failed: ' . $e->getMessage());
                $suggestionsHtml = '<p style="color:var(--rose)">⚠️ AI analysis failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }

            // ── 7. Return JSON ────────────────────────────────────────────────
            return response()->json([
                'success' => true,
                'resume'  => [
                    'resume_content'       => $resumeHtml,
                    'suggestions_html'     => $suggestionsHtml,
                    'suggestions_markdown' => $markdown,
                    'match_score'          => $matchScore,
                ],
            ]);

        } catch (\Throwable $e) {
            \Log::error('[ResumeAnalyzer] Unexpected error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ═════════════════════════════════════════════════════════════════════════
    //
    //  PDF EXTRACTION — 3-tier pipeline
    //
    //  Tier 1 — pdftohtml -xml (Poppler)
    //           Extracts every text element with X/Y coordinates + font size.
    //           Groups elements into columns by X position so multi-column
    //           layouts (Skills | Software Skills, etc.) render correctly.
    //           This is the BEST option for text-based PDFs.
    //
    //  Tier 2 — smalot/pdfparser
    //           Pure-PHP fallback. Linear text extraction — fine for
    //           single-column resumes, may jumble multi-column ones.
    //           Used when pdftohtml is not installed.
    //
    //  Tier 3 — OCR (pdftoppm/Ghostscript + Tesseract)
    //           For scanned / image-only PDFs where no text layer exists.
    //
    // ═════════════════════════════════════════════════════════════════════════
    private static function extractPdf(string $pdfPath): string
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        // ── Resolve binary paths ──────────────────────────────────────────────
        if ($isWindows) {
            $pdftohtmlBin = env('PDFTOHTML_PATH', 'C:\\Program Files\\poppler\\Library\\bin\\pdftohtml.exe');
            $pdftoppmBin  = env('PDFTOPPM_PATH',  'C:\\Program Files\\poppler\\Library\\bin\\pdftoppm.exe');
            $tesseractBin = env('TESSERACT_PATH', 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe');
        } else {
            $pdftohtmlBin = trim(shell_exec('which pdftohtml 2>/dev/null') ?? '') ?: 'pdftohtml';
            $pdftoppmBin  = trim(shell_exec('which pdftoppm 2>/dev/null')  ?? '') ?: 'pdftoppm';
            $tesseractBin = trim(shell_exec('which tesseract 2>/dev/null') ?? '') ?: 'tesseract';
        }

        $hasPdftohtml = self::binaryExists($pdftohtmlBin, $isWindows);
        $hasPdftoppm  = self::binaryExists($pdftoppmBin,  $isWindows);
        $hasTesseract = self::binaryExists($tesseractBin, $isWindows);

        \Log::info('[ResumeAnalyzer] pdftohtml=' . ($hasPdftohtml?'yes':'no')
            . ' pdftoppm=' . ($hasPdftoppm?'yes':'no')
            . ' tesseract=' . ($hasTesseract?'yes':'no'));

        // ════════════════════════════════════════════════════════════════════
        //  TIER 1 — pdftohtml -xml  (layout-aware, best quality)
        // ════════════════════════════════════════════════════════════════════
        if ($hasPdftohtml) {
            $result = self::extractViapdftohtmlXml($pdfPath, $pdftohtmlBin, $isWindows);
            if ($result !== null) {
                \Log::info('[ResumeAnalyzer] Tier 1 (pdftohtml XML) succeeded');
                return $result;
            }
            \Log::warning('[ResumeAnalyzer] Tier 1 failed or empty, falling to Tier 2');
        }

        // ════════════════════════════════════════════════════════════════════
        //  TIER 2 — smalot/pdfparser  (pure-PHP, linear text)
        // ════════════════════════════════════════════════════════════════════
        try {
            $parser = new PdfParser();
            $pdf    = $parser->parseFile($pdfPath);
            $text   = trim($pdf->getText());

            if (mb_strlen($text) > 50) {
                \Log::info('[ResumeAnalyzer] Tier 2 (pdfparser) succeeded (' . mb_strlen($text) . ' chars)');
                return self::formatExtractedTextAsHtml($text);
            }
        } catch (\Exception $e) {
            \Log::warning('[ResumeAnalyzer] Tier 2 (pdfparser) failed: ' . $e->getMessage());
        }

        // ════════════════════════════════════════════════════════════════════
        //  TIER 3 — OCR (image-based PDF fallback)
        // ════════════════════════════════════════════════════════════════════
        \Log::info('[ResumeAnalyzer] Tier 3 — attempting OCR');
        return self::extractViaOcr($pdfPath, $pdftoppmBin, $tesseractBin, $hasPdftoppm, $hasTesseract, $isWindows);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  TIER 1 — pdftohtml -xml layout reconstruction
    // ─────────────────────────────────────────────────────────────────────────
    /**
     * Runs: pdftohtml -xml -i -nodrm -q <pdf> <outbase>
     * Parses the resulting XML to get each text token with (left, top, width,
     * height, font-size).  Tokens are grouped into visual rows by their top
     * coordinate (±threshold), then within each row into columns by their left
     * coordinate.  Side-by-side columns are rendered as an HTML table so
     * multi-column resume sections stay aligned.
     */
    private static function extractViapdftohtmlXml(string $pdfPath, string $bin, bool $isWindows): ?string
    {
        $workDir = sys_get_temp_dir() . '/resume_xml_' . uniqid();
        $outBase = $workDir . '/out';

        try {
            if (!mkdir($workDir, 0755, true)) {
                throw new \Exception('Cannot create work dir');
            }

            // pdftohtml writes <outbase>.xml
            $cmd = sprintf(
                '%s -xml -i -nodrm -q %s %s' . ($isWindows ? '' : ' 2>/dev/null'),
                escapeshellarg($bin),
                escapeshellarg($pdfPath),
                escapeshellarg($outBase)
            );
            exec($cmd, $out, $rc);

            $xmlFile = $outBase . '.xml';
            if ($rc !== 0 || !file_exists($xmlFile)) {
                return null;
            }

            $xmlContent = file_get_contents($xmlFile);
            @unlink($xmlFile);

            if (empty(trim($xmlContent))) {
                return null;
            }

            return self::xmlToHtml($xmlContent);

        } catch (\Exception $e) {
            \Log::warning('[ResumeAnalyzer] pdftohtml XML parse error: ' . $e->getMessage());
            return null;
        } finally {
            if (is_dir($workDir)) {
                array_map('unlink', glob($workDir . '/*.*'));
                @rmdir($workDir);
            }
        }
    }

    /**
     * Parse pdftohtml XML output into layout-aware HTML.
     *
     * The XML looks like:
     * <pdf2xml>
     *   <page number="1" width="595" height="842">
     *     <text top="120" left="50"  width="200" height="14" font="0">John Doe</text>
     *     <text top="140" left="50"  width="150" height="12" font="1">Skills</text>
     *     <text top="155" left="50"  width="180" height="11" font="2">• Communication</text>
     *     <text top="140" left="300" width="150" height="12" font="1">Software</text>
     *     <text top="155" left="300" width="180" height="11" font="2">• Photoshop</text>
     *   </page>
     * </pdf2xml>
     *
     * Algorithm:
     * 1. Collect all <text> elements with their attributes.
     * 2. Sort by (top, left).
     * 3. Cluster into "rows" — tokens whose top values are within ROW_THRESHOLD px.
     * 4. Within each row, detect if there are 2+ distinct X clusters (= columns).
     * 5. If multi-column row: emit as <div class="rp-row"> with flex children.
     * 6. Classify each token by font-size to assign heading/body/meta styles.
     */
    private static function xmlToHtml(string $xmlContent): ?string
    {
        // Suppress XML warnings for malformed content
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent);
        libxml_clear_errors();

        if (!$xml) {
            return null;
        }

        // ── Collect all text tokens across all pages ──────────────────────────
        $tokens = [];
        foreach ($xml->page as $page) {
            $pageWidth = (float)($page['width'] ?? 595);

            foreach ($page->text as $textEl) {
                $top    = (float)($textEl['top']    ?? 0);
                $left   = (float)($textEl['left']   ?? 0);
                $height = (float)($textEl['height'] ?? 10);
                $width  = (float)($textEl['width']  ?? 100);

                // Get inner text including <b>, <i>, <a> child elements
                $raw = self::innerXml($textEl);
                $raw = trim(strip_tags($raw, '<b><i><u>'));
                $raw = html_entity_decode($raw, ENT_QUOTES | ENT_HTML5);
                $raw = trim($raw);

                if ($raw === '') continue;

                $tokens[] = [
                    'top'    => $top,
                    'left'   => $left,
                    'width'  => $width,
                    'height' => $height,
                    'size'   => $height,      // font height ≈ font size
                    'text'   => $raw,
                    'page_w' => $pageWidth,
                ];
            }
        }

        if (empty($tokens)) {
            return null;
        }

        // ── Sort by top then left ─────────────────────────────────────────────
        usort($tokens, fn($a, $b) =>
            $a['top'] <=> $b['top'] ?: $a['left'] <=> $b['left']
        );

        // ── Cluster tokens into rows (top within ROW_THRESHOLD px) ───────────
        $ROW_THRESHOLD = 4; // px — tokens this close in Y = same row
        $rows          = [];
        $currentRow    = [$tokens[0]];
        $rowTop        = $tokens[0]['top'];

        for ($i = 1; $i < count($tokens); $i++) {
            $t = $tokens[$i];
            if (abs($t['top'] - $rowTop) <= $ROW_THRESHOLD) {
                $currentRow[] = $t;
            } else {
                $rows[]     = $currentRow;
                $currentRow = [$t];
                $rowTop     = $t['top'];
            }
        }
        $rows[] = $currentRow;

        // ── Determine page midpoint for column detection ──────────────────────
        $pageWidth  = $tokens[0]['page_w'] ?? 595;
        $midpoint   = $pageWidth / 2;
        $COL_GAP    = $pageWidth * 0.12; // gap must be >12% of page width to be "two columns"

        // ── Build HTML from rows ──────────────────────────────────────────────
        $html       = '';
        $prevBottom = 0;

        foreach ($rows as $row) {
            // Detect vertical gap → blank spacer
            $rowTop = $row[0]['top'];
            if ($prevBottom > 0 && ($rowTop - $prevBottom) > 14) {
                $html .= '<div class="rp-spacer"></div>';
            }
            $prevBottom = $rowTop + ($row[0]['height'] ?? 10);

            // Classify row as multi-column if tokens span left AND right halves
            // with a meaningful gap between them
            $leftTokens  = array_filter($row, fn($t) => $t['left'] < $midpoint);
            $rightTokens = array_filter($row, fn($t) => $t['left'] >= $midpoint);

            $isMultiCol = !empty($leftTokens) && !empty($rightTokens);

            if ($isMultiCol) {
                // Check there's actually a gap (not just one word that crosses middle)
                $maxLeftEdge  = max(array_map(fn($t) => $t['left'] + $t['width'], $leftTokens));
                $minRightEdge = min(array_map(fn($t) => $t['left'], $rightTokens));
                $isMultiCol   = ($minRightEdge - $maxLeftEdge) > $COL_GAP;
            }

            if ($isMultiCol) {
                // Render as two-column flex row
                $leftHtml  = self::renderTokens(array_values($leftTokens));
                $rightHtml = self::renderTokens(array_values($rightTokens));
                $html .= '<div class="rp-row">'
                    . '<div class="rp-col">' . $leftHtml . '</div>'
                    . '<div class="rp-col">' . $rightHtml . '</div>'
                    . '</div>';
            } else {
                // Single column — render all tokens in order
                $html .= self::renderTokens($row);
            }
        }

        return '<div class="rp-doc">' . $html . '</div>';
    }

    /**
     * Render an array of tokens (same row or same column) as styled HTML.
     * Joins them with a space — handles heading vs body via font size.
     */
    private static function renderTokens(array $tokens): string
    {
        if (empty($tokens)) return '';

        // Determine the dominant font size for this cluster
        $sizes    = array_map(fn($t) => $t['size'], $tokens);
        $maxSize  = max($sizes);
        $avgSize  = array_sum($sizes) / count($sizes);

        // Concatenate all token text
        $text = implode(' ', array_map(fn($t) => htmlspecialchars($t['text']), $tokens));

        // Trim leftover noise
        $text = trim($text);
        if ($text === '') return '';

        $len     = mb_strlen(strip_tags($text));
        $isShort = $len < 80;

        // ── Classify by font size ─────────────────────────────────────────────
        // Large + short + all-caps → section heading
        $plain      = strip_tags($text);
        $letters    = preg_replace('/[^a-zA-Z]/', '', $plain);
        $isAllCaps  = $letters !== ''
            && $letters === strtoupper($letters)
            && mb_strlen($letters) >= 2
            && $isShort;

        if ($isAllCaps && $maxSize >= 9) {
            return '<div class="rp-heading">' . $text . '</div>';
        }

        // Large font → name or prominent label
        if ($maxSize >= 16 && $isShort) {
            return '<div class="rp-name">' . $text . '</div>';
        }

        // Medium-large + short → sub-heading / job title
        if ($maxSize >= 12 && $isShort && preg_match('/^[A-Z]/', $plain)) {
            return '<div class="rp-subheading">' . $text . '</div>';
        }

        // Bullet markers
        if (preg_match('/^[✓✔•▸►·◦‣⁃\-\*]\s/', $plain)) {
            $content = preg_replace('/^[✓✔•▸►·◦‣⁃\-\*]\s*/', '', $text);
            return '<div class="rp-bullet"><span class="rp-bull-dot">•</span><span>' . $content . '</span></div>';
        }

        // Contact / meta (has @ | phone pattern, short)
        if ($isShort && preg_match('/[@|]|(\d{3}[\s\-\.]\d{3}[\s\-\.]\d{4})/', $plain)) {
            return '<div class="rp-contact">' . $text . '</div>';
        }

        // Date/period line
        if ($isShort && preg_match('/(19|20)\d{2}|present|current|\bto\b/i', $plain)) {
            return '<div class="rp-meta">' . $text . '</div>';
        }

        // Default body line
        return '<div class="rp-line">' . $text . '</div>';
    }

    /**
     * Get inner XML of a SimpleXMLElement as a string (preserves child tags).
     */
    private static function innerXml(\SimpleXMLElement $el): string
    {
        $dom  = dom_import_simplexml($el);
        $out  = '';
        foreach ($dom->childNodes as $child) {
            $out .= $dom->ownerDocument->saveHTML($child);
        }
        return $out;
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  TIER 3 — OCR via pdftoppm + Tesseract
    // ─────────────────────────────────────────────────────────────────────────
    private static function extractViaOcr(
        string $pdfPath,
        string $pdftoppmBin,
        string $tesseractBin,
        bool   $hasPdftoppm,
        bool   $hasTesseract,
        bool   $isWindows
    ): string {
        if (!$hasTesseract) {
            return '<div class="rp-doc"><div class="rp-line">'
                . '(OCR unavailable — Tesseract not found. Install Tesseract or use a text-based PDF.)'
                . '</div></div>';
        }

        $workDir = sys_get_temp_dir() . '/resume_ocr_' . uniqid();
        $imgBase = $workDir . '/page';
        $ocrText = '';

        try {
            if (!mkdir($workDir, 0755, true)) {
                throw new \Exception('Cannot create OCR work directory');
            }

            $converted = false;

            // Convert PDF pages → images
            if ($hasPdftoppm) {
                $cmd = sprintf(
                    '%s -r 200 -png -l 5 %s %s' . ($isWindows ? '' : ' 2>&1'),
                    escapeshellarg($pdftoppmBin),
                    escapeshellarg($pdfPath),
                    escapeshellarg($imgBase)
                );
                exec($cmd, $out, $rc);
                $converted = ($rc === 0);
            }

            if (!$converted) {
                // Ghostscript fallback
                $cmd = sprintf(
                    'gs -dBATCH -dNOPAUSE -dQUIET -sDEVICE=png16m -r200 -dFirstPage=1 -dLastPage=5 -sOutputFile=%s %s 2>&1',
                    escapeshellarg($imgBase . '-%03d.png'),
                    escapeshellarg($pdfPath)
                );
                exec($cmd, $out, $rc);
                $converted = ($rc === 0);
            }

            if (!$converted) {
                throw new \Exception('Could not convert PDF to images (install pdftoppm or ghostscript)');
            }

            $images = glob($workDir . '/*.png');
            sort($images);

            if (empty($images)) {
                throw new \Exception('No images generated from PDF');
            }

            foreach ($images as $img) {
                $outBase = $img . '_ocr';
                $cmd = sprintf(
                    '%s %s %s -l eng --psm 3' . ($isWindows ? '' : ' 2>/dev/null'),
                    escapeshellarg($tesseractBin),
                    escapeshellarg($img),
                    escapeshellarg($outBase)
                );
                exec($cmd, $tOut, $tRc);

                $txtFile = $outBase . '.txt';
                if (file_exists($txtFile)) {
                    $ocrText .= file_get_contents($txtFile) . "\n\n";
                    @unlink($txtFile);
                }
                @unlink($img);
            }

            $ocrText = trim($ocrText);
            \Log::info('[ResumeAnalyzer] OCR: ' . mb_strlen($ocrText) . ' chars from ' . count($images) . ' page(s)');

        } catch (\Exception $e) {
            \Log::error('[ResumeAnalyzer] OCR failed: ' . $e->getMessage());
            $ocrText = '';
        } finally {
            if (is_dir($workDir)) {
                array_map('unlink', glob($workDir . '/*'));
                @rmdir($workDir);
            }
        }

        if (empty($ocrText)) {
            return '<div class="rp-doc"><div class="rp-line">'
                . '(Could not extract text — PDF may be encrypted or corrupted)'
                . '</div></div>';
        }

        return self::formatExtractedTextAsHtml($ocrText);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  FALLBACK TEXT FORMATTER  (used by Tier 2 / Tier 3 / TXT files)
    //  Single-column, line-by-line classification.
    // ─────────────────────────────────────────────────────────────────────────
    private static function formatExtractedTextAsHtml(string $text): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $html  = '';
        $total = count($lines);

        $firstContentIdx = 0;
        foreach ($lines as $idx => $l) {
            if (trim($l) !== '') { $firstContentIdx = $idx; break; }
        }

        for ($i = 0; $i < $total; $i++) {
            $line    = rtrim($lines[$i]);
            $trimmed = trim($line);

            if ($trimmed === '') {
                $html .= '<div class="rp-spacer"></div>';
                continue;
            }

            $len     = mb_strlen($trimmed);
            $isShort = $len < 60;

            if (preg_match('/^[-=─━═]{4,}$/', $trimmed)) {
                $html .= '<div class="rp-rule"></div>';
                continue;
            }

            if (preg_match('/^[•\-\*▪▸►·◦‣⁃✓✔]\s+(.+)/', $line, $m)) {
                $html .= '<div class="rp-bullet">'
                    . '<span class="rp-bull-dot">•</span>'
                    . '<span>' . htmlspecialchars(trim($m[1])) . '</span>'
                    . '</div>';
                continue;
            }

            if (preg_match('/^\d+[\.)]\s+(.+)/', $line, $m)) {
                $html .= '<div class="rp-bullet">'
                    . '<span class="rp-bull-dot" style="min-width:18px">›</span>'
                    . '<span>' . htmlspecialchars(trim($m[1])) . '</span>'
                    . '</div>';
                continue;
            }

            $lettersOnly = preg_replace('/[^a-zA-Z]/', '', $trimmed);
            $isAllCaps   = $lettersOnly !== ''
                && $lettersOnly === strtoupper($lettersOnly)
                && mb_strlen($lettersOnly) >= 2
                && $isShort;

            if ($isAllCaps) {
                $html .= '<div class="rp-heading">' . htmlspecialchars($trimmed) . '</div>';
                continue;
            }

            if ($i === $firstContentIdx && $isShort && !preg_match('/[@\d]/', $trimmed)) {
                $wc = str_word_count($trimmed);
                if ($wc >= 1 && $wc <= 5) {
                    $html .= '<div class="rp-name">' . htmlspecialchars($trimmed) . '</div>';
                    continue;
                }
            }

            if ($isShort && preg_match('/[@|]|(\d{3}[\s\-\.]\d{3}[\s\-\.]\d{4})/', $trimmed)) {
                $html .= '<div class="rp-contact">' . htmlspecialchars($trimmed) . '</div>';
                continue;
            }

            if ($isShort && preg_match('/(19|20)\d{2}|present|current|\bto\b/i', $trimmed)) {
                $html .= '<div class="rp-meta">' . htmlspecialchars($trimmed) . '</div>';
                continue;
            }

            $html .= '<div class="rp-line">' . htmlspecialchars($trimmed) . '</div>';
        }

        return '<div class="rp-doc">' . $html . '</div>';
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  HELPER — check if a binary is available
    // ─────────────────────────────────────────────────────────────────────────
    private static function binaryExists(string $bin, bool $isWindows): bool
    {
        if (file_exists($bin)) return true;
        if ($isWindows) return false;
        return !empty(trim(shell_exec('which ' . escapeshellarg($bin) . ' 2>/dev/null') ?? ''));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  DEBUG  ·  GET /resume/debug-ocr  (remove in production)
    // ─────────────────────────────────────────────────────────────────────────
    public function debugOcr()
    {
        $isWindows        = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $pdftohtmlBin     = env('PDFTOHTML_PATH', 'C:/Program Files/poppler/Library/bin/pdftohtml.exe');
        $tesseractBin     = env('TESSERACT_PATH', 'C:/Program Files/Tesseract-OCR/tesseract.exe');
        $pdftoppmBin      = env('PDFTOPPM_PATH',  'C:/Program Files/poppler/Library/bin/pdftoppm.exe');
        $shellExecEnabled = function_exists('shell_exec')
            && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))));

        $pdftohtmlOut = $tesseractOut = $pdftoppmOut = '';
        if ($shellExecEnabled) {
            $pdftohtmlOut = shell_exec(escapeshellarg($pdftohtmlBin) . ' -v 2>&1') ?? 'null';
            $tesseractOut = shell_exec(escapeshellarg($tesseractBin) . ' --version 2>&1') ?? 'null';
            $pdftoppmOut  = shell_exec(escapeshellarg($pdftoppmBin)  . ' -v 2>&1') ?? 'null';
        }

        return response()->json([
            'os'                 => PHP_OS,
            'is_windows'         => $isWindows,
            'shell_exec_enabled' => $shellExecEnabled,
            'disable_functions'  => ini_get('disable_functions'),
            'pdftohtml_path'     => $pdftohtmlBin,
            'pdftohtml_exists'   => file_exists($pdftohtmlBin),
            'pdftohtml_output'   => $pdftohtmlOut,
            'tesseract_path'     => $tesseractBin,
            'tesseract_exists'   => file_exists($tesseractBin),
            'tesseract_output'   => $tesseractOut,
            'pdftoppm_path'      => $pdftoppmBin,
            'pdftoppm_exists'    => file_exists($pdftoppmBin),
            'pdftoppm_output'    => $pdftoppmOut,
            'tmp_dir'            => sys_get_temp_dir(),
            'tmp_writable'       => is_writable(sys_get_temp_dir()),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  DOWNLOAD  ·  POST /resume/download/docx
    // ─────────────────────────────────────────────────────────────────────────
    public function downloadDocx(Request $request)
    {
        $markdown = $request->input('resume_markdown');

        if (empty($markdown)) {
            return response()->json(['message' => 'No resume content provided.'], 422);
        }

        $phpWord  = ResumeDocxService::generate($markdown);
        $fileName = 'AI_Optimized_Resume_' . date('Ymd_His') . '.docx';
        $path     = storage_path('app/' . $fileName);

        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return response()->download($path, $fileName)->deleteFileAfterSend(true);
    }
}