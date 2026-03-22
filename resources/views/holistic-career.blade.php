<div id="page-holistic-career" class="page">
    <div class="page-header">
        <div>
            <h1 class="page-title">🔮 Holistic Career Advisor</h1>
            <p class="page-subtitle" style="font-family:'Newsreader',serif;font-style:italic;color:var(--muted);font-size:.88rem;margin-top:4px">
                Bridge your professional skills with your authentic self
            </p>
        </div>
        <button class="btn" id="hca-submit-btn" onclick="holisticCareerAnalyze()"
                style="background:linear-gradient(135deg,rgba(79,142,247,.15),rgba(167,139,250,.15));border-color:rgba(79,142,247,.35);color:var(--accent);font-weight:700">
            🔮 Generate My Holistic Career Report
        </button>
    </div>

    {{-- ── AI Stack Banner ────────────────────────────────────── --}}
    <div style="
        background:linear-gradient(135deg,rgba(167,139,250,.07),rgba(45,212,191,.05));
        border:1px solid rgba(167,139,250,.2);
        border-radius:16px;padding:18px 22px;margin-bottom:28px;
        display:flex;align-items:flex-start;gap:14px;
    ">
        <span style="font-size:1.4rem;flex-shrink:0;margin-top:2px">🔮</span>
        <div>
            <div style="font-size:.82rem;font-weight:700;margin-bottom:4px;color:var(--text);display:flex;align-items:center;gap:10px">
                Your Whole-Self Career Guide
                <span style="
                    font-family:'JetBrains Mono',monospace;font-size:.5rem;font-weight:700;
                    text-transform:uppercase;letter-spacing:.06em;
                    padding:2px 7px;border-radius:5px;
                    background:linear-gradient(135deg,rgba(124,58,237,.3),rgba(167,139,250,.15));
                    border:1px solid rgba(167,139,250,.35);color:#c4b5fd;
                ">New</span>
            </div>
            <div style="font-family:'Newsreader',serif;font-size:.85rem;color:var(--muted);line-height:1.65;font-weight:300">
                The Holistic Career Advisor goes beyond your resume. It reads your journal entries to surface the values, fears, and passions driving your choices — then bridges your professional skills with your authentic self to reveal career paths that truly fit, shadow patterns that may be holding you back, and a more compelling story to tell the world.
            </div>
        </div>
    </div>

    {{-- ── Alert ──────────────────────────────────────────────── --}}
    <div id="hca-alert" style="display:none;padding:10px 14px;border-radius:8px;font-size:.82rem;margin-bottom:16px;font-family:'JetBrains Mono',monospace"></div>

    {{-- ── TWO COLUMN LAYOUT ──────────────────────────────────── --}}
    <div class="two-col-layout" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:start">

        {{-- ── LEFT COLUMN: Inputs ────────────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:14px">

            {{-- Resume Card --}}
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
                    <span style="font-size:1.1rem">📄</span>
                    <div>
                        <div style="font-size:.85rem;font-weight:700">Your Resume</div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)">Paste plain text or use from Resume Analyzer</div>
                    </div>
                </div>

                {{-- Enhanced badge + clear button row --}}
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;min-height:28px">
                    <div id="hca-resume-prefill-badge" style="
                        display:none;
                        font-family:'JetBrains Mono',monospace;font-size:.6rem;
                        padding:4px 10px;border-radius:6px;
                        background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.25);color:#34d399;
                        display:flex;align-items:center;gap:5px;
                    ">
                        <span style="font-size:.7rem">✓</span> Pre-filled from Resume Analyzer
                    </div>
                    <button id="hca-resume-clear-btn" onclick="_hcaClearResume()" class="hca-clear-btn">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                            <path d="M1 1L9 9M9 1L1 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Clear
                    </button>
                </div>

                <textarea
                    id="hca-resume-text"
                    placeholder="Paste your resume here — work experience, skills, education, summary...&#10;&#10;Tip: run the Resume Analyzer first and it will auto-fill here."
                    style="
                        width:100%;min-height:180px;resize:vertical;
                        background:var(--surface2);border:1px solid var(--border);
                        border-radius:8px;color:var(--text);padding:10px 12px;
                        font-family:'JetBrains Mono',monospace;font-size:.72rem;
                        line-height:1.6;box-sizing:border-box;
                        transition:border-color .15s;
                    "
                    onfocus="this.style.borderColor='var(--lavender)'"
                    onblur="this.style.borderColor='var(--border)'"
                ></textarea>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:6px">
                    <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted)">
                        Min 100 chars · Max 6,000 chars
                    </div>
                    <div id="hca-char-count" style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);transition:color .2s">
                        0 / 6,000
                    </div>
                </div>
            </div>

            {{-- Target Role Card --}}
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                    <span style="font-size:1.1rem">🎯</span>
                    <div>
                        <div style="font-size:.85rem;font-weight:700">Career Direction <span style="font-weight:400;color:var(--muted)">(optional)</span></div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)">Leave blank for open-ended analysis</div>
                    </div>
                </div>
                <input
                    id="hca-job-target"
                    type="text"
                    placeholder="e.g. Senior Product Manager, UX Designer, Career Change to Coaching…"
                    style="
                        width:100%;background:var(--surface2);border:1px solid var(--border);
                        border-radius:8px;color:var(--text);padding:9px 12px;
                        font-family:'JetBrains Mono',monospace;font-size:.75rem;
                        box-sizing:border-box;transition:border-color .15s;
                    "
                    onfocus="this.style.borderColor='var(--lavender)'"
                    onblur="this.style.borderColor='var(--border)'"
                />
            </div>

            {{-- Journal Status Card --}}
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                    <span style="font-size:1.1rem">📔</span>
                    <div>
                        <div style="font-size:.85rem;font-weight:700">Your Journal Entries</div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)">Auto-loaded from your journal</div>
                    </div>
                </div>

                <div id="hca-journal-status" style="
                    padding:10px 12px;border-radius:8px;
                    background:var(--surface2);border:1px solid var(--border);
                    font-family:'JetBrains Mono',monospace;font-size:.72rem;
                    color:var(--muted);line-height:1.5;
                ">
                    ⏳ Checking journal entries…
                </div>

                <div style="
                    margin-top:10px;padding:10px 12px;border-radius:8px;
                    background:rgba(79,142,247,.05);border:1px solid rgba(79,142,247,.12);
                    font-family:'Newsreader',serif;font-size:.78rem;color:var(--muted);
                    line-height:1.65;font-style:italic;
                ">
                    The AI reads your journal themes to identify your values, fears, and passions —
                    never the raw text. Your entries are only used for this analysis.
                </div>
            </div>

        </div>{{-- ── END LEFT COLUMN ── --}}

        {{-- ── RIGHT COLUMN: Results ──────────────────────────── --}}
        <div id="hca-results-panel" style="display:flex;flex-direction:column;gap:14px;position:sticky;top:24px;align-self:start">

            {{-- Empty state --}}
            <div id="hca-empty-state" class="card" style="
                display:flex;flex-direction:column;align-items:center;justify-content:center;
                text-align:center;padding:48px 24px;min-height:300px;
            ">
                <div style="font-size:3rem;margin-bottom:16px;opacity:.6">🔮</div>
                <div style="font-size:.9rem;font-weight:700;color:var(--text);margin-bottom:8px">
                    Your Report Will Appear Here
                </div>
                <div style="font-family:'Newsreader',serif;font-size:.82rem;color:var(--muted);line-height:1.7;max-width:280px;font-style:italic">
                    Fill in your resume and let the AI synthesize your journals to reveal your authentic career path.
                </div>

                {{-- How it works steps --}}
                <div style="margin-top:28px;display:flex;flex-direction:column;gap:10px;width:100%;max-width:300px;text-align:left">
                    @foreach([
                        ['📄','Resume skills & experience','var(--accent)'],
                        ['📔','Journal values & fears','var(--lavender)'],
                        ['🧠','Shadow pattern detection','var(--rose)'],
                        ['✨','Authentic narrative crafting','var(--amber)'],
                    ] as [$icon, $label, $color])
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:8px;background:var(--surface2);border:1px solid var(--border)">
                        <span style="font-size:.9rem">{{ $icon }}</span>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted)">{{ $label }}</span>
                        <div style="margin-left:auto;width:6px;height:6px;border-radius:50%;background:{{ $color }};flex-shrink:0"></div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Loading state (hidden by default) --}}
            <div id="hca-loading-state" class="card" style="
                display:none;flex-direction:column;align-items:center;justify-content:center;
                text-align:center;padding:48px 24px;min-height:300px;
            ">
                <div class="hca-spinner"></div>
                <div style="font-size:.9rem;font-weight:700;color:var(--text);margin-bottom:16px">
                    🔮 Synthesizing Your Holistic Profile…
                </div>
                <div id="hca-loading-steps" style="display:flex;flex-direction:column;gap:8px;width:100%;max-width:280px">
                    <div class="hca-step" id="hca-step-1">📄 Reading your resume…</div>
                    <div class="hca-step" id="hca-step-2">📔 Analyzing journal themes…</div>
                    <div class="hca-step" id="hca-step-3">🧠 Detecting shadow patterns…</div>
                    <div class="hca-step" id="hca-step-4">✨ Crafting authentic narrative…</div>
                </div>
            </div>

            {{-- Score card (hidden until result) --}}
            <div id="hca-score-card" class="card hca-result-card" style="display:none">
                <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                    {{-- SVG Gauge --}}
                    <div style="flex-shrink:0;position:relative">
                        <svg id="hca-gauge-svg" viewBox="0 0 120 120" width="110" height="110">
                            <circle cx="60" cy="60" r="48" fill="none" stroke="var(--surface2)" stroke-width="10"/>
                            <circle id="hca-gauge-arc" cx="60" cy="60" r="48"
                                fill="none" stroke="#7c3aed" stroke-width="10"
                                stroke-dasharray="301.6" stroke-dashoffset="301.6"
                                stroke-linecap="round"
                                transform="rotate(-90 60 60)"
                                style="transition:stroke-dashoffset 1.2s ease-out,stroke .4s"/>
                            <text id="hca-score-text" x="60" y="56" text-anchor="middle"
                                fill="var(--text)" font-size="22" font-weight="800" font-family="Syne,sans-serif">—</text>
                            <text x="60" y="71" text-anchor="middle"
                                fill="var(--muted)" font-size="9" font-family="JetBrains Mono,monospace">/ 100</text>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:160px">
                        <div id="hca-score-label" style="font-size:1rem;font-weight:800;margin-bottom:4px;color:#a78bfa">—</div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.06em">Authentic Alignment Score</div>
                        <div id="hca-score-desc" style="font-family:'Newsreader',serif;font-size:.82rem;color:var(--muted);line-height:1.6;font-style:italic">—</div>
                    </div>
                </div>
                {{-- Meta row --}}
                <div id="hca-meta-row" style="display:flex;gap:6px;flex-wrap:wrap;margin-top:14px;padding-top:12px;border-top:1px solid var(--border)"></div>
            </div>

            {{-- Report Content (hidden until result) --}}
            <div id="hca-report-card" class="card hca-result-card" style="display:none">
                {{-- Report toolbar --}}
                <div class="hca-report-toolbar">
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="font-size:.75rem">📋</span>
                        <span style="font-size:.82rem;font-weight:700;color:var(--text)">Full Report</span>
                        <span id="hca-report-timestamp" style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:var(--muted)"></span>
                    </div>
                    <div style="display:flex;gap:6px;align-items:center">
                        <button onclick="hcaCopyMarkdown()" class="hca-action-btn hca-action-btn--ghost" title="Copy as Markdown">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            Copy
                        </button>
                        <button onclick="window.print()" class="hca-action-btn hca-action-btn--ghost" title="Print report">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            Print
                        </button>
                        <button onclick="hcaSaveReport()" class="hca-action-btn hca-action-btn--primary" title="Save to Saved Items">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Save
                        </button>
                    </div>
                </div>

                {{-- Section nav pills (populated by JS) --}}
                <div id="hca-section-nav" style="display:none;gap:6px;flex-wrap:wrap;padding:0 0 14px;border-bottom:1px solid var(--border);margin-bottom:16px"></div>

                {{-- Report body --}}
                <div id="hca-report-content" style="
                    font-family:'Newsreader',serif;
                    font-size:.88rem;
                    line-height:1.75;
                    color:rgba(232,234,240,.88);
                "></div>

                {{-- Regenerate footer --}}
                <div style="
                    margin-top:20px;padding-top:16px;border-top:1px solid var(--border);
                    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;
                ">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)">
                        Generated by Claude · Results are for own reflection.
                    </span>
                    <button onclick="holisticCareerAnalyze()" class="hca-action-btn hca-action-btn--ghost" style="font-size:.65rem">
                        🔄 Regenerate
                    </button>
                </div>
            </div>

        </div>{{-- ── END RIGHT COLUMN ── --}}

    </div>{{-- ── END TWO-COL LAYOUT ── --}}
