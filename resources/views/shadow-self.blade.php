{{-- resources/views/shadow-self.blade.php --}}
<div id="page-shadow-self" class="page">
  <div class="page-header">
    <div>
      <div class="page-title">🪞 Shadow Self <span style="background:linear-gradient(135deg,var(--rose),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent">Analyzer</span></div>
      <div class="page-subtitle">Uncover hidden patterns and recurring shadows in your inner world</div>
    </div>
    <button class="btn" id="shadow-analyze-btn"
            onclick="analyzeShadowSelf()"
            style="background:linear-gradient(135deg,rgba(248,113,113,.15),rgba(167,139,250,.15));border-color:rgba(248,113,113,.35);color:var(--rose);font-weight:700">
      🔍 Analyze My Patterns
    </button>
  </div>

  {{-- Warning / Framing Banner --}}
  <div style="background:linear-gradient(135deg,rgba(248,113,113,.07),rgba(167,139,250,.07));border:1px solid rgba(248,113,113,.2);border-radius:16px;padding:18px 22px;margin-bottom:28px;display:flex;align-items:flex-start;gap:14px">
    <span style="font-size:1.4rem;flex-shrink:0;margin-top:2px">🫶</span>
    <div>
      <div style="font-size:.82rem;font-weight:700;margin-bottom:4px;color:var(--text)">A mirror, not a judge</div>
      <div style="font-family:'Newsreader',serif;font-size:.85rem;color:var(--muted);line-height:1.65;font-weight:300">
        The Shadow Self Analyzer surfaces recurring negative thought patterns, self-limiting beliefs, and emotional blind spots hidden in your journal entries. It also offers compassionate reframes — because awareness is the first step to growth.
      </div>
    </div>
  </div>

  {{-- Loading State --}}
  <div id="shadow-loading" style="display:none;text-align:center;padding:64px 24px">
    <div style="width:64px;height:64px;border-radius:50%;border:2px solid var(--border);border-top-color:var(--rose);animation:shadow-spin 1.2s linear infinite;margin:0 auto 24px"></div>
    <div style="font-size:.9rem;font-weight:700;margin-bottom:8px">Looking into the mirror…</div>
    <div id="shadow-loading-step" style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em">Scanning journal entries</div>
  </div>

  {{-- Empty State --}}
  <div id="shadow-empty" style="text-align:center;padding:64px 24px">
    <div style="font-size:3.5rem;margin-bottom:16px;opacity:.35">🪞</div>
    <div style="font-size:.95rem;font-weight:700;margin-bottom:8px;color:var(--muted)">The mirror is waiting</div>
    <div style="font-family:'Newsreader',serif;font-style:italic;font-size:.85rem;color:var(--muted);opacity:.7;max-width:340px;margin:0 auto 28px;line-height:1.65">
      Click "Analyze My Patterns" to discover the recurring shadows hiding in your journal entries
    </div>
    <div style="display:flex;gap:20px;justify-content:center;flex-wrap:wrap">
      @foreach([
        ['var(--rose)',    '😔', 'Self-Doubt',  'Patterns of insecurity'],
        ['var(--amber)',   '😤', 'Inner Critic', 'Recurring self-judgment'],
        ['var(--lavender)','😟', 'Fear Loops',   'Anxious thought cycles'],
        ['var(--teal)',    '😞', 'Avoidance',    'What you keep putting off'],
      ] as [$color, $emoji, $label, $desc])
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px 20px;text-align:center;min-width:130px">
        <div style="font-size:1.6rem;margin-bottom:6px">{{ $emoji }}</div>
        <div style="font-size:.78rem;font-weight:700;color:{{ $color }};margin-bottom:3px">{{ $label }}</div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">{{ $desc }}</div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Results --}}
  <div id="shadow-results" style="display:none">

    {{-- Overall Score --}}
    <div style="background:linear-gradient(135deg,rgba(248,113,113,.1),rgba(167,139,250,.08));border:1px solid rgba(248,113,113,.2);border-radius:16px;padding:24px;margin-bottom:24px;display:flex;align-items:center;gap:24px;flex-wrap:wrap">
      <div style="text-align:center;min-width:90px">
        <div id="shadow-score" style="font-size:3rem;font-weight:800;letter-spacing:-.05em;background:linear-gradient(135deg,var(--rose),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1">—</div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-top:4px">Awareness Score</div>
      </div>
      <div style="flex:1;min-width:200px">
        <div id="shadow-summary-title" style="font-size:.95rem;font-weight:800;letter-spacing:-.02em;margin-bottom:6px"></div>
        <div id="shadow-summary-text" style="font-family:'Newsreader',serif;font-size:.85rem;color:rgba(232,234,240,.75);font-weight:300;line-height:1.65"></div>
      </div>
      {{-- Action buttons --}}
      <div style="display:flex;gap:8px;flex-shrink:0;flex-wrap:wrap">
        <button class="btn" onclick="analyzeShadowSelf()" style="font-size:.75rem">🔄 Re-analyze</button>
        <button class="btn" id="shadow-save-btn" onclick="saveCurrentAnalysis()"
                style="font-size:.75rem;border-color:rgba(52,211,153,.35);color:var(--green);background:rgba(52,211,153,.08)">
          🔖 Save Analysis
        </button>
      </div>
    </div>

    {{-- Patterns Grid --}}
    <div style="margin-bottom:10px;font-family:'JetBrains Mono',monospace;font-size:.62rem;text-transform:uppercase;letter-spacing:.14em;color:var(--muted);display:flex;align-items:center;gap:10px">
      <span>Detected Patterns</span>
      <span style="flex:1;height:1px;background:var(--border)"></span>
    </div>
    <div id="shadow-patterns-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;margin-bottom:28px"></div>

    {{-- Reframes --}}
    <div style="margin-bottom:10px;font-family:'JetBrains Mono',monospace;font-size:.62rem;text-transform:uppercase;letter-spacing:.14em;color:var(--muted);display:flex;align-items:center;gap:10px">
      <span>Compassionate Reframes</span>
      <span style="flex:1;height:1px;background:var(--border)"></span>
    </div>
    <div id="shadow-reframes-list" style="display:flex;flex-direction:column;gap:12px;margin-bottom:28px"></div>

    {{-- Growth Actions --}}
    <div class="card" style="margin-bottom:24px">
      <div class="card-title">🌱 Suggested Growth Actions</div>
      <div id="shadow-actions-list" style="display:flex;flex-direction:column;gap:10px"></div>
    </div>

    {{-- Strengths --}}
    <div class="card">
      <div class="card-title">✨ Hidden Strengths Discovered</div>
      <div style="font-family:'Newsreader',serif;font-size:.82rem;color:var(--muted);font-style:italic;font-weight:300;margin-bottom:16px">
        Even in your shadows, your journal reveals remarkable strengths:
      </div>
      <div id="shadow-strengths-list" style="display:flex;gap:10px;flex-wrap:wrap"></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
