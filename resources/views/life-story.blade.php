{{-- resources/views/life-story.blade.php --}}
<div id="page-life-story" class="page">

  <div class="page-header">
    <div>
      <div class="page-title">📖 Life Story
        <span style="background:linear-gradient(135deg,var(--accent),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent">Generator</span>
      </div>
      <div class="page-subtitle">Turn your journal entries into a beautifully written narrative</div>
    </div>
    <button class="btn btn-primary" id="generate-story-btn" onclick="generateLifeStory()">
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

  {{-- ── TWO-COLUMN LAYOUT ──────────────────────────────────── --}}
  <div class="grid-2" style="margin-bottom:24px;align-items:start">

    {{-- ── LEFT COLUMN: Time Range + Narrative Style ────────────────── --}}
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
          <div class="story-range-option {{ $val === 'last7' ? 'selected' : '' }}"
               data-range="{{ $val }}"
               onclick="selectRange('{{ $val }}')"
               style="padding:14px;background:var(--surface2);border-radius:12px;border:1.5px solid {{ $val === 'last7' ? 'var(--accent)' : 'var(--border)' }};cursor:pointer;transition:all .18s">
            <div style="font-size:1.2rem;margin-bottom:5px">{{ $icon }}</div>
            <div class="range-label" style="font-size:.78rem;font-weight:700;margin-bottom:2px;color:{{ $val === 'last7' ? 'var(--accent)' : 'var(--text)' }}">{{ $label }}</div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">{{ $sub }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- ── Narrative Style (collapsible) ──────── --}}
      <div class="card" style="overflow:hidden">

        {{-- Toggle header --}}
        <div onclick="toggleNarrativeStyle()"
             style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none">
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            <span style="font-size:.82rem;font-weight:700;color:var(--text)">&#127917; Narrative Style</span>
            <span id="narrative-selected-pill"
                  style="display:none;font-family:'JetBrains Mono',monospace;font-size:.58rem;padding:3px 10px;border-radius:99px;background:rgba(79,142,247,.12);border:1px solid rgba(79,142,247,.25);color:var(--accent)">
              &#128221; Memoir
            </span>
          </div>
          <div id="narrative-chevron"
               style="width:22px;height:22px;border-radius:6px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:.65rem;color:var(--muted);transition:transform .3s;flex-shrink:0">
            &#9650;
          </div>
        </div>

        {{-- Collapsible list --}}
        <div id="narrative-style-body"
             style="display:flex;flex-direction:column;gap:8px;margin-top:14px;max-height:1000px;opacity:1;overflow:hidden;transition:max-height .35s cubic-bezier(.4,0,.2,1),opacity .25s ease,margin-top .25s ease">
          @foreach([
            ['memoir',                  '&#128221;', 'Memoir',                 'Reflective, intimate first-person prose'],
            ['literary',                '&#128218;', 'Literary Fiction',        'Vivid, character-driven storytelling'],
            ['poetic',                  '&#127800;', 'Poetic Prose',            'Lyrical, metaphor-rich language'],
            ['cinematic',               '&#127916;', 'Cinematic',               'Scene-by-scene, visual narrative'],
            ['noir',                    '&#128373;&#65039;', 'Noir',            'Hardboiled, cynical and full of suspense'],
            ['epistolary',              '&#128140;', 'Epistolary',              'A series of letters or diary entries'],
            ['mythic',                  '&#10024;',  'Mythic',                  'Allegorical tale with mythic archetypes'],
            ['stream-of-consciousness', '&#129504;', 'Stream of Consciousness', 'Flowing, unfiltered internal monologue'],
            ['gonzo',                   '&#129322;', 'Gonzo',                   'Subjective first-person, blurring fact & fiction'],
            ['teen-musical',            '&#127908;', 'Teen Musical',            'Upbeat, dramatic, emotionally heightened'],
          ] as [$val, $icon, $label, $desc])
          <div class="story-style-option {{ $val === 'memoir' ? 'selected' : '' }}"
               data-style="{{ $val }}"
               data-icon="{{ $icon }}"
               data-label="{{ $label }}"
               onclick="selectStyle('{{ $val }}')"
               style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:var(--surface2);border-radius:10px;border:1.5px solid {{ $val === 'memoir' ? 'var(--accent)' : 'var(--border)' }};cursor:pointer;transition:all .18s">
            <span style="font-size:1.1rem;flex-shrink:0">{!! $icon !!}</span>
            <div style="flex:1">
              <div class="style-label" style="font-size:.8rem;font-weight:700;color:{{ $val === 'memoir' ? 'var(--accent)' : 'var(--text)' }}">{{ $label }}</div>
              <div style="font-family:'Newsreader',serif;font-size:.72rem;color:var(--muted);font-weight:300;margin-top:1px">{{ $desc }}</div>
            </div>
            <div class="style-check" style="width:18px;height:18px;border-radius:50%;border:1.5px solid {{ $val === 'memoir' ? 'var(--accent)' : 'var(--border)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $val === 'memoir' ? 'var(--accent)' : 'transparent' }};transition:all .18s">
              @if($val === 'memoir')<span style="font-size:.6rem;color:white;font-weight:700">&#10003;</span>@endif
            </div>
          </div>
          @endforeach
        </div>

      </div>

    </div>{{-- /left column --}}

    {{-- ── RIGHT COLUMN: Story output + Focus Themes ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

      {{-- ╔══ Story Output Card ══╗ --}}
      <div class="card" id="story-output-card" style="display:flex;flex-direction:column">

        <div class="card-title">
          📖 Your Story
          <span id="story-word-count" style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);font-weight:400;margin-left:auto"></span>
        </div>

        {{-- Empty State --}}
        <div id="story-empty-state" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:24px 16px">
          <div style="font-size:2.2rem;margin-bottom:10px;opacity:.3">📖</div>
          <div style="font-size:.82rem;font-weight:700;color:var(--muted);margin-bottom:5px">Your story awaits</div>
          <div style="font-family:'Newsreader',serif;font-style:italic;font-size:.76rem;color:var(--muted);opacity:.65;max-width:220px;line-height:1.5">
            Configure your settings and click Generate to turn your journal into a narrative
          </div>
          <div style="margin-top:14px;display:flex;gap:5px;align-items:center">
            <div style="width:5px;height:5px;border-radius:50%;background:var(--accent);opacity:.25"></div>
            <div style="width:5px;height:5px;border-radius:50%;background:var(--lavender);opacity:.25"></div>
            <div style="width:5px;height:5px;border-radius:50%;background:var(--teal);opacity:.25"></div>
          </div>
        </div>

        {{-- Loading State --}}
        <div id="story-loading-state" style="display:none;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:32px 16px">
          <div style="width:44px;height:44px;border-radius:50%;border:2px solid var(--border);border-top-color:var(--accent);animation:ls-spin 1s linear infinite;margin:0 auto 16px"></div>
          <div style="font-size:.84rem;font-weight:700;margin-bottom:5px;color:var(--text)">Weaving your narrative…</div>
          <div id="story-loading-step" style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em">Reading your journals</div>
        </div>

        {{-- Error State --}}
        <div id="story-error-state" style="display:none;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:24px 16px">
          <div style="font-size:1.8rem;margin-bottom:10px">⚠️</div>
          <div style="font-size:.84rem;font-weight:700;color:var(--text);margin-bottom:6px">Generation failed</div>
          <div id="story-error-msg" style="font-family:'JetBrains Mono',monospace;font-size:.66rem;color:var(--muted);max-width:260px;line-height:1.7;word-break:break-word"></div>
          <button class="btn btn-primary" onclick="generateLifeStory()" style="margin-top:14px;font-size:.76rem">🔄 Try Again</button>
        </div>

        {{-- Result --}}
        <div id="story-result" style="display:none;flex-direction:column">
          <div id="story-chapter-title"
               style="font-family:'Newsreader',serif;font-size:1.1rem;font-weight:400;font-style:italic;color:var(--accent);margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid var(--border);line-height:1.4">
          </div>
          <div id="story-text"
               style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.9;color:rgba(232,234,240,.88);font-weight:300;white-space:pre-wrap;overflow-y:auto;padding-right:4px;max-height:480px">
          </div>
          <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);flex-wrap:wrap">
            <button class="btn btn-primary" onclick="generateLifeStory()" style="font-size:.78rem">🔄 Regenerate</button>
            <button class="btn" onclick="copyStory()" style="font-size:.78rem">📋 Copy</button>
            <button class="btn" onclick="saveStoryToJournal()" style="font-size:.78rem;border-color:rgba(52,211,153,.3);color:var(--green)">📓 Save to Journal</button>
            <button class="btn" id="story-save-btn" onclick="saveStoryToSaved()" style="font-size:.78rem;border-color:rgba(167,139,250,.35);color:var(--lavender);background:rgba(167,139,250,.08)">🔖 Save Story</button>
            <button class="btn" onclick="shareStory()" style="font-size:.78rem;border-color:rgba(45,212,191,.3);color:var(--teal)">↗ Share</button>
          </div>
        </div>

      </div>{{-- /story-output-card --}}

      {{-- ── Focus Themes (now beside Narrative Style) ───────────── --}}
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

    </div>{{-- /right column --}}
  </div>