</div>

{{-- ── Inline styles scoped to this page ─────────────────────── --}}
<style>
/* ── Clear button ─────────────────────────────────────────────── */
.hca-clear-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 7px;
    border: 1px solid rgba(239,68,68,.25);
    background: rgba(239,68,68,.06);
    color: rgba(248,113,113,.7);
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: .03em;
    transition: all .18s ease;
    white-space: nowrap;
}
.hca-clear-btn:hover {
    background: rgba(239,68,68,.14);
    border-color: rgba(239,68,68,.45);
    color: #f87171;
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(239,68,68,.15);
}
.hca-clear-btn:active {
    transform: translateY(0);
    box-shadow: none;
}

/* ── Spinner ──────────────────────────────────────────────────── */
.hca-spinner {
    width: 44px; height: 44px;
    border: 3px solid var(--surface2);
    border-top-color: #7c3aed;
    border-right-color: rgba(124,58,237,.4);
    border-radius: 50%;
    animation: hca-spin .75s linear infinite;
    margin: 0 auto 20px;
}
@keyframes hca-spin { to { transform: rotate(360deg); } }

/* ── Loading step states ──────────────────────────────────────── */
.hca-step {
    font-family: 'JetBrains Mono', monospace;
    font-size: .65rem;
    padding: 7px 12px;
    border-radius: 7px;
    border: 1px solid transparent;
    color: var(--muted);
    background: transparent;
    transition: all .35s ease;
}
.hca-step-active {
    background: rgba(167,139,250,.1) !important;
    border-color: rgba(167,139,250,.3) !important;
    color: #c4b5fd !important;
    padding-left: 16px !important;
}
.hca-step-active::before { content: '▶ '; opacity: .7; }
.hca-step-done {
    background: rgba(52,211,153,.06) !important;
    border-color: rgba(52,211,153,.18) !important;
    color: rgba(52,211,153,.7) !important;
    text-decoration: line-through;
    text-decoration-color: rgba(52,211,153,.35);
}

