{{-- resources/views/saved-analyses.blade.php --}}
<div id="page-saved" class="page">
  <div class="page-header">
    <div>
      <div class="page-title">🔖 Saved <span style="background:linear-gradient(135deg,var(--accent),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent">Items</span></div>
      <div class="page-subtitle">Your Shadow Self insights, Life Stories, and Resume Analyses — preserved</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <button class="btn" onclick="navigateTo('shadow-self')"
              style="border-color:rgba(248,113,113,.35);color:var(--rose);font-weight:700;font-size:.78rem">
        🪞 New Analysis
      </button>
      <button class="btn" onclick="navigateTo('life-story')"
              style="border-color:rgba(79,142,247,.35);color:var(--accent);font-weight:700;font-size:.78rem">
        📖 New Story
      </button>
      <button class="btn" onclick="navigateTo('analyzer')"
              style="border-color:rgba(52,211,153,.35);color:var(--green);font-weight:700;font-size:.78rem">
        📄 New Resume
      </button>
    </div>
  </div>

  {{-- Tab Filter --}}
  <div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
    <button id="saved-tab-all" onclick="setSavedTab('all')"
            style="font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:600;padding:7px 16px;border-radius:99px;border:1px solid var(--accent);background:rgba(79,142,247,.12);color:var(--accent);cursor:pointer;text-transform:uppercase;letter-spacing:.08em;transition:all .18s">
      All
    </button>
    <button id="saved-tab-shadow" onclick="setSavedTab('shadow')"
            style="font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:600;padding:7px 16px;border-radius:99px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;text-transform:uppercase;letter-spacing:.08em;transition:all .18s">
      🪞 Shadow Analyses
    </button>
    <button id="saved-tab-story" onclick="setSavedTab('story')"
            style="font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:600;padding:7px 16px;border-radius:99px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;text-transform:uppercase;letter-spacing:.08em;transition:all .18s">
      📖 Life Stories
    </button>
    <button id="saved-tab-resume" onclick="setSavedTab('resume')"
            style="font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:600;padding:7px 16px;border-radius:99px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;text-transform:uppercase;letter-spacing:.08em;transition:all .18s">
      📄 Resume Analyses
    </button>
  </div>

  {{-- Loading --}}
  <div id="saved-loading" style="text-align:center;padding:64px 24px">
    <div style="width:48px;height:48px;border-radius:50%;border:2px solid var(--border);border-top-color:var(--accent);animation:saved-spin 1.2s linear infinite;margin:0 auto 20px"></div>
    <div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em">Loading saved items…</div>
  </div>

  {{-- Empty State --}}
  <div id="saved-empty" style="text-align:center;padding:64px 24px;display:none">
    <div style="font-size:3.5rem;margin-bottom:16px;opacity:.35">🔖</div>
    <div style="font-size:.95rem;font-weight:700;margin-bottom:8px;color:var(--muted)">Nothing saved yet</div>
    <div style="font-family:'Newsreader',serif;font-style:italic;font-size:.85rem;color:var(--muted);opacity:.7;max-width:360px;margin:0 auto 28px;line-height:1.65">
      Save a Shadow Self analysis, a Life Story, or a Resume Analysis to build your personal archive
    </div>
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
      <button class="btn btn-primary" onclick="navigateTo('shadow-self')">🪞 Analyze My Patterns</button>
      <button class="btn" onclick="navigateTo('life-story')" style="border-color:rgba(79,142,247,.35);color:var(--accent)">📖 Generate a Story</button>
      <button class="btn" onclick="navigateTo('analyzer')" style="border-color:rgba(52,211,153,.35);color:var(--green)">📄 Analyze a Resume</button>
    </div>
  </div>

  {{-- Saved List --}}
  <div id="saved-list" style="display:none;flex-direction:column;gap:16px"></div>
</div>

