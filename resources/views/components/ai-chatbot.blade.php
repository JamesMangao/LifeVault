{{-- ════════════════════════════════════════════
     FLOATING AI CHAT BUTTON
════════════════════════════════════════════ --}}
<button id="lv-chat-fab" onclick="lvToggleChatbot()" title="Chat with LifeVault AI">
    <span id="lv-chat-fab-icon"><img src="{{ asset('logo.svg') }}" alt="AI" style="width:36px;height:36px;border-radius:50%;object-fit:cover;display:block;"></span>
</button>

{{-- ═══════════════════════════════════════════
     AI CHATBOT OVERLAY
═══════════════════════════════════════════ --}}
<div id="lv-chatbot-overlay">
    <div id="lv-chatbot-container">

        <!-- HEADER -->
        <div id="lv-chatbot-header">
            <div id="lv-chatbot-title">
                <div id="lv-chatbot-avatar"><img src="{{ asset('logo.svg') }}" alt="AI" style="width:48px;height:48px;border-radius:12px;object-fit:cover;"></div>
                <div>
                    <div style="font-weight:700;font-size:.95rem;background:linear-gradient(135deg,#a78bfa,#4f8ef7);-webkit-background-clip:text;-webkit-text-fill-color:transparent">LifeVault AI</div>
                    <div style="font-size:.65rem;color:#4a5270;font-family:'JetBrains Mono',monospace;margin-top:1px">● Online</div>
                </div>
            </div>
            <button id="lv-chatbot-close" onclick="lvToggleChatbot()">✕</button>
        </div>

        <!-- MESSAGES -->
        <div id="lv-chatbot-messages"></div>

        <!-- FAQ ROWS — always visible, fixed height -->
        <div id="lv-chatbot-faqs">
            <div class="lv-faq-track lv-track-left" style="animation-duration:25s">
                <button class="lv-faq-item" onclick="lvSendFAQ('What is LifeVault?')">What is LifeVault?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Is my data private?')">Is my data private?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('How does Shadow Work help?')">How does Shadow Work help?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Can I analyze my resume?')">Can I analyze my resume?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('What is LifeVault?')">What is LifeVault?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Is my data private?')">Is my data private?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('How does Shadow Work help?')">How does Shadow Work help?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Can I analyze my resume?')">Can I analyze my resume?</button>
            </div>
            <div class="lv-faq-track lv-track-right" style="animation-duration:20s">
                <button class="lv-faq-item" onclick="lvSendFAQ('What is the Life Story tool?')">What is the Life Story tool?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('How to start journaling?')">How to start journaling?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Is it really free?')">Is it really free?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Who is Sentinel?')">Who is Sentinel?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('What is the Life Story tool?')">What is the Life Story tool?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('How to start journaling?')">How to start journaling?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Is it really free?')">Is it really free?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Who is Sentinel?')">Who is Sentinel?</button>
            </div>
            <div class="lv-faq-track lv-track-left" style="animation-duration:30s">
                <button class="lv-faq-item" onclick="lvSendFAQ('Can I export my data?')">Can I export my data?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('How does AI analyze me?')">How does AI analyze me?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('What are GIG entries?')">What are GIG entries?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Is there a mobile app?')">Is there a mobile app?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Can I export my data?')">Can I export my data?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('How does AI analyze me?')">How does AI analyze me?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('What are GIG entries?')">What are GIG entries?</button>
                <button class="lv-faq-item" onclick="lvSendFAQ('Is there a mobile app?')">Is there a mobile app?</button>
            </div>
        </div>

        <!-- INPUT -->
        <div id="lv-chatbot-input-area">
            <input type="text" id="lv-chatbot-input"
                   placeholder="Ask about journaling, career, self-discovery…"
                   onkeypress="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();lvSendChat()}">
            <button id="lv-chatbot-send" onclick="lvSendChat()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
            </button>
        </div>

    </div>
</div>