/* ── Result cards — entrance animation ───────────────────────── */
.hca-result-card {
    animation: hca-slide-in .4s cubic-bezier(.16,1,.3,1) both;
}
@keyframes hca-slide-in {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Report toolbar ───────────────────────────────────────────── */
.hca-report-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
    gap: 10px;
    flex-wrap: wrap;
}

/* ── Action buttons ───────────────────────────────────────────── */
.hca-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 7px;
    font-family: 'JetBrains Mono', monospace;
    font-size: .62rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s ease;
    white-space: nowrap;
    letter-spacing: .02em;
}
.hca-action-btn--ghost {
    border: 1px solid var(--border);
    background: var(--surface2);
    color: var(--muted);
}
.hca-action-btn--ghost:hover {
    border-color: rgba(255,255,255,.15);
    color: var(--text);
    background: rgba(255,255,255,.06);
    transform: translateY(-1px);
}
.hca-action-btn--primary {
    border: 1px solid rgba(167,139,250,.35);
    background: rgba(167,139,250,.1);
    color: #c4b5fd;
}
.hca-action-btn--primary:hover {
    background: rgba(167,139,250,.2);
    border-color: rgba(167,139,250,.5);
    color: #ddd6fe;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(167,139,250,.15);
}

/* ── Section nav pills ────────────────────────────────────────── */
.hca-nav-pill {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 99px;
    border: 1px solid var(--border);
    background: var(--surface2);
    color: var(--muted);
    cursor: pointer;
    transition: all .15s ease;
    letter-spacing: .03em;
}
.hca-nav-pill:hover {
    border-color: rgba(167,139,250,.4);
    color: #c4b5fd;
    background: rgba(167,139,250,.08);
}

