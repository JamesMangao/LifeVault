{{-- resources/views/life-story.blade.php --}}
<div id="page-life-story" class="page">

  <div class="page-header">
    <div>
      <div class="page-title">📖 Life Story
        <span style="background:linear-gradient(135deg,var(--accent),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent">Generator</span>
      </div>
      <div class="page-subtitle">Turn your journal entries into a beautifully written narrative</div>
    </div>
    <button class="btn" id="generate-story-btn" onclick="generateLifeStory()"
            style="background:linear-gradient(135deg,rgba(79,142,247,.15),rgba(167,139,250,.15));border-color:rgba(79,142,247,.35);color:var(--accent);font-weight:700">
      ✨ Generate My Story
    </button>
  </div>

  {{-- Info Banner --}}
  <div style="background:linear-gradient(135deg,rgba(79,142,247,.08),rgba(167,139,250,.06));border:1px solid rgba(79,142,247,.18);border-radius:16px;padding:18px 22px;margin-bottom:28px;display:flex;align-items:flex-start;gap:14px">
    <span style="font-size:1.4rem;flex-shrink:0;margin-top:2px">🪄</span>
    <div>
      <div style="font-size:.82rem;font-weight:700;margin-bottom:4px;color:var(--text)">How it works</div>
      <div style="font-family:'Newsreader',serif;font-size:.85rem;color:var(--muted);line-height:1.65;font-weight:300">
        The AI reads your journal entries and weaves them into a cohesive, beautifully written memoir chapter —
        your real words, elevated into storytelling. Choose a time range and narrative style, then watch your life become literature.
      </div>
    </div>
  </div>

  <div class="grid-2" style="margin-bottom:24px">

    {{-- ── LEFT: Controls ──────────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

      {{-- Time Range --}}
      <div class="card">
        <div class="card-title">🗓 Time Range</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
          @foreach([
            ['last7',  '🌙', 'Last 7 Days',   'Recent moments'],
            ['last30', '📅', 'Last 30 Days',  'This month'],
            ['last90', '🍂', 'Last 3 Months', 'A season of life'],
            ['all',    '🌍', 'All Time',       'Your full story'],
          ] as [$val, $icon, $label, $sub])
          <div class="story-range-option {{ $val === 'last30' ? 'selected' : '' }}"
               data-range="{{ $val }}"
               onclick="selectRange('{{ $val }}')"
               style="padding:14px;background:var(--surface2);border-radius:12px;border:1.5px solid {{ $val === 'last30' ? 'var(--accent)' : 'var(--border)' }};cursor:pointer;transition:all .18s">
            <div style="font-size:1.2rem;margin-bottom:5px">{{ $icon }}</div>
            <div class="range-label" style="font-size:.78rem;font-weight:700;margin-bottom:2px;color:{{ $val === 'last30' ? 'var(--accent)' : 'var(--text)' }}">{{ $label }}</div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">{{ $sub }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Narrative Style --}}
      <div class="card">
        <div class="card-title">🎭 Narrative Style</div>
        <div style="display:flex;flex-direction:column;gap:8px">
          @foreach([
            ['memoir',      '📝', 'Memoir',                    'Reflective, intimate first-person prose'],
            ['literary',    '📚', 'Literary Fiction',          'Vivid, character-driven storytelling'],
            ['poetic',      '🌸', 'Poetic Prose',              'Lyrical, metaphor-rich language'],
            ['cinematic',   '🎬', 'Cinematic',                 'Scene-by-scene, visual narrative'],
            ['epistolary',  '✉️',  'Epistolary',               'Written as letters to your future self'],
            ['stream',      '🌊', 'Stream of Consciousness',  'Raw, unfiltered inner monologue'],
            ['mythic',      '⚔️',  'Mythic Hero\'s Journey',  'Your story as an epic quest'],
            ['detective',   '🔍', 'Self-Discovery',            'Uncovering clues about who you are'],
          ] as [$val, $icon, $label, $desc])
          <div class="story-style-option {{ $val === 'memoir' ? 'selected' : '' }}"
               data-style="{{ $val }}"
               onclick="selectStyle('{{ $val }}')"
               style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:var(--surface2);border-radius:10px;border:1.5px solid {{ $val === 'memoir' ? 'var(--accent)' : 'var(--border)' }};cursor:pointer;transition:all .18s">
            <span style="font-size:1.1rem;flex-shrink:0">{{ $icon }}</span>
            <div style="flex:1">
              <div class="style-label" style="font-size:.8rem;font-weight:700;color:{{ $val === 'memoir' ? 'var(--accent)' : 'var(--text)' }}">{{ $label }}</div>
              <div style="font-family:'Newsreader',serif;font-size:.72rem;color:var(--muted);font-weight:300;margin-top:1px">{{ $desc }}</div>
            </div>
            <div class="style-check" style="width:18px;height:18px;border-radius:50%;border:1.5px solid {{ $val === 'memoir' ? 'var(--accent)' : 'var(--border)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $val === 'memoir' ? 'var(--accent)' : 'transparent' }};transition:all .18s">
              @if($val === 'memoir')<span style="font-size:.6rem;color:white;font-weight:700">✓</span>@endif
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Focus Themes --}}
      <div class="card">
        <div class="card-title">
          🏷 Focus Themes
          <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);font-weight:400;margin-left:4px">optional</span>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px" id="theme-chips">
          @foreach(['Growth','Relationships','Challenges','Joy','Work','Health','Dreams','Gratitude'] as $theme)
          <span class="theme-chip"
                data-theme="{{ $theme }}"
                onclick="toggleTheme('{{ $theme }}', this)"
                style="font-family:'JetBrains Mono',monospace;font-size:.6rem;padding:5px 12px;border-radius:99px;border:1px solid var(--border);background:var(--surface2);color:var(--muted);cursor:pointer;text-transform:uppercase;letter-spacing:.08em;transition:all .18s;user-select:none">
            {{ $theme }}
          </span>
          @endforeach
        </div>
        <input type="text" class="form-input" id="story-extra-context"
               placeholder="Any extra context for the AI... (optional)"
               style="font-size:.82rem">
      </div>
    </div>

    {{-- ── RIGHT: Output ────────────────────────────────────── --}}
    <div class="card" style="display:flex;flex-direction:column;min-height:520px">

      <div class="card-title">
        📖 Your Story
        <span id="story-word-count" style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);font-weight:400;margin-left:auto"></span>
      </div>

      {{-- Empty State --}}
      <div id="story-empty-state" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:32px">
        <div style="font-size:3rem;margin-bottom:16px;opacity:.4">📖</div>
        <div style="font-size:.9rem;font-weight:700;color:var(--muted);margin-bottom:8px">Your story awaits</div>
        <div style="font-family:'Newsreader',serif;font-style:italic;font-size:.82rem;color:var(--muted);opacity:.7;max-width:260px;line-height:1.6">
          Configure your settings and click Generate to turn your journal into a narrative
        </div>
        <div style="margin-top:24px;display:flex;gap:6px;align-items:center">
          <div style="width:6px;height:6px;border-radius:50%;background:var(--accent);opacity:.3"></div>
          <div style="width:6px;height:6px;border-radius:50%;background:var(--lavender);opacity:.3"></div>
          <div style="width:6px;height:6px;border-radius:50%;background:var(--teal);opacity:.3"></div>
        </div>
      </div>

      {{-- Loading State --}}
      <div id="story-loading-state" style="flex:1;display:none;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:32px">
        <div style="width:56px;height:56px;border-radius:50%;border:2px solid var(--border);border-top-color:var(--accent);animation:ls-spin 1s linear infinite;margin:0 auto 20px"></div>
        <div style="font-size:.88rem;font-weight:700;margin-bottom:6px;color:var(--text)">Weaving your narrative…</div>
        <div id="story-loading-step" style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em">Reading your journals</div>
      </div>

      {{-- Error State --}}
      <div id="story-error-state" style="flex:1;display:none;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:32px">
        <div style="font-size:2.4rem;margin-bottom:12px">⚠️</div>
        <div style="font-size:.88rem;font-weight:700;color:var(--text);margin-bottom:8px">Generation failed</div>
        <div id="story-error-msg" style="font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--muted);max-width:300px;line-height:1.7;word-break:break-word"></div>
        <button class="btn btn-primary" onclick="generateLifeStory()" style="margin-top:18px;font-size:.78rem">🔄 Try Again</button>
      </div>

      {{-- Result --}}
      <div id="story-result" style="display:none;flex:1;flex-direction:column">
        <div id="story-chapter-title"
             style="font-family:'Newsreader',serif;font-size:1.1rem;font-weight:400;font-style:italic;color:var(--accent);margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid var(--border);line-height:1.4">
        </div>
        <div id="story-text"
             style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.9;color:rgba(232,234,240,.88);font-weight:300;white-space:pre-wrap;flex:1;overflow-y:auto;padding-right:4px;max-height:420px">
        </div>
        <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);flex-wrap:wrap">
          <button class="btn btn-primary" onclick="generateLifeStory()" style="font-size:.78rem">🔄 Regenerate</button>
          <button class="btn" onclick="copyStory()" style="font-size:.78rem">📋 Copy</button>
          <button class="btn" id="save-story-btn" onclick="saveStoryToSaved()"
                  style="font-size:.78rem;border-color:rgba(167,139,250,.3);color:var(--lavender);background:rgba(167,139,250,.06)">
            🔖 Save to Saved Items
          </button>
          <button class="btn" onclick="saveStoryToJournal()" style="font-size:.78rem;border-color:rgba(52,211,153,.3);color:var(--green)">📓 Save to Journal</button>
          <button class="btn" onclick="shareStory()" style="font-size:.78rem;border-color:rgba(45,212,191,.3);color:var(--teal)">↗ Share</button>
        </div>
      </div>
    </div>
  </div>