<style>
/* ══════════════════════ FAB ══════════════════════ */
#lv-chat-fab {
    position: fixed;
    bottom: 32px; right: 32px;
    z-index: 9999;
    height: 58px; width: 58px; 
    border-radius: 50%; border: none; cursor: pointer;
    background: linear-gradient(135deg, #7c3aed, #4f8ef7);
    color: white;
    box-shadow: 0 8px 32px rgba(124,58,237,.5);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    transition: all .35s cubic-bezier(.34,1.56,.64,1);
    overflow: hidden; padding: 0;
    animation: lv-fab-pulse 2.5s infinite;
}

#lv-chat-fab:after {
    content: '';
    position: absolute;
    top: 0; left: -100%; width: 50%; height: 100%;
    background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
    transform: skewX(-20deg);
    transition: all .5s;
}

#lv-chat-fab:hover:after {
    left: 150%;
    transition: all 1s ease-in-out;
}

#lv-chat-fab:hover {
    transform: scale(1.1) translateY(-3px);
    box-shadow: 0 14px 40px rgba(124,58,237,.65), 0 0 0 8px rgba(124,58,237,.1);
    animation: none;
}

#lv-chat-fab-icon {
    display: flex; align-items: center; justify-content: center;
    width: 58px; height: 58px;
    flex-shrink: 0;
    transition: transform 0.3s;
}

#lv-chat-fab:active { transform: scale(.95); }
#lv-chat-fab.lv-chat-open { 
    background: linear-gradient(135deg,#4b5563,#374151); 
    width: 58px; border-radius: 50%;
    animation: none;
}
#lv-chat-fab.lv-chat-open #lv-chat-fab-icon { transform: rotate(90deg) scale(0.9); }

/* ══════════════════════ OVERLAY ══════════════════════ */
#lv-chatbot-overlay {
    position: fixed;
    bottom: 104px; right: 32px;
    z-index: 499;
    width: 350px;
    /* Fixed height — NOT max-height — guarantees all rows always have space */
    height: 520px;
    background: linear-gradient(145deg, rgba(13,15,35,.97), rgba(18,20,46,.97));
    border: 1px solid rgba(124,58,237,.3);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04);
    backdrop-filter: blur(20px);
    transform: scale(.88) translateY(20px);
    opacity: 0;
    pointer-events: none;
    transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
}
#lv-chatbot-overlay.lv-chat-visible {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: all;
}

/* ══════════════════════ CONTAINER — CSS Grid ══════════════════════ */
/*
   Row breakdown (total = 520px):
   1. header     — auto  (~69px)
   2. messages   — 1fr   (fills leftover space, scrolls)
   3. faqs       — 100px (HARD fixed, never squashed)
   4. input-area — auto  (~62px)
*/
#lv-chatbot-container {
    display: grid;
    grid-template-rows: auto 1fr 100px auto;
    height: 100%;
    overflow: hidden;
}

/* ══════════════════════ HEADER ══════════════════════ */
#lv-chatbot-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 1px solid rgba(255,255,255,.06);
    background: rgba(255,255,255,.02);
}
#lv-chatbot-title { display: flex; align-items: center; gap: 10px; }
#lv-chatbot-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: transparent;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
#lv-chatbot-close {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
    color: #8892b0; font-size: .85rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}
#lv-chatbot-close:hover {
    background: rgba(248,113,113,.15);
    border-color: rgba(248,113,113,.3);
    color: #fb7185;
}

/* ══════════════════════ MESSAGES ══════════════════════ */
#lv-chatbot-messages {
    overflow-y: auto;
    padding: 16px;
    display: flex; flex-direction: column; gap: 12px;
    scrollbar-width: thin;
    scrollbar-color: rgba(124,58,237,.2) transparent;
}
#lv-chatbot-messages::-webkit-scrollbar { width: 3px; }
#lv-chatbot-messages::-webkit-scrollbar-thumb {
    background: rgba(124,58,237,.3); border-radius: 99px;
}