/* ── Report typography ────────────────────────────────────────── */
#hca-report-content h2 {
    font-family: 'Syne', sans-serif;
    font-size: .95rem; font-weight: 800;
    color: var(--text);
    margin: 1.8rem 0 .65rem;
    padding-bottom: .5rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 6px;
    scroll-margin-top: 16px;
}
#hca-report-content h2:first-child { margin-top: 0; }
#hca-report-content h3 {
    font-family: 'Syne', sans-serif;
    font-size: .82rem; font-weight: 700;
    color: var(--text); margin: 1rem 0 .4rem;
}
#hca-report-content ul  { padding-left: 1.4rem; margin: .4rem 0; }
#hca-report-content li  { margin-bottom: .4rem; color: rgba(232,234,240,.82); }
#hca-report-content p   { margin-bottom: .65rem; }
#hca-report-content strong { color: var(--text); font-weight: 700; }
#hca-report-content em  { color: var(--lavender); font-style: italic; }
#hca-report-content ol  { padding-left: 1.4rem; margin: .4rem 0; }
#hca-report-content ol li { margin-bottom: .5rem; }
#hca-report-content blockquote {
    border-left: 3px solid rgba(167,139,250,.5);
    padding: .5rem .9rem; margin: .8rem 0;
    background: rgba(167,139,250,.05);
    border-radius: 0 8px 8px 0;
    font-style: italic; color: rgba(232,234,240,.65);
}
#hca-report-content code {
    font-family: 'JetBrains Mono', monospace; font-size: .75em;
    background: var(--surface2); padding: 1px 5px; border-radius: 4px;
    color: var(--accent);
}