let shadowAnalysis = null;

function animateShadowSteps() {
  const steps = [
    'Scanning journal entries…',
    'Identifying thought patterns…',
    'Mapping emotional cycles…',
    'Finding recurring shadows…',
    'Crafting compassionate reframes…',
    'Discovering hidden strengths…',
  ];
  let i = 0;
  const el = document.getElementById('shadow-loading-step');
  return setInterval(() => { if (el) el.textContent = steps[i++ % steps.length]; }, 1500);
}

window.analyzeShadowSelf = async () => {
  const entries = (window.journals || []).slice(0, 30);
  if (entries.length < 3) {
    window.toast('Write at least 3 journal entries first', '📓');
    return;
  }

  document.getElementById('shadow-empty').style.display   = 'none';
  document.getElementById('shadow-results').style.display = 'none';
  document.getElementById('shadow-loading').style.display = 'block';
  document.getElementById('shadow-analyze-btn').disabled  = true;

  const stepInterval = animateShadowSteps();

  const formattedEntries = entries.map(e => ({
    title:     e.title     || 'Untitled',
    content:   (e.content   || '').substring(0, 1900),
    mood:      parseInt(e.mood) || 3,
    createdAt: e.createdAt ? new Date(e.createdAt).toISOString() : null,
  }));

  try {
    const response = await fetch('http://127.0.0.1:8000/ai/shadow-self/analyze', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ entries: formattedEntries }),
    });

    clearInterval(stepInterval);

    if (!response.ok) {
      const err = await response.json().catch(() => ({}));
      throw new Error(err.error || `HTTP ${response.status}`);
    }

    const json = await response.json();

    if (!json.success || !json.data) {
      throw new Error(json.error || 'Unexpected response from server');
    }

    shadowAnalysis = json.data;
    renderShadowResults(shadowAnalysis);

    document.getElementById('shadow-loading').style.display = 'none';
    document.getElementById('shadow-results').style.display = 'block';

    // Reset save button state for new analysis
    const saveBtn = document.getElementById('shadow-save-btn');
    if (saveBtn) {
      saveBtn.textContent      = '🔖 Save Analysis';
      saveBtn.style.borderColor = 'rgba(52,211,153,.35)';
      saveBtn.style.background  = 'rgba(52,211,153,.08)';
      saveBtn.style.color       = 'var(--green)';
      saveBtn.disabled          = false;
    }

    window.toast('Pattern analysis complete 🪞', '✨');

  } catch (err) {
    clearInterval(stepInterval);
    document.getElementById('shadow-loading').style.display = 'none';
    document.getElementById('shadow-empty').style.display   = 'block';
    window.toast('Error: ' + err.message, '❌');
  } finally {
    document.getElementById('shadow-analyze-btn').disabled = false;
  }
};