/* ══════════════════════ FAQ SECTION ══════════════════════ */
/*
   grid-row 3 = exactly 120px (enforced by the grid definition above).
   overflow:hidden clips the wide pill rows — they scroll behind
   invisible left/right walls, creating the marquee effect.
*/
#lv-chatbot-faqs {
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 7px;
    padding: 10px 0;
    border-top: 1px solid rgba(255,255,255,.06);
    border-bottom: 1px solid rgba(255,255,255,.06);
    background: rgba(124,58,237,.03);
}
#lv-chatbot-faqs:hover .lv-faq-track {
    animation-play-state: paused;
}
/* One scrolling row — pills sit in a single horizontal line */
.lv-faq-track {
    display: flex !important;
    flex-direction: row;
    gap: 10px;
    padding: 0 12px;
    white-space: nowrap;
    will-change: transform;
    height: 32px;
    align-items: center;
    visibility: visible !important;
    opacity: 1 !important;
    /* width is intentionally NOT set — it must be natural (max-content)
        so the -50% keyframe moves exactly one copy off-screen */
}

.lv-track-left  { animation: lv-faq-left  linear infinite; }
.lv-track-right { animation: lv-faq-right linear infinite; }

@keyframes lv-faq-left {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
@keyframes lv-faq-right {
    0%   { transform: translateX(-50%); }
    100% { transform: translateX(0); }
}

/* Pill buttons */
.lv-faq-item {
    flex-shrink: 0;
    display: inline-block;
    background: rgba(124,58,237,.1);
    border: 1px solid rgba(124,58,237,.25);
    border-radius: 20px;
    padding: 5px 14px;
    color: #c4b5fd;
    font-size: .71rem;
    font-family: 'Syne', sans-serif;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    line-height: 1.6;
    transition: background .18s, border-color .18s, color .18s, transform .15s;
}
.lv-faq-item:hover {
    background: rgba(124,58,237,.25);
    border-color: rgba(167,139,250,.6);
    color: #fff;
    transform: translateY(-1px);
}

/* ══════════════════════ INPUT AREA ══════════════════════ */
#lv-chatbot-input-area {
    display: flex; gap: 8px;
    padding: 12px 14px;
    border-top: 1px solid rgba(255,255,255,.06);
    background: rgba(255,255,255,.02);
}
#lv-chatbot-input {
    flex: 1;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 99px;
    padding: 10px 16px;
    font-size: .82rem;
    color: #f0f2ff;
    font-family: 'Newsreader', serif;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
#lv-chatbot-input::placeholder { color: #4a5270; }
#lv-chatbot-input:focus {
    border-color: rgba(124,58,237,.5);
    box-shadow: 0 0 0 3px rgba(124,58,237,.1);
}
#lv-chatbot-send {
    width: 38px; height: 38px; border-radius: 50%; border: none; cursor: pointer;
    background: linear-gradient(135deg, #7c3aed, #4f8ef7);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: transform .2s, box-shadow .2s;
}
#lv-chatbot-send:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 16px rgba(124,58,237,.5);
}
#lv-chatbot-send:disabled { opacity: .4; cursor: not-allowed; transform: none; }