/* ── Char counter color states ────────────────────────────────── */
.hca-char-ok    { color: var(--green)  !important; }
.hca-char-warn  { color: var(--amber)  !important; }
.hca-char-over  { color: var(--rose)   !important; }

/* ── Responsive ───────────────────────────────────────────────── */
@media (max-width: 768px) {
    .two-col-layout { grid-template-columns: 1fr !important; }
    #hca-results-panel { position: static !important; }
    .hca-report-toolbar { flex-direction: column; align-items: flex-start; }
}
</style>

@push('scripts')
<script>
/* ══════════════════════════════════════════════════════════════
   HOLISTIC CAREER ADVISOR — client-side logic
══════════════════════════════════════════════════════════════ */

// ── Char counter ──────────────────────────────────────────────
(function _initCharCounter() {
    function _setup() {
        const ta = document.getElementById('hca-resume-text');
        const counter = document.getElementById('hca-char-count');
        if (!ta || !counter) return;
        ta.addEventListener('input', () => {
            const n = ta.value.length;
            counter.textContent = n.toLocaleString() + ' / 6,000';
            counter.className = n > 6000 ? 'hca-char-over' : n >= 5000 ? 'hca-char-warn' : n >= 100 ? 'hca-char-ok' : '';
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', _setup);
    else _setup();
})();

// ── On page load: prefill resume + check journals ─────────────
document.addEventListener('DOMContentLoaded', () => {
    _hcaPrefillResume();
    const journalCheck = setInterval(() => {
        if (typeof window.journals !== 'undefined') {
            clearInterval(journalCheck);
            _hcaCheckJournals();
        }
    }, 250);
});

// Re-check whenever user navigates to this page
const _origHcaNavigateTo = window.navigateTo;
window.navigateTo = function(page, event) {
    _origHcaNavigateTo(page, event);
    if (page === 'holistic-career') {
        _hcaPrefillResume();
        setTimeout(_hcaCheckJournals, 300);
    }
};

// ── Prefill resume ────────────────────────────────────────────
function _hcaPrefillResume() {
    let cached = sessionStorage.getItem('lifevault_resume_text');
    const source = sessionStorage.getItem('lifevault_resume_source');
    if (cached) {
        const tmp = document.createElement('div');
        tmp.innerHTML = cached;
        tmp.querySelectorAll('style,script').forEach(el => el.remove());
        cached = (tmp.textContent || tmp.innerText || '').trim();
    }
    const textarea = document.getElementById('hca-resume-text');
    const badge    = document.getElementById('hca-resume-prefill-badge');
    if (badge) badge.style.display = 'none';
    if (cached && textarea && !textarea.value && source === 'analyzer') {
        textarea.value = cached;
        if (badge) badge.style.display = 'flex';
        // update char counter
        textarea.dispatchEvent(new Event('input'));
    }
    if (textarea) {
        textarea.addEventListener('input', () => {
            if (badge) badge.style.display = 'none';
            sessionStorage.removeItem('lifevault_resume_source');
        });
    }
}

// ── Check journals ────────────────────────────────────────────
function _hcaCheckJournals() {
    const statusEl = document.getElementById('hca-journal-status');
    if (!statusEl) return;
    const entries = (window.journals && window.journals.length) ? window.journals : [];
    if (entries.length === 0) {
        statusEl.style.cssText += ';background:rgba(251,191,36,.06);border-color:rgba(251,191,36,.25);color:#fbbf24';
        statusEl.textContent = '⚠️ No journal entries found. Please write at least 3 entries first.';
    } else if (entries.length < 3) {
        statusEl.style.cssText += ';background:rgba(251,191,36,.06);border-color:rgba(251,191,36,.25);color:#fbbf24';
        statusEl.textContent = `⚠️ Only ${entries.length} journal ${entries.length === 1 ? 'entry' : 'entries'} found. At least 3 needed.`;
    } else {
        statusEl.style.cssText += ';background:rgba(52,211,153,.06);border-color:rgba(52,211,153,.2);color:#34d399';
        statusEl.textContent = `✓ ${entries.length} journal ${entries.length === 1 ? 'entry' : 'entries'} ready for synthesis`;
    }
}

// ── Loading step animator ─────────────────────────────────────
let _hcaStepTimer = null;
function _hcaAnimateSteps() {
    const steps = ['hca-step-1','hca-step-2','hca-step-3','hca-step-4'];
    steps.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.className = 'hca-step';
    });
    let i = 0;
    _hcaStepTimer = setInterval(() => {
        if (i > 0) {
            const prev = document.getElementById(steps[i - 1]);
            if (prev) { prev.classList.remove('hca-step-active'); prev.classList.add('hca-step-done'); }
        }
        if (i < steps.length) {
            const cur = document.getElementById(steps[i]);
            if (cur) cur.classList.add('hca-step-active');
            i++;
        } else {
            clearInterval(_hcaStepTimer);
        }
    }, 3500);
}

// ── Format journal entries ────────────────────────────────────
function _hcaFormatJournals() {
    return (window.journals || []).slice(0, 20).map(e => ({
        title:     e.title     || 'Untitled',
        content:   (e.content  || '').substring(0, 500),
        mood:      e.mood      || 3,
        createdAt: e.createdAt ? (e.createdAt instanceof Date ? e.createdAt.toISOString() : e.createdAt) : null,
    }));
}

// ── Main analyze function ─────────────────────────────────────
window.holisticCareerAnalyze = async function() {
    const resumeText = document.getElementById('hca-resume-text')?.value?.trim();
    const jobTarget  = document.getElementById('hca-job-target')?.value?.trim() || '';
    const entries    = _hcaFormatJournals();

    if (!resumeText || resumeText.length < 100) {
        _hcaShowAlert('Please paste your resume text (minimum 100 characters).', 'error'); return;
    }
    if (entries.length < 3) {
        _hcaShowAlert('You need at least 3 journal entries. Head to the Journal page first! 📓', 'warning'); return;
    }

    _hcaSetLoading(true);
    _hcaAnimateSteps();
    _hcaHideAlert();

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/ai/holistic-career/analyze', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ resume_text: resumeText, job_title_target: jobTarget, journal_entries: entries }),
        });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || `Server error (${response.status})`);

        sessionStorage.setItem('lifevault_resume_text', resumeText);
        sessionStorage.removeItem('lifevault_resume_source');

        _hcaRenderScore(data.report);
        _hcaRenderReport(data.report);
        window._hcaLastReport = data.report;

        toast('Holistic Career Report ready! 🔮', '✨');
    } catch (err) {
        console.error('[HolisticCareer]', err);
        _hcaShowAlert('Analysis failed: ' + err.message, 'error');
        document.getElementById('hca-empty-state').style.display  = 'flex';
        document.getElementById('hca-score-card').style.display   = 'none';
        document.getElementById('hca-report-card').style.display  = 'none';
    } finally {
        _hcaSetLoading(false);
        clearInterval(_hcaStepTimer);
        // mark remaining steps done
        ['hca-step-1','hca-step-2','hca-step-3','hca-step-4'].forEach(id => {
            const el = document.getElementById(id);
            if (el && !el.classList.contains('hca-step-done')) {
                el.classList.remove('hca-step-active');
                el.classList.add('hca-step-done');
            }
        });
    }
};