</div>{{-- /page --}}

@push('scripts')
<script>
(function () {
  'use strict';

  if (!document.getElementById('ls-styles')) {
    const s = document.createElement('style');
    s.id = 'ls-styles';
    s.textContent = `
      @keyframes ls-spin { to { transform: rotate(360deg) } }
      .story-range-option:hover { border-color: var(--accent) !important; }
      .story-style-option:hover  { border-color: var(--accent) !important; }
      #story-result { animation: ls-fadeIn .4s cubic-bezier(.4,0,.2,1) both; }
      @keyframes ls-fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
      }
    `;
    document.head.appendChild(s);
  }

  // ── State ─────────────────────────────────────────────────────
  let selectedRange  = 'last7';
  let selectedStyle  = 'memoir';
  let selectedThemes = [];
  let currentStory   = null;

  const GENERATE_URL = '{{ route("ai.life-story.generate") }}';
  const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]')?.content ?? '';


  // ── Narrative Style collapse ──────────────────────────────────
  let _narrativeOpen = true;
  window.toggleNarrativeStyle = function () {
    _narrativeOpen = !_narrativeOpen;
    const body     = document.getElementById('narrative-style-body');
    const chevron  = document.getElementById('narrative-chevron');
    const pill     = document.getElementById('narrative-selected-pill');
    if (!body) return;
    if (_narrativeOpen) {
      body.style.maxHeight  = '1000px';
      body.style.opacity    = '1';
      body.style.marginTop  = '14px';
      chevron.style.transform = 'rotate(0deg)';
      if (pill) pill.style.display = 'none';
    } else {
      body.style.maxHeight  = '0';
      body.style.opacity    = '0';
      body.style.marginTop  = '0';
      chevron.style.transform = 'rotate(180deg)';
      if (pill) pill.style.display = 'inline-block';
    }
  };

  // ── Range selector ────────────────────────────────────────────
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

  // ── Style selector ────────────────────────────────────────────
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
      // Update the collapsed pill label
      if (on) {
        const pill = document.getElementById('narrative-selected-pill');
        if (pill) pill.textContent = (o.dataset.icon || '') + ' ' + (o.dataset.label || val);
      }
    });
  };

  // ── Theme toggle ──────────────────────────────────────────────
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
    ['story-empty-state','story-loading-state','story-error-state','story-result'].forEach(pid => {
      const el = document.getElementById(pid);
      if (!el) return;
      el.style.display = (pid === id) ? 'flex' : 'none';
      if (pid === id) el.style.flexDirection = 'column';
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
      memoir:                   'first-person memoir prose — reflective, warm, and intimate',
      literary:                 'literary fiction with vivid sensory detail and a strong character voice',
      poetic:                   'lyrical poetic prose rich with metaphors and emotional imagery',
      cinematic:                'cinematic scene-by-scene storytelling — show-don\'t-tell, visual and immersive',
      noir:                     'hardboiled detective style — cynical, shadowy, and full of suspense',
      epistolary:               'a series of dated letters or diary entries, creating a personal, unfolding story',
      mythic:                   'an allegorical tale using mythic archetypes and symbolic, larger-than-life language',
      'stream-of-consciousness':'a flowing, unfiltered internal monologue, capturing thoughts and feelings as they occur',
      gonzo:                    'a highly subjective, first-person narrative that blurs the line between fact and fiction, with frenetic energy',
      'teen-musical':           'an upbeat, dramatic teen-musical style where emotions run high and every moment feels amplified',
    };

    const summarised = entries.slice(0, 20).map(e =>
      `[${new Date(e.createdAt).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' })}]\n` +
      `Mood: ${e.moodEmoji ?? '😐'} | Title: ${e.title ?? 'Untitled'}\n` +
      (e.content ?? '').slice(0, 500)
    ).join('\n\n---\n\n');

    const themeNote    = selectedThemes.length ? `Weave these themes prominently: ${selectedThemes.join(', ')}.` : '';
    const extraContext = document.getElementById('story-extra-context')?.value?.trim() ?? '';

    return `You are a master literary author helping someone turn their private journal entries into a beautifully written memoir chapter.

JOURNAL ENTRIES (${entries.length} total, showing up to 20):
${summarised}

WRITING INSTRUCTIONS:
- Write exactly 5 paragraphs of moving, genuine prose in English.
- Style: ${styleMap[selectedStyle] ?? styleMap.memoir}
- Use second person ("You…") to make it feel personal and immersive.
- Draw directly from the real emotions, events, and details in the journals.
- The very first line must be the chapter title in this EXACT format: TITLE: [your evocative title here] - LIFE STORY GENERATOR
- After the title line, write ONLY the narrative — no headers, no bullet points, no extra commentary.
- ${themeNote}
${extraContext ? `- Context from the author: ${extraContext}` : ''}
- End the final paragraph with a sentence that feels like a quiet but meaningful turning point.

Begin now with the TITLE: line, then the five paragraphs.`;
  }

  // ── MAIN: Generate ────────────────────────────────────────────
  window.generateLifeStory = async () => {
    const entries = getJournalsInRange();
    if (!entries.length) { notify('No journal entries found for this time range 📓'); return; }

    showPanel('story-loading-state');
    document.getElementById('generate-story-btn').disabled = true;
    document.getElementById('story-word-count').textContent = '';

    const saveBtn = document.getElementById('story-save-btn');
    if (saveBtn) {
      saveBtn.textContent       = '🔖 Save Story';
      saveBtn.style.borderColor = 'rgba(167,139,250,.35)';
      saveBtn.style.background  = 'rgba(167,139,250,.08)';
      saveBtn.style.color       = 'var(--lavender)';
      saveBtn.disabled          = false;
    }

    const stepInterval = animateLoadingSteps();

    try {
      const res = await fetch(GENERATE_URL, {
        method:  'POST',
        headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ prompt: buildPrompt(entries), language: 'English', max_tokens: 1400 }),
      });

      clearInterval(stepInterval);
      const data = await res.json();
      if (!res.ok || data.error) throw new Error(data.message ?? `HTTP ${res.status}`);

      const rawText      = (data.content ?? '').trim();
      const lines        = rawText.split('\n');
      const titleLine    = lines.find(l => /^TITLE:/i.test(l.trim()));
      const chapterTitle = titleLine
        ? titleLine.replace(/^TITLE:\s*/i, '').replace(/\s*-\s*LIFE STORY GENERATOR\s*$/i, '').trim()
        : 'A Chapter of Your Life';
      const storyBody = lines.filter(l => !/^TITLE:/i.test(l.trim())).join('\n').trim();

      currentStory = {
        title: chapterTitle, body: storyBody,
        range: selectedRange, style: selectedStyle, language: 'English',
        themes: [...selectedThemes], createdAt: new Date(),
      };

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

  // ── Save to Firestore ─────────────────────────────────────────
  window.saveStoryToSaved = async function () {
    if (!currentStory) { notify('No story to save yet ⚠️'); return; }
    const cu = window.currentUser;
    if (!cu) { notify('You must be logged in to save ⚠️'); return; }

    const btn = document.getElementById('story-save-btn');
    if (btn) { btn.disabled = true; btn.textContent = '💾 Saving…'; }

    try {
      const { addDoc, collection, serverTimestamp } = window._fbFS;
      await addDoc(collection(window.db, 'users', cu.uid, 'life_stories'), { ...currentStory, savedAt: serverTimestamp() });

      if (btn) {
        btn.textContent = '✅ Saved!';
        btn.style.borderColor = 'rgba(167,139,250,.5)';
        btn.style.background  = 'rgba(167,139,250,.15)';
        btn.style.color       = 'var(--lavender)';
        setTimeout(() => {
          btn.textContent       = '🔖 Save Story';
          btn.style.borderColor = 'rgba(167,139,250,.35)';
          btn.style.background  = 'rgba(167,139,250,.08)';
          btn.style.color       = 'var(--lavender)';
          btn.disabled          = false;
        }, 2500);
      }
      notify('Story saved to 🔖 Saved! ✅');
    } catch (e) {
      if (btn) { btn.textContent = '🔖 Save Story'; btn.disabled = false; }
      notify('Error saving: ' + e.message + ' ❌');
    }
  };

  // ── Other actions ─────────────────────────────────────────────
  window.copyStory = () => {
    if (!currentStory) return;
    const text = `"${currentStory.title}"\n\n${currentStory.body}`;
    if (navigator.clipboard) navigator.clipboard.writeText(text).then(() => notify('Copied to clipboard! 📋'));
    else {
      const ta = Object.assign(document.createElement('textarea'), { value: text });
      document.body.appendChild(ta); ta.select(); document.execCommand('copy'); ta.remove();
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
    } else notify('Open the Journal page to save your story 📓');
  };

  window.shareStory = () => {
    if (!currentStory) return;
    if (typeof openShareModal === 'function') openShareModal('journal');
    else notify('Share feature not available ⚠️');
  };

  function notify(msg) {
    if (typeof window.toast === 'function') window.toast(msg);
    else console.info('[LifeStory]', msg);
  }

})();
</script>
@endpush