function renderShadowResults(data) {
  const colorMap   = { rose:'var(--rose)', amber:'var(--amber)', lavender:'var(--lavender)', teal:'var(--teal)', accent:'var(--accent)' };
  const bgMap      = { rose:'rgba(248,113,113,.1)', amber:'rgba(251,191,36,.1)', lavender:'rgba(167,139,250,.12)', teal:'rgba(45,212,191,.1)', accent:'rgba(79,142,247,.1)' };
  const borderMap  = { rose:'rgba(248,113,113,.25)', amber:'rgba(251,191,36,.25)', lavender:'rgba(167,139,250,.28)', teal:'rgba(45,212,191,.25)', accent:'rgba(79,142,247,.25)' };

  document.getElementById('shadow-score').textContent         = (data.awarenessScore ?? '—') + '%';
  document.getElementById('shadow-summary-title').textContent = data.summaryTitle || '';
  document.getElementById('shadow-summary-text').textContent  = data.summaryText  || '';

  // Patterns
  document.getElementById('shadow-patterns-grid').innerHTML = (data.patterns || []).map((p, i) => {
    const color  = colorMap[p.color]  || 'var(--rose)';
    const bg     = bgMap[p.color]     || 'rgba(248,113,113,.1)';
    const border = borderMap[p.color] || 'rgba(248,113,113,.25)';
    const bars   = Array.from({ length: 5 }, (_, j) =>
      `<div style="flex:1;height:5px;border-radius:99px;background:${j < p.severity ? color : 'var(--surface2)'}"></div>`
    ).join('');
    return `
      <div style="background:var(--surface);border:1px solid ${border};border-radius:16px;padding:20px;transition:transform .2s,box-shadow .2s;animation:slideDown .3s ease both;animation-delay:${i * 80}ms"
           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.25)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
          <div style="width:38px;height:38px;border-radius:10px;background:${bg};display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;border:1px solid ${border}">${p.emoji || '😔'}</div>
          <div style="flex:1">
            <div style="font-size:.82rem;font-weight:700;color:${color}">${shadowEsc(p.name || '')}</div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);margin-top:2px;text-transform:uppercase;letter-spacing:.06em">Severity ${p.severity || 1}/5</div>
          </div>
        </div>
        <div style="display:flex;gap:3px;margin-bottom:14px">${bars}</div>
        <div style="font-family:'Newsreader',serif;font-size:.82rem;color:rgba(232,234,240,.75);line-height:1.6;font-weight:300;margin-bottom:10px">${shadowEsc(p.description || '')}</div>
        ${p.evidence ? `<div style="background:${bg};border-left:3px solid ${color};border-radius:0 8px 8px 0;padding:8px 12px;font-family:'Newsreader',serif;font-size:.78rem;color:var(--muted);font-style:italic;font-weight:300;line-height:1.55">${shadowEsc(p.evidence)}</div>` : ''}
      </div>`;
  }).join('');

  // Reframes
  document.getElementById('shadow-reframes-list').innerHTML = (data.reframes || []).map((r, i) => `
    <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:stretch;animation:slideDown .3s ease both;animation-delay:${i * 70}ms">
      <div style="background:rgba(248,113,113,.07);border:1px solid rgba(248,113,113,.18);border-radius:12px 0 0 12px;padding:16px 18px">
        <div style="font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;color:var(--rose);margin-bottom:8px">Shadow Belief</div>
        <div style="font-family:'Newsreader',serif;font-size:.85rem;color:rgba(232,234,240,.7);font-weight:300;line-height:1.6;font-style:italic">"${shadowEsc(r.shadow || '')}"</div>
      </div>
      <div style="background:var(--surface2);display:flex;align-items:center;justify-content:center;padding:0 12px;font-size:1.2rem;border-top:1px solid var(--border);border-bottom:1px solid var(--border)">→</div>
      <div style="background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.18);border-radius:0 12px 12px 0;padding:16px 18px">
        <div style="font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;color:var(--green);margin-bottom:8px">Compassionate Truth</div>
        <div style="font-family:'Newsreader',serif;font-size:.85rem;color:rgba(232,234,240,.85);font-weight:300;line-height:1.6">${shadowEsc(r.reframe || '')}</div>
      </div>
    </div>`).join('');

  // Growth actions
  const actionIcons = ['🌱', '💪', '📝', '🧘', '🗣️'];
  document.getElementById('shadow-actions-list').innerHTML = (data.growthActions || []).map((a, i) => `
    <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);animation:slideDown .3s ease both;animation-delay:${i * 60}ms">
      <div style="width:28px;height:28px;border-radius:8px;background:rgba(79,142,247,.12);border:1px solid rgba(79,142,247,.25);display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0">${actionIcons[i % actionIcons.length]}</div>
      <div style="flex:1;font-family:'Newsreader',serif;font-size:.85rem;color:rgba(232,234,240,.8);line-height:1.6;font-weight:300;padding-top:4px">${shadowEsc(a)}</div>
    </div>`).join('');

  // Strengths
  const strengthColors = [
    ['rgba(79,142,247,.12)',  'rgba(79,142,247,.35)',  'var(--accent)'],
    ['rgba(52,211,153,.1)',   'rgba(52,211,153,.3)',   'var(--green)'],
    ['rgba(167,139,250,.12)', 'rgba(167,139,250,.35)', 'var(--lavender)'],
    ['rgba(45,212,191,.1)',   'rgba(45,212,191,.3)',   'var(--teal)'],
    ['rgba(251,191,36,.1)',   'rgba(251,191,36,.3)',   'var(--amber)'],
    ['rgba(248,113,113,.1)',  'rgba(248,113,113,.3)',  'var(--rose)'],
  ];
  document.getElementById('shadow-strengths-list').innerHTML = (data.hiddenStrengths || []).map((s, i) => {
    const [bg, border, color] = strengthColors[i % strengthColors.length];
    return `<span style="font-family:'JetBrains Mono',monospace;font-size:.62rem;padding:6px 14px;border-radius:99px;background:${bg};border:1px solid ${border};color:${color};text-transform:uppercase;letter-spacing:.07em;animation:slideDown .3s ease both;animation-delay:${i * 50}ms">${shadowEsc(s)}</span>`;
  }).join('');
}

