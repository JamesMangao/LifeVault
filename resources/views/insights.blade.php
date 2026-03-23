<div id="page-insights" class="page">

  <div class="page-header">
    <div>
      <div class="page-title">Insights</div>
      <div class="page-subtitle">Patterns in your mood &amp; productivity</div>
    </div>
  </div>

  {{-- ── Streak Banner ── --}}
  <div class="ins-streak-banner">
    <div class="ins-streak-inner">
      <div class="ins-streak-flame">🔥</div>
      <div class="ins-streak-num" id="insight-streak">0</div>
      <div class="ins-streak-label">Day Journaling Streak</div>
    </div>
    <div class="ins-streak-orb ins-orb1"></div>
    <div class="ins-streak-orb ins-orb2"></div>
  </div>

  {{-- Debug box removed — data source confirmed as window.journals (app.js) --}}

  {{-- ── Top Row: Mood This Week + Activity Summary ── --}}
  <div class="ins-grid-2">

    {{-- Mood This Week --}}
    <div class="ins-card">
      <div class="ins-card-title">
        <span class="ins-card-icon">📈</span>
        Mood This Week
      </div>
      <div class="ins-mood-week" id="mood-chart">
        {{-- Rendered by JS --}}
      </div>
    </div>

    {{-- Activity Summary --}}
    <div class="ins-card">
      <div class="ins-card-title">
        <span class="ins-card-icon">📊</span>
        Activity Summary
      </div>
      <div class="ins-activity" id="activity-summary">
        <div class="ins-stat-row">
          <div class="ins-stat-icon" style="background:rgba(99,102,241,.15);color:#818cf8">📓</div>
          <div class="ins-stat-info">
            <div class="ins-stat-label">Journal Entries</div>
            <div class="ins-stat-sub">Total written</div>
          </div>
          <div class="ins-stat-value" id="ins-journal-count">0</div>
        </div>
        <div class="ins-stat-divider"></div>
        <div class="ins-stat-row">
          <div class="ins-stat-icon" style="background:rgba(52,211,153,.15);color:#34d399">✅</div>
          <div class="ins-stat-info">
            <div class="ins-stat-label">Task Completion</div>
            <div class="ins-stat-sub" id="ins-task-sub">0 of 0 done</div>
          </div>
          <div class="ins-stat-value" id="ins-task-pct">—</div>
        </div>
        <div class="ins-stat-divider"></div>
        <div class="ins-stat-row">
          <div class="ins-stat-icon" style="background:rgba(251,191,36,.15);color:#fbbf24">😊</div>
          <div class="ins-stat-info">
            <div class="ins-stat-label">Average Mood</div>
            <div class="ins-stat-sub">Based on journals</div>
          </div>
          <div class="ins-stat-value" id="ins-avg-mood">—</div>
        </div>
        <div class="ins-stat-divider"></div>
        <div class="ins-stat-row">
          <div class="ins-stat-icon" style="background:rgba(56,189,248,.15);color:#38bdf8">🎯</div>
          <div class="ins-stat-info">
            <div class="ins-stat-label">Active Goals</div>
            <div class="ins-stat-sub">In progress</div>
          </div>
          <div class="ins-stat-value" id="ins-goals-count">0</div>
        </div>
      </div>
    </div>

  </div>


  {{-- ── Bottom Row: Writing Frequency + Mood Distribution ── --}}
  <div class="ins-grid-2" style="margin-top:18px">

    <div class="ins-card">
      <div class="ins-card-title">
        <span class="ins-card-icon">✍️</span>
        Writing Frequency
      </div>
      <div class="ins-freq-wrap" id="ins-freq-wrap">
        <div class="ins-empty-state" id="ins-freq-empty">
          <div class="ins-empty-icon">📅</div>
          <div class="ins-empty-text">No entries yet.</div>
        </div>
        <div id="ins-freq-grid" style="display:none"></div>
      </div>
    </div>

    <div class="ins-card">
      <div class="ins-card-title">
        <span class="ins-card-icon">🎭</span>
        Mood Distribution
      </div>
      <div class="ins-dist-wrap" id="ins-mood-dist">
        <div class="ins-empty-state" id="ins-dist-empty">
          <div class="ins-empty-icon">🌈</div>
          <div class="ins-empty-text">No mood data yet.</div>
        </div>
        <div id="ins-dist-bars" style="display:none"></div>
      </div>
    </div>

  </div>

