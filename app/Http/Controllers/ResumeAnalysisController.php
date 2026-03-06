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
    public function store(Request $request)
    {
        return redirect()->back();
    }

    public function storeAjax(Request $request)
    {
        try {
            // 1️⃣ Validate
            $validator = \Validator::make($request->all(), [
                'resume_file'     => 'required|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain|max:5120',
                'job_description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()
                ], 422);
            }

            $file      = $request->file('resume_file');
            $extension = strtolower($file->getClientOriginalExtension());
            $resumeContent = '';
            $usedOcr = false;

            // 2️⃣ Move to temp & parse
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $extension;
            $file->move(dirname($tempPath), basename($tempPath));

            try {
                switch ($extension) {
                    case 'txt':
                        $resumeContent = '<pre>' . htmlspecialchars(file_get_contents($tempPath)) . '</pre>';
                        break;

                    case 'doc':
                    case 'docx':
                        $phpWord = IOFactory::load($tempPath);
                        $writer  = IOFactory::createWriter($phpWord, 'HTML');
                        ob_start();
                        $writer->save('php://output');
                        $resumeContent = ob_get_clean();
                        break;

                    case 'pdf':
                        // — Try normal text extraction first —
                        $extractedText = '';

                        try {
                            $parser = new PdfParser();
                            $pdf    = $parser->parseFile($tempPath);
                            $extractedText = $pdf->getText();

                            // Fallback: page-by-page
                            if (empty(trim($extractedText))) {
                                $pages = $pdf->getPages();
                                foreach ($pages as $page) {
                                    try {
                                        $extractedText .= $page->getText() . "\n";
                                    } catch (\Exception $pe) {
                                        continue;
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            \Log::warning('PDF text extraction failed: ' . $e->getMessage());
                        }

                        // — If normal extraction worked, use it —
                        if (!empty(trim($extractedText))) {
                            $resumeContent = '<pre>' . htmlspecialchars(trim($extractedText)) . '</pre>';
                        } else {
                            // — Tesseract OCR fallback —
                            \Log::info('PDF text empty — falling back to Tesseract OCR...');
                            $ocrText = $this->extractTextViaTesseract($tempPath);

                            if (!empty(trim($ocrText))) {
                                $resumeContent = '<pre>' . htmlspecialchars(trim($ocrText)) . '</pre>';
                                $usedOcr = true;
                                \Log::info('Tesseract OCR succeeded. Text length: ' . strlen($ocrText));
                            } else {
                                @unlink($tempPath);
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Could not extract text from this PDF. Please try uploading a .docx or .txt version of your resume.'
                                ], 422);
                            }
                        }
                        break;

                    default:
                        throw new \Exception('Unsupported file type.');
                }
            } catch (\Exception $e) {
                @unlink($tempPath);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to read resume: ' . $e->getMessage()
                ], 500);
            }

            @unlink($tempPath);

            // 3️⃣ Save to DB
            $insertResult = DB::select('CALL usp_insert_resume(?, ?, ?)', [
                1,
                $resumeContent,
                $request->job_description
            ]);

            $newId  = $insertResult[0]->inserted_id;
            $result = DB::select('CALL usp_get_resume(?, ?)', [1, $newId]);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'No resume found'
                ], 500);
            }

            // 4️⃣ Clean resume text for AI prompt
            $resumeText = html_entity_decode($resumeContent, ENT_QUOTES | ENT_HTML5);
            $resumeText = preg_replace('/@page\s+[^{]+\{[^}]*\}/i', '', $resumeText);
            $resumeText = preg_replace('/\.[a-zA-Z0-9_-]+\s*\{[^}]*\}/', '', $resumeText);
            $resumeText = strip_tags($resumeText);
            $resumeText = preg_replace('/(Hyperlink|WordSection\d+|NormalTable)/i', '', $resumeText);
            $resumeText = preg_replace('/\r\n|\r/', "\n", $resumeText);
            $resumeText = preg_replace('/\n{2,}/', "\n", $resumeText);
            $resumeText = preg_replace('/\s{2,}/', ' ', $resumeText);
            $resumeText = trim($resumeText);
            $resumeText = mb_substr($resumeText, 0, 4000);

            $jobDescription = trim($request->job_description);

            // 5️⃣ Build AI prompt
            // FIX 2: Stricter score instruction to ensure consistent format and regex extraction
            $prompt = <<<PROMPT
You are an expert resume coach and ATS specialist. Analyze the resume below against the job description and provide a detailed structured report.

RESUME:
{$resumeText}