// ── Save current analysis to Firestore ────────────────────────
window.saveCurrentAnalysis = async function () {
  if (!shadowAnalysis) {
    window.toast?.('No analysis to save yet', '⚠️');
    return;
  }

  const cu = window.currentUser;
  if (!cu) {
    window.toast?.('You must be logged in to save', '⚠️');
    return;
  }

  const btn = document.getElementById('shadow-save-btn');
  if (btn) { btn.disabled = true; btn.textContent = '💾 Saving…'; }

  try {
    const { addDoc, collection, serverTimestamp } = window._fbFS;
    await addDoc(collection(window.db, 'users', cu.uid, 'shadow_analyses'), {
      ...shadowAnalysis,
      savedAt: serverTimestamp(),
    });

    if (btn) {
      btn.textContent       = '✅ Saved!';
      btn.style.borderColor = 'rgba(52,211,153,.5)';
      btn.style.background  = 'rgba(52,211,153,.15)';
      btn.style.color       = 'var(--green)';
      setTimeout(() => {
        btn.textContent       = '🔖 Save Analysis';
        btn.style.borderColor = 'rgba(52,211,153,.35)';
        btn.style.background  = 'rgba(52,211,153,.08)';
        btn.style.color       = 'var(--green)';
        btn.disabled          = false;
      }, 2500);
    }

    window.toast?.('Analysis saved! View it in 🔖 Saved', '✅');

  } catch (e) {
    if (btn) { btn.textContent = '🔖 Save Analysis'; btn.disabled = false; }
    window.toast?.('Error saving: ' + e.message, '❌');
  }
};

// Named shadowEsc to avoid any conflict with the global esc()
function shadowEsc(s) {
  return String(s || '')
    .replace(/&/g, '&amp;').replace(/</g, '&lt;')
    .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

if (!document.getElementById('shadow-spin-style')) {
  const s = document.createElement('style');
  s.id = 'shadow-spin-style';
  s.textContent = '@keyframes shadow-spin { to { transform: rotate(360deg) } }';
  document.head.appendChild(s);
}
</script>
@endpush