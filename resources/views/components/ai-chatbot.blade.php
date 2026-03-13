<div id="ai-chatbot" class="ai-chatbot-overlay" style="display:none">
  <div class="ai-chatbot-container">
    <div class="ai-chatbot-header">
      <div class="ai-chatbot-title">🤖 LifeVault AI Assistant</div>
      <button class="ai-chatbot-close" onclick="toggleAIChatbot()">×</button>
    </div>
    <div class="ai-chatbot-messages" id="ai-chat-messages"></div>
    <div class="ai-chatbot-input-area">
      <input type="text" id="ai-chat-input" placeholder="Ask about your journals, career advice, shadow work..." onkeypress="handleAIChatInput(event)">
      <button onclick="sendAIChatMessage()">Send</button>
    </div>
  </div>
</div>

<style>
.ai-chatbot-overlay {
  position: fixed;
  inset: 0;
  background: rgba(11,15,26,0.95);
  z-index: 1000;
  display: flex;
  align-items: flex-end;
  backdrop-filter: blur(12px);
}
.ai-chatbot-container {
  background: var(--surface);
  border: 1px solid var(--accent);
  border-radius: 24px 24px 0 0;
  width: 100%;
  max-width: 480px;
  max-height: 85vh;
  display: flex;
  flex-direction: column;
  margin: 0 auto 20px;
  box-shadow: 0 -20px 60px rgba(0,0,0,0.5);
}
.ai-chatbot-header {
  padding: 20px 24px 16px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.ai-chatbot-title {
  font-family: 'Syne', sans-serif;
  font-weight: 700;
  font-size: 1.1rem;
  background: linear-gradient(135deg, var(--accent), #a78bfa);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.ai-chatbot-close {
  background: none;
  border: none;
  font-size: 1.4rem;
  color: var(--muted);
  cursor: pointer;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}
.ai-chatbot-close:hover { background: rgba(255,255,255,0.05); color: var(--text); }
.ai-chatbot-messages {
  flex: 1;
  padding: 16px 24px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: rgba(79,142,247,0.3) transparent;
}
.ai-chatbot-messages::-webkit-scrollbar { width: 4px; }
.ai-chatbot-messages::-webkit-scrollbar-track { background: transparent; }
.ai-chatbot-messages::-webkit-scrollbar-thumb {
  background: rgba(79,142,247,0.3);
  border-radius: 2px;
}
.ai-message {
  margin-bottom: 16px;
  display: flex;
  gap: 12px;
  animation: messageSlide 0.3s ease;
}
@keyframes messageSlide { from { opacity: 0; transform: translateY(10px); } }
.ai-message.user { flex-direction: row-reverse; }
.ai-message.user .ai-message-bubble {
  background: linear-gradient(135deg, var(--accent), #60a5fa);
  color: white;
}
.ai-message.ai .ai-message-avatar {
  background: linear-gradient(135deg, #a78bfa, #ec4899);
}
.ai-message-bubble {
  max-width: 80%;
  padding: 12px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
  line-height: 1.5;
  position: relative;
}
.ai-message-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  font-weight: 600;
}
.ai-message.user .ai-message-avatar { background: rgba(79,142,247,0.2); color: var(--accent); }
.ai-chatbot-input-area {
  padding: 20px 24px;
  border-top: 1px solid var(--border);
  display: flex;
  gap: 12px;
}
#ai-chat-input {
  flex: 1;
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 24px;
  padding: 14px 20px;
  font-size: 0.9rem;
  outline: none;
  transition: all 0.2s;
}
#ai-chat-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,142,247,0.1); }
.ai-chatbot-input-area button {
  background: var(--accent);
  color: white;
  border: none;
  border-radius: 24px;
  padding: 14px 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
  flex-shrink: 0;
}
.ai-chatbot-input-area button:hover { background: #3b82f6; transform: translateY(-1px); }
.ai-chatbot-input-area button:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
</style>

{{-- ════════════════════════════════════════════
     FLOATING AI CHAT BUTTON
════════════════════════════════════════════ --}}
<button id="lv-chat-fab" onclick="lvToggleChatbot()" title="Chat with LifeVault AI">
    <span id="lv-chat-fab-icon">🤖</span>
</button>

{{-- ════════════════════════════════════════════
     AI CHATBOT OVERLAY
════════════════════════════════════════════ --}}
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

<style>
#lv-chat-fab {
    position: fixed;
    bottom: 32px; right: 32px;
    z-index: 500;
    width: 58px; height: 58px;
    border-radius: 50%; border: none; cursor: pointer;
    background: linear-gradient(135deg, #7c3aed, #4f8ef7);
    box-shadow: 0 8px 32px rgba(124,58,237,.5), 0 0 0 0 rgba(124,58,237,.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    transition: transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .25s;
    animation: lv-fadeup .7s .8s ease both;
}
#lv-chat-fab:hover {
    transform: scale(1.12) translateY(-3px);
    box-shadow: 0 14px 40px rgba(124,58,237,.65), 0 0 0 8px rgba(124,58,237,.1);
}
#lv-chat-fab:active { transform: scale(.95); }
#lv-chat-fab.lv-chat-open { background: linear-gradient(135deg,#4b5563,#374151); }

#lv-chatbot-overlay {
    position: fixed;
    bottom: 104px; right: 32px;
    z-index: 499;
    width: 390px;
    max-height: 580px;
    background: linear-gradient(145deg, rgba(13,15,35,.98), rgba(18,20,46,.98));
    border: 1px solid rgba(124,58,237,.3);
    border-radius: 24px;
    display: flex; flex-direction: column;
    box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04);
    transform: scale(.88) translateY(20px);
    opacity: 0;
    pointer-events: none;
    transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
    overflow: hidden;
}
#lv-chatbot-overlay.lv-chat-visible {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: all;
}