{{-- ═══ EXPANDED VIEW MODAL ═══ --}}
<div id="saved-expand-overlay"
     style="position:fixed;inset:0;background:rgba(11,15,26,.92);z-index:9500;display:none;align-items:center;justify-content:center;padding:24px"
     onclick="if(event.target===this)closeSavedExpand()">
  <div id="saved-expand-modal"
       style="background:var(--surface);border:1px solid var(--border);border-radius:20px;max-width:760px;width:100%;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 40px 80px rgba(0,0,0,.6);animation:expandCardIn .35s cubic-bezier(.34,1.4,.64,1) both;overflow:hidden"
       onclick="event.stopPropagation()">

    {{-- Header --}}
    <div id="saved-exp-header"
         style="padding:20px 24px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;gap:12px">
      <div style="flex:1;min-width:0">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap">
          <span id="saved-exp-type-badge"
                style="font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;flex-shrink:0"></span>
          <div id="saved-exp-title" style="font-size:.95rem;font-weight:800;letter-spacing:-.02em"></div>
        </div>
        <div id="saved-exp-date" style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)"></div>
      </div>
      <button onclick="closeSavedExpand()"
              style="background:var(--surface2);border:1px solid var(--border);color:var(--muted);cursor:pointer;font-size:.9rem;width:36px;height:36px;min-width:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0"
              onmouseover="this.style.color='var(--rose)';this.style.borderColor='var(--rose)'"
              onmouseout="this.style.color='var(--muted)';this.style.borderColor='var(--border)'">✕</button>
    </div>

    {{-- Body --}}
    <div id="saved-exp-body" style="padding:24px;overflow-y:auto;flex:1;min-height:0"></div>

  </div>
</div>