</div>{{-- /page --}}

@push('scripts')
<script>
(function () {
  'use strict';

  /* ── Session key ────────────────────────────────────────── */
  const STORY_KEY = 'ls_last_story';

  // ── Inject styles once ───────────────────────────────────────
  if (!document.getElementById('ls-styles')) {
    const s = document.createElement('style');
    s.id = 'ls-styles';
    s.textContent = `
      @keyframes ls-spin { to { transform: rotate(360deg) } }
      .story-range-option:hover { border-color: var(--accent) !important; }
      .story-style-option:hover { border-color: var(--accent) !important; }
    `;
    document.head.appendChild(s);
  }

  // ── State ────────────────────────────────────────────────────
  let selectedRange  = 'last30';
  let selectedStyle  = 'memoir';
  let selectedThemes = [];
  let currentStory   = null;

  const GENERATE_URL = '{{ route("ai.life-story.generate") }}';
  const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  // ── Range selector ───────────────────────────────────────────
  window.selectRange = (val) => {
    selectedRange = val;
    document.querySelectorAll('.story-range-option').forEach(o => {
      const on = o.dataset.range === val;
      o.style.borderColor = on ? 'var(--accent)' : 'var(--border)';
      o.classList.toggle('selected', on);
      const lbl = o.querySelector('.range-label');
      if (lbl) lbl.style.color = on ? 'var(--accent)' : 'var(--text)';
    });
  };

  // ── Style selector ───────────────────────────────────────────
  window.selectStyle = (val) => {
    selectedStyle = val;
    document.querySelectorAll('.story-style-option').forEach(o => {
      const on    = o.dataset.style === val;
      const lbl   = o.querySelector('.style-label');
      const check = o.querySelector('.style-check');
      o.style.borderColor = on ? 'var(--accent)' : 'var(--border)';
      o.classList.toggle('selected', on);
      if (lbl) lbl.style.color = on ? 'var(--accent)' : 'var(--text)';
      if (check) {
        check.style.background  = on ? 'var(--accent)' : 'transparent';
        check.style.borderColor = on ? 'var(--accent)' : 'var(--border)';
        check.innerHTML = on ? '<span style="font-size:.6rem;color:white;font-weight:700">✓</span>' : '';
      }
    });
  };

  // ── Theme toggle ─────────────────────────────────────────────
  window.toggleTheme = (theme, el) => {
    const idx = selectedThemes.indexOf(theme);
    if (idx > -1) {
      selectedThemes.splice(idx, 1);
      el.style.background  = 'var(--surface2)';
      el.style.borderColor = 'var(--border)';
      el.style.color       = 'var(--muted)';
    } else {
      selectedThemes.push(theme);
      el.style.background  = 'rgba(79,142,247,.12)';
      el.style.borderColor = 'var(--accent)';
      el.style.color       = 'var(--accent)';
    }
  };

  // ── Panel switcher ────────────────────────────────────────────
  function showPanel(id) {
    ['story-empty-state','story-loading-state','story-error-state','story-result']
      .forEach(pid => {
        const el = document.getElementById(pid);
        if (el) el.style.display = pid === id ? 'flex' : 'none';
      });
  }

  // ── Loading steps animation ───────────────────────────────────
  function animateLoadingSteps() {
    const steps = [
      'Reading your journals…',
      'Finding key moments…',
      'Identifying themes…',
      'Crafting your narrative…',
      'Polishing the prose…',
    ];
    let i = 0;
    const el = document.getElementById('story-loading-step');
    return setInterval(() => { if (el) el.textContent = steps[i++ % steps.length]; }, 1500);
  }

  // ── Filter journals by range ──────────────────────────────────
  function getJournalsInRange() {
    const days   = { last7: 7, last30: 30, last90: 90, all: 99999 }[selectedRange] ?? 30;
    const cutoff = Date.now() - days * 86_400_000;
    return (window.journals || []).filter(j => new Date(j.createdAt).getTime() >= cutoff);
  }

  // ── Build prompt ──────────────────────────────────────────────
  function buildPrompt(entries) {
    const styleMap = {
      memoir:     'first-person memoir prose — reflective, warm, and intimate',
      literary:   'literary fiction with vivid sensory detail and a strong character voice',
      poetic:     'lyrical poetic prose rich with metaphors and emotional imagery',
      cinematic:  'cinematic scene-by-scene storytelling — show-don\'t-tell, visual and immersive',
      epistolary: 'heartfelt letters written directly to your future self — raw, honest, and personal',
      stream:     'stream of consciousness — unfiltered inner monologue, associative, honest, vulnerable',
      mythic:     'the mythic hero\'s journey — you are the protagonist of an epic quest, facing trials and transformation',
      detective:  'self-discovery written like a detective narrative — uncovering clues, patterns, and revelations about who you are',
    };

    const summarised = entries.slice(0, 20).map(e =>
      `[${new Date(e.createdAt).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' })}]\n` +
      `Mood: ${e.moodEmoji ?? '😐'} | Title: ${e.title ?? 'Untitled'}\n` +
      (e.content ?? '').slice(0, 500)
    ).join('\n\n---\n\n');

    const themeNote    = selectedThemes.length
      ? `Weave these themes prominently: ${selectedThemes.join(', ')}.` : '';
    const extraContext = document.getElementById('story-extra-context')?.value?.trim() ?? '';

    return `You are a master literary author helping someone turn their private journal entries into a beautifully written memoir chapter.

JOURNAL ENTRIES (${entries.length} total, showing up to 20):
${summarised}

WRITING INSTRUCTIONS:
- Write exactly 5 paragraphs of moving, genuine prose
- Style: ${styleMap[selectedStyle] || styleMap.memoir}
- Use second person ("You…") to make it feel personal and immersive
- Draw directly from the real emotions, events, and details in the journals
- The very first line must be the chapter title in this EXACT format: TITLE: [your evocative title here]
- After the title line, write ONLY the narrative — no headers, no bullet points, no commentary
- ${themeNote}
${extraContext ? `- Context from the author: ${extraContext}` : ''}
- End the final paragraph with a sentence that feels like a quiet but meaningful turning point

Begin now with the TITLE: line, then the five paragraphs.`;
  }

  // ── MAIN: Generate ────────────────────────────────────────────
  window.generateLifeStory = async () => {
    const entries = getJournalsInRange();

    if (!entries.length) {
      notify('No journal entries found for this time range 📓');
      return;
    }

    showPanel('story-loading-state');
    document.getElementById('generate-story-btn').disabled = true;
    document.getElementById('story-word-count').textContent = '';

    /* Remove any existing session banner when regenerating */
    const oldBanner = document.getElementById('ls-session-banner');
    if (oldBanner) oldBanner.remove();

    /* Reset save button */
    const saveBtn = document.getElementById('save-story-btn');
    if (saveBtn) { saveBtn.textContent = '🔖 Save to Saved Items'; saveBtn.disabled = false; saveBtn.style.opacity = '1'; }

    const stepInterval = animateLoadingSteps();

    try {
      const res = await fetch(GENERATE_URL, {
        method:  'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept':       'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify({
          prompt:     buildPrompt(entries),
          max_tokens: 1400,
        }),
      });

      clearInterval(stepInterval);

      const data = await res.json();

      if (!res.ok || data.error) {
        throw new Error(data.message ?? `HTTP ${res.status}`);
      }

      // ── Parse TITLE: line ────────────────────────────────────
      const rawText      = (data.content ?? '').trim();
      const lines        = rawText.split('\n');
      const titleLine    = lines.find(l => /^TITLE:/i.test(l.trim()));
      const chapterTitle = titleLine
        ? titleLine.replace(/^TITLE:\s*/i, '').trim()
        : 'A Chapter of Your Life';
      const storyBody    = lines
        .filter(l => !/^TITLE:/i.test(l.trim()))
        .join('\n')
        .trim();

      currentStory = {
        title:     chapterTitle,
        body:      storyBody,
        range:     selectedRange,
        style:     selectedStyle,
        themes:    [...selectedThemes],
        createdAt: new Date(),
      };

      /* ── Persist to sessionStorage ────────────────────────── */
      try { sessionStorage.setItem(STORY_KEY, JSON.stringify(currentStory)); } catch (e) {}

      document.getElementById('story-chapter-title').textContent = `"${chapterTitle}"`;
      document.getElementById('story-text').textContent          = storyBody;

      const wc = storyBody.split(/\s+/).filter(Boolean).length;
      document.getElementById('story-word-count').textContent = `${wc} words`;

      showPanel('story-result');
      notify('Your life story is ready ✨');

    } catch (err) {
      clearInterval(stepInterval);
      showPanel('story-error-state');
      const errEl = document.getElementById('story-error-msg');
      if (errEl) errEl.textContent = err.message ?? 'Unknown error';
      console.error('[LifeStory]', err);
    } finally {
      document.getElementById('generate-story-btn').disabled = false;
    }
  };

  // ── Restore session on page load ──────────────────────────────
  (function restoreStorySession() {
    let saved;
    try { saved = JSON.parse(sessionStorage.getItem(STORY_KEY)); } catch (e) {}
    if (!saved) return;

    currentStory = saved;

    /* Restore selector UI state */
    if (saved.range) selectRange(saved.range);
    if (saved.style) selectStyle(saved.style);

    /* Restore theme chip highlights */
    if (Array.isArray(saved.themes)) {
      saved.themes.forEach(theme => {
        const chip = document.querySelector(`.theme-chip[data-theme="${theme}"]`);
        if (chip) toggleTheme(theme, chip);
      });
    }

    /* Restore story output */
    document.getElementById('story-chapter-title').textContent = `"${saved.title}"`;
    document.getElementById('story-text').textContent          = saved.body;

    const wc = (saved.body || '').split(/\s+/).filter(Boolean).length;
    document.getElementById('story-word-count').textContent = `${wc} words`;

    showPanel('story-result');

    /* Teal session banner */
    const resultEl = document.getElementById('story-result');
    if (resultEl && !document.getElementById('ls-session-banner')) {
      const banner = document.createElement('div');
      banner.id = 'ls-session-banner';
      banner.style.cssText = 'display:flex;align-items:center;gap:10px;padding:10px 14px;margin-bottom:16px;background:rgba(45,212,191,.05);border:1px solid rgba(45,212,191,.18);border-radius:10px;flex-shrink:0';
      banner.innerHTML = `
        <span style="font-size:.9rem">🔄</span>
        <span style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--teal);flex:1">Story restored from your last session</span>
        <button style="background:transparent;border:none;color:var(--muted);cursor:pointer;font-size:.75rem;padding:2px 6px;opacity:.6"
                onclick="sessionStorage.removeItem('${STORY_KEY}');this.closest('#ls-session-banner').remove()">✕</button>`;
      resultEl.insertBefore(banner, resultEl.firstChild);
    }
  })();

  // ── Save to Saved Items ───────────────────────────────────────
  window.saveStoryToSaved = () => {
    if (!currentStory) return;
    const btn = document.getElementById('save-story-btn');
    if (typeof window.savedAddItem === 'function') {
      window.savedAddItem({
        type:     'story',
        title:    currentStory.title,
        body:     currentStory.body,
        range:    currentStory.range,
        style:    currentStory.style,
        themes:   currentStory.themes || [],
      });
      if (btn) { btn.textContent = '✅ Saved!'; btn.disabled = true; btn.style.opacity = '.6'; }
    } else {
      notify('Saved Items not available ⚠️');
    }
  };

  // ── Actions ───────────────────────────────────────────────────
  window.copyStory = () => {
    if (!currentStory) return;
    const text = `"${currentStory.title}"\n\n${currentStory.body}`;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(text).then(() => notify('Copied to clipboard! 📋'));
    } else {
      const ta = Object.assign(document.createElement('textarea'), { value: text });
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      ta.remove();
      notify('Copied! 📋');
    }
  };

  window.saveStoryToJournal = () => {
    if (!currentStory) return;
    if (typeof openJournalModal === 'function') {
      openJournalModal();
      setTimeout(() => {
        const t = document.getElementById('journal-title');
        const c = document.getElementById('journal-content');
        if (t) t.value = `📖 ${currentStory.title}`;
        if (c) c.value = currentStory.body;
        notify('Story loaded into journal editor 📓');
      }, 300);
    } else {
      notify('Open the Journal page to save your story 📓');
    }
  };

  window.shareStory = () => {
    if (!currentStory) return;
    if (typeof openShareModal === 'function') openShareModal('journal');
    else notify('Share feature not available ⚠️');
  };

  // ── Toast helper ──────────────────────────────────────────────
  function notify(msg) {
    if (typeof window.toast === 'function') window.toast(msg);
    else console.info('[LifeStory]', msg);
  }

})();
</script>
@endpush