/* ══════════════════════ CHAT BUBBLES ══════════════════════ */
.lv-chat-msg { display: flex; gap: 8px; animation: lv-fadeup .25s ease; }
.lv-chat-msg.lv-chat-user { flex-direction: row-reverse; }
.lv-chat-bubble {
    max-width: 80%; padding: 10px 14px;
    border-radius: 18px; font-size: .85rem; line-height: 1.6;
    font-family: 'Newsreader', serif;
}
.lv-chat-bubble strong { font-weight: 700; color: #f0f2ff; }
.lv-chat-bubble em { font-style: italic; color: #c4b5fd; }
.lv-chat-bubble ul {
    list-style: none; margin: 6px 0; padding: 0;
    display: flex; flex-direction: column; gap: 4px;
}
.lv-chat-bubble ul li { display: flex; gap: 7px; align-items: flex-start; }
.lv-chat-bubble ul li::before {
    content: '✦'; color: #a78bfa;
    flex-shrink: 0; font-size: .65rem; margin-top: 3px;
}
.lv-chat-bubble code {
    background: rgba(255,255,255,.1); padding: 1px 5px;
    border-radius: 4px; font-size: .72rem;
    font-family: 'JetBrains Mono', monospace;
}
.lv-chat-bubble p { margin-bottom: 6px; }
.lv-chat-bubble p:last-child { margin-bottom: 0; }
.lv-chat-msg.lv-chat-ai .lv-chat-bubble {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.07);
    color: rgba(232,234,240,.85);
    border-radius: 4px 18px 18px 18px;
}
.lv-chat-msg.lv-chat-user .lv-chat-bubble {
    background: linear-gradient(135deg, #7c3aed, #4f8ef7);
    color: #fff;
    border-radius: 18px 4px 18px 18px;
}
.lv-chat-ico {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; margin-top: 2px;
}
.lv-chat-msg.lv-chat-ai .lv-chat-ico { background: transparent; }
.lv-chat-msg.lv-chat-user .lv-chat-ico { background: rgba(79,142,247,.2); color: #4f8ef7; }
.lv-chat-meta { font-size: .62rem; color: #4a5270; padding: 2px 4px; }
.lv-chat-user-meta { text-align: right; }

/* Typing dots */
.lv-chat-typing span {
    display: inline-block; width: 6px; height: 6px;
    background: #a78bfa; border-radius: 50%; margin: 0 2px;
    animation: lv-typing .9s ease-in-out infinite;
}
.lv-chat-typing span:nth-child(2) { animation-delay: .15s; }
.lv-chat-typing span:nth-child(3) { animation-delay: .3s; }

/* Error nudge */
.lv-chat-error-nudge {
    background: rgba(251,113,133,.08);
    border: 1px solid rgba(251,113,133,.2);
    border-radius: 10px; padding: 10px 12px;
    font-size: .78rem; color: #fb7185;
}
.lv-chat-error-nudge span { color: #8892b0; }
.lv-chat-error-nudge button {
    background: none; border: none; color: #a78bfa;
    font-weight: 700; cursor: pointer; font-size: .78rem;
    padding: 0; font-family: 'Syne', sans-serif;
}

/* ══════════════════════ KEYFRAMES ══════════════════════ */
@keyframes lv-fab-pulse {
    0% { box-shadow: 0 0 0 0 rgba(124,58,237, 0.7); }
    70% { box-shadow: 0 0 0 15px rgba(124,58,237, 0); }
    100% { box-shadow: 0 0 0 0 rgba(124,58,237, 0); }
}
@keyframes lv-typing {
    0%,60%,100% { transform: translateY(0); opacity: .4; }
    30%          { transform: translateY(-6px); opacity: 1; }
}
@keyframes lv-fadeup {
    from { opacity: 0; transform: translateY(8px); }
}

/* ══════════════════════ RESPONSIVE ══════════════════════ */
@media (max-width: 600px) {
    #lv-chatbot-overlay {
        width: calc(100vw - 32px);
        right: 16px;
        bottom: 96px;
        height: 520px;
    }
    #lv-chat-fab { right: 20px; bottom: 24px; }
}
</style>

<script>
(function () {
    var _chatOpen    = false;
    var _chatHistory = [];
    var _isTyping    = false;
    var _userName    = "{{ Auth::check() ? Auth::user()->name : '' }}";
    var _isLoggedIn  = {{ Auth::check() ? 'true' : 'false' }};

    var fab      = document.getElementById('lv-chat-fab');
    var overlay  = document.getElementById('lv-chatbot-overlay');
    var messages = document.getElementById('lv-chatbot-messages');
    var input    = document.getElementById('lv-chatbot-input');
    var sendBtn  = document.getElementById('lv-chatbot-send');

    var SYSTEM_PROMPT = `You are LifeVault AI — the built-in personal growth assistant for LifeVault, a private AI-powered journaling and self-discovery web app.

ABOUT LIFEVAULT — know this deeply and reference it accurately:
LifeVault is a personal growth platform where users journal their daily lives and use AI to gain deep insights about themselves. Everything is private, encrypted, and free to start. Users sign in with Google.

CORE TOOLS & FEATURES:

📓 Journal
- The heart of LifeVault. Write daily entries with mood tracking (emoji-based), categories (e.g. GIG, Personal, Work), and timestamps.
- Rich text writing experience powered by Newsreader typography.
- All entries are stored privately and become the data source for every AI feature below.

🔮 Shadow Self Analyzer
- AI reads across ALL your journal entries and detects hidden emotional patterns you may not consciously recognize.
- Surfaces things like: Fear of Abandonment, Perfectionism, Self-Doubt, Suppressed Joy — each shown as a percentage score with explanation.
- Based on Jungian shadow work psychology.

📖 Life Story Generator
- AI weaves your journal entries into beautifully written memoir-style chapters.
- Transforms raw daily thoughts into literary prose — like a ghost-written autobiography.

✨ Holistic Career Advisor
- AI synthesizes your journals to identify your most authentic career path.
- Returns ranked career paths with personal explanations drawn from your actual journal entries.

📄 Resume Analyzer
- Upload your resume + a job description → AI gives you a match score.
- Color-coded skill tags: green = match, yellow = partial, red = missing.

✅ Tasks & 🎯 Goals
- Beautiful task manager and goal tracker linked to your journals.

🔖 Saved Items
- All AI-generated reports saved automatically in your personal vault.

PRIVACY & VALUES:
- End-to-end encrypted — your data is never sold.
- Free to start — always. Sign in with Google.

CURRENT USER CONTEXT:
- User is: ` + (_isLoggedIn ? 'LOGGED IN as ' + _userName : 'NOT LOGGED IN (Guest)') + `
- If logged in, you can reference their journey and encourage exploring their features.
- If not logged in, emphasize that signing in with Google unlocks the AI analyzers (Shadow Self, Life Story, etc.) which need journal data to work.

YOUR PERSONALITY:
- Warm, insightful, encouraging.
- 2-5 sentences for simple questions, richer for deep ones.
- Use **bold** for feature names, bullet points for lists, *italics* for emphasis.
- Only discuss LifeVault. Politely decline anything else and redirect.`;

    /* ── markdown renderer ── */
    function md(text) {
        return text
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            .replace(/`(.+?)`/g, '<code>$1</code>')
            .replace(/^[-•]\s(.+)/gm, '<li>$1</li>')
            .replace(/(<li>[\s\S]+?<\/li>)/g, '<ul>$1</ul>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>');
    }

    function ts() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    /* ── append AI bubble ── */
    function appendAI(text, typing) {
        var wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;flex-direction:column;gap:3px';
        if (typing) wrap.id = 'lv-typing-wrap';

        var row = document.createElement('div');
        row.className = 'lv-chat-msg lv-chat-ai';

        var ico = document.createElement('div');
        ico.className = 'lv-chat-ico';
        ico.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="1em" height="1em"><path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.911 1.911l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.911 1.911l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.911-1.911l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.911-1.911l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0116.5 15z" clip-rule="evenodd" /></svg>';

        var bubble = document.createElement('div');
        bubble.className = 'lv-chat-bubble';
        bubble.innerHTML = typing
            ? '<span class="lv-chat-typing"><span></span><span></span><span></span></span>'
            : '<p>' + md(text) + '</p>';

        row.appendChild(ico);
        row.appendChild(bubble);
        wrap.appendChild(row);

        if (!typing) {
            var meta = document.createElement('div');
            meta.className = 'lv-chat-meta';
            meta.style.paddingLeft = '40px';
            meta.textContent = ts();
            wrap.appendChild(meta);
        }

        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
        return { wrap: wrap, bubble: bubble };
    }

    /* ── append user bubble ── */
    function appendUser(text) {
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
        meta.textContent = ts();
        wrap.appendChild(meta);

        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
    }

    /* ── welcome message ── */
    function seedWelcome() {
        if (!messages || messages.children.length) return;
        var greeting = "Hi" + (_userName ? " " + _userName.split(' ')[0] : "") + "! I'm **LifeVault AI** ✨";
        if (!_isLoggedIn) {
            appendAI(greeting + "\n\nI can help with journaling tips or explain how LifeVault works. **Sign in** to unlock your personal AI Shadow Self and Life Story reports!");
        } else {
            appendAI(greeting + "\n\nI can help with journaling tips, shadow work, career advice, or explain any feature. How's your journey going today?");
        }
    }

    /* ── toggle open/close ── */
    window.lvToggleChatbot = function () {
        _chatOpen = !_chatOpen;
        overlay.classList.toggle('lv-chat-visible', _chatOpen);
        fab.classList.toggle('lv-chat-open', _chatOpen);
        document.getElementById('lv-chat-fab-icon').innerHTML = _chatOpen ? '✕' : '<img src="/logo.svg" alt="AI" style="width:36px;height:36px;border-radius:50%;object-fit:cover;display:block;">';
        if (_chatOpen) {
            seedWelcome();
            setTimeout(function () { input && input.focus(); }, 350);
        }
    };

    /* ── send message (streaming) ── */
    window.lvSendChat = function () {
        if (!input || _isTyping) return;
        var text = input.value.trim();
        if (!text) return;

        input.value = '';
        sendBtn.disabled = true;
        _isTyping = true;

        appendUser(text);
        if (_chatHistory.length > 18) _chatHistory = _chatHistory.slice(-18);
        _chatHistory.push({ role: 'user', content: text });

        var els        = appendAI('', true);
        var typingWrap = document.getElementById('lv-typing-wrap');
        var bubbleEl   = els.bubble;
        var acc = '', started = false;

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
        .then(function (res) {
            if (!res.ok || !res.body) throw new Error('Stream error');
            var reader = res.body.getReader();
            var dec    = new TextDecoder();

            function pump() {
                return reader.read().then(function (r) {
                    if (r.done) {
                        _chatHistory.push({ role: 'assistant', content: acc });
                        return;
                    }
                    dec.decode(r.value, { stream: true }).split('\n').forEach(function (line) {
                        line = line.trim();
                        if (!line.startsWith('data:')) return;
                        var json = line.slice(5).trim();
                        if (json === '[DONE]') return;
                        try {
                            var delta = JSON.parse(json).choices[0].delta.content;
                            if (delta) {
                                if (!started) { bubbleEl.innerHTML = '<p>'; started = true; }
                                acc += delta;
                                bubbleEl.innerHTML = '<p>' + md(acc) + '</p>';
                                messages.scrollTop = messages.scrollHeight;
                            }
                        } catch (e) {}
                    });
                    return pump();
                });
            }
            return pump();
        })
        .catch(function () {
            bubbleEl.innerHTML =
                '<div class="lv-chat-error-nudge">⚠️ Couldn\'t reach AI. ' +
                '<span>Check your connection or </span>' +
                '<button onclick="window.signInWithGoogle&&window.signInWithGoogle()">sign in</button>' +
                '<span> for the full experience.</span></div>';
        })
        .finally(function () {
            if (typingWrap && !typingWrap.querySelector('.lv-chat-meta')) {
                var m = document.createElement('div');
                m.className = 'lv-chat-meta';
                m.style.paddingLeft = '40px';
                m.textContent = ts();
                typingWrap.appendChild(m);
                typingWrap.removeAttribute('id');
            }
            _isTyping = false;
            sendBtn.disabled = false;
            input.focus();
            messages.scrollTop = messages.scrollHeight;
        });
    };

/* ── Optional: hide when logged in (commented out to show FAQ for all users) ── */
// document.addEventListener('userLoggedIn', function () {
    //     if (fab)     fab.style.display = 'none';
    //     if (overlay) overlay.style.display = 'none';
    //     _chatHistory = [];
// });

    /* ── FAQ click shortcut ── */
    window.lvSendFAQ = function (q) {
        if (!_chatOpen) window.lvToggleChatbot();
        if (input) { input.value = q; window.lvSendChat(); }
    };

})();
</script>