#lv-chatbot-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 1px solid rgba(255,255,255,.06);
    background: rgba(255,255,255,.02);
    flex-shrink: 0;
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
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}
#lv-chatbot-close:hover { background: rgba(248,113,113,.15); border-color: rgba(248,113,113,.3); color: #fb7185; }

#lv-chatbot-messages {
    flex: 1; overflow-y: auto;
    padding: 16px;
    display: flex; flex-direction: column; gap: 12px;
    scrollbar-width: thin; scrollbar-color: rgba(124,58,237,.2) transparent;
}
#lv-chatbot-messages::-webkit-scrollbar { width: 3px; }
#lv-chatbot-messages::-webkit-scrollbar-thumb { background: rgba(124,58,237,.3); border-radius: 99px; }

.lv-chat-msg { display: flex; gap: 8px; animation: lv-fadeup .25s ease; }
.lv-chat-msg.lv-chat-user { flex-direction: row-reverse; }
.lv-chat-bubble {
    max-width: 80%; padding: 10px 14px;
    border-radius: 18px; font-size: .82rem; line-height: 1.6;
    font-family: 'Newsreader', serif;
}
.lv-chat-bubble strong { font-weight: 700; color: #f0f2ff; }
.lv-chat-bubble em { font-style: italic; color: #c4b5fd; }
.lv-chat-bubble ul { list-style: none; margin: 6px 0; padding: 0; display: flex; flex-direction: column; gap: 4px; }
.lv-chat-bubble ul li { display: flex; gap: 7px; align-items: flex-start; }
.lv-chat-bubble ul li::before { content: '✦'; color: #a78bfa; flex-shrink: 0; font-size: .65rem; margin-top: 3px; }
.lv-chat-bubble code { background: rgba(255,255,255,.1); padding: 1px 5px; border-radius: 4px; font-size: .75rem; font-family: 'JetBrains Mono', monospace; }
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
    display: flex; align-items: center; justify-content: center; font-size: .85rem;
    margin-top: 2px;
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
    background: rgba(255,255,255,.02);
    flex-shrink: 0;
}
#lv-chatbot-input {
    flex: 1; background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08); border-radius: 99px;
    padding: 10px 16px; font-size: .82rem;
    color: #f0f2ff; font-family: 'Newsreader', serif;
    outline: none; transition: border-color .2s, box-shadow .2s;
    resize: none;
}
#lv-chatbot-input::placeholder { color: #4a5270; }
#lv-chatbot-input:focus { border-color: rgba(124,58,237,.5); box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
#lv-chatbot-send {
    width: 38px; height: 38px; border-radius: 50%; border: none; cursor: pointer;
    background: linear-gradient(135deg, #7c3aed, #4f8ef7);
    color: #fff; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: transform .2s, box-shadow .2s;
}
#lv-chatbot-send:hover { transform: scale(1.1); box-shadow: 0 4px 16px rgba(124,58,237,.5); }
#lv-chatbot-send:disabled { opacity: .4; cursor: not-allowed; transform: none; }

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