// ── Render score gauge ────────────────────────────────────────
function _hcaRenderScore(report) {
    const scoreCard = document.getElementById('hca-score-card');
    const score = report.alignment_score;
    if (score === null || score === undefined) { scoreCard.style.display = 'none'; return; }

    scoreCard.style.display = 'block';
    const circumference = 2 * Math.PI * 48;
    const arc    = document.getElementById('hca-gauge-arc');
    const offset = circumference - (score / 100) * circumference;
    const meta   = _hcaScoreMeta(score);

    if (arc) {
        arc.style.strokeDasharray  = circumference;
        arc.style.strokeDashoffset = circumference;
        arc.style.stroke           = meta.color;
        requestAnimationFrame(() => setTimeout(() => { arc.style.strokeDashoffset = offset; }, 100));
    }

    const scoreText  = document.getElementById('hca-score-text');
    const scoreLabel = document.getElementById('hca-score-label');
    const scoreDesc  = document.getElementById('hca-score-desc');
    const metaRow    = document.getElementById('hca-meta-row');

    if (scoreText)  { scoreText.textContent = score; scoreText.style.fill = meta.color; }
    if (scoreLabel) { scoreLabel.textContent = meta.emoji + ' ' + meta.label; scoreLabel.style.color = meta.color; }
    if (scoreDesc)  scoreDesc.textContent = meta.description;

    if (metaRow) {
        const badges = [
            ['🎯 ' + (report.job_target !== 'Not specified' ? report.job_target : 'Open analysis'), 'rgba(79,142,247,.12)', 'var(--accent)'],
            ['📔 ' + report.entries_count + ' journals', 'rgba(167,139,250,.1)', 'var(--lavender)'],
            ['✦ AI Analysis', 'rgba(52,211,153,.1)', '#34d399'],
        ];
        metaRow.innerHTML = badges.map(([label, bg, color]) =>
            `<span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;padding:3px 8px;border-radius:5px;background:${bg};color:${color}">${label}</span>`
        ).join('');
    }
}