JOB DESCRIPTION:
{$jobDescription}

Respond ONLY in Markdown using exactly these section headings (##):

## Match Score
You MUST output the score on its very first line in this EXACT format with no variation: **Score: XX/100**
Replace XX with an integer 0-100. Do not write anything before this line. Follow with a one-sentence summary.

## Executive Summary
2-3 sentences summarizing how well this resume fits the role.

## Key Strengths
Bullet list of 3-5 strengths relevant to the job.

## Critical Gaps
Bullet list of 3-5 missing skills or experience areas the job requires.

## Keyword Optimization
List missing keywords/phrases from the job description that should be added to the resume.

## Section-by-Section Improvements
For each section (Summary, Experience, Skills, Education), give specific rewrite suggestions.

## ATS Tips
Bullet list of 3-5 formatting and keyword tips to improve ATS pass rate.

## Quick Wins
Numbered list of the top 5 highest-impact changes to make right now.
PROMPT;

            // 6️⃣ Call Cerebras API
            $suggestionsHtml = '<p>No suggestions available.</p>';
            $markdown        = '';
            $matchScore      = null;

            try {
                $client = new \GuzzleHttp\Client();
                $apiKey = env('CEREBRAS_API_KEY');

                if (empty($apiKey)) {
                    throw new \Exception('CEREBRAS_API_KEY is not set in .env');
                }

                $response = $client->post('https://api.cerebras.ai/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type'  => 'application/json',
                    ],
                    // FIX 1: temperature set to 0 for deterministic, consistent output
                    'json' => [
                        'model'       => 'llama3.1-8b',
                        'max_tokens'  => 2048,
                        'temperature' => 0,
                        'messages'    => [
                            [
                                'role'    => 'system',
                                'content' => 'You are an expert resume coach and ATS specialist. Always respond in well-structured Markdown. Always start the ## Match Score section with **Score: XX/100** on the very first line, where XX is an integer.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $prompt,
                            ],
                        ],
                    ],
                    'timeout' => 60,
                ]);

                $body = json_decode($response->getBody()->getContents(), true);

                \Log::info('Cerebras raw response: ' . json_encode($body));

                if (!empty($body['choices'][0]['message']['content'])) {
                    $markdown = $body['choices'][0]['message']['content'];

                    \Log::info('Cerebras markdown (first 500): ' . mb_substr($markdown, 0, 500));

                    // Extract match score — multiple patterns for robustness
                    $scorePatterns = [
                        '/\*{0,2}Score[:\s]+\*{0,2}(\d{1,3})\*{0,2}\s*\/\s*100/i',
                        '/\*{1,2}(\d{1,3})\*{1,2}\s*\/\s*100/',
                        '/(\d{1,3})\s*out\s*of\s*100/i',
                        '/match[^\d]{0,20}(\d{1,3})\s*\/\s*100/i',
                        '/score[^\d]{0,20}(\d{1,3})/i',
                        '/(\d{1,3})\s*\/\s*100/',
                    ];

                    foreach ($scorePatterns as $pattern) {
                        if (preg_match($pattern, $markdown, $m)) {
                            $val = (int) $m[1];
                            if ($val >= 0 && $val <= 100) {
                                $matchScore = $val;
                                break;
                            }
                        }
                    }

                    \Log::info('Extracted match score: ' . ($matchScore ?? 'null'));

                    $converter = new CommonMarkConverter([
                        'html_input'         => 'strip',
                        'allow_unsafe_links' => false,
                    ]);

                    $suggestionsHtml = $converter->convert($markdown)->getContent();
                }

            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $errorBody = $e->getResponse()->getBody()->getContents();
                \Log::error('Cerebras API ClientException: ' . $errorBody);
                $suggestionsHtml = '<p>AI Error (4xx): ' . htmlspecialchars($errorBody) . '</p>';
                $markdown        = '## Error' . "\n" . $errorBody;
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                $errorBody = $e->getResponse()->getBody()->getContents();
                \Log::error('Cerebras API ServerException: ' . $errorBody);
                $suggestionsHtml = '<p>AI Error (5xx): ' . htmlspecialchars($errorBody) . '</p>';
                $markdown        = '## Error' . "\n" . $errorBody;
            } catch (\Exception $e) {
                \Log::error('Cerebras API General Error: ' . $e->getMessage());
                $suggestionsHtml = '<p>AI Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                $markdown        = '## Error' . "\n" . $e->getMessage();
            }

            // 7️⃣ Return result
            return response()->json([
                'success' => true,
                'resume'  => [
                    'resume_content'       => $resumeContent,
                    'suggestions_html'     => $suggestionsHtml,
                    'suggestions_markdown' => $markdown,
                    'match_score'          => $matchScore,
                    'used_ocr'             => $usedOcr,
                ]
            ]);

        } catch (\Throwable $e) {
            \Log::error('Resume AJAX Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Extract text from an image-based PDF using:
     *   1. pdftoppm (from .env PDFTOPPM_PATH) → converts PDF pages to PNG
     *   2. Tesseract (from .env TESSERACT_PATH) → OCR each PNG → text
     */
    private function extractTextViaTesseract(string $pdfPath): string
    {
        // Read paths from .env
        $tesseractBin = env('TESSERACT_PATH', 'tesseract');
        $pdftoppmBin  = env('PDFTOPPM_PATH',  'pdftoppm');

        // Verify both binaries exist
        if (!file_exists($tesseractBin)) {
            \Log::error('Tesseract binary not found at: ' . $tesseractBin);
            return '';
        }

        if (!file_exists($pdftoppmBin)) {
            \Log::error('pdftoppm binary not found at: ' . $pdftoppmBin);
            return '';
        }

        // Create isolated temp working directory
        $workDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('tess_ocr_');
        mkdir($workDir, 0755, true);

        $allText  = '';
        $pageBase = $workDir . DIRECTORY_SEPARATOR . 'page';

        try {
            // ── Step 1: PDF → PNG pages via pdftoppm ────────────────────
            $cmd = '"' . $pdftoppmBin . '"'
                . ' -png'
                . ' -r 300'
                . ' "' . $pdfPath . '"'
                . ' "' . $pageBase . '"'
                . ' 2>&1';

            exec($cmd, $ppmOutput, $ppmCode);
            \Log::info('pdftoppm exit code: ' . $ppmCode);
            \Log::info('pdftoppm output: ' . implode(' | ', $ppmOutput));

            $pageFiles = glob($workDir . DIRECTORY_SEPARATOR . 'page-*.png') ?: [];

            // Some versions of pdftoppm use page-01.png, page-001.png etc.
            if (empty($pageFiles)) {
                $pageFiles = glob($workDir . DIRECTORY_SEPARATOR . 'page*.png') ?: [];
            }

            if (empty($pageFiles)) {
                \Log::error('pdftoppm produced no PNG files. Output: ' . implode(' ', $ppmOutput));
                return '';
            }

            sort($pageFiles); // ensure correct page order
            $pageFiles = array_slice($pageFiles, 0, 6); // cap at 6 pages

            \Log::info('pdftoppm produced ' . count($pageFiles) . ' page(s).');

            // ── Step 2: Tesseract OCR on each PNG ───────────────────────
            foreach ($pageFiles as $pagePng) {
                $outBase = $workDir . DIRECTORY_SEPARATOR
                    . pathinfo($pagePng, PATHINFO_FILENAME) . '_out';

                $cmd = '"' . $tesseractBin . '"'
                    . ' "' . $pagePng . '"'
                    . ' "' . $outBase . '"'
                    . ' -l eng'   // English language
                    . ' --oem 3' // LSTM engine — best accuracy
                    . ' --psm 1' // Auto page segmentation + OSD
                    . ' 2>&1';

                exec($cmd, $tessOutput, $tessCode);
                \Log::info('Tesseract on ' . basename($pagePng) . ' exit: ' . $tessCode);

                $txtFile = $outBase . '.txt';
                if (file_exists($txtFile)) {
                    $pageText = file_get_contents($txtFile);
                    $allText .= $pageText . "\n\n";
                    @unlink($txtFile);
                } else {
                    \Log::warning('Tesseract produced no .txt for: ' . basename($pagePng));
                    \Log::warning('Tesseract output: ' . implode(' | ', $tessOutput));
                }

                @unlink($pagePng);
            }

        } finally {
            // Clean up any leftover files and the work directory
            $leftovers = glob($workDir . DIRECTORY_SEPARATOR . '*') ?: [];
            foreach ($leftovers as $f) {
                @unlink($f);
            }
            @rmdir($workDir);
        }

        return trim($allText);
    }

    public function downloadDocx(Request $request)
    {
        $markdown = $request->input('resume_markdown');

        if (!$markdown) {
            return response()->json(['message' => 'No resume content provided'], 422);
        }

        $phpWord  = ResumeDocxService::generate($markdown);
        $fileName = 'AI_Optimized_Resume.docx';
        $path     = storage_path("app/$fileName");

        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
}