@media(max-width:600px) {
    #lv-chatbot-overlay { width: calc(100vw - 32px); right: 16px; bottom: 96px; }
    #lv-chat-fab { right: 20px; bottom: 24px; }
}
</style>

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

CORE TOOLS & FEATURES:

📓 Journal
- The heart of LifeVault. Write daily entries with mood tracking (emoji-based), categories (e.g. GIG, Personal, Work), and timestamps.
- Rich text writing experience powered by Newsreader typography.
- All entries are stored privately and become the data source for every AI feature below.

🔮 Shadow Self Analyzer
- AI reads across ALL your journal entries and detects hidden emotional patterns you may not consciously recognize.
- Surfaces things like: Fear of Abandonment, Perfectionism, Self-Doubt, Suppressed Joy — each shown as a percentage score with explanation.
- Gives deep psychological insights like: "You minimize your accomplishments when writing about others but celebrate them in private reflections."
- Based on Jungian shadow work psychology.

📖 Life Story Generator
- AI weaves your journal entries into beautifully written memoir-style chapters.
- It transforms raw daily thoughts into literary prose — like a ghost-written autobiography.
- Chapters are titled, dated, and written in a warm narrative voice.
- Perfect for people who want to preserve their memories in a meaningful way.

✨ Holistic Career Advisor
- AI synthesizes your journals to identify your most authentic career path.
- Detects patterns in what makes you happiest, your natural strengths, your recurring interests.
- Returns ranked career paths (e.g. Full-Stack Developer 96%, UX Designer 84%) with personal explanations drawn from your actual journal entries.
- Goes beyond skills — it reads your emotions and values.

📄 Resume Analyzer
- Upload your resume + a job description → AI gives you a match score (e.g. 78%).
- Shows which skills match, which are partial, and which are missing.
- Gives specific, actionable improvement suggestions (e.g. "Add quantified achievements", "Mention cloud experience").
- Color-coded skill tags: green = match, yellow = partial, red = missing.

✅ Tasks
- Simple, beautiful task manager linked to your goals and daily life.
- Check off tasks with satisfying interactions.
- Designed to complement your journal — capture what you're working on alongside what you're feeling.

🎯 Goals
- Set long-term goals and track them with visual progress rings and bars.
- Break goals into milestones (e.g. "Graduate with Latin Honors — 68% complete").
- Goals connect to your journal so the AI understands what matters most to you.

🔖 Saved Items
- All your AI-generated reports (Shadow Self analyses, Career Paths, Life Story chapters, Resume scores) are saved here automatically.
- Your personal vault of insights, always accessible.

📊 Insights & Community
- Mood trends, writing streaks, emotional analytics over time.
- Community features for shared growth (coming soon / in beta).

PRIVACY & VALUES:
- End-to-end encrypted — your data is never sold.
- Free to start — always.
- Sign in with Google — no passwords needed.

YOUR PERSONALITY:
- Warm, insightful, encouraging — like a brilliant friend who happens to know psychology, career coaching, and journaling deeply.
- Concise but never shallow. 2-5 sentences for simple questions. Richer answers for deep questions.
- Use light markdown: **bold** for feature names and key terms, bullet points for lists, *italics* for emphasis.
- When users ask what LifeVault is or what it can do, give a full, enthusiastic breakdown of the tools — not a vague summary.
- When users ask about a specific feature, explain it deeply and mention how it connects to their journals.
- Always end feature explanations with an invitation to sign in and try it.
- Never be preachy or robotic. Sound like a real person who loves this product.`;

    // ── Render markdown ──
    function renderMarkdown(text) {
        return text
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>')
            .replace(/\*(.+?)\*/g,'<em>$1</em>')
            .replace(/`(.+?)`/g,'<code>$1</code>')
            .replace(/^[-•]\s(.+)/gm,'<li>$1</li>')
            .replace(/(<li>[\s\S]+?<\/li>)(?=\n|$)/g,'<ul>$1</ul>')
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
        return { wrap, bubble };
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
        appendAIMsg("Hi! I'm **LifeVault AI** ✨\n\nI can help you with journaling tips, shadow work, career advice, or explain everything LifeVault can do for you.\n\nWhat's on your mind?");
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

        var els         = appendAIMsg('', true);
        var typingWrap  = document.getElementById('lv-typing-wrap');
        var bubbleEl    = els.bubble;
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
            // Append timestamp after streaming
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