// ── Render report + section nav ───────────────────────────────
function _hcaRenderReport(report) {
    const reportCard    = document.getElementById('hca-report-card');
    const reportContent = document.getElementById('hca-report-content');
    const emptyState    = document.getElementById('hca-empty-state');
    const sectionNav    = document.getElementById('hca-section-nav');
    const timestamp     = document.getElementById('hca-report-timestamp');

    if (emptyState)  emptyState.style.display  = 'none';
    reportCard.style.display = 'block';
    reportContent.innerHTML  = report.report_html;

    // Timestamp
    if (timestamp) {
        timestamp.textContent = '· ' + new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    // Build section nav from h2 headings
    if (sectionNav) {
        const headings = reportContent.querySelectorAll('h2');
        if (headings.length > 1) {
            sectionNav.style.display = 'flex';
            sectionNav.innerHTML = Array.from(headings).map((h, i) => {
                const id = 'hca-section-' + i;
                h.id = id;
                const label = h.textContent.replace(/^[^\w]+/, '').trim().split(' ').slice(0, 4).join(' ');
                return `<button class="hca-nav-pill" onclick="document.getElementById('${id}')?.scrollIntoView({behavior:'smooth',block:'start'})">${label}</button>`;
            }).join('');
        } else {
            sectionNav.style.display = 'none';
        }
    }

    reportCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── Score metadata ────────────────────────────────────────────
function _hcaScoreMeta(score) {
    if (score >= 80) return { label:'Highly Aligned',     color:'#34d399', emoji:'🌟', description:'Your career path and authentic self are in strong harmony.' };
    if (score >= 60) return { label:'Moderately Aligned', color:'#a78bfa', emoji:'🌱', description:'Good alignment with room to grow into full authenticity.' };
    if (score >= 40) return { label:'Partially Aligned',  color:'#fbbf24', emoji:'🔍', description:'Significant tension between your career path and inner values.' };
    return                  { label:'Misaligned',         color:'#f87171', emoji:'⚡', description:'Your current direction may be working against your authentic self.' };
}

// ── Loading state toggle ──────────────────────────────────────
function _hcaSetLoading(loading) {
    const btn        = document.getElementById('hca-submit-btn');
    const loadingEl  = document.getElementById('hca-loading-state');
    const emptyEl    = document.getElementById('hca-empty-state');
    const scoreCard  = document.getElementById('hca-score-card');
    const reportCard = document.getElementById('hca-report-card');

    if (loading) {
        btn.disabled = true; btn.style.opacity = '.65'; btn.style.cursor = 'not-allowed';
        btn.innerHTML = '⏳ Analyzing…';
        loadingEl.style.display = 'flex';
        emptyEl.style.display = 'none';
        scoreCard.style.display = 'none';
        reportCard.style.display = 'none';
    } else {
        btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer';
        btn.innerHTML = '🔮 Generate My Holistic Career Report';
        loadingEl.style.display = 'none';
    }
}

// ── Alert helpers ─────────────────────────────────────────────
function _hcaShowAlert(msg, type) {
    const el = document.getElementById('hca-alert');
    if (!el) return;
    const s = {
        error:   'background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#fca5a5',
        warning: 'background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);color:#fcd34d',
        success: 'background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.25);color:#6ee7b7',
    };
    el.setAttribute('style', (s[type] || s.error) + ";display:block;padding:10px 14px;border-radius:8px;font-size:.78rem;margin-bottom:16px;font-family:'JetBrains Mono',monospace");
    el.textContent = msg;
}
function _hcaHideAlert() {
    const el = document.getElementById('hca-alert');
    if (el) el.style.display = 'none';
}

// ── Clear resume ──────────────────────────────────────────────
function _hcaClearResume() {
    const ta = document.getElementById('hca-resume-text');
    const badge = document.getElementById('hca-resume-prefill-badge');
    const counter = document.getElementById('hca-char-count');
    if (ta) { ta.value = ''; ta.dispatchEvent(new Event('input')); ta.focus(); }
    if (badge) badge.style.display = 'none';
    if (counter) { counter.textContent = '0 / 6,000'; counter.className = ''; }
    sessionStorage.removeItem('lifevault_resume_text');
    sessionStorage.removeItem('lifevault_resume_source');
}

// ── Copy markdown ─────────────────────────────────────────────
window.hcaCopyMarkdown = function() {
    const md = window._hcaLastReport?.report_markdown;
    if (!md) { toast('No report to copy yet.', '⚠️'); return; }
    navigator.clipboard.writeText(md)
        .then(() => toast('Report copied to clipboard! 📋', '✅'))
        .catch(() => toast('Copy failed — try selecting and copying manually.', '⚠️'));
};

// ── Save report ───────────────────────────────────────────────
window.hcaSaveReport = function() {
    const report = window._hcaLastReport;
    if (!report) { toast('No report to save yet.', '⚠️'); return; }
    if (typeof window.savedAddItem !== 'function') { toast('Saved Items not ready.', '⚠️'); return; }
    window.savedAddItem({
        type: 'holistic-career', icon: '🔮',
        title: 'Holistic Career Report' + (report.job_target && report.job_target !== 'Not specified' ? ' — ' + report.job_target : ''),
        content: report.report_markdown,
        score: report.alignment_score,
    });
};
</script>
@endpush