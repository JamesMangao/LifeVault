<?php

use App\Http\Controllers\ResumeAnalysisController;
use App\Http\Controllers\LifeStoryAIController;
use App\Http\Controllers\ShadowSelfAIController;
use App\Http\Controllers\HolisticCareerAdvisorController;

Route::get('/', function () {
    return view('welcome');
});

// ── Resume Analysis ───────────────────────────────────────────
Route::post('/analyze-ajax', [ResumeAnalysisController::class, 'storeAjax'])
    ->name('analyze.resume.ajax');

Route::post('/resume/download-docx', [ResumeAnalysisController::class, 'downloadDocx'])
    ->name('resume.download.docx');

// ── Life Story AI ─────────────────────────────────────────────
Route::post('/ai/life-story/generate', [LifeStoryAIController::class, 'generate'])
    ->name('ai.life-story.generate');

Route::get('/ai/life-story/models', [LifeStoryAIController::class, 'models'])
    ->name('ai.life-story.models');

// ── Shadow Self AI ────────────────────────────────────────────
Route::post('/ai/shadow-self/analyze', [ShadowSelfAIController::class, 'analyzeShadowSelf'])
    ->name('ai.shadow-self.analyze');

Route::post('/ai/holistic-career/analyze', [HolisticCareerAdvisorController::class, 'analyze'])
     ->name('ai.holistic-career.analyze');

// ── AI Chat API ──────────────────────────────────────────────
Route::post('/api/chat', function (\Illuminate\Http\Request $request) {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
        'HTTP-Referer'  => config('app.url'),
        'X-Title'       => 'LifeVault',
    ])->post('https://openrouter.ai/api/v1/chat/completions', $request->all());

    return $response->json();
})->middleware('web');

Route::get('/insights', function () {
    return view('insights');
})->name('insights');