@push('scripts')
<style>
@keyframes saved-spin { to { transform: rotate(360deg) } }
</style>
<script>
(function () {

  let _allItems  = [];
  let _activeTab = 'all';

  // ── Tab switcher ──────────────────────────────────────────────
  window.setSavedTab = function (tab) {
    _activeTab = tab;
    ['all','shadow','story','resume'].forEach(t => {
      const btn = document.getElementById('saved-tab-' + t);
      if (!btn) return;
      const on = t === tab;
      btn.style.borderColor = on ? 'var(--accent)' : 'var(--border)';
      btn.style.background  = on ? 'rgba(79,142,247,.12)' : 'transparent';
      btn.style.color       = on ? 'var(--accent)' : 'var(--muted)';
    });
    renderFilteredList();
  };

  function renderFilteredList() {
    const filtered = _activeTab === 'all'    ? _allItems
                   : _activeTab === 'shadow' ? _allItems.filter(x => x._type === 'shadow')
                   : _activeTab === 'story'  ? _allItems.filter(x => x._type === 'story')
                   :                           _allItems.filter(x => x._type === 'resume');

    if (!filtered.length) {
      document.getElementById('saved-list').style.display  = 'none';
      document.getElementById('saved-empty').style.display = 'block';
      return;
    }
    document.getElementById('saved-empty').style.display = 'none';
    document.getElementById('saved-list').style.display  = 'flex';
    document.getElementById('saved-list').innerHTML = filtered.map((a, i) =>
      a._type === 'shadow' ? renderShadowCard(a, i)
    : a._type === 'story'  ? renderStoryCard(a, i)
    :                        renderResumeCard(a, i)
    ).join('');
  }

  // ── Load ALL three Firestore collections in parallel ──────────
  window.loadSavedAnalyses = async function () {
    const cu = window.currentUser;
    if (!cu) return;

    document.getElementById('saved-loading').style.display = 'block';
    document.getElementById('saved-empty').style.display   = 'none';
    document.getElementById('saved-list').style.display    = 'none';

    try {
      const { getDocs, collection, query, orderBy } = window._fbFS;

      const [shadowSnap, storySnap, resumeSnap] = await Promise.all([
        getDocs(query(collection(window.db, 'users', cu.uid, 'shadow_analyses'),  orderBy('savedAt', 'desc'))),
        getDocs(query(collection(window.db, 'users', cu.uid, 'life_stories'),     orderBy('savedAt', 'desc'))),
        getDocs(query(collection(window.db, 'users', cu.uid, 'resume_analyses'),  orderBy('savedAt', 'desc'))),
      ]);

      const shadows = shadowSnap.docs.map(d => ({ id: d.id, _type: 'shadow', ...d.data() }));
      const stories = storySnap.docs.map(d => ({ id: d.id, _type: 'story',  ...d.data() }));
      const resumes = resumeSnap.docs.map(d => ({ id: d.id, _type: 'resume', ...d.data() }));

      _allItems = [...shadows, ...stories, ...resumes].sort((a, b) => {
        const ta = a.savedAt?.toDate ? a.savedAt.toDate() : new Date(a.savedAt || 0);
        const tb = b.savedAt?.toDate ? b.savedAt.toDate() : new Date(b.savedAt || 0);
        return tb - ta;
      });

      document.getElementById('saved-loading').style.display = 'none';

      if (!_allItems.length) {
        document.getElementById('saved-empty').style.display = 'block';
        return;
      }

      renderFilteredList();

    } catch (e) {
      document.getElementById('saved-loading').style.display = 'none';
      document.getElementById('saved-empty').style.display   = 'block';
      console.error('Load saved items:', e);
    }
  };

  // ── Shadow card ───────────────────────────────────────────────
  function renderShadowCard(a, i) {
    const date    = fmtDate(a.savedAt);
    const score   = a.awarenessScore ?? '—';
    const title   = esc(a.summaryTitle || 'Shadow Analysis');
    const summary = esc((a.summaryText || '').substring(0, 150)) + ((a.summaryText||'').length > 150 ? '…' : '');
    const tags    = (a.patterns || []).slice(0, 3).map(p =>
      `<span style="font-family:'JetBrains Mono',monospace;font-size:.57rem;padding:3px 9px;border-radius:99px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);color:var(--rose)">${esc(p.emoji||'')} ${esc(p.name||'')}</span>`
    ).join('');

    return `
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:22px;transition:border-color .2s,box-shadow .2s;animation:slideDown .3s ease both;animation-delay:${i*60}ms"
           onmouseover="this.style.borderColor='rgba(248,113,113,.3)';this.style.boxShadow='0 4px 20px rgba(248,113,113,.06)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow=''">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
          <div style="flex:1;min-width:200px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap">
              <span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);color:var(--rose)">🪞 Shadow</span>
              <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted)">${date}</span>
            </div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
              <div style="font-size:2rem;font-weight:800;letter-spacing:-.05em;background:linear-gradient(135deg,var(--rose),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1;flex-shrink:0">${score}%</div>
              <div style="font-size:.88rem;font-weight:700;letter-spacing:-.01em">${title}</div>
            </div>
            <div style="font-family:'Newsreader',serif;font-size:.82rem;color:rgba(232,234,240,.65);line-height:1.6;font-weight:300;margin-bottom:10px">${summary}</div>
            <div style="display:flex;gap:6px;flex-wrap:wrap">${tags}</div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0">
            <button onclick="expandSavedItem('${a.id}')"
                    style="font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;padding:8px 14px;border-radius:8px;border:1px solid rgba(248,113,113,.3);background:rgba(248,113,113,.08);color:var(--rose);cursor:pointer;transition:all .18s"
                    onmouseover="this.style.background='rgba(248,113,113,.15)'" onmouseout="this.style.background='rgba(248,113,113,.08)'">
              👁 View Full
            </button>
            <button onclick="deleteSavedItem('${a.id}','shadow',this)"
                    style="font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;padding:8px 14px;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;transition:all .18s"
                    onmouseover="this.style.borderColor='var(--rose)';this.style.color='var(--rose)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
              🗑 Delete
            </button>
          </div>
        </div>
      </div>`;
  }

  // ── Story card ────────────────────────────────────────────────
  function renderStoryCard(a, i) {
    const date    = fmtDate(a.savedAt);
    const title   = esc(a.title || 'Life Story');
    const preview = esc((a.body || '').substring(0, 150)) + ((a.body||'').length > 150 ? '…' : '');
    const wc      = a.body ? a.body.split(/\s+/).filter(Boolean).length : 0;
    const style_  = esc(a.style || 'memoir');

    return `
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:22px;transition:border-color .2s,box-shadow .2s;animation:slideDown .3s ease both;animation-delay:${i*60}ms"
           onmouseover="this.style.borderColor='rgba(79,142,247,.3)';this.style.boxShadow='0 4px 20px rgba(79,142,247,.06)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow=''">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
          <div style="flex:1;min-width:200px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap">
              <span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);color:var(--accent)">📖 Life Story</span>
              <span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.08em;padding:3px 9px;border-radius:99px;background:var(--surface2);border:1px solid var(--border);color:var(--muted)">${style_}</span>
              <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted)">${date}</span>
            </div>
            <div style="font-family:'Newsreader',serif;font-size:.9rem;font-weight:400;font-style:italic;color:var(--accent);margin-bottom:8px">"${title}"</div>
            <div style="font-family:'Newsreader',serif;font-size:.82rem;color:rgba(232,234,240,.65);line-height:1.6;font-weight:300;margin-bottom:10px">${preview}</div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted)">${wc} words</div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0">
            <button onclick="expandSavedItem('${a.id}')"
                    style="font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;padding:8px 14px;border-radius:8px;border:1px solid rgba(79,142,247,.3);background:rgba(79,142,247,.08);color:var(--accent);cursor:pointer;transition:all .18s"
                    onmouseover="this.style.background='rgba(79,142,247,.15)'" onmouseout="this.style.background='rgba(79,142,247,.08)'">
              👁 View Full
            </button>
            <button onclick="deleteSavedItem('${a.id}','story',this)"
                    style="font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;padding:8px 14px;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;transition:all .18s"
                    onmouseover="this.style.borderColor='var(--rose)';this.style.color='var(--rose)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
              🗑 Delete
            </button>
          </div>
        </div>
      </div>`;
  }

  // ── Resume card ───────────────────────────────────────────────
  function renderResumeCard(a, i) {
    const date     = fmtDate(a.savedAt);
    const fileName = esc(a.fileName || 'Resume');
    const jobSnip  = esc((a.jobDescription || '').substring(0, 120)) + ((a.jobDescription||'').length > 120 ? '…' : '');
    const score    = a.matchScore != null ? `${a.matchScore}%` : null;

    return `
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:22px;transition:border-color .2s,box-shadow .2s;animation:slideDown .3s ease both;animation-delay:${i*60}ms"
           onmouseover="this.style.borderColor='rgba(52,211,153,.3)';this.style.boxShadow='0 4px 20px rgba(52,211,153,.06)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow=''">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
          <div style="flex:1;min-width:200px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap">
              <span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.2);color:var(--green)">📄 Resume</span>
              <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted)">${date}</span>
            </div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
              ${score ? `<div style="font-size:2rem;font-weight:800;letter-spacing:-.05em;background:linear-gradient(135deg,var(--green),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1;flex-shrink:0">${score}</div>` : ''}
              <div style="font-size:.88rem;font-weight:700;letter-spacing:-.01em">📎 ${fileName}</div>
            </div>
            <div style="font-family:'Newsreader',serif;font-size:.82rem;color:rgba(232,234,240,.65);line-height:1.6;font-weight:300;margin-bottom:6px">
              <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.08em;color:var(--muted)">Job: </span>${jobSnip}
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0">
            <button onclick="expandSavedItem('${a.id}')"
                    style="font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;padding:8px 14px;border-radius:8px;border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.08);color:var(--green);cursor:pointer;transition:all .18s"
                    onmouseover="this.style.background='rgba(52,211,153,.15)'" onmouseout="this.style.background='rgba(52,211,153,.08)'">
              👁 View Full
            </button>
            <button onclick="deleteSavedItem('${a.id}','resume',this)"
                    style="font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;padding:8px 14px;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;transition:all .18s"
                    onmouseover="this.style.borderColor='var(--rose)';this.style.color='var(--rose)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
              🗑 Delete
            </button>
          </div>
        </div>
      </div>`;
  }

  // ── Expand modal ──────────────────────────────────────────────
  window.expandSavedItem = function (id) {
    const a = _allItems.find(x => x.id === id);
    if (!a) return;

    const date   = fmtDate(a.savedAt);
    const modal  = document.getElementById('saved-expand-modal');
    const header = document.getElementById('saved-exp-header');
    const badge  = document.getElementById('saved-exp-type-badge');
    const body   = document.getElementById('saved-exp-body');
    body.scrollTop = 0;

    if (a._type === 'shadow') {
      header.style.background = 'linear-gradient(135deg,rgba(248,113,113,.06),rgba(167,139,250,.04))';
      modal.style.borderColor = 'rgba(248,113,113,.2)';
      badge.textContent       = '🪞 Shadow Analysis';
      badge.style.cssText     = 'font-family:"JetBrains Mono",monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);color:var(--rose)';
      document.getElementById('saved-exp-title').textContent = a.summaryTitle || 'Shadow Analysis';
      document.getElementById('saved-exp-date').textContent  = `Saved ${date} · Awareness Score: ${a.awarenessScore ?? '—'}%`;
      body.innerHTML = buildShadowHtml(a);

    } else if (a._type === 'story') {
      header.style.background = 'linear-gradient(135deg,rgba(79,142,247,.06),rgba(167,139,250,.04))';
      modal.style.borderColor = 'rgba(79,142,247,.2)';
      badge.textContent       = '📖 Life Story';
      badge.style.cssText     = 'font-family:"JetBrains Mono",monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);color:var(--accent)';
      document.getElementById('saved-exp-title').textContent = `"${a.title || 'Life Story'}"`;
      const wc = a.body ? a.body.split(/\s+/).filter(Boolean).length : 0;
      document.getElementById('saved-exp-date').textContent  = `Saved ${date} · ${a.style || 'memoir'} · ${wc} words`;
      body.innerHTML = buildStoryHtml(a);

    } else {
      // resume
      header.style.background = 'linear-gradient(135deg,rgba(52,211,153,.06),rgba(45,212,191,.04))';
      modal.style.borderColor = 'rgba(52,211,153,.2)';
      badge.textContent       = '📄 Resume Analysis';
      badge.style.cssText     = 'font-family:"JetBrains Mono",monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.2);color:var(--green)';
      document.getElementById('saved-exp-title').textContent = `📎 ${a.fileName || 'Resume Analysis'}`;
      const scoreLabel = a.matchScore != null ? ` · Match Score: ${a.matchScore}%` : '';
      document.getElementById('saved-exp-date').textContent  = `Saved ${date}${scoreLabel}`;
      body.innerHTML = buildResumeHtml(a);
    }

    document.getElementById('saved-expand-overlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
  };

  function buildShadowHtml(a) {
    const colorMap  = { rose:'var(--rose)', amber:'var(--amber)', lavender:'var(--lavender)', teal:'var(--teal)', accent:'var(--accent)' };
    const bgMap     = { rose:'rgba(248,113,113,.1)', amber:'rgba(251,191,36,.1)', lavender:'rgba(167,139,250,.12)', teal:'rgba(45,212,191,.1)', accent:'rgba(79,142,247,.1)' };
    const borderMap = { rose:'rgba(248,113,113,.25)', amber:'rgba(251,191,36,.25)', lavender:'rgba(167,139,250,.28)', teal:'rgba(45,212,191,.25)', accent:'rgba(79,142,247,.25)' };

    let h = `<div style="font-family:'Newsreader',serif;font-size:.9rem;color:rgba(232,234,240,.75);line-height:1.7;font-weight:300;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border)">${esc(a.summaryText||'')}</div>`;

    if (a.patterns?.length) {
      h += lbl('Detected Patterns') + `<div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px">`;
      h += a.patterns.map(p => {
        const c = colorMap[p.color]||'var(--rose)', bg = bgMap[p.color]||'rgba(248,113,113,.1)', br = borderMap[p.color]||'rgba(248,113,113,.25)';
        const bars = Array.from({length:5},(_,j)=>`<div style="flex:1;height:4px;border-radius:99px;background:${j<(p.severity||1)?c:'var(--surface2)'}"></div>`).join('');
        return `<div style="background:var(--surface2);border:1px solid ${br};border-radius:12px;padding:14px 16px;display:flex;align-items:flex-start;gap:12px">
          <div style="width:34px;height:34px;border-radius:8px;background:${bg};display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;border:1px solid ${br}">${esc(p.emoji||'😔')}</div>
          <div style="flex:1">
            <div style="font-size:.8rem;font-weight:700;color:${c};margin-bottom:4px">${esc(p.name||'')} <span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;font-weight:400;color:var(--muted)">Severity ${p.severity||1}/5</span></div>
            <div style="display:flex;gap:2px;margin-bottom:6px">${bars}</div>
            <div style="font-family:'Newsreader',serif;font-size:.8rem;color:rgba(232,234,240,.7);line-height:1.55;font-weight:300">${esc(p.description||'')}</div>
          </div></div>`;
      }).join('') + `</div>`;
    }

    if (a.reframes?.length) {
      h += lbl('Compassionate Reframes') + `<div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px">`;
      h += a.reframes.map(r => `
        <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:stretch">
          <div style="background:rgba(248,113,113,.07);border:1px solid rgba(248,113,113,.18);border-radius:10px 0 0 10px;padding:12px 14px">
            <div style="font-family:'JetBrains Mono',monospace;font-size:.52rem;text-transform:uppercase;letter-spacing:.1em;color:var(--rose);margin-bottom:6px">Shadow</div>
            <div style="font-family:'Newsreader',serif;font-size:.8rem;color:rgba(232,234,240,.7);font-weight:300;line-height:1.5;font-style:italic">"${esc(r.shadow||'')}"</div>
          </div>
          <div style="background:var(--surface2);display:flex;align-items:center;justify-content:center;padding:0 10px;border-top:1px solid var(--border);border-bottom:1px solid var(--border)">→</div>
          <div style="background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.18);border-radius:0 10px 10px 0;padding:12px 14px">
            <div style="font-family:'JetBrains Mono',monospace;font-size:.52rem;text-transform:uppercase;letter-spacing:.1em;color:var(--green);margin-bottom:6px">Truth</div>
            <div style="font-family:'Newsreader',serif;font-size:.8rem;color:rgba(232,234,240,.85);font-weight:300;line-height:1.5">${esc(r.reframe||'')}</div>
          </div>
        </div>`).join('') + `</div>`;
    }

    if (a.growthActions?.length) {
      const icons = ['🌱','💪','📝','🧘','🗣️'];
      h += lbl('Growth Actions') + `<div style="display:flex;flex-direction:column;gap:8px;margin-bottom:24px">`;
      h += a.growthActions.map((action,i) => `
        <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid var(--border)">
          <span style="font-size:.85rem;flex-shrink:0;margin-top:1px">${icons[i%icons.length]}</span>
          <div style="font-family:'Newsreader',serif;font-size:.82rem;color:rgba(232,234,240,.8);line-height:1.55;font-weight:300">${esc(action)}</div>
        </div>`).join('') + `</div>`;
    }

    if (a.hiddenStrengths?.length) {
      const sc = [
        ['rgba(79,142,247,.12)','rgba(79,142,247,.35)','var(--accent)'],
        ['rgba(52,211,153,.1)','rgba(52,211,153,.3)','var(--green)'],
        ['rgba(167,139,250,.12)','rgba(167,139,250,.35)','var(--lavender)'],
        ['rgba(45,212,191,.1)','rgba(45,212,191,.3)','var(--teal)'],
        ['rgba(251,191,36,.1)','rgba(251,191,36,.3)','var(--amber)'],
        ['rgba(248,113,113,.1)','rgba(248,113,113,.3)','var(--rose)'],
      ];
      h += lbl('Hidden Strengths') + `<div style="display:flex;gap:8px;flex-wrap:wrap">`;
      h += a.hiddenStrengths.map((s,i) => {
        const [bg,b,c] = sc[i%sc.length];
        return `<span style="font-family:'JetBrains Mono',monospace;font-size:.6rem;padding:5px 12px;border-radius:99px;background:${bg};border:1px solid ${b};color:${c};text-transform:uppercase;letter-spacing:.07em">${esc(s)}</span>`;
      }).join('') + `</div>`;
    }
    return h;
  }

  function buildStoryHtml(a) {
    let h = '';
    if (a.themes?.length) {
      h += `<div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px">`;
      h += a.themes.map(t =>
        `<span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;padding:3px 10px;border-radius:99px;background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);color:var(--accent);text-transform:uppercase;letter-spacing:.07em">${esc(t)}</span>`
      ).join('') + `</div>`;
    }
    h += `<div style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.9;color:rgba(232,234,240,.88);font-weight:300;white-space:pre-wrap">${esc(a.body||'')}</div>`;
    return h;
  }

  function buildResumeHtml(a) {
    let h = '';

    // Job description snippet
    if (a.jobDescription) {
      h += lbl('Job Description Targeted');
      h += `<div style="font-family:'Newsreader',serif;font-size:.85rem;color:rgba(232,234,240,.7);line-height:1.65;font-weight:300;margin-bottom:24px;padding:14px 16px;background:var(--surface2);border-radius:10px;border:1px solid var(--border)">${esc(a.jobDescription)}</div>`;
    }

    // AI Suggestions (rendered HTML)
    if (a.suggestionsHtml) {
      h += lbl('AI Suggestions & Improvements');
      h += `<div style="font-family:'Newsreader',serif;font-size:.88rem;line-height:1.75;color:rgba(232,234,240,.85);font-weight:300">${a.suggestionsHtml}</div>`;
    } else {
      h += `<div style="font-family:'Newsreader',serif;font-size:.85rem;color:var(--muted);font-style:italic">No AI suggestions were saved with this analysis.</div>`;
    }

    return h;
  }

  window.closeSavedExpand = function () {
    document.getElementById('saved-expand-overlay').style.display = 'none';
    document.body.style.overflow = '';
  };

  // ── Delete ────────────────────────────────────────────────────
  window.deleteSavedItem = async function (id, type, btn) {
    if (!confirm('Delete this saved item?')) return;
    const cu = window.currentUser;
    if (!cu) return;
    const collMap = { shadow: 'shadow_analyses', story: 'life_stories', resume: 'resume_analyses' };
    const coll    = collMap[type] ?? 'resume_analyses';
    try {
      const { deleteDoc, doc } = window._fbFS;
      await deleteDoc(doc(window.db, 'users', cu.uid, coll, id));
      _allItems = _allItems.filter(a => a.id !== id);
      btn.closest('div[style*="background:var(--surface)"]').remove();
      const visibleCount = _activeTab === 'all' ? _allItems.length
        : _allItems.filter(a => a._type === _activeTab).length;
      if (!visibleCount) {
        document.getElementById('saved-list').style.display  = 'none';
        document.getElementById('saved-empty').style.display = 'block';
      }
      window.toast?.('Deleted', '🗑️');
    } catch (e) {
      window.toast?.('Error: ' + e.message, '❌');
    }
  };

  // ── Helpers ───────────────────────────────────────────────────
  function fmtDate(ts) {
    if (!ts) return '—';
    const d = ts.toDate ? ts.toDate() : new Date(ts);
    return d.toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit' });
  }
  function lbl(text) {
    return `<div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;text-transform:uppercase;letter-spacing:.14em;color:var(--muted);margin-bottom:12px">${text}</div>`;
  }
  function esc(s) {
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && document.getElementById('saved-expand-overlay').style.display !== 'none')
      window.closeSavedExpand();
  });

  const observer = new MutationObserver(() => {
    const pg = document.getElementById('page-saved');
    if (pg && pg.classList.contains('active')) window.loadSavedAnalyses();
  });
  const pg = document.getElementById('page-saved');
  if (pg) observer.observe(pg, { attributes: true, attributeFilter: ['class'] });

})();
</script>
@endpush