</div>

{{-- ════════════════════════════════════════
     INSIGHTS STYLES
════════════════════════════════════════ --}}
<style>
/* ── Streak Banner ── */
.ins-streak-banner {
  position: relative; overflow: hidden;
  background: linear-gradient(135deg, rgba(124,58,237,.18), rgba(79,142,247,.12));
  border: 1px solid rgba(124,58,237,.25);
  border-radius: 20px;
  padding: 32px 24px;
  margin-bottom: 18px;
  display: flex; align-items: center; justify-content: center;
}
.ins-streak-inner {
  display: flex; align-items: center; gap: 14px;
  position: relative; z-index: 1;
}
.ins-streak-flame { font-size: 2.2rem; line-height: 1; }
.ins-streak-num {
  font-family: 'Syne', sans-serif;
  font-size: 3.5rem; font-weight: 800; line-height: 1;
  background: linear-gradient(135deg, #a78bfa, #4f8ef7);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text;
  min-width: 60px; text-align: center;
}
.ins-streak-label {
  font-size: .85rem; font-weight: 600;
  color: var(--muted, #8892b0);
  letter-spacing: .05em; text-transform: uppercase;
}
.ins-streak-orb {
  position: absolute; border-radius: 50%;
  filter: blur(60px); pointer-events: none;
}
.ins-orb1 { width: 300px; height: 300px; top: -80px; right: -60px; background: rgba(124,58,237,.15); }
.ins-orb2 { width: 200px; height: 200px; bottom: -60px; left: 10%; background: rgba(79,142,247,.12); }

/* ── Layout ── */
.ins-grid-2 {
  display: grid; grid-template-columns: 1fr 1fr; gap: 18px;
}
@media(max-width:768px) { .ins-grid-2 { grid-template-columns: 1fr; } }

/* ── Cards ── */
.ins-card {
  background: var(--surface, #131929);
  border: 1px solid var(--border, rgba(255,255,255,.07));
  border-radius: 18px; padding: 22px;
}
.ins-card-title {
  display: flex; align-items: center; gap: 9px;
  font-family: 'Syne', sans-serif; font-weight: 700;
  font-size: .95rem; color: var(--text, #f0f2ff);
  margin-bottom: 18px;
}
.ins-card-icon { font-size: 1.1rem; }

/* ── Mood This Week ── */
.ins-mood-week { display: flex; flex-direction: column; gap: 8px; }
/* ── Classes injected by app.js renderInsights() ── */
.mood-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}
.mood-day-label {
  width: 36px;
  font-family: 'JetBrains Mono', monospace;
  font-size: .68rem;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: .05em;
  flex-shrink: 0;
}
.mood-bar-wrap {
  flex: 1;
  background: rgba(255,255,255,.06);
  border-radius: 99px;
  height: 10px;
  overflow: hidden;
}
.mood-bar {
  height: 100%;
  border-radius: 99px;
  transition: width .6s cubic-bezier(.4,0,.2,1);
  min-width: 4px;
}
.mood-val {
  width: 28px;
  font-family: 'JetBrains Mono', monospace;
  font-size: .78rem;
  font-weight: 700;
  color: var(--accent);
  text-align: right;
  flex-shrink: 0;
}

/* ── Classes injected by app.js renderInsights() — do not remove ── */
.mood-row {
  display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
}
.mood-day-label {
  width: 38px; font-family: 'JetBrains Mono', monospace;
  font-size: .68rem; color: var(--muted); text-transform: uppercase;
  letter-spacing: .04em; flex-shrink: 0;
}
.mood-bar-wrap {
  flex: 1; background: rgba(255,255,255,.06);
  border-radius: 99px; height: 10px; overflow: hidden;
}
.mood-bar {
  height: 100%; border-radius: 99px;
  transition: width .6s cubic-bezier(.4,0,.2,1);
  min-width: 3px;
}
.mood-val {
  width: 28px; font-family: 'JetBrains Mono', monospace;
  font-size: .78rem; font-weight: 700; color: var(--accent);
  text-align: right; flex-shrink: 0;
}

.ins-mood-row {
  display: flex; align-items: center; gap: 10px;
}
.ins-mood-day {
  font-size: .72rem; font-weight: 700;
  color: var(--muted, #8892b0);
  width: 30px; flex-shrink: 0; text-transform: uppercase;
  font-family: 'JetBrains Mono', monospace;
}
.ins-mood-bar-track {
  flex: 1; height: 10px;
  background: rgba(255,255,255,.05);
  border-radius: 99px; overflow: hidden;
}
.ins-mood-bar-fill {
  height: 100%; border-radius: 99px;
  transition: width .8s cubic-bezier(.23,1,.32,1);
}
.ins-mood-emoji {
  font-size: .95rem; width: 22px; text-align: center; flex-shrink: 0;
}
.ins-mood-score {
  font-size: .72rem; font-weight: 700;
  color: var(--muted, #8892b0);
  width: 20px; text-align: right; flex-shrink: 0;
  font-family: 'JetBrains Mono', monospace;
}

/* ── Activity Summary ── */
.ins-stat-row {
  display: flex; align-items: center; gap: 14px;
  padding: 10px 0;
}
.ins-stat-icon {
  width: 38px; height: 38px; border-radius: 11px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; flex-shrink: 0;
}
.ins-stat-info { flex: 1; min-width: 0; }
.ins-stat-label { font-size: .83rem; font-weight: 700; color: var(--text, #f0f2ff); }
.ins-stat-sub   { font-size: .72rem; color: var(--muted, #8892b0); margin-top: 2px; }
.ins-stat-value {
  font-family: 'Syne', sans-serif;
  font-size: 1.3rem; font-weight: 800;
  color: var(--text, #f0f2ff);
  flex-shrink: 0;
}
.ins-stat-divider {
  height: 1px; background: rgba(255,255,255,.05); margin: 0 0;
}

/* ── Trend chart ── */
.ins-trend-wrap {
  min-height: 120px;
  display: flex; align-items: center; justify-content: center;
}

/* ── Freq grid ── */
.ins-freq-grid-inner {
  display: grid; grid-template-columns: repeat(7, 1fr);
  gap: 5px; margin-top: 4px;
}
.ins-freq-day-label {
  font-size: .62rem; text-align: center;
  color: var(--muted, #8892b0);
  font-family: 'JetBrains Mono', monospace;
  text-transform: uppercase; margin-bottom: 4px;
}
.ins-freq-cell {
  width: 100%; aspect-ratio: 1;
  border-radius: 6px;
  background: rgba(255,255,255,.04);
  border: 1px solid rgba(255,255,255,.06);
  transition: background .3s;
}
.ins-freq-cell.has-entry {
  background: linear-gradient(135deg, rgba(124,58,237,.5), rgba(79,142,247,.4));
  border-color: rgba(124,58,237,.4);
}

/* ── Mood distribution bars ── */
.ins-dist-inner { display: flex; flex-direction: column; gap: 10px; }
.ins-dist-row { display: flex; align-items: center; gap: 10px; }
.ins-dist-emoji { font-size: 1.1rem; width: 24px; text-align: center; flex-shrink: 0; }
.ins-dist-track {
  flex: 1; height: 10px;
  background: rgba(255,255,255,.05);
  border-radius: 99px; overflow: hidden;
}
.ins-dist-fill {
  height: 100%; border-radius: 99px;
  transition: width .8s cubic-bezier(.23,1,.32,1);
}
.ins-dist-count {
  font-size: .72rem; font-weight: 700; color: var(--muted, #8892b0);
  width: 24px; text-align: right; flex-shrink: 0;
  font-family: 'JetBrains Mono', monospace;
}

/* ── Empty state ── */
.ins-empty-state {
  text-align: center; padding: 24px 16px;
  display: flex; flex-direction: column; align-items: center; gap: 8px;
}
.ins-empty-icon { font-size: 2rem; opacity: .4; }
.ins-empty-text { font-size: .85rem; font-weight: 600; color: var(--muted, #8892b0); }
.ins-empty-sub  { font-size: .75rem; color: rgba(136,146,176,.6); max-width: 220px; line-height: 1.5; }
</style>

{{-- ════════════════════════════════════════
     INSIGHTS JAVASCRIPT
════════════════════════════════════════ --}}
<script>
(function(){
  // ── Mood config ──
  var MOODS = [
    { emoji: '😄', label: 'Happy',   score: 5, color: '#34d399' },
    { emoji: '🙂', label: 'Good',    score: 4, color: '#38bdf8' },
    { emoji: '😐', label: 'Neutral', score: 3, color: '#a78bfa' },
    { emoji: '😔', label: 'Sad',     score: 2, color: '#fbbf24' },
    { emoji: '😢', label: 'Bad',     score: 1, color: '#fb7185' },
  ];
  var MOOD_MAP = {};
  MOODS.forEach(function(m){ MOOD_MAP[m.emoji] = m; });

  var DAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

  function moodColor(score) {
    if (score >= 4.5) return '#34d399';
    if (score >= 3.5) return '#38bdf8';
    if (score >= 2.5) return '#a78bfa';
    if (score >= 1.5) return '#fbbf24';
    return '#fb7185';
  }
  function moodEmoji(score) {
    if (score >= 4.5) return '😄';
    if (score >= 3.5) return '🙂';
    if (score >= 2.5) return '😐';
    if (score >= 1.5) return '😔';
    return '😢';
  }

  // ── Load data — tries window globals first (Firebase), falls back to localStorage ──
  function getEntries() {
    // Try every common global your app might use
    var sources = [
      window.journalEntries, window.allJournalEntries, window.entries,
      window._journalEntries, window.journals, window.allEntries,
      window.lvJournalEntries, window.firebaseEntries
    ];
    for (var i = 0; i < sources.length; i++) {
      if (Array.isArray(sources[i]) && sources[i].length) return sources[i];
    }
    // Try localStorage fallback
    var lsKeys = [
      'lifevault_journal_entries', 'journal_entries', 'lv_entries',
      'lifevault_journals', 'journals'
    ];
    for (var k = 0; k < lsKeys.length; k++) {
      try {
        var raw = localStorage.getItem(lsKeys[k]);
        if (raw) { var parsed = JSON.parse(raw); if (parsed && parsed.length) return parsed; }
      } catch(e) {}
    }
    return [];
  }
  function getTasks() {
    var sources = [
      window.tasks, window.allTasks, window._tasks,
      window.lvTasks, window.firebaseTasks
    ];
    for (var i = 0; i < sources.length; i++) {
      if (Array.isArray(sources[i]) && sources[i].length) return sources[i];
    }
    var lsKeys = ['lifevault_tasks', 'tasks', 'lv_tasks'];
    for (var k = 0; k < lsKeys.length; k++) {
      try {
        var raw = localStorage.getItem(lsKeys[k]);
        if (raw) { var parsed = JSON.parse(raw); if (parsed && parsed.length) return parsed; }
      } catch(e) {}
    }
    return [];
  }
  function getGoals() {
    var sources = [
      window.goals, window.allGoals, window._goals,
      window.lvGoals, window.firebaseGoals
    ];
    for (var i = 0; i < sources.length; i++) {
      if (Array.isArray(sources[i]) && sources[i].length) return sources[i];
    }
    var lsKeys = ['lifevault_goals', 'goals', 'lv_goals'];
    for (var k = 0; k < lsKeys.length; k++) {
      try {
        var raw = localStorage.getItem(lsKeys[k]);
        if (raw) { var parsed = JSON.parse(raw); if (parsed && parsed.length) return parsed; }
      } catch(e) {}
    }
    return [];
  }

  // ── Save app.js renderInsights reference (it runs before this script) ──
  var _appRenderInsights = (typeof renderInsights === 'function') ? renderInsights : null;

  // ── Render everything ──
  // Delegates mood-chart + trend + activity + streak to app.js renderInsights()
  // which reads window.journals/tasks/goals directly with j.mood + j.moodEmoji fields.
  // We only add the extra panels app.js does not provide.
  window.renderInsights = function(entriesArg, tasksArg, goalsArg) {
    // 1. app.js handles: streak, mood-chart bars, mood-trend-chart, activity-summary
    if (_appRenderInsights) {
      try { _appRenderInsights(); } catch(e) { console.warn('[Insights] app render err:', e); }
    }

    // 2. Use window.journals directly — same source as app.js, correctly typed
    var entries = (window.journals && window.journals.length) ? window.journals
                : (entriesArg || getEntries());
    var tasks   = (window.tasks  && window.tasks.length)   ? window.tasks   : getTasks();
    var goals   = (window.goals  && window.goals.length)   ? window.goals   : getGoals();

    debugEntries(entries);

    // 3. Extra panels only
    renderFrequency(entries);
    renderMoodDistribution(entries);
  };

  // ── Flatten Firestore REST API field format if needed ──
  function flattenFirestoreFields(fields) {
    var obj = {};
    Object.keys(fields).forEach(function(k) {
      var v = fields[k];
      obj[k] = v.stringValue || v.integerValue || v.booleanValue || v.doubleValue || v.timestampValue || v;
    });
    return obj;
  }

  // ── Normalise date field — handles ISO string, Firestore Timestamp, JS Date ──
  function getEntryDate(entry) {
    var d = entry.date || entry.createdAt || entry.timestamp || entry.created_at ||
            entry.dateCreated || entry.entryDate || entry.journalDate ||
            entry.created || entry.updatedAt || entry.time;
    if (!d) return null;
    if (typeof d === 'string') return d.slice(0, 10);
    if (d.toDate) return d.toDate().toISOString().slice(0, 10); // Firestore Timestamp
    if (d instanceof Date) return d.toISOString().slice(0, 10);
    if (d.seconds) return new Date(d.seconds * 1000).toISOString().slice(0, 10); // Firestore Timestamp plain obj
    return String(d).slice(0, 10);
  }

  // ── Normalise mood field — handles any field name, emoji, label, or numeric ──
  function getEntryMoodScore(entry) {
    // Try every possible field name your app might use
    var mood = entry.mood || entry.moodEmoji || entry.moodLabel ||
               entry.emoji || entry.feeling || entry.emotion ||
               entry.moodScore || entry.mood_emoji || entry.mood_label ||
               entry.moodValue || entry.moodRating || null;
    if (!mood) return null;

    var moodStr = String(mood).trim();

    // Direct emoji match
    if (MOOD_MAP[moodStr]) return MOOD_MAP[moodStr].score;

    // Partial emoji match (in case there's extra text like "😄 Happy")
    for (var em in MOOD_MAP) {
      if (moodStr.indexOf(em) !== -1) return MOOD_MAP[em].score;
    }

    // Label match (case insensitive, partial)
    var lower = moodStr.toLowerCase();
    var labelMap = {
      'happy': 5, 'great': 5, 'amazing': 5, 'excellent': 5, 'joy': 5, 'joyful': 5,
      'good': 4, 'well': 4, 'fine': 4, 'okay': 3, 'ok': 3, 'neutral': 3, 'meh': 3,
      'sad': 2, 'down': 2, 'low': 2, 'unhappy': 2, 'bad': 1, 'awful': 1,
      'terrible': 1, 'depressed': 1, 'angry': 1, 'stressed': 2, 'anxious': 2,
      'excited': 5, 'content': 4, 'calm': 3, 'tired': 2, 'exhausted': 2
    };
    for (var label in labelMap) {
      if (lower.indexOf(label) !== -1) return labelMap[label];
    }
    for (var i = 0; i < MOODS.length; i++) {
      if (MOODS[i].label.toLowerCase() === lower) return MOODS[i].score;
    }

    // Numeric score (1–5 or 1–10 scale)
    var n = parseFloat(moodStr);
    if (!isNaN(n)) {
      if (n >= 1 && n <= 5)  return n;
      if (n >= 1 && n <= 10) return Math.round((n / 10) * 5); // normalise 1–10 to 1–5
    }

    return null;
  }

  // ── Normalise mood field to emoji ──
  function getEntryMoodEmoji(entry) {
    var score = getEntryMoodScore(entry);
    return score ? moodEmoji(score) : null;
  }

  // ── Debug: console only (no DOM manipulation) ──
  function debugEntries(entries) {
    if (!entries.length) { console.log('[LV Insights] No entries found'); return; }
    console.log('[LV Insights] Found', entries.length, 'entries. mood sample:', entries[0] && entries[0].mood, entries[0] && entries[0].moodEmoji);
  }

  // ── Streak ──
  function renderStreak(entries) {
    var streak = 0;
    var today  = new Date(); today.setHours(0,0,0,0);
    for (var i = 0; i < 365; i++) {
      var d = new Date(today); d.setDate(d.getDate() - i);
      var ds = d.toISOString().slice(0,10);
      var found = entries.some(function(e){ return getEntryDate(e) === ds; });
      if (found) { streak++; } else if (i > 0) { break; }
    }
    var el = document.getElementById('insight-streak');
    if (el) el.textContent = streak;
  }

  // ── Mood This Week ──
  function renderMoodWeek(entries) {
    var container = document.getElementById('mood-chart');
    if (!container) return;

    var today = new Date(); today.setHours(0,0,0,0);
    var rows  = '';
    for (var i = 6; i >= 0; i--) {
      var d  = new Date(today); d.setDate(d.getDate() - i);
      var ds = d.toISOString().slice(0,10);
      var dayLabel = DAYS[d.getDay()];

      var dayEntries = entries.filter(function(e){ return getEntryDate(e) === ds; });
      var scores     = dayEntries.map(function(e){ return getEntryMoodScore(e); })
                                 .filter(function(s){ return s !== null; });

      var avg   = scores.length ? scores.reduce(function(a,b){return a+b},0) / scores.length : 0;
      var pct   = avg ? (avg / 5) * 100 : 0;
      var color = avg ? moodColor(avg) : 'rgba(255,255,255,.08)';
      var emoji = avg ? moodEmoji(avg) : '';
      var scoreLabel = avg ? avg.toFixed(1) : '–';

      rows += '<div class="ins-mood-row">'
        + '<div class="ins-mood-day">' + dayLabel + '</div>'
        + '<div class="ins-mood-bar-track"><div class="ins-mood-bar-fill" style="width:' + pct + '%;background:' + color + '"></div></div>'
        + '<div class="ins-mood-emoji">' + emoji + '</div>'
        + '<div class="ins-mood-score">' + scoreLabel + '</div>'
        + '</div>';
    }
    container.innerHTML = '<div class="ins-mood-week">' + rows + '</div>';
  }

  // ── Activity Summary ──
  function renderActivitySummary(entries, tasks, goals) {
    // Journal count
    var jc = document.getElementById('ins-journal-count');
    if (jc) jc.textContent = entries.length;

    // Task completion
    var done  = tasks.filter(function(t){ return t.completed || t.done; }).length;
    var total = tasks.length;
    var pct   = total ? Math.round((done / total) * 100) : null;
    var tp  = document.getElementById('ins-task-pct');
    var ts  = document.getElementById('ins-task-sub');
    if (tp) tp.textContent = pct !== null ? pct + '%' : '—';
    if (ts) ts.textContent = done + ' of ' + total + ' done';

    // Average mood
    var scores = entries.map(function(e){ return getEntryMoodScore(e); })
                        .filter(function(s){ return s !== null; });
    var avg = scores.length ? scores.reduce(function(a,b){return a+b},0) / scores.length : null;
    var am  = document.getElementById('ins-avg-mood');
    if (am) am.textContent = avg ? moodEmoji(avg) + ' ' + avg.toFixed(1) : '—';

    // Goals count
    var gc = document.getElementById('ins-goals-count');
    if (gc) gc.textContent = goals.length;
  }

  // ── 7-Day Mood Trend (simple SVG line) ──
  function renderMoodTrend(entries) {
    var canvas  = document.getElementById('ins-mood-canvas');
    var empty   = document.getElementById('ins-trend-empty');
    if (!canvas || !empty) return;

    var today = new Date(); today.setHours(0,0,0,0);
    var points = [];
    for (var i = 6; i >= 0; i--) {
      var d  = new Date(today); d.setDate(d.getDate() - i);
      var ds = d.toISOString().slice(0,10);
      var dayEntries = entries.filter(function(e){ return getEntryDate(e) === ds; });
      var scores     = dayEntries.map(function(e){ return getEntryMoodScore(e); })
                                 .filter(function(s){ return s !== null; });
      var avg = scores.length ? scores.reduce(function(a,b){return a+b},0)/scores.length : null;
      points.push({ day: DAYS[d.getDay()], val: avg });
    }

    var hasData = points.some(function(p){ return p.val !== null; });
    if (!hasData) {
      empty.style.display  = 'flex';
      canvas.style.display = 'none';
      return;
    }
    empty.style.display  = 'none';
    canvas.style.display = 'block';

    // Draw SVG trend line
    var W = 700, H = 160, padX = 30, padY = 20;
    var innerW = W - padX * 2, innerH = H - padY * 2;
    var step   = innerW / 6;

    // Build path
    var svgPoints = points.map(function(p, i){
      var x = padX + i * step;
      var y = p.val ? padY + innerH - ((p.val - 1) / 4) * innerH : null;
      return { x: x, y: y, day: p.day, val: p.val };
    });

    var pathD = '';
    var dotsSVG = '';
    var labelsSVG = '';
    var prevValid = null;

    svgPoints.forEach(function(pt, i) {
      // Day labels
      labelsSVG += '<text x="' + pt.x + '" y="' + (H - 2) + '" text-anchor="middle" fill="rgba(136,146,176,.6)" font-size="10" font-family="JetBrains Mono,monospace">' + pt.day + '</text>';

      if (pt.y !== null) {
        if (pathD === '' || prevValid === null) {
          pathD += 'M ' + pt.x + ' ' + pt.y + ' ';
        } else {
          // Smooth curve
          var cpx = (prevValid.x + pt.x) / 2;
          pathD += 'C ' + cpx + ' ' + prevValid.y + ', ' + cpx + ' ' + pt.y + ', ' + pt.x + ' ' + pt.y + ' ';
        }
        prevValid = pt;

        // Dot
        dotsSVG += '<circle cx="' + pt.x + '" cy="' + pt.y + '" r="5" fill="' + moodColor(pt.val) + '" stroke="rgba(13,15,35,.9)" stroke-width="2"/>';
        // Emoji label
        dotsSVG += '<text x="' + pt.x + '" y="' + (pt.y - 10) + '" text-anchor="middle" font-size="13">' + moodEmoji(pt.val) + '</text>';
      }
    });

    // Area fill path (close to bottom)
    var areaPath = pathD;
    if (prevValid) {
      areaPath += 'L ' + prevValid.x + ' ' + (H - padY) + ' ';
      // Find first valid
      var firstValid = svgPoints.find(function(p){ return p.y !== null; });
      if (firstValid) areaPath += 'L ' + firstValid.x + ' ' + (H - padY) + ' Z';
    }

    var svg = '<svg viewBox="0 0 ' + W + ' ' + H + '" width="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">'
      + '<defs>'
      + '<linearGradient id="ins-area-grad" x1="0" y1="0" x2="0" y2="1">'
      + '<stop offset="0%" stop-color="#a78bfa" stop-opacity="0.25"/>'
      + '<stop offset="100%" stop-color="#a78bfa" stop-opacity="0"/>'
      + '</linearGradient>'
      + '</defs>'
      // Grid lines
      + [1,2,3,4,5].map(function(v){
          var gy = padY + innerH - ((v-1)/4)*innerH;
          return '<line x1="' + padX + '" y1="' + gy + '" x2="' + (W-padX) + '" y2="' + gy + '" stroke="rgba(255,255,255,.04)" stroke-width="1"/>';
        }).join('')
      + (areaPath ? '<path d="' + areaPath + '" fill="url(#ins-area-grad)"/>' : '')
      + (pathD    ? '<path d="' + pathD    + '" fill="none" stroke="url(#ins-line-grad)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>' : '')
      + '<defs><linearGradient id="ins-line-grad" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="#a78bfa"/><stop offset="100%" stop-color="#4f8ef7"/></linearGradient></defs>'
      + dotsSVG
      + labelsSVG
      + '</svg>';

    // Replace canvas with SVG (canvas was just a placeholder)
    var wrap = canvas.parentNode;
    canvas.style.display = 'none';
    var existing = document.getElementById('ins-trend-svg');
    if (existing) existing.remove();
    var div = document.createElement('div');
    div.id = 'ins-trend-svg';
    div.style.cssText = 'width:100%;padding-top:8px';
    div.innerHTML = svg;
    wrap.appendChild(div);
  }

  // ── Writing Frequency (last 28 days grid) ──
  function renderFrequency(entries) {
    var grid  = document.getElementById('ins-freq-grid');
    var empty = document.getElementById('ins-freq-empty');
    if (!grid || !empty) return;

    var today = new Date(); today.setHours(0,0,0,0);
    var dates = {};
    entries.forEach(function(e){
      // app.js maps createdAt to a JS Date — handle both Date and string
      var raw = e.createdAt;
      var d = null;
      if (raw instanceof Date) d = raw.toISOString().slice(0,10);
      else d = getEntryDate(e);
      if (d) dates[d] = true;
    });

    if (!Object.keys(dates).length) {
      empty.style.display = 'flex'; grid.style.display = 'none'; return;
    }
    empty.style.display = 'none'; grid.style.display = 'block';

    // 4 weeks × 7 days
    var labels = '<div class="ins-freq-grid-inner" style="margin-bottom:2px">';
    DAYS.forEach(function(d){ labels += '<div class="ins-freq-day-label">' + d.slice(0,1) + '</div>'; });
    labels += '</div>';

    var cells = '<div class="ins-freq-grid-inner">';
    for (var i = 27; i >= 0; i--) {
      var d  = new Date(today); d.setDate(d.getDate() - i);
      var ds = d.toISOString().slice(0,10);
      var has = dates[ds] ? ' has-entry' : '';
      cells += '<div class="ins-freq-cell' + has + '" title="' + ds + '"></div>';
    }
    cells += '</div>';

    grid.innerHTML = labels + cells;
  }

  // ── Mood Distribution ──
  function renderMoodDistribution(entries) {
    var bars  = document.getElementById('ins-dist-bars');
    var empty = document.getElementById('ins-dist-empty');
    if (!bars || !empty) return;

    var counts = {};
    MOODS.forEach(function(m){ counts[m.emoji] = 0; });
    entries.forEach(function(e){
      // app.js stores moodEmoji as the emoji string directly (e.g. "😄")
      var emoji = e.moodEmoji || getEntryMoodEmoji(e);
      if (emoji && counts[emoji] !== undefined) counts[emoji]++;
    });

    var total = Object.values(counts).reduce(function(a,b){return a+b},0);
    if (!total) {
      empty.style.display = 'flex'; bars.style.display = 'none'; return;
    }
    empty.style.display = 'none'; bars.style.display = 'block';

    var max = Math.max.apply(null, Object.values(counts));
    var html = '<div class="ins-dist-inner">';
    MOODS.forEach(function(m){
      var c   = counts[m.emoji];
      var pct = max ? (c / max) * 100 : 0;
      html += '<div class="ins-dist-row">'
        + '<div class="ins-dist-emoji">' + m.emoji + '</div>'
        + '<div class="ins-dist-track"><div class="ins-dist-fill" style="width:' + pct + '%;background:' + m.color + '"></div></div>'
        + '<div class="ins-dist-count">' + c + '</div>'
        + '</div>';
    });
    html += '</div>';
    bars.innerHTML = html;
  }

  // ── Auto-render when page becomes visible ──
  var _lastRendered = 0;
  var _insightsPollTimer = null;

  function isInsightsVisible() {
    var page = document.getElementById('page-insights');
    if (!page) return false;
    if (page.classList.contains('hidden')) return false;
    if (page.style.display === 'none') return false;
    if (page.classList.contains('active')) return true;
    // fallback: check computed display
    return getComputedStyle(page).display !== 'none';
  }

  function tryRender() {
    if (!isInsightsVisible()) return;
    var now = Date.now();
    if (now - _lastRendered < 800) return; // debounce
    _lastRendered = now;
    window.renderInsights();
  }

  // Watch for navigation (class/style changes on page or siblings)
  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(m) {
      if (m.target && (m.target.id === 'page-insights' || m.target.classList && m.target.classList.contains('page'))) {
        setTimeout(tryRender, 50);
      }
    });
  });
  var app = document.getElementById('app');
  if (app) observer.observe(app, { subtree: true, attributes: true, attributeFilter: ['class','style','hidden'] });

  // Poll for Firebase data arriving after page load (Firestore is async)
  function startPolling() {
    var attempts = 0;
    _insightsPollTimer = setInterval(function() {
      attempts++;
      if (isInsightsVisible()) {
        tryRender();
      }
      if (attempts >= 20) clearInterval(_insightsPollTimer); // stop after 10s
    }, 500);
  }

  document.addEventListener('DOMContentLoaded', function(){
    startPolling();
    setTimeout(tryRender, 300);
  });

  // Listen for custom events your app might fire
  document.addEventListener('journalUpdated',  function(){ setTimeout(tryRender, 100); });
  document.addEventListener('tasksUpdated',    function(){ setTimeout(tryRender, 100); });
  document.addEventListener('goalsUpdated',    function(){ setTimeout(tryRender, 100); });
  document.addEventListener('dataLoaded',      function(){ setTimeout(tryRender, 100); });
  document.addEventListener('firebaseReady',   function(){ setTimeout(tryRender, 100); });
  document.addEventListener('userLoggedIn',    function(){ setTimeout(tryRender, 800); });
  window.addEventListener('insightsRefresh',   function(){ tryRender(); });

})();
</script>