<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LifeVault — Your Personal Space</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;1,6..72,300;1,6..72,400&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">

    <script>
        window.firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.app_id') }}",
            measurementId: "{{ config('services.firebase.measurement_id') }}"
        };
    </script>
</head>
<body>

    {{-- ─────────────────────────────────────────────
         LOADING SCREEN
    ───────────────────────────────────────────── --}}
    <div id="loading">
        <div class="loader-logo">LifeVault</div>
        <div class="loader-bar"></div>
    </div>

    {{-- ─────────────────────────────────────────────
         AUTH / WELCOME SCREEN
    ───────────────────────────────────────────── --}}
    <div id="auth-screen">

        <div class="lv-bg">
            <div class="lv-orb lv-orb1"></div>
            <div class="lv-orb lv-orb2"></div>
            <div class="lv-orb lv-orb3"></div>
            <div class="lv-orb lv-orb4"></div>
            <div class="lv-orb lv-orb5"></div>
        </div>

        <nav class="lv-nav" id="lv-nav">
            <div class="lv-nav-logo">LifeVault</div>
            <div class="lv-nav-right">
                <button class="lv-btn-ghost" onclick="lvOpenModal('lv-journal-modal')">See Demo</button>
                <button class="lv-btn-primary" id="google-login-btn">Start Free →</button>
            </div>
        </nav>

        <section class="lv-hero">
            <div class="lv-badge">
                <div class="lv-badge-pulse"><div class="lv-badge-dot"></div></div>
                AI-Powered Personal Growth
            </div>
            <h1 class="lv-hero-title">
                Journal your life.<br>
                Let AI reveal your<br>
                <em>hidden self.</em>
            </h1>
            <p class="lv-hero-sub">
                Your private space to write, reflect, and grow—powered by AI that understands you deeper than anyone else.
            </p>
            <div class="lv-hero-actions">
                <button class="lv-google-btn" id="google-login-btn-hero">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google">
                    Start Free with Google
                </button>
                <button class="lv-btn-outline" onclick="document.getElementById('lv-features').scrollIntoView({behavior:'smooth'})">
                    Explore AI Tools ↓
                </button>
            </div>
            <div class="lv-scroll-hint">
                <span>scroll to explore</span>
                <div class="lv-scroll-arrow">↓</div>
            </div>
        </section>

        <section class="lv-features" id="lv-features">
            <p class="lv-eyebrow lv-reveal">Everything you need</p>
            <h2 class="lv-section-title lv-reveal">Your vault of <em>powerful tools</em></h2>

            <div class="lv-grid">

                <div class="lv-card lv-c-purple lv-reveal" onclick="lvOpenModal('lv-resume-modal')" style="transition-delay:.05s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">📄</div>
                    <h3 class="lv-card-title">Resume Analyzer</h3>
                    <p class="lv-card-desc">Upload resume + job desc for AI match score & tailored improvements</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

                <div class="lv-card lv-c-rose lv-reveal" onclick="lvOpenModal('lv-shadow-modal')" style="transition-delay:.1s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">🔮</div>
                    <h3 class="lv-card-title">Shadow Self Analyzer</h3>
                    <p class="lv-card-desc">AI uncovers hidden fears & patterns lurking in your journal entries</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

                <div class="lv-card lv-c-amber lv-reveal" onclick="lvOpenModal('lv-story-modal')" style="transition-delay:.15s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">📖</div>
                    <h3 class="lv-card-title">Life Story Generator</h3>
                    <p class="lv-card-desc">AI weaves your journal entries into beautiful memoir chapters</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

                <div class="lv-card lv-c-teal lv-reveal" onclick="lvOpenModal('lv-career-modal')" style="transition-delay:.2s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">✨</div>
                    <h3 class="lv-card-title">Holistic Career Advisor</h3>
                    <p class="lv-card-desc">AI synthesizes your journals into your most authentic career path</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

                <div class="lv-card lv-c-indigo lv-reveal" onclick="lvOpenModal('lv-journal-modal')" style="transition-delay:.25s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">📓</div>
                    <h3 class="lv-card-title">Journal</h3>
                    <p class="lv-card-desc">The foundation—capture your raw thoughts, moods, and memories daily</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

                <div class="lv-card lv-c-sky lv-reveal" onclick="lvOpenModal('lv-tasks-modal')" style="transition-delay:.3s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">✅</div>
                    <h3 class="lv-card-title">Tasks</h3>
                    <p class="lv-card-desc">Break your biggest goals into small, satisfying actionable steps</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

                <div class="lv-card lv-c-cyan lv-reveal" onclick="lvOpenModal('lv-goals-modal')" style="transition-delay:.35s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">🎯</div>
                    <h3 class="lv-card-title">Goals</h3>
                    <p class="lv-card-desc">Track long-term aspirations visually with beautiful progress rings</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

                <div class="lv-card lv-c-pink lv-reveal" onclick="lvOpenModal('lv-saved-modal')" style="transition-delay:.4s">
                    <div class="lv-card-glow"></div>
                    <div class="lv-card-icon">🔖</div>
                    <h3 class="lv-card-title">Saved Items</h3>
                    <p class="lv-card-desc">All your AI insights, reports, and analyses in one private collection</p>
                    <div class="lv-card-cta">See demo <span class="lv-cta-arrow">→</span></div>
                </div>

            </div>
        </section>

        <div class="lv-privacy-footer">
            <div class="lv-privacy-chip"><span>🔒</span> End-to-end encrypted</div>
            <div class="lv-privacy-chip"><span>🚫</span> Never sold</div>
            <div class="lv-privacy-chip"><span>🆓</span> Always free to start</div>
        </div>

    </div>{{-- /#auth-screen --}}

    {{-- ─────────────────────────────────────────────
         FLOATING AI CHAT BUTTON
    ───────────────────────────────────────────── --}}
    <button id="lv-chat-fab" onclick="lvToggleChatbot()" title="Chat with LifeVault AI">
        <span id="lv-chat-fab-icon">🤖</span>
    </button>

    {{-- ─────────────────────────────────────────────
         AI CHATBOT OVERLAY — IMPROVED
    ───────────────────────────────────────────── --}}
    <div id="lv-chatbot-overlay">
        <div id="lv-chatbot-container">
            <div id="lv-chatbot-header">
                <div id="lv-chatbot-title">
                    <div id="lv-chatbot-avatar">🤖</div>
                    <div>
                        <div style="font-weight:700;font-size:.95rem;background:linear-gradient(135deg,#a78bfa,#4f8ef7);-webkit-background-clip:text;-webkit-text-fill-color:transparent">LifeVault AI</div>
                        <div style="font-size:.65rem;color:#4a5270;font-family:'JetBrains Mono',monospace;margin-top:1px">● Online</div>
                    </div>
                </div>
                <button id="lv-chatbot-close" onclick="lvToggleChatbot()">✕</button>
            </div>
            <div id="lv-chatbot-messages"></div>
            <div id="lv-chatbot-input-area">
                <input type="text" id="lv-chatbot-input"
                       placeholder="Ask about journaling, career, self-discovery…"
                       onkeypress="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();lvSendChat()}">
                <button id="lv-chatbot-send" onclick="lvSendChat()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ─────────────────────────────────────────────
         MAIN APP (hidden until authenticated)
    ───────────────────────────────────────────── --}}
    <div id="app">
        @include('layouts.partials._sidebar')
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

        <div class="main">
            @include('dashboard')
            @include('journal')
            @include('tasks')
            @include('goals')
            @include('insights')
            @include('community')
            @include('analyzer')
            @include('shadow-self')
            @include('life-story')
            @include('holistic-career')
            @include('settings')
            @include('profile')
            @include('saved')
        </div>
    </div>

    {{-- User Profile Modal --}}
    @include('layouts.partials.user-profile-modal')

    {{-- ─────────────────────────────────────────────
         DEMO MODALS
    ───────────────────────────────────────────── --}}

    {{-- Resume Analyzer Modal --}}
    <div class="lv-modal-overlay" id="lv-resume-modal" onclick="lvHandleOverlay(event,'lv-resume-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#a78bfa,#6366f1)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">📄</div>
                    <div>
                        <div class="lv-modal-name">Resume Analyzer</div>
                        <div class="lv-modal-sub">AI-powered match scoring & suggestions</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-resume-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Match Score</div>
                <div class="lv-demo-surface">
                    <div class="lv-score-wrap">
                        <div class="lv-ring-box">
                            <svg viewBox="0 0 96 96" width="96" height="96">
                                <circle class="lv-ring-bg" cx="48" cy="48" r="43"/>
                                <circle class="lv-ring-fill" id="lv-resume-ring" cx="48" cy="48" r="43"/>
                            </svg>
                            <span class="lv-ring-num" id="lv-resume-num">0%</span>
                        </div>
                        <div class="lv-score-info">
                            <h4>Senior Frontend Engineer @ Acme Co.</h4>
                            <p>Strong in React & TypeScript. Consider adding cloud deployment experience.</p>
                            <div class="lv-tag-row">
                                <span class="lv-tag lv-tg">React ✓</span>
                                <span class="lv-tag lv-tg">TypeScript ✓</span>
                                <span class="lv-tag lv-ta">Node.js ~</span>
                                <span class="lv-tag lv-tr">AWS ✗</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lv-demo-label">Top Improvements</div>
                <div class="lv-demo-surface">
                    <ul class="lv-improve-list">
                        <li>Add quantified achievements — "increased performance by 40%" beats "improved performance"</li>
                        <li>Mention cloud experience (AWS, GCP, or Azure) — listed as required in job posting</li>
                        <li>Include a portfolio link or GitHub — 78% of hired candidates in this role had one</li>
                        <li>Tailor your summary paragraph to mirror the job's language around "scalable systems"</li>
                    </ul>
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-resume">✦ Try with Your Resume</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-resume-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Shadow Self Modal --}}
    <div class="lv-modal-overlay" id="lv-shadow-modal" onclick="lvHandleOverlay(event,'lv-shadow-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#fb7185,#a78bfa)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">🔮</div>
                    <div>
                        <div class="lv-modal-name">Shadow Self Analyzer</div>
                        <div class="lv-modal-sub">Uncover what hides in your words</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-shadow-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Detected Emotional Patterns</div>
                <div class="lv-demo-surface">
                    <div class="lv-bar-list">
                        <div class="lv-bar-item">
                            <div class="lv-bar-row"><span>Fear of Abandonment</span><b>74%</b></div>
                            <div class="lv-bar-track"><div class="lv-bar-fill" id="lv-bar1" style="background:linear-gradient(90deg,#fb7185,#f472b6)"></div></div>
                        </div>
                        <div class="lv-bar-item">
                            <div class="lv-bar-row"><span>Perfectionism</span><b>61%</b></div>
                            <div class="lv-bar-track"><div class="lv-bar-fill" id="lv-bar2" style="background:linear-gradient(90deg,#fbbf24,#f97316)"></div></div>
                        </div>
                        <div class="lv-bar-item">
                            <div class="lv-bar-row"><span>Self-Doubt</span><b>55%</b></div>
                            <div class="lv-bar-track"><div class="lv-bar-fill" id="lv-bar3" style="background:linear-gradient(90deg,#a78bfa,#6366f1)"></div></div>
                        </div>
                        <div class="lv-bar-item">
                            <div class="lv-bar-row"><span>Suppressed Joy</span><b>42%</b></div>
                            <div class="lv-bar-track"><div class="lv-bar-fill" id="lv-bar4" style="background:linear-gradient(90deg,#34d399,#38bdf8)"></div></div>
                        </div>
                    </div>
                </div>
                <div class="lv-insight-box">
                    "Across 47 journal entries, you tend to minimize your accomplishments when writing about others—but celebrate them in private reflections. This gap often signals internalized expectations that don't truly belong to you."
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-shadow">✦ Analyze My Journals</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-shadow-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Life Story Modal --}}
    <div class="lv-modal-overlay" id="lv-story-modal" onclick="lvHandleOverlay(event,'lv-story-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#fbbf24,#fb7185)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">📖</div>
                    <div>
                        <div class="lv-modal-name">Life Story Generator</div>
                        <div class="lv-modal-sub">Your entries, woven into memoir</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-story-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Generated Chapter — February 2026</div>
                <div class="lv-demo-surface">
                    <div class="lv-chapter">
                        <h4>Chapter 3: The Late Afternoons</h4>
                        <p>There is a particular kind of grace in arriving late to your own day. In February, she discovered this—not as failure, but as permission. The afternoons began at noon and stretched long; choir practice echoed through the next room while she counted her small victories over lukewarm coffee and the smell of Potato Corner.</p>
                    </div>
                    <div class="lv-chapter">
                        <h4>Chapter 4: Software & Solitude</h4>
                        <p>The cat arrived without announcement, as cats do. She had been integrating Firebase into Activity 9 when Diel settled on the back of the monitor, a soft anchor in an otherwise buzzing evening. The code compiled. She felt—briefly, cleanly—proud.</p>
                    </div>
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-story">✦ Generate My Story</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-story-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Holistic Career Modal --}}
    <div class="lv-modal-overlay" id="lv-career-modal" onclick="lvHandleOverlay(event,'lv-career-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#34d399,#38bdf8)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">✨</div>
                    <div>
                        <div class="lv-modal-name">Holistic Career Advisor</div>
                        <div class="lv-modal-sub">Your authentic path, discovered</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-career-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Best-Fit Career Paths</div>
                <div class="lv-demo-surface">
                    <div class="lv-career-list">
                        <div class="lv-career-row">
                            <span class="lv-career-ico">💻</span>
                            <div><h5>Full-Stack Developer</h5><p>Aligns with your technical depth, creative flow, and love of building</p></div>
                            <span class="lv-career-match">96%</span>
                        </div>
                        <div class="lv-career-row">
                            <span class="lv-career-ico">🎨</span>
                            <div><h5>UX / Product Designer</h5><p>Your journaling style shows strong narrative & empathy patterns</p></div>
                            <span class="lv-career-match">84%</span>
                        </div>
                        <div class="lv-career-row">
                            <span class="lv-career-ico">✍️</span>
                            <div><h5>Technical Writer</h5><p>Clear prose, structured thinking, passion for documentation</p></div>
                            <span class="lv-career-match">77%</span>
                        </div>
                    </div>
                </div>
                <div class="lv-insight-box" style="background:rgba(52,211,153,.06);border-color:rgba(52,211,153,.18)">
                    "Based on 4 journal entries, your happiest moments cluster around late-night building and seeing things you created work. This is a strong signal—follow the late nights."
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-career">✦ Discover My Path</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-career-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Journal Modal --}}
    <div class="lv-modal-overlay" id="lv-journal-modal" onclick="lvHandleOverlay(event,'lv-journal-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#6366f1,#a78bfa)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">📓</div>
                    <div>
                        <div class="lv-modal-name">Journal</div>
                        <div class="lv-modal-sub">Your thoughts, preserved forever</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-journal-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Recent Entry</div>
                <div class="lv-demo-surface">
                    <p class="lv-journal-text">Today was a bit of a wake-up call regarding my digital footprint. I received one of those "Unusual sign-in activity" emails. At first glance, it looked official—mentioning a login from Japan—but after taking a closer look, the red flags started popping up...</p>
                    <div class="lv-journal-meta">
                        <span class="lv-jdate">MON, MAR 2, 2026 · 04:33 AM</span>
                        <span class="lv-mood-pill">🤔 Curious</span>
                        <span class="lv-cat-pill">Email</span>
                    </div>
                </div>
                <div class="lv-demo-label">Try writing</div>
                <div class="lv-demo-surface" style="padding:0">
                    <textarea placeholder="What's on your mind today? Start writing..." style="width:100%;background:transparent;border:none;outline:none;padding:18px;color:#8892b0;font-family:'Newsreader',serif;font-size:.9rem;line-height:1.75;resize:none;min-height:90px;border-radius:16px"></textarea>
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-journal">✦ Start My Journal</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-journal-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tasks Modal --}}
    <div class="lv-modal-overlay" id="lv-tasks-modal" onclick="lvHandleOverlay(event,'lv-tasks-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#38bdf8,#22d3ee)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">✅</div>
                    <div>
                        <div class="lv-modal-name">Tasks</div>
                        <div class="lv-modal-sub">Small steps, big momentum</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-tasks-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Today's Tasks</div>
                <div class="lv-demo-surface">
                    <div class="lv-task-list">
                        <div class="lv-task-row lv-task-done" id="lv-t1"><div class="lv-task-chk lv-chk-done" onclick="lvToggleTask('lv-t1',this)">✓</div>Finish Firebase integration for Activity 9</div>
                        <div class="lv-task-row lv-task-done" id="lv-t2"><div class="lv-task-chk lv-chk-done" onclick="lvToggleTask('lv-t2',this)">✓</div>Buy Potato Corner for girlfriend 🍟</div>
                        <div class="lv-task-row" id="lv-t3"><div class="lv-task-chk" onclick="lvToggleTask('lv-t3',this)"></div>Plan Post-Valentine's event budget</div>
                        <div class="lv-task-row" id="lv-t4"><div class="lv-task-chk" onclick="lvToggleTask('lv-t4',this)"></div>Review SocCom orientation feedback</div>
                        <div class="lv-task-row" id="lv-t5"><div class="lv-task-chk" onclick="lvToggleTask('lv-t5',this)"></div>Study for midterms — Web Dev</div>
                    </div>
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-tasks">✦ Manage My Tasks</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-tasks-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Goals Modal --}}
    <div class="lv-modal-overlay" id="lv-goals-modal" onclick="lvHandleOverlay(event,'lv-goals-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#22d3ee,#34d399)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">🎯</div>
                    <div>
                        <div class="lv-modal-name">Goals</div>
                        <div class="lv-modal-sub">Your aspirations, made visible</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-goals-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Active Goals</div>
                <div class="lv-demo-surface">
                    <div class="lv-goal-list">
                        <div class="lv-goal-item">
                            <div class="lv-goal-top"><h5>🎓 Graduate with Latin Honors</h5><span class="lv-goal-pct">68%</span></div>
                            <div class="lv-goal-track"><div class="lv-goal-fill" id="lv-goal1" style="background:linear-gradient(90deg,#38bdf8,#6366f1)"></div></div>
                            <div class="lv-goal-sub">3 out of 5 milestones completed</div>
                        </div>
                        <div class="lv-goal-item">
                            <div class="lv-goal-top"><h5>💻 Ship Personal Portfolio</h5><span class="lv-goal-pct">40%</span></div>
                            <div class="lv-goal-track"><div class="lv-goal-fill" id="lv-goal2" style="background:linear-gradient(90deg,#a78bfa,#fb7185)"></div></div>
                            <div class="lv-goal-sub">Design done · Development in progress</div>
                        </div>
                        <div class="lv-goal-item">
                            <div class="lv-goal-top"><h5>📚 Read 12 Books This Year</h5><span class="lv-goal-pct">25%</span></div>
                            <div class="lv-goal-track"><div class="lv-goal-fill" id="lv-goal3" style="background:linear-gradient(90deg,#34d399,#22d3ee)"></div></div>
                            <div class="lv-goal-sub">3 of 12 books completed</div>
                        </div>
                    </div>
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-goals">✦ Set My Goals</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-goals-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Saved Items Modal --}}
    <div class="lv-modal-overlay" id="lv-saved-modal" onclick="lvHandleOverlay(event,'lv-saved-modal')">
        <div class="lv-modal">
            <div class="lv-modal-bar" style="background:linear-gradient(90deg,#ec4899,#fb7185)"></div>
            <div class="lv-modal-head">
                <div class="lv-modal-head-left">
                    <div class="lv-modal-icon">🔖</div>
                    <div>
                        <div class="lv-modal-name">Saved Items</div>
                        <div class="lv-modal-sub">Your AI insights, always accessible</div>
                    </div>
                </div>
                <button class="lv-modal-close" onclick="lvCloseModal('lv-saved-modal')">✕</button>
            </div>
            <div class="lv-modal-divider"></div>
            <div class="lv-modal-body">
                <div class="lv-demo-label">Recent Saves</div>
                <div class="lv-demo-surface">
                    <div class="lv-saved-list">
                        <div class="lv-saved-row"><div class="lv-saved-ico">🔮</div><div><div class="lv-saved-title">Shadow Self Report — March 2026</div><div class="lv-saved-date">MAR 1, 2026</div></div><span class="lv-saved-badge lv-sb-purple">Shadow</span></div>
                        <div class="lv-saved-row"><div class="lv-saved-ico">📄</div><div><div class="lv-saved-title">Resume Score: Acme Co. Frontend Role</div><div class="lv-saved-date">FEB 28, 2026</div></div><span class="lv-saved-badge lv-sb-green">Resume</span></div>
                        <div class="lv-saved-row"><div class="lv-saved-ico">✨</div><div><div class="lv-saved-title">Career Path Analysis — Q1 2026</div><div class="lv-saved-date">FEB 22, 2026</div></div><span class="lv-saved-badge lv-sb-amber">Career</span></div>
                        <div class="lv-saved-row"><div class="lv-saved-ico">📖</div><div><div class="lv-saved-title">Life Story Chapter Draft #3</div><div class="lv-saved-date">FEB 15, 2026</div></div><span class="lv-saved-badge lv-sb-purple">Story</span></div>
                    </div>
                </div>
                <div class="lv-modal-actions">
                    <button class="lv-modal-try" id="google-login-btn-saved">✦ Open My Vault</button>
                    <button class="lv-modal-dismiss" onclick="lvCloseModal('lv-saved-modal')">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ─────────────────────────────────────────────
         WELCOME PAGE STYLES
    ───────────────────────────────────────────── --}}
    <style>
    #auth-screen *, #auth-screen *::before, #auth-screen *::after { box-sizing: border-box; }
    #auth-screen { position: relative; overflow-x: hidden; min-height: 100vh; font-family: 'Syne', sans-serif; }

    .lv-bg {
        position: fixed; inset: 0; z-index: 0;
        pointer-events: none; overflow: hidden;
        background: #07081a;
    }
    .lv-bg::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, #0d0e24 0%, #07081a 50%, #0a0d20 100%);
        background-size: 400% 400%;
        animation: lv-basehue 40s ease-in-out infinite alternate;
    }
    @keyframes lv-basehue {
        0%   { background-position: 0% 0% }
        50%  { background-position: 100% 50% }
        100% { background-position: 0% 100% }
    }
    .lv-orb { position: absolute; border-radius: 50%; filter: blur(120px); will-change: transform; }
    .lv-orb1 { width:900px;height:900px;left:-20%;top:-30%;background:radial-gradient(circle,rgba(79,70,229,.22) 0%,rgba(79,70,229,.08) 40%,transparent 70%);animation:lv-d1 38s ease-in-out infinite alternate; }
    .lv-orb2 { width:800px;height:800px;right:-20%;top:-15%;background:radial-gradient(circle,rgba(124,58,237,.18) 0%,rgba(124,58,237,.06) 40%,transparent 70%);animation:lv-d2 44s ease-in-out infinite alternate; }
    .lv-orb3 { width:1000px;height:1000px;left:15%;bottom:-40%;background:radial-gradient(circle,rgba(14,116,144,.16) 0%,rgba(14,116,144,.05) 40%,transparent 70%);animation:lv-d3 50s ease-in-out infinite alternate; }
    .lv-orb4 { width:700px;height:700px;left:-10%;bottom:5%;background:radial-gradient(circle,rgba(157,23,77,.12) 0%,rgba(157,23,77,.04) 40%,transparent 70%);animation:lv-d4 42s ease-in-out infinite alternate; }
    .lv-orb5 { width:750px;height:750px;right:-15%;bottom:-10%;background:radial-gradient(circle,rgba(6,78,59,.15) 0%,rgba(6,78,59,.05) 40%,transparent 70%);animation:lv-d5 36s ease-in-out infinite alternate; }
    @keyframes lv-d1 { from{transform:translate(0,0)} to{transform:translate(50px,40px)} }
    @keyframes lv-d2 { from{transform:translate(0,0)} to{transform:translate(-45px,55px)} }
    @keyframes lv-d3 { from{transform:translate(0,0)} to{transform:translate(-55px,-35px)} }
    @keyframes lv-d4 { from{transform:translate(0,0)} to{transform:translate(40px,-45px)} }
    @keyframes lv-d5 { from{transform:translate(0,0)} to{transform:translate(-40px,-50px)} }

    .lv-nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 100;
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 48px;
        background: rgba(8,9,26,0.6);
        backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
        border-bottom: 1px solid rgba(255,255,255,0.04);
        transition: background .4s;
    }
    .lv-nav.lv-scrolled { background: rgba(8,9,26,0.88); }
    .lv-nav-logo {
        font-weight: 800; font-size: 1.35rem; letter-spacing: -.04em;
        background: linear-gradient(135deg,#a78bfa,#22d3ee);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .lv-nav-right { display: flex; gap: 12px; align-items: center; }
    .lv-btn-ghost {
        background: transparent; border: 1px solid rgba(255,255,255,.07);
        color: #8892b0; padding: 8px 20px; border-radius: 99px;
        font-family: 'Syne', sans-serif; font-weight: 600; font-size: .82rem;
        cursor: pointer; transition: all .2s;
    }
    .lv-btn-ghost:hover { border-color: rgba(255,255,255,.15); color: #f0f2ff; }
    .lv-btn-primary {
        background: linear-gradient(135deg,#7c3aed,#4338ca);
        border: none; color: #fff; padding: 9px 22px; border-radius: 99px;
        font-family: 'Syne', sans-serif; font-weight: 700; font-size: .82rem;
        cursor: pointer; transition: all .25s;
        box-shadow: 0 0 24px rgba(124,58,237,.35);
    }
    .lv-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 32px rgba(124,58,237,.55); }

    #auth-screen .lv-hero {
        position: relative; z-index: 2;
        min-height: 100vh;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        text-align: center;
        padding: 130px 24px 90px;
    }
    .lv-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(167,139,250,.1); border: 1px solid rgba(167,139,250,.22);
        border-radius: 99px; padding: 6px 16px 6px 10px;
        font-size: .75rem; font-weight: 700; color: #a78bfa;
        margin-bottom: 36px;
        animation: lv-fadeup .7s ease both;
    }
    .lv-badge-pulse {
        width: 22px; height: 22px; border-radius: 50%;
        background: rgba(52,211,153,.15); border: 1px solid rgba(52,211,153,.3);
        display: flex; align-items: center; justify-content: center;
    }
    .lv-badge-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: #34d399;
        animation: lv-pulsate 2s ease-in-out infinite;
    }
    @keyframes lv-pulsate { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(.6);opacity:.5} }

    .lv-hero-title {
        font-family: 'Newsreader', serif;
        font-size: clamp(2.8rem,7.5vw,6rem);
        font-weight: 300; line-height: 1.07;
        color: #f0f2ff; margin-bottom: 16px;
        animation: lv-fadeup .7s .1s ease both;
    }
    .lv-hero-title em {
        font-style: italic;
        background: linear-gradient(135deg,#a78bfa 0%,#fb7185 45%,#fbbf24 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        background-size: 300% 300%;
        animation: lv-shimmer 5s ease-in-out infinite;
    }
    @keyframes lv-shimmer { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }

    .lv-hero-sub {
        font-family: 'Newsreader', serif;
        font-size: clamp(1.05rem,2.2vw,1.35rem);
        color: #8892b0; max-width: 540px; margin: 0 auto 48px;
        line-height: 1.7;
        animation: lv-fadeup .7s .2s ease both;
    }
    .lv-hero-actions {
        display: flex; gap: 14px; flex-wrap: wrap; justify-content: center;
        margin-bottom: 80px;
        animation: lv-fadeup .7s .3s ease both;
    }
    .lv-google-btn {
        display: flex; align-items: center; gap: 10px;
        background: #fff; color: #1a1a2e;
        border: none; padding: 14px 30px; border-radius: 99px;
        font-family: 'Syne', sans-serif; font-weight: 700; font-size: .95rem;
        cursor: pointer; transition: all .25s;
        box-shadow: 0 4px 30px rgba(0,0,0,.3);
    }
    .lv-google-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 40px rgba(0,0,0,.4); }
    .lv-google-btn img { width: 20px; height: 20px; }
    .lv-btn-outline {
        background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.12);
        color: #f0f2ff; padding: 14px 28px; border-radius: 99px;
        font-family: 'Syne', sans-serif; font-weight: 600; font-size: .95rem;
        cursor: pointer; transition: all .25s; backdrop-filter: blur(10px);
    }
    .lv-btn-outline:hover { border-color: #a78bfa; background: rgba(167,139,250,.08); }
    .lv-scroll-hint {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        color: #4a5270; font-size: .75rem; letter-spacing: .1em; text-transform: uppercase;
        animation: lv-fadeup .7s .5s ease both;
    }
    .lv-scroll-arrow {
        width: 28px; height: 28px; border: 1px solid rgba(255,255,255,.1);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: .7rem;
        animation: lv-bounce 2s ease-in-out infinite;
    }
    @keyframes lv-bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(6px)} }

    #auth-screen .lv-features {
        position: relative; z-index: 2;
        padding: 80px 24px 120px;
        max-width: 1120px; margin: 0 auto;
    }
    .lv-eyebrow {
        font-size: .72rem; font-weight: 700; letter-spacing: .18em;
        text-transform: uppercase; color: #a78bfa;
        margin-bottom: 14px; text-align: center;
    }
    .lv-section-title {
        font-family: 'Newsreader', serif;
        font-size: clamp(1.9rem,4vw,3rem);
        font-weight: 300; color: #f0f2ff;
        text-align: center; line-height: 1.2; margin-bottom: 64px;
    }
    .lv-section-title em { font-style: italic; color: #a78bfa; }
    .lv-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }

    .lv-card {
        position: relative; overflow: hidden;
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.07);
        border-radius: 22px; padding: 28px;
        cursor: pointer;
        transition: transform .35s cubic-bezier(.34,1.56,.64,1), border-color .3s, box-shadow .3s;
        user-select: none;
    }
    .lv-card::after {
        content: ''; position: absolute;
        top: 0; left: -100%; width: 60%; height: 100%;
        background: linear-gradient(90deg,transparent,rgba(255,255,255,.04),transparent);
        transition: left .5s ease; pointer-events: none;
    }
    .lv-card:hover::after { left: 140%; }
    .lv-card:hover { transform: translateY(-10px) scale(1.02); }
    .lv-card:active { transform: translateY(-5px) scale(1.01); transition-duration: .1s; }
    .lv-card-glow {
        position: absolute; top: -40%; left: -20%;
        width: 140%; height: 140%; border-radius: 50%;
        opacity: 0; transition: opacity .4s; pointer-events: none;
    }
    .lv-card:hover .lv-card-glow { opacity: 1; }
    .lv-card-icon {
        width: 46px; height: 46px; border-radius: 14px;
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.09);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; margin-bottom: 18px;
        transition: transform .35s cubic-bezier(.34,1.56,.64,1);
    }
    .lv-card:hover .lv-card-icon { transform: scale(1.15) rotate(6deg); }
    .lv-card-title { font-weight: 700; font-size: .95rem; color: #f0f2ff; margin-bottom: 8px; }
    .lv-card-desc  { font-size: .8rem; color: #8892b0; line-height: 1.6; }
    .lv-card-cta {
        display: inline-flex; align-items: center; gap: 6px;
        margin-top: 18px; font-size: .78rem; font-weight: 700;
        opacity: 0; transform: translateY(4px);
        transition: opacity .3s, transform .3s;
    }
    .lv-card:hover .lv-card-cta { opacity: 1; transform: translateY(0); }
    .lv-cta-arrow { display: inline-block; transition: transform .2s; }
    .lv-card:hover .lv-cta-arrow { transform: translateX(4px); }

    .lv-c-purple:hover{border-color:rgba(167,139,250,.35);box-shadow:0 24px 60px rgba(167,139,250,.18)} .lv-c-purple .lv-card-glow{background:radial-gradient(circle,rgba(167,139,250,.1),transparent 65%)} .lv-c-purple .lv-card-cta{color:#a78bfa}
    .lv-c-teal:hover  {border-color:rgba(52,211,153,.35); box-shadow:0 24px 60px rgba(52,211,153,.15)}  .lv-c-teal .lv-card-glow  {background:radial-gradient(circle,rgba(52,211,153,.09),transparent 65%)}  .lv-c-teal .lv-card-cta  {color:#34d399}
    .lv-c-rose:hover  {border-color:rgba(251,113,133,.35);box-shadow:0 24px 60px rgba(251,113,133,.15)} .lv-c-rose .lv-card-glow  {background:radial-gradient(circle,rgba(251,113,133,.09),transparent 65%)} .lv-c-rose .lv-card-cta  {color:#fb7185}
    .lv-c-amber:hover {border-color:rgba(251,191,36,.35); box-shadow:0 24px 60px rgba(251,191,36,.15)}  .lv-c-amber .lv-card-glow {background:radial-gradient(circle,rgba(251,191,36,.09),transparent 65%)}  .lv-c-amber .lv-card-cta {color:#fbbf24}
    .lv-c-sky:hover   {border-color:rgba(56,189,248,.35); box-shadow:0 24px 60px rgba(56,189,248,.15)}  .lv-c-sky .lv-card-glow   {background:radial-gradient(circle,rgba(56,189,248,.09),transparent 65%)}   .lv-c-sky .lv-card-cta   {color:#38bdf8}
    .lv-c-indigo:hover{border-color:rgba(129,140,248,.35);box-shadow:0 24px 60px rgba(129,140,248,.15)} .lv-c-indigo .lv-card-glow{background:radial-gradient(circle,rgba(129,140,248,.09),transparent 65%)} .lv-c-indigo .lv-card-cta{color:#818cf8}
    .lv-c-cyan:hover  {border-color:rgba(34,211,238,.35); box-shadow:0 24px 60px rgba(34,211,238,.15)}  .lv-c-cyan .lv-card-glow  {background:radial-gradient(circle,rgba(34,211,238,.09),transparent 65%)}  .lv-c-cyan .lv-card-cta  {color:#22d3ee}
    .lv-c-pink:hover  {border-color:rgba(236,72,153,.35); box-shadow:0 24px 60px rgba(236,72,153,.15)}  .lv-c-pink .lv-card-glow  {background:radial-gradient(circle,rgba(236,72,153,.09),transparent 65%)}  .lv-c-pink .lv-card-cta  {color:#ec4899}

    .lv-privacy-footer {
        position: relative; z-index: 2;
        display: flex; align-items: center; justify-content: center;
        gap: 24px; flex-wrap: wrap;
        padding: 32px 24px 80px;
    }
    .lv-privacy-chip { display: flex; align-items: center; gap: 6px; font-size: .75rem; color: #4a5270; font-weight: 600; }

    /* ── Modals ── */
    .lv-modal-overlay {
        position: fixed; inset: 0; z-index: 300;
        background: rgba(5,6,18,.88);
        backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
        opacity: 0; pointer-events: none;
        transition: opacity .3s;
    }
    .lv-modal-overlay.lv-open { opacity: 1; pointer-events: all; }
    .lv-modal {
        background: linear-gradient(145deg,rgba(14,16,38,.97),rgba(20,22,50,.97));
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 28px; width: 100%; max-width: 680px;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 60px 120px rgba(0,0,0,.65), inset 0 1px 0 rgba(255,255,255,.06);
        transform: scale(.92) translateY(24px);
        transition: transform .4s cubic-bezier(.34,1.56,.64,1);
        scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.08) transparent;
    }
    .lv-modal-overlay.lv-open .lv-modal { transform: scale(1) translateY(0); }
    .lv-modal-bar { height: 2px; border-radius: 28px 28px 0 0; }
    .lv-modal-head { display: flex; align-items: flex-start; justify-content: space-between; padding: 28px 28px 0; margin-bottom: 6px; }
    .lv-modal-head-left { display: flex; align-items: center; gap: 16px; }
    .lv-modal-icon { width: 54px; height: 54px; border-radius: 18px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.09); display: flex; align-items: center; justify-content: center; font-size: 1.7rem; flex-shrink: 0; }
    .lv-modal-name  { font-weight: 800; font-size: 1.25rem; color: #f0f2ff; }
    .lv-modal-sub   { font-size: .78rem; color: #8892b0; margin-top: 3px; }
    .lv-modal-close { width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08); color: #8892b0; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; flex-shrink: 0; }
    .lv-modal-close:hover { background: rgba(255,255,255,.12); color: #f0f2ff; }
    .lv-modal-divider { height: 1px; background: rgba(255,255,255,.06); margin: 20px 28px; }
    .lv-modal-body  { padding: 0 28px 28px; }
    .lv-demo-label  { font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #4a5270; margin-bottom: 10px; }
    .lv-demo-surface { background: rgba(255,255,255,.025); border: 1px solid rgba(255,255,255,.06); border-radius: 16px; padding: 20px; margin-bottom: 16px; }

    .lv-score-wrap  { display: flex; align-items: center; gap: 20px; margin-bottom: 18px; }
    .lv-ring-box    { width: 96px; height: 96px; flex-shrink: 0; position: relative; display: flex; align-items: center; justify-content: center; }
    .lv-ring-box svg { position: absolute; inset: 0; transform: rotate(-90deg); }
    .lv-ring-bg     { stroke: rgba(255,255,255,.07); stroke-width: 6; fill: none; }
    .lv-ring-fill   { stroke: #34d399; stroke-width: 6; fill: none; stroke-linecap: round; stroke-dasharray: 270; stroke-dashoffset: 270; transition: stroke-dashoffset 1.6s cubic-bezier(.23,1,.32,1); }
    .lv-ring-num    { font-size: 1.35rem; font-weight: 800; color: #f0f2ff; position: relative; z-index: 1; }
    .lv-score-info h4 { font-weight: 700; font-size: .95rem; color: #f0f2ff; margin-bottom: 4px; }
    .lv-score-info p  { font-size: .8rem; color: #8892b0; line-height: 1.5; }
    .lv-tag-row     { display: flex; gap: 7px; flex-wrap: wrap; margin-top: 10px; }
    .lv-tag         { padding: 3px 11px; border-radius: 99px; font-size: .72rem; font-weight: 700; }
    .lv-tg { background:rgba(52,211,153,.12);  color:#34d399; border:1px solid rgba(52,211,153,.2);  }
    .lv-tr { background:rgba(251,113,133,.12); color:#fb7185; border:1px solid rgba(251,113,133,.2); }
    .lv-ta { background:rgba(251,191,36,.12);  color:#fbbf24; border:1px solid rgba(251,191,36,.2);  }
    .lv-improve-list { list-style: none; display: flex; flex-direction: column; gap: 9px; }
    .lv-improve-list li { display: flex; gap: 9px; align-items: flex-start; font-size: .8rem; color: #8892b0; line-height: 1.5; }
    .lv-improve-list li::before { content: '✦'; color: #a78bfa; flex-shrink: 0; margin-top: 1px; }

    .lv-bar-list  { display: flex; flex-direction: column; gap: 14px; }
    .lv-bar-row   { display: flex; justify-content: space-between; font-size: .78rem; color: #8892b0; margin-bottom: 6px; }
    .lv-bar-row b { color: #f0f2ff; font-weight: 700; }
    .lv-bar-track { height: 7px; background: rgba(255,255,255,.06); border-radius: 99px; overflow: hidden; }
    .lv-bar-fill  { height: 100%; border-radius: 99px; width: 0; transition: width 1.3s cubic-bezier(.23,1,.32,1); }
    .lv-insight-box { background: rgba(167,139,250,.07); border: 1px solid rgba(167,139,250,.18); border-radius: 14px; padding: 16px; font-family: 'Newsreader', serif; font-style: italic; font-size: .88rem; color: #8892b0; line-height: 1.65; margin-top: 16px; }

    .lv-chapter { border-left: 2px solid rgba(167,139,250,.4); padding-left: 16px; margin-bottom: 18px; }
    .lv-chapter h4 { font-family: 'Newsreader', serif; font-weight: 400; font-size: 1rem; color: #f0f2ff; margin-bottom: 6px; }
    .lv-chapter p  { font-family: 'Newsreader', serif; font-size: .85rem; color: #8892b0; line-height: 1.75; }

    .lv-career-list { display: flex; flex-direction: column; gap: 10px; }
    .lv-career-row  { display: flex; align-items: center; gap: 14px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); border-radius: 12px; padding: 14px; }
    .lv-career-ico  { font-size: 1.3rem; flex-shrink: 0; }
    .lv-career-row h5 { font-size: .85rem; font-weight: 700; color: #f0f2ff; }
    .lv-career-row p  { font-size: .75rem; color: #8892b0; margin-top: 2px; }
    .lv-career-match  { margin-left: auto; font-size: .82rem; font-weight: 800; color: #34d399; flex-shrink: 0; }

    .lv-journal-text { font-family: 'Newsreader', serif; font-size: .9rem; color: #8892b0; line-height: 1.85; margin-bottom: 14px; }
    .lv-journal-meta { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .lv-jdate        { font-family: 'JetBrains Mono', monospace; font-size: .72rem; color: #4a5270; }
    .lv-mood-pill    { background: rgba(251,191,36,.1);  border: 1px solid rgba(251,191,36,.2);  color: #fbbf24; font-size: .72rem; padding: 3px 10px; border-radius: 99px; }
    .lv-cat-pill     { background: rgba(167,139,250,.1); border: 1px solid rgba(167,139,250,.2); color: #a78bfa; font-size: .72rem; padding: 3px 10px; border-radius: 99px; }

    .lv-task-list { display: flex; flex-direction: column; }
    .lv-task-row  { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1px solid rgba(255,255,255,.05); font-size: .83rem; color: #8892b0; }
    .lv-task-row:last-child { border-bottom: none; }
    .lv-task-chk  { width: 18px; height: 18px; border-radius: 50%; border: 2px solid rgba(255,255,255,.18); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: .6rem; color: transparent; transition: all .3s; cursor: pointer; }
    .lv-chk-done  { background: #34d399; border-color: #34d399; color: #fff; }
    .lv-task-done { text-decoration: line-through; opacity: .45; }

    .lv-goal-list  { display: flex; flex-direction: column; gap: 14px; }
    .lv-goal-top   { display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; }
    .lv-goal-top h5 { font-size: .85rem; font-weight: 700; color: #f0f2ff; }
    .lv-goal-pct   { font-size: .78rem; font-weight: 800; color: #38bdf8; }
    .lv-goal-track { height: 9px; background: rgba(255,255,255,.06); border-radius: 99px; overflow: hidden; }
    .lv-goal-fill  { height: 100%; border-radius: 99px; width: 0; transition: width 1.3s cubic-bezier(.23,1,.32,1); }
    .lv-goal-sub   { font-size: .72rem; color: #4a5270; margin-top: 5px; }

    .lv-saved-list  { display: flex; flex-direction: column; gap: 9px; }
    .lv-saved-row   { display: flex; gap: 12px; align-items: center; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); border-radius: 12px; padding: 12px; }
    .lv-saved-ico   { font-size: 1.15rem; flex-shrink: 0; }
    .lv-saved-title { font-size: .82rem; font-weight: 700; color: #f0f2ff; }
    .lv-saved-date  { font-size: .7rem; color: #4a5270; font-family: 'JetBrains Mono', monospace; margin-top: 2px; }
    .lv-saved-badge { margin-left: auto; font-size: .68rem; font-weight: 700; padding: 3px 9px; border-radius: 99px; flex-shrink: 0; }
    .lv-sb-purple { background:rgba(167,139,250,.12);color:#a78bfa;border:1px solid rgba(167,139,250,.2); }
    .lv-sb-green  { background:rgba(52,211,153,.12); color:#34d399;border:1px solid rgba(52,211,153,.2);  }
    .lv-sb-amber  { background:rgba(251,191,36,.12); color:#fbbf24;border:1px solid rgba(251,191,36,.2);  }

    .lv-modal-actions { display: flex; gap: 12px; margin-top: 24px; }
    .lv-modal-try { flex: 1; background: linear-gradient(135deg,#7c3aed,#4338ca); border: none; color: #fff; padding: 13px 20px; border-radius: 12px; font-family: 'Syne', sans-serif; font-weight: 700; font-size: .88rem; cursor: pointer; transition: all .25s; box-shadow: 0 4px 20px rgba(124,58,237,.3); }
    .lv-modal-try:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(124,58,237,.5); }
    .lv-modal-dismiss { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08); color: #8892b0; padding: 13px 20px; border-radius: 12px; font-family: 'Syne', sans-serif; font-weight: 600; font-size: .88rem; cursor: pointer; transition: all .2s; }
    .lv-modal-dismiss:hover { background: rgba(255,255,255,.09); color: #f0f2ff; }

    .lv-reveal { opacity: 0; transform: translateY(30px); transition: opacity .7s ease, transform .7s ease; }
    .lv-reveal.lv-in { opacity: 1; transform: translateY(0); }

    @keyframes lv-fadeup { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }

    /* ════ CHATBOT FAB ════ */
    #lv-chat-fab {
        position: fixed; bottom: 32px; right: 32px; z-index: 500;
        width: 58px; height: 58px; border-radius: 50%; border: none; cursor: pointer;
        background: linear-gradient(135deg, #7c3aed, #4f8ef7);
        box-shadow: 0 8px 32px rgba(124,58,237,.5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        transition: transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .25s;
        animation: lv-fadeup .7s .8s ease both;
    }
    #lv-chat-fab:hover { transform: scale(1.12) translateY(-3px); box-shadow: 0 14px 40px rgba(124,58,237,.65), 0 0 0 8px rgba(124,58,237,.1); }
    #lv-chat-fab:active { transform: scale(.95); }
    #lv-chat-fab.lv-chat-open { background: linear-gradient(135deg,#4b5563,#374151); }

    /* ════ CHATBOT OVERLAY ════ */
    #lv-chatbot-overlay {
        position: fixed; bottom: 104px; right: 32px; z-index: 499;
        width: 390px;
        max-height: min(580px, calc(100vh - 130px));
        background: linear-gradient(145deg, rgba(13,15,35,.98), rgba(18,20,46,.98));
        border: 1px solid rgba(124,58,237,.3); border-radius: 24px;
        display: flex; flex-direction: column;
        box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04);
        transform: scale(.88) translateY(20px); opacity: 0; pointer-events: none;
        transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
        overflow: hidden;
    }
    #lv-chatbot-overlay.lv-chat-visible { transform: scale(1) translateY(0); opacity: 1; pointer-events: all; }

    #lv-chatbot-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 18px; border-bottom: 1px solid rgba(255,255,255,.06);
        background: rgba(255,255,255,.02); flex-shrink: 0;
    }
    #lv-chatbot-title { display: flex; align-items: center; gap: 10px; }
    #lv-chatbot-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg,#a78bfa,#ec4899);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    #lv-chatbot-close {
        width: 28px; height: 28px; border-radius: 50%;
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
        color: #8892b0; font-size: .85rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: all .2s;
    }
    #lv-chatbot-close:hover { background: rgba(248,113,113,.15); border-color: rgba(248,113,113,.3); color: #fb7185; }

    #lv-chatbot-messages {
        flex: 1;
        min-height: 80px;
        max-height: 420px;
        overflow-y: auto;
        padding: 16px;
        display: flex; flex-direction: column; gap: 12px;
        scrollbar-width: thin; scrollbar-color: rgba(124,58,237,.2) transparent;
    }
    #lv-chatbot-messages::-webkit-scrollbar { width: 3px; }
    #lv-chatbot-messages::-webkit-scrollbar-thumb { background: rgba(124,58,237,.3); border-radius: 99px; }

    .lv-chat-msg { display: flex; gap: 8px; animation: lv-fadeup .25s ease; }
    .lv-chat-msg.lv-chat-user { flex-direction: row-reverse; }
    .lv-chat-bubble {
        max-width: 80%; padding: 10px 14px; border-radius: 18px;
        font-size: .82rem; line-height: 1.6; font-family: 'Newsreader', serif;
    }
    .lv-chat-bubble strong { font-weight: 700; color: #f0f2ff; }
    .lv-chat-bubble em { font-style: italic; color: #c4b5fd; }
    .lv-chat-bubble ul { list-style: none; margin: 6px 0; padding: 0; display: flex; flex-direction: column; gap: 5px; }
    .lv-chat-bubble ul li { display: flex; gap: 7px; align-items: flex-start; }
    .lv-chat-bubble ul li::before { content: '✦'; color: #a78bfa; flex-shrink: 0; font-size: .65rem; margin-top: 3px; }
    .lv-chat-bubble code { background: rgba(255,255,255,.1); padding: 1px 5px; border-radius: 4px; font-size: .75rem; font-family: 'JetBrains Mono', monospace; }
    .lv-chat-bubble p { margin-bottom: 6px; }
    .lv-chat-bubble p:last-child { margin-bottom: 0; }
    .lv-chat-msg.lv-chat-ai .lv-chat-bubble {
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.07);
        color: rgba(232,234,240,.85); border-radius: 4px 18px 18px 18px;
    }
    .lv-chat-msg.lv-chat-user .lv-chat-bubble {
        background: linear-gradient(135deg, #7c3aed, #4f8ef7);
        color: #fff; border-radius: 18px 4px 18px 18px;
    }
    .lv-chat-ico {
        width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; margin-top: 2px;
    }
    .lv-chat-msg.lv-chat-ai .lv-chat-ico { background: linear-gradient(135deg,#a78bfa,#ec4899); }
    .lv-chat-msg.lv-chat-user .lv-chat-ico { background: rgba(79,142,247,.2); color: #4f8ef7; }
    .lv-chat-meta { font-size: .62rem; color: #4a5270; padding: 2px 4px; }
    .lv-chat-user-meta { text-align: right; }

    .lv-chat-typing span {
        display: inline-block; width: 6px; height: 6px;
        background: #a78bfa; border-radius: 50%; margin: 0 2px;
        animation: lv-typing .9s ease-in-out infinite;
    }
    .lv-chat-typing span:nth-child(2) { animation-delay: .15s; }
    .lv-chat-typing span:nth-child(3) { animation-delay: .3s; }
    @keyframes lv-typing { 0%,60%,100%{transform:translateY(0);opacity:.4} 30%{transform:translateY(-6px);opacity:1} }

    #lv-chatbot-input-area {
        display: flex; gap: 8px; padding: 12px 14px;
        border-top: 1px solid rgba(255,255,255,.06);
        background: rgba(255,255,255,.02); flex-shrink: 0;
    }
    #lv-chatbot-input {
        flex: 1; background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.08); border-radius: 99px;
        padding: 10px 16px; font-size: .82rem; color: #f0f2ff;
        font-family: 'Newsreader', serif; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    #lv-chatbot-input::placeholder { color: #4a5270; }
    #lv-chatbot-input:focus { border-color: rgba(124,58,237,.5); box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
    #lv-chatbot-send {
        width: 38px; height: 38px; border-radius: 50%; border: none; cursor: pointer;
        background: linear-gradient(135deg, #7c3aed, #4f8ef7); color: #fff;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: transform .2s, box-shadow .2s;
    }
    #lv-chatbot-send:hover { transform: scale(1.1); box-shadow: 0 4px 16px rgba(124,58,237,.5); }
    #lv-chatbot-send:disabled { opacity: .4; cursor: not-allowed; transform: none; }

    .lv-chat-error-nudge {
        background: rgba(251,113,133,.08); border: 1px solid rgba(251,113,133,.2);
        border-radius: 10px; padding: 10px 12px; font-size: .78rem; color: #fb7185;
    }
    .lv-chat-error-nudge span { color: #8892b0; }
    .lv-chat-error-nudge button {
        background: none; border: none; color: #a78bfa; font-weight: 700;
        cursor: pointer; font-size: .78rem; padding: 0; font-family: 'Syne', sans-serif;
    }

    @media(max-width:960px) { .lv-grid { grid-template-columns: repeat(2,1fr); } .lv-nav { padding: 16px 24px; } }
    @media(max-width:600px) {
        .lv-hero-title { font-size: 2.4rem; }
        .lv-grid { grid-template-columns: 1fr; }
        .lv-hero-actions { flex-direction: column; align-items: center; }
        .lv-nav-right { gap: 8px; }
        .lv-modal { border-radius: 20px; }
        #lv-chatbot-overlay { width: calc(100vw - 32px); right: 16px; bottom: 96px; }
        #lv-chat-fab { right: 20px; bottom: 24px; }
    }
    </style>

    {{-- Existing app styles --}}
    <style>
    .user-profile-modal-backdrop { position:fixed;inset:0;background:rgba(11,15,26,.9);z-index:300;display:none;align-items:center;justify-content:center;padding:20px;cursor:pointer; }
    .user-profile-modal-backdrop.open { display:flex; }
    .user-profile-modal { background:var(--surface);border:1px solid var(--border);border-radius:20px;max-width:480px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 40px 80px rgba(0,0,0,.5);animation:modalIn .3s cubic-bezier(.34,1.56,.64,1) both;cursor:default;padding:0; }
    .user-profile-modal .profile-card  { border:none;background:transparent;margin-bottom:0; }
    .user-profile-modal .profile-cover { height:140px;border-radius:20px 20px 0 0; }
    .user-profile-modal .profile-identity { padding:0 24px 24px; }
    .user-profile-modal .profile-avatar-large { width:80px;height:80px;margin-top:-40px; }
    .user-profile-modal .profile-display-name { font-size:1.3rem; }
    .user-profile-modal .profile-section-title { padding:0 24px; }
    .user-profile-modal .profile-recent-activity { padding:0 24px 24px; }
    .user-profile-modal .profile-cover-edit, .user-profile-modal .profile-avatar-edit-btn { display:none; }
    </style>

    @include('layouts.partials.modals')
    @include('layouts.partials._toast')
    @include('layouts.partials._journal-expand')

    <script>
    (function () {
        var LS_KEY = 'lifevault_saved_items';
        function loadItems() { try { return JSON.parse(localStorage.getItem(LS_KEY)) || []; } catch(e) { return []; } }
        function persistItems(items) { try { localStorage.setItem(LS_KEY, JSON.stringify(items)); } catch(e) {} }
        window.savedAddItem = function(item) {
            var items = loadItems();
            var newItem = Object.assign({}, item, { id: Date.now() + Math.random().toString(36).slice(2), savedAt: new Date().toISOString() });
            items.unshift(newItem);
            persistItems(items);
            if (typeof window._savedRender === 'function') window._savedRender();
            if (typeof window.toast === 'function') window.toast('Saved to 🔖 Saved Items! ✨');
        };
    })();
    </script>

    <script src="{{ asset('js/app.js') }}?v={{ time() }}" type="module"></script>
    <script src="{{ asset('js/profile-popup.js') }}?v={{ time() }}"></script>
    @stack('scripts')

    <div id="profile-popup">
        <div class="pp-header">
            <img class="pp-avatar" id="pp-avatar" src="" alt="">
            <div style="min-width:0">
                <div class="pp-name" id="pp-name" style="color:var(--text)"></div>
                <div class="pp-email" id="pp-email"></div>
            </div>
        </div>
        <div class="pp-menu">
            <div class="pp-item" style="color:var(--text)" onclick="navigateTo('profile');closeProfilePopup()"><span class="pp-icon">👤</span> View Profile</div>
            <div class="pp-item" style="color:var(--text)" onclick="navigateTo('settings');closeProfilePopup()"><span class="pp-icon">⚙️</span> Settings</div>
            <div class="pp-item" style="color:var(--text)" onclick="exportAsJSON();closeProfilePopup()"><span class="pp-icon">💾</span> Export Backup</div>
            <div class="pp-divider"></div>
            <div class="pp-item pp-item--danger" onclick="closeProfilePopup();signOutUser()"><span class="pp-icon">⏻</span> Sign Out</div>
        </div>
    </div>

    @include('layouts.partials.confirm-modal')

    {{-- ─────────────────────────────────────────────
         WELCOME PAGE JAVASCRIPT
    ───────────────────────────────────────────── --}}
    <script>
    // ── Scroll reveal ──
    (function(){
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('lv-in'); });
        }, { threshold: .12 });
        document.querySelectorAll('.lv-reveal').forEach(el => io.observe(el));
        window.addEventListener('scroll', () => {
            document.getElementById('lv-nav')?.classList.toggle('lv-scrolled', window.scrollY > 60);
        });
    })();

    // ── Demo modal helpers ──
    function lvOpenModal(id) {
        document.getElementById(id).classList.add('lv-open');
        document.body.style.overflow = 'hidden';
        setTimeout(() => lvRunAnims(id), 300);
    }
    function lvCloseModal(id) {
        document.getElementById(id).classList.remove('lv-open');
        document.body.style.overflow = '';
        lvResetAnims(id);
    }
    function lvHandleOverlay(e, id) {
        if (e.target === e.currentTarget) lvCloseModal(id);
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.lv-modal-overlay.lv-open').forEach(m => lvCloseModal(m.id));
        }
    });

    function lvRunAnims(id) {
        if (id === 'lv-resume-modal') {
            const ring = document.getElementById('lv-resume-ring');
            const num  = document.getElementById('lv-resume-num');
            ring.style.strokeDashoffset = '59';
            let n = 0;
            const iv = setInterval(() => { n = Math.min(n + 2, 78); num.textContent = n + '%'; if (n >= 78) clearInterval(iv); }, 18);
        }
        if (id === 'lv-shadow-modal') {
            [{el:'lv-bar1',v:74},{el:'lv-bar2',v:61},{el:'lv-bar3',v:55},{el:'lv-bar4',v:42}]
                .forEach((b,i) => setTimeout(() => { document.getElementById(b.el).style.width = b.v + '%'; }, i * 120));
        }
        if (id === 'lv-goals-modal') {
            setTimeout(() => {
                document.getElementById('lv-goal1').style.width = '68%';
                document.getElementById('lv-goal2').style.width = '40%';
                document.getElementById('lv-goal3').style.width = '25%';
            }, 200);
        }
    }
    function lvResetAnims(id) {
        if (id === 'lv-resume-modal') {
            document.getElementById('lv-resume-ring').style.strokeDashoffset = '270';
            document.getElementById('lv-resume-num').textContent = '0%';
        }
        if (id === 'lv-shadow-modal') {
            ['lv-bar1','lv-bar2','lv-bar3','lv-bar4'].forEach(b => { document.getElementById(b).style.width = '0'; });
        }
        if (id === 'lv-goals-modal') {
            ['lv-goal1','lv-goal2','lv-goal3'].forEach(g => { document.getElementById(g).style.width = '0'; });
        }
    }

    function lvToggleTask(rowId, chk) {
        chk.classList.toggle('lv-chk-done');
        document.getElementById(rowId).classList.toggle('lv-task-done');
        chk.textContent = chk.classList.contains('lv-chk-done') ? '✓' : '';
    }

    // ── Wire ALL Google login buttons ──
    document.addEventListener('DOMContentLoaded', () => {
        const ids = [
            'google-login-btn', 'google-login-btn-hero',
            'google-login-btn-resume', 'google-login-btn-shadow',
            'google-login-btn-story', 'google-login-btn-career',
            'google-login-btn-journal', 'google-login-btn-tasks',
            'google-login-btn-goals', 'google-login-btn-saved'
        ];
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('click', () => {
                if (typeof window.signInWithGoogle === 'function') {
                    window.signInWithGoogle();
                } else {
                    document.getElementById('google-login-btn')?.click();
                }
            });
        });
    });
    </script>

    {{-- ═══════════════════════════════════════════════════
         AI CHATBOT — IMPROVED (OpenRouter + Streaming)
    ═══════════════════════════════════════════════════ --}}
    <script>
    (function(){
        var _chatOpen    = false;
        var _chatHistory = [];
        var _isTyping    = false;

        var fab      = document.getElementById('lv-chat-fab');
        var overlay  = document.getElementById('lv-chatbot-overlay');
        var messages = document.getElementById('lv-chatbot-messages');
        var input    = document.getElementById('lv-chatbot-input');
        var sendBtn  = document.getElementById('lv-chatbot-send');

        var SYSTEM_PROMPT = `You are LifeVault AI — the built-in personal growth assistant for LifeVault, a private AI-powered journaling and self-discovery web app.

ABOUT LIFEVAULT — know this deeply and reference it accurately:
LifeVault is a personal growth platform where users journal their daily lives and use AI to gain deep insights about themselves. Everything is private, encrypted, and free to start. Users sign in with Google.

CORE TOOLS & FEATURES (explain these thoroughly when asked):

📓 JOURNAL
The heart of LifeVault. Users write daily entries with mood tracking (emoji-based), categories (e.g. GIG, Personal, Work), and timestamps. Beautiful Newsreader typography makes writing a pleasure. All entries are stored privately and become the data source for every AI feature. Think of it as your raw material — the more you write, the smarter every AI tool becomes.

🔮 SHADOW SELF ANALYZER
Inspired by Carl Jung's concept of the "shadow" — the unconscious part of yourself you don't show the world. LifeVault AI reads across ALL your journal entries and detects hidden emotional patterns you may not consciously recognize. It surfaces things like: Fear of Abandonment, Perfectionism, Self-Doubt, Suppressed Joy — each shown as a percentage score with a deep personal explanation. Example insight: "Across 47 entries, you minimize your accomplishments when writing about others but celebrate them in private reflections. This gap often signals internalized expectations that don't truly belong to you." Deeply psychological, deeply personal.

📖 LIFE STORY GENERATOR
AI weaves your journal entries into beautifully written memoir-style chapters — like having a ghost-writer turn your raw daily thoughts into literary prose. Chapters are titled, dated, and written in a warm narrative voice. Perfect for preserving memories, processing your past, or simply seeing your life as the remarkable story it actually is.

✨ HOLISTIC CAREER ADVISOR
Goes far beyond a skills assessment. AI synthesizes your journals to identify your most authentic career path by detecting patterns in what makes you happiest, your natural strengths, your recurring passions, and your deepest values. Returns ranked career paths (e.g. Full-Stack Developer 96%, UX Designer 84%) with personal explanations drawn from your actual journal entries — not generic advice. Example: "Your happiest moments cluster around late-night building sessions where you see things you created come to life. This is a strong signal."

📄 RESUME ANALYZER
Upload your resume + a job description → AI gives you a match score (e.g. 78%). Shows which skills match (green), which are partial (yellow), and which are missing (red). Gives specific, actionable improvement suggestions like "Add quantified achievements — 'increased performance by 40%' beats 'improved performance'" or "Mention cloud experience — listed as required in this posting." Practical, direct, and tailored to the exact role.

✅ TASKS
Simple, beautiful task manager. Check off tasks with satisfying interactions. Designed to complement your journal — capture what you're working on alongside what you're feeling. Helps turn journal reflections into concrete action.

🎯 GOALS
Set long-term goals and track them with visual progress bars and percentage indicators. Break goals into milestones (e.g. "Graduate with Latin Honors — 68% complete, 3 of 5 milestones done"). Goals connect to your journal so the AI understands what matters most to you and can reference your aspirations in its analyses.

🔖 SAVED ITEMS
All your AI-generated reports — Shadow Self analyses, Career Paths, Life Story chapters, Resume scores — are automatically saved here. Your personal vault of insights, always accessible, always private.

PRIVACY & VALUES:
- End-to-end encrypted — your data is never sold, never shared
- Free to start — always
- Sign in with Google — no passwords needed
- Your journal is only ever used to power YOUR insights

YOUR PERSONALITY & RESPONSE STYLE:
- Warm, insightful, encouraging — like a brilliant friend who knows psychology, career coaching, and journaling deeply
- When someone asks "what is LifeVault?" or "what can LifeVault do?" — give a FULL, enthusiastic breakdown of all the tools with their core highlights. Don't summarize vaguely. Make them excited.
- When someone asks about a specific feature — explain it deeply, give an example of what it might surface or produce, and mention how it connects to their journal entries.
- Use light markdown: **bold** for feature names and key terms, bullet points for lists, *italics* for emphasis or examples
- Be concise for simple questions (2-3 sentences). Be rich and detailed for feature/product questions.
- Always end feature explanations with a natural invitation to sign in and try it.
- Never be preachy, robotic, or corporate. Sound like someone who genuinely loves this product and wants people to experience it.
- If someone seems stressed, anxious, or struggling — acknowledge their feelings with warmth before pivoting to how LifeVault might help.`;

        // ── Render markdown ──
        function renderMarkdown(text) {
            return text
                .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                .replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>')
                .replace(/\*(.+?)\*/g,'<em>$1</em>')
                .replace(/`(.+?)`/g,'<code>$1</code>')
                .replace(/^[-•]\s(.+)/gm,'<li>$1</li>')
                .replace(/(<li>[\s\S]+?<\/li>)(?=(\n|$))/g,'<ul>$1</ul>')
                .replace(/\n\n/g,'</p><p>')
                .replace(/\n/g,'<br>');
        }

        function getTime() {
            return new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
        }

        function escapeHtml(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
        }

        // ── Append AI message ──
        function appendAIMsg(text, isTyping) {
            var wrap = document.createElement('div');
            wrap.style.cssText = 'display:flex;flex-direction:column;gap:3px';
            if (isTyping) wrap.id = 'lv-typing-wrap';

            var row = document.createElement('div');
            row.className = 'lv-chat-msg lv-chat-ai';

            var ico = document.createElement('div');
            ico.className = 'lv-chat-ico';
            ico.textContent = '🤖';

            var bubble = document.createElement('div');
            bubble.className = 'lv-chat-bubble';
            if (isTyping) {
                bubble.innerHTML = '<span class="lv-chat-typing"><span></span><span></span><span></span></span>';
            } else {
                bubble.innerHTML = '<p>' + renderMarkdown(text) + '</p>';
            }

            row.appendChild(ico);
            row.appendChild(bubble);
            wrap.appendChild(row);

            if (!isTyping) {
                var meta = document.createElement('div');
                meta.className = 'lv-chat-meta';
                meta.style.paddingLeft = '40px';
                meta.textContent = getTime();
                wrap.appendChild(meta);
            }

            messages.appendChild(wrap);
            messages.scrollTop = messages.scrollHeight;
            return bubble;
        }

        // ── Append user message ──
        function appendUserMsg(text) {
            var wrap = document.createElement('div');
            wrap.style.cssText = 'display:flex;flex-direction:column;gap:3px';

            var row = document.createElement('div');
            row.className = 'lv-chat-msg lv-chat-user';

            var ico = document.createElement('div');
            ico.className = 'lv-chat-ico';
            ico.textContent = '👤';

            var bubble = document.createElement('div');
            bubble.className = 'lv-chat-bubble';
            bubble.textContent = text;

            row.appendChild(ico);
            row.appendChild(bubble);
            wrap.appendChild(row);

            var meta = document.createElement('div');
            meta.className = 'lv-chat-meta lv-chat-user-meta';
            meta.textContent = getTime();
            wrap.appendChild(meta);

            messages.appendChild(wrap);
            messages.scrollTop = messages.scrollHeight;
        }

        // ── Seed welcome message ──
        function seedWelcome() {
            if (!messages || messages.children.length) return;
            appendAIMsg("Hi! I'm **LifeVault AI** ✨\n\nAsk me anything — what LifeVault can do, how journaling works, shadow work, career advice, or anything on your mind.\n\nWhat would you like to know?");
        }

        // ── Toggle chatbot ──
        window.lvToggleChatbot = function() {
            _chatOpen = !_chatOpen;
            overlay.classList.toggle('lv-chat-visible', _chatOpen);
            fab.classList.toggle('lv-chat-open', _chatOpen);
            document.getElementById('lv-chat-fab-icon').textContent = _chatOpen ? '✕' : '🤖';
            if (_chatOpen) {
                seedWelcome();
                setTimeout(function(){ input && input.focus(); }, 350);
            }
        };

        // ── Send message with streaming ──
        window.lvSendChat = function() {
            if (!input || _isTyping) return;
            var text = input.value.trim();
            if (!text) return;

            input.value = '';
            sendBtn.disabled = true;
            _isTyping = true;

            appendUserMsg(text);

            // Cap history to last 18 messages
            if (_chatHistory.length > 18) _chatHistory = _chatHistory.slice(-18);
            _chatHistory.push({ role: 'user', content: text });

            var bubbleEl    = appendAIMsg('', true);
            var typingWrap  = document.getElementById('lv-typing-wrap');
            var accumulated = '';
            var started     = false;

            fetch('https://openrouter.ai/api/v1/chat/completions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer {{ env("OPENROUTER_API_KEY") }}',
                    'HTTP-Referer': '{{ config("app.url") }}',
                    'X-Title': 'LifeVault'
                },
                body: JSON.stringify({
                    model: 'anthropic/claude-3.5-haiku',
                    max_tokens: 800,
                    stream: true,
                    messages: [{ role: 'system', content: SYSTEM_PROMPT }].concat(_chatHistory)
                })
            })
            .then(function(res) {
                if (!res.ok || !res.body) throw new Error('Stream error');
                var reader  = res.body.getReader();
                var decoder = new TextDecoder();

                function pump() {
                    return reader.read().then(function(result) {
                        if (result.done) {
                            _chatHistory.push({ role: 'assistant', content: accumulated });
                            return;
                        }
                        var chunk = decoder.decode(result.value, { stream: true });
                        chunk.split('\n').forEach(function(line) {
                            line = line.trim();
                            if (!line.startsWith('data:')) return;
                            var json = line.slice(5).trim();
                            if (json === '[DONE]') return;
                            try {
                                var parsed = JSON.parse(json);
                                var delta  = parsed.choices &&
                                             parsed.choices[0] &&
                                             parsed.choices[0].delta &&
                                             parsed.choices[0].delta.content;
                                if (delta) {
                                    if (!started) {
                                        bubbleEl.innerHTML = '<p>';
                                        started = true;
                                    }
                                    accumulated += delta;
                                    bubbleEl.innerHTML = '<p>' + renderMarkdown(accumulated) + '</p>';
                                    messages.scrollTop = messages.scrollHeight;
                                }
                            } catch(e) {}
                        });
                        return pump();
                    });
                }
                return pump();
            })
            .catch(function() {
                bubbleEl.innerHTML =
                    '<div class="lv-chat-error-nudge">' +
                        '⚠️ Couldn\'t reach AI right now. <span>Check your connection or </span>' +
                        '<button onclick="window.signInWithGoogle&&window.signInWithGoogle()">sign in</button>' +
                        '<span> for the full experience.</span>' +
                    '</div>';
            })
            .finally(function() {
                if (typingWrap && !typingWrap.querySelector('.lv-chat-meta')) {
                    var meta = document.createElement('div');
                    meta.className = 'lv-chat-meta';
                    meta.style.paddingLeft = '40px';
                    meta.textContent = getTime();
                    typingWrap.appendChild(meta);
                    if (typingWrap.id) typingWrap.removeAttribute('id');
                }
                _isTyping        = false;
                sendBtn.disabled = false;
                input.focus();
                messages.scrollTop = messages.scrollHeight;
            });
        };

        // ── Hide FAB when logged in ──
        document.addEventListener('userLoggedIn', function() {
            if (fab)     fab.style.display     = 'none';
            if (overlay) overlay.style.display = 'none';
            _chatHistory = [];
        });

    })();
    </script>

</body>
</html>