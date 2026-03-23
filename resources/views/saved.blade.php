{{-- resources/views/saved.blade.php --}}
<div id="page-saved" class="page">

  <div class="page-header">
    <div>
      <div class="page-title">🔖 Saved
        <span style="background:linear-gradient(135deg,var(--accent),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent">Items</span>
      </div>
      <div class="page-subtitle">Your saved AI analyses, life stories, and shadow patterns in one place</div>
    </div>
    <button class="btn" onclick="savedClearAll()"
            style="background:rgba(248,113,113,.08);border-color:rgba(248,113,113,.25);color:var(--rose);font-weight:700;font-size:.75rem">
      🗑 Clear All
    </button>
  </div>

  {{-- Filter Tabs --}}
  <div style="display:flex;gap:8px;margin-bottom:28px;flex-wrap:wrap" id="saved-tabs">
    @foreach([
      ['all',    '🔖', 'All Items'],
      ['resume', '📄', 'Resumes'],
      ['story',  '📖', 'Life Stories'],
      ['shadow', '🪞', 'Shadow Patterns'],
      ['holistic-career', '🔮', 'Career Reports'],
    ] as [$tab, $icon, $label])
    <button class="saved-tab {{ $tab === 'all' ? 'active' : '' }}"
            data-tab="{{ $tab }}"
            onclick="savedFilter('{{ $tab }}')"
            style="display:flex;align-items:center;gap:7px;padding:9px 18px;border-radius:99px;font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;cursor:pointer;transition:all .18s;border:1.5px solid {{ $tab === 'all' ? 'var(--accent)' : 'var(--border)' }};background:{{ $tab === 'all' ? 'rgba(79,142,247,.12)' : 'var(--surface)' }};color:{{ $tab === 'all' ? 'var(--accent)' : 'var(--muted)' }}">
      <span>{{ $icon }}</span>
      <span>{{ $label }}</span>
      <span class="saved-tab-count" data-tab="{{ $tab }}"
            style="font-size:.55rem;padding:2px 7px;border-radius:99px;background:rgba(79,142,247,.12);color:var(--accent);border:1px solid rgba(79,142,247,.2)">0</span>
    </button>
    @endforeach
  </div>

  {{-- Search --}}
  <div style="position:relative;margin-bottom:24px">
    <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="saved-search" placeholder="Search saved items…"
           oninput="savedSearchItems(this.value)"
           style="width:100%;box-sizing:border-box;padding:11px 14px 11px 38px;background:var(--surface);border:1.5px solid var(--border);border-radius:12px;color:var(--text);font-family:var(--font-journal);font-size:.9rem;font-weight:300;outline:none;transition:border-color .2s,box-shadow .2s"
           onfocus="this.style.borderColor='rgba(79,142,247,.5)';this.style.boxShadow='0 0 0 3px rgba(79,142,247,.08)'"
           onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
  </div>

  {{-- Empty State --}}
  <div id="saved-empty" style="text-align:center;padding:80px 24px">
    <div style="font-size:3.5rem;margin-bottom:16px;opacity:.25">🔖</div>
    <div style="font-size:.95rem;font-weight:700;color:var(--muted);margin-bottom:10px">Nothing saved yet</div>
    <div style="font-family:var(--font-journal);font-style:italic;font-size:.85rem;color:var(--muted);opacity:.6;max-width:360px;margin:0 auto 28px;line-height:1.7">
      Use the Save button in the Resume Analyzer, Life Story Generator, or Shadow Self Analyzer to bookmark your results here.
    </div>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      @foreach([
        ['var(--accent)',   'rgba(79,142,247,.1)',  'rgba(79,142,247,.25)',  '📄', 'Resume Analyzer', 'analyzer'],
        ['var(--lavender)', 'rgba(167,139,250,.1)', 'rgba(167,139,250,.25)', '📖', 'Life Story',       'life-story'],
        ['var(--rose)',     'rgba(248,113,113,.1)', 'rgba(248,113,113,.25)', '🪞', 'Shadow Self',      'shadow-self'],
        ['var(--accent)',   'rgba(167,139,250,.1)', 'rgba(167,139,250,.25)', '🔮', 'Career Advisor',   'holistic-career'],
      ] as [$color, $bg, $br, $emoji, $label, $page])
      <div onclick="navigateTo('{{ $page }}', event)"
           style="background:{{ $bg }};border:1px solid {{ $br }};border-radius:14px;padding:18px 22px;cursor:pointer;transition:transform .18s,box-shadow .18s;min-width:140px;text-align:center"
           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.25)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="font-size:1.8rem;margin-bottom:8px">{{ $emoji }}</div>
        <div style="font-size:.75rem;font-weight:700;color:{{ $color }}">{{ $label }}</div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Items Grid --}}
  <div id="saved-grid" style="display:none;columns:1;gap:16px" class="saved-masonry"></div>

</div>

{{-- ══════════════════════════════════════════════
     MODAL OVERLAY
═══════════════════════════════════════════════ --}}
<div id="saved-modal-overlay"
     onclick="savedModalClose(event)"
     style="display:none;position:fixed;inset:0;z-index:9999;
            background:rgba(8,10,18,.78);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
            align-items:center;justify-content:center;padding:20px;box-sizing:border-box;">

  <div id="saved-modal-box"
       style="position:relative;width:100%;max-width:700px;max-height:88vh;
              background:var(--surface,#13151f);
              border:1px solid var(--border,rgba(255,255,255,.09));
              border-radius:20px;display:flex;flex-direction:column;
              box-shadow:0 40px 100px rgba(0,0,0,.65),0 0 0 1px rgba(255,255,255,.04);
              transform:scale(.94) translateY(18px);opacity:0;
              transition:transform .3s cubic-bezier(.34,1.2,.64,1),opacity .22s ease;">

    <div id="saved-modal-header"
         style="display:flex;align-items:flex-start;gap:14px;
                padding:20px 22px 16px;
                border-bottom:1px solid var(--border,rgba(255,255,255,.08));flex-shrink:0">
      <div id="saved-modal-icon"
           style="width:44px;height:44px;border-radius:12px;flex-shrink:0;
                  display:flex;align-items:center;justify-content:center;font-size:1.35rem"></div>
      <div style="flex:1;min-width:0">
        <div id="saved-modal-title"
             style="font-size:.97rem;font-weight:700;color:var(--text);line-height:1.3;margin-bottom:5px"></div>
        <div id="saved-modal-meta"
             style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)"></div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;margin-top:2px">
        <div id="saved-modal-badge"></div>
        <button onclick="savedModalClose()"
                style="width:30px;height:30px;border-radius:8px;
                       background:rgba(255,255,255,.05);border:1px solid var(--border,rgba(255,255,255,.08));
                       color:var(--muted);cursor:pointer;display:flex;align-items:center;justify-content:center;
                       transition:all .15s;font-size:1rem;line-height:1;font-family:monospace"
                onmouseover="this.style.background='rgba(248,113,113,.14)';this.style.borderColor='rgba(248,113,113,.35)';this.style.color='var(--rose)'"
                onmouseout="this.style.background='rgba(255,255,255,.05)';this.style.borderColor='var(--border)';this.style.color='var(--muted)'">✕</button>
      </div>
    </div>

    <div id="saved-modal-summary"
         style="padding:13px 22px;border-bottom:1px solid var(--border,rgba(255,255,255,.08));flex-shrink:0"></div>

    <div id="saved-modal-body"
         style="flex:1;overflow-y:auto;padding:22px;
                font-family:var(--font-journal);font-size:var(--font-size-journal);line-height:1.87;
                color:rgba(232,234,240,.82);font-weight:300;
                scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent"></div>

    <div id="saved-modal-footer"
         style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;
                padding:13px 22px;
                border-top:1px solid var(--border,rgba(255,255,255,.08));
                flex-shrink:0;background:rgba(255,255,255,.015)"></div>
  </div>
</div>

{{-- ══════════════════════════════════════════════
     SHARE CHOICE MODAL
═══════════════════════════════════════════════ --}}
<div id="saved-share-choice-overlay"
     onclick="if(event.target===this) this.style.display='none'"
     style="display:none;position:fixed;inset:0;z-index:10001;
            background:rgba(8,10,18,0.85);backdrop-filter:blur(12px);
            align-items:center;justify-content:center;padding:20px;">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:24px;width:100%;max-width:420px;padding:32px;box-shadow:0 40px 100px rgba(0,0,0,0.6);animation:saved-in 0.3s ease both;">
        <div style="text-align:center;margin-bottom:24px">
            <div style="font-size:2.5rem;margin-bottom:12px">↗️</div>
            <h3 style="font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;margin-bottom:8px">Share Item</h3>
            <p style="font-family:var(--font-journal);font-size:0.9rem;color:var(--muted);font-style:italic">Where would you like to share this?</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px">
            <button class="btn btn-primary" id="btn-share-community" style="padding:16px;justify-content:center;font-size:0.95rem">
                <span>🌐</span> Share to Community
            </button>
            <button class="btn" id="btn-share-journal" style="padding:16px;justify-content:center;font-size:0.95rem;background:rgba(255,255,255,0.05)">
                <span>📓</span> Save to Journal
            </button>
            <button class="btn" onclick="document.getElementById('saved-share-choice-overlay').style.display='none'" style="margin-top:8px;justify-content:center;border:none;background:transparent;color:var(--muted);font-size:0.8rem">
                Cancel
            </button>
        </div>
    </div>
</div>


<style>
@keyframes saved-in { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

.saved-tab:hover { border-color:var(--accent)!important; color:var(--accent)!important; }

.saved {
  break-inside:avoid; margin-bottom:16px;
  background:var(--surface); border-radius:16px; border:1px solid var(--border);
  overflow:hidden; animation:saved-in .28s cubic-bezier(.4,0,.2,1) both;
  transition:transform .2s,box-shadow .2s,border-color .2s; cursor:pointer;
}
.saved:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(0,0,0,.32); }

.saved-header { display:flex;align-items:center;gap:12px;padding:16px 18px 12px;border-bottom:1px solid var(--border); }
.saved-body   { padding:16px 18px; }
.saved-footer { display:flex;align-items:center;gap:8px;padding:12px 18px;border-top:1px solid var(--border);background:rgba(255,255,255,.015); }

.saved-type-badge { font-family:'JetBrains Mono',monospace;font-size:.54rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px; }
.saved-score-pill { display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:99px;font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:700; }

.saved-delete-btn { background:transparent;border:1px solid var(--border);color:var(--muted);padding:5px 10px;border-radius:8px;font-size:.7rem;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:5px; }
.saved-delete-btn:hover { border-color:var(--rose);color:var(--rose);background:rgba(248,113,113,.08); }

.saved-view-btn { background:transparent;border:1px solid var(--border);color:var(--muted);padding:5px 12px;border-radius:8px;font-size:.7rem;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:5px; }
.saved-view-btn:hover { border-color:var(--accent);color:var(--accent);background:rgba(79,142,247,.08); }

#saved-modal-body::-webkit-scrollbar { width:4px; }
#saved-modal-body::-webkit-scrollbar-track { background:transparent; }
#saved-modal-body::-webkit-scrollbar-thumb { background:rgba(255,255,255,.13);border-radius:99px; }

.sm-pattern { background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:10px;padding:13px 15px;margin-bottom:10px; }
.sm-reframe  { background:rgba(52,211,153,.05);border:1px solid rgba(52,211,153,.15);border-radius:10px;padding:11px 15px;margin-bottom:8px; }
.sm-label    { font-family:'JetBrains Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:10px;margin-top:20px; }
.sm-label:first-child { margin-top:0; }

@media(min-width:900px)  { .saved-masonry { columns:2!important; } }
@media(min-width:1300px) { .saved-masonry { columns:3!important; } }

@media(max-width:600px) {
  #saved-modal-overlay { align-items:flex-end; padding:0; }
  #saved-modal-box     { max-width:100%;max-height:92vh;border-radius:20px 20px 0 0; }
}

.markdown-content h1, .markdown-content h2, .markdown-content h3 { font-family: 'Syne', sans-serif; margin-top: 1.5rem; margin-bottom: 0.8rem; color: var(--text); }
.markdown-content h1 { font-size: 1.4rem; }
.markdown-content h2 { font-size: 1.25rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; }
.markdown-content h3 { font-size: 1.1rem; }
.markdown-content p { margin-bottom: 1rem; }
.markdown-content ul, .markdown-content ol { margin-bottom: 1rem; padding-left: 1.5rem; }
.markdown-content li { margin-bottom: 0.4rem; }
.markdown-content strong { color: var(--accent); font-weight: 700; }
.markdown-content code { background: rgba(255,255,255,0.05); padding: 0.2rem 0.4rem; border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 0.85em; }
</style>

@push('scripts')
<script>


(function(){
'use strict';

/* ════════════════════════════════════════════════════════════
   FIRESTORE INTEGRATION
════════════════════════════════════════════════════════════ */

var currentUser = null;
var savedItemsCache = [];
var activeTab = 'all';
var searchQuery = '';
var isLoading = false;

function initSavedAuth() {
  if (!window.auth || !window.db || !window.firebase) {
    setTimeout(initSavedAuth, 200);
    return;
  }
  window.firebase.onAuthStateChanged(window.auth, function(user) {
    currentUser = user;
    if (user) {
      loadSavedItems();
    } else {
      savedItemsCache = [];
      renderSaved();
    }
  });
}

initSavedAuth();

/* ── Firestore Load ─────────────────────────────────────── */
function loadSavedItems() {
  if (!currentUser || !window.db) return;
  
  isLoading = true;
  
  try {
    var ref = window.firebase.collection(window.db, 'users', currentUser.uid, 'saved_items');
    window.firebase.onSnapshot(ref, 
      function(snapshot) {
        savedItemsCache = [];
        snapshot.forEach(function(doc) {
          var item = Object.assign({}, doc.data());
          item.id = doc.id;
          if (!item.savedAt) item.savedAt = new Date().toISOString();
          savedItemsCache.push(item);
        });
        isLoading = false;
        renderSaved();
      },
      function(err) {
        console.error('[saved] Firestore load error:', err);
        isLoading = false;
        renderSaved();
      }
    );
  } catch(e) {
    console.warn('[saved] Firebase not fully initialized:', e.message);
    isLoading = false;
  }
}

/* ── Add Item (Firestore) ────────────────────────────────── */
window.savedAddItem = function(item) {
  if (!currentUser || !window.db) {
    console.warn('[saved] User not authenticated or Firebase not ready');
    return;
  }

  var now = new Date().toISOString();
  var docData = Object.assign({}, item, {
    savedAt: now,
    updatedAt: now,
    uid: currentUser.uid
  });

  try {
    var ref = window.firebase.collection(window.db, 'users', currentUser.uid, 'saved_items');
    window.firebase.addDoc(ref, docData).then(function(docRef) {
      if (typeof window.toast === 'function') {
        window.toast('Saved to 🔖 Saved Items! ✨');
      }
    }).catch(function(err) {
      console.error('[saved] Add error:', err);
      if (typeof window.toast === 'function') {
        window.toast('Failed to save item', 'error');
      }
    });
  } catch(e) {
    console.error('[saved] Add error:', e);
  }
};

/* ── Delete Item ────────────────────────────────────────── */
window.savedDeleteItem = function(id) {
  if (!currentUser || !window.db) return;

  window.confirmAction({
    emoji: '🗑️',
    title: 'Delete Item?',
    body: 'Are you sure you want to remove this saved item?',
    confirm: 'Delete',
    danger: true,
    onConfirm: (close) => {
      var items = loadItems();
      items = items.filter(function(i){return i.id !== id;});
      persistItems(items);
      _savedRender();
      if(typeof window.toast==='function') window.toast('Item deleted', '🗑️');
      close();
    }
  });

  try {
    var ref = window.firebase.doc(window.db, 'users', currentUser.uid, 'saved_items', id);
    window.firebase.deleteDoc(ref).then(function() {
      var el = document.getElementById('saved-' + id);
      if (el) {
        el.style.transition = 'opacity .2s,transform .2s';
        el.style.opacity = '0';
        el.style.transform = 'scale(.95)';
        setTimeout(function() { 
          el.remove(); 
          recheckEmpty();
        }, 220);
      }
      savedModalClose();
    }).catch(function(err) {
      console.error('[saved] Delete error:', err);
    });
  } catch(e) {
    console.error('[saved] Delete error:', e);
  }
};

/* ── Clear All ──────────────────────────────────────────── */
window.savedClearAll = function() {
  window.confirmAction({
    emoji: '⚠️',
    title: 'Clear All?',
    body: 'Are you sure you want to delete ALL saved items? This action cannot be undone.',
    confirm: 'Clear All',
    danger: true,
    onConfirm: (close) => {
      persistItems([]);
      _savedRender();
      if(typeof window.toast==='function') window.toast('All items cleared', '🗑️');
      close();
    }
  });

  if (!currentUser || !window.db) return;

  try {
    var ref = window.firebase.collection(window.db, 'users', currentUser.uid, 'saved_items');
    window.firebase.getDocs(ref).then(function(snapshot) {
      var batch = window.firebase.writeBatch(window.db);
      snapshot.forEach(function(doc) {
        batch.delete(doc.ref);
      });
      return batch.commit();
    }).then(function() {
      savedItemsCache = [];
      renderSaved();
    }).catch(function(err) {
      console.error('[saved] Clear all error:', err);
    });
  } catch(e) {
    console.error('[saved] Clear all error:', e);
  }
};

/* ── Copy Story ────────────────────────────────────────── */
window.savedCopyStory = function(id) {
  var item = savedItemsCache.find(function(i) { return i.id === id; });
  if (!item) return;
  
  var text = '"' + item.title + '"\n\n' + item.body;
  if (navigator.clipboard) {
    navigator.clipboard.writeText(text).then(function() {
      if (typeof window.toast === 'function') window.toast('Copied! 📋');
    });
  } else {
    var ta = Object.assign(document.createElement('textarea'), {value: text});
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    ta.remove();
  }
};

/* ════════════════════════════════════════════════════════════
   SHARING LOGIC
════════════════════════════════════════════════════════════ */

window.savedShareItem = function(id) {
  var item = savedItemsCache.find(function(i) { return i.id === id; });
  if (!item) return;

  var overlay = document.getElementById('saved-share-choice-overlay');
  if (!overlay) return;

  overlay.style.display = 'flex';

  var btnComm = document.getElementById('btn-share-community');
  var btnJour = document.getElementById('btn-share-journal');

  btnComm.onclick = function() {
    overlay.style.display = 'none';
    shareToCommunity(item);
  };

  btnJour.onclick = function() {
    overlay.style.display = 'none';
    shareToJournal(item);
  };
};

async function shareToCommunity(item) {
  if (!currentUser || !window.db || !window._fbFS) {
    if (typeof window.toast === 'function') window.toast('Login required', '🔐');
    return;
  }

  window.confirmAction({
    emoji: '🌐',
    title: 'Share to Community?',
    body: 'This will post a summary of your report to the public community feed. Continue?',
    confirm: 'Post to Feed',
    onConfirm: async (close) => {
      try {
        const { addDoc, collection, serverTimestamp } = window._fbFS;
        const p = window.userProfile || {};
        const authorName = p.displayName || currentUser.displayName || 'Anonymous';
        const authorAvatar = p.avatarUrl || currentUser.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
        
        // Prepare content
        let shareTitle = item.title || item.summaryTitle || 'My Report';
        let shareBody = '';

        const TYPE_ICONS = { 'resume': '📄', 'holistic-career': '🔮', 'story': '📖', 'shadow': '🪞' };
        const typeIcon = TYPE_ICONS[item.type] || '🔖';

        if (item.type === 'story') {
            const SM = {memoir:'Memoir', literary:'Literary', poetic:'Poetic', cinematic:'Cinematic', epistolary:'Epistolary', stream:'Stream', mythic:'Mythic', detective:'Self-Discovery'};
            const RL = {last7:'Last 7 Days', last30:'Last 30 Days', last90:'Last 3 Months', all:'All Time'};
            const themes = (item.themes || []).slice(0, 4).map(t => `\`${t}\``).join(' · ');
            const preview = (item.body || '').trim().slice(0, 600);
            const hasMore = (item.body || '').length > 600;
            shareBody = [
                themes ? `*${SM[item.style] || 'Story'} · ${RL[item.range] || 'All Time'} · ${themes}*` : `*${SM[item.style] || 'Story'} · ${RL[item.range] || 'All Time'}*`,
                '',
                preview + (hasMore ? '…' : '')
            ].join('\n');

        } else if (item.type === 'shadow') {
            const score = item.awarenessScore || 0;
            const scoreLbl = score >= 75 ? 'High Awareness' : score >= 50 ? 'Growing Awareness' : 'Early Awareness';
            const topPatterns = (item.patterns || []).slice(0, 4).map(p => `${p.emoji || '🎭'} **${p.name}**`).join('  ·  ');
            const topStrengths = (item.strengths || []).slice(0, 3).join(', ');
            const summary = (item.summaryText || '').trim().slice(0, 300);
            const parts = [
                `**🪞 Shadow Self Analysis** · **${score}% Awareness** · *${scoreLbl}*`,
                '',
            ];
            if (summary) parts.push('> ' + summary.split('\n').join('\n> '), '');
            if (topPatterns) parts.push(`**Patterns detected:** ${topPatterns}`, '');
            if (topStrengths) parts.push(`**Hidden strengths:** ${topStrengths}`);
            shareBody = parts.join('\n');

        } else if (item.type === 'resume' || item.type === 'holistic-career') {
            const s = item.score || 0;
            const isHolistic = item.type === 'holistic-career';
            const lbl = isHolistic
              ? (s >= 80 ? 'Highly Aligned ✨' : s >= 60 ? 'Moderately Aligned' : s >= 40 ? 'Partially Aligned' : 'Misaligned')
              : (s >= 75 ? 'Strong Match ✨' : s >= 50 ? 'Moderate Match' : 'Needs Work');
            const icon = isHolistic ? '🔮' : '📄';
            // Extract first meaningful paragraph from the report
            const rawText = (item.markdown || item.content || '')
                .replace(/#{1,3} .+\n?/g, '')
                .replace(/\*\*/g, '')
                .replace(/\*/g, '')
                .trim();
            const firstPara = rawText.split(/\n\n+/).find(p => p.trim().length > 60) || '';
            const preview = firstPara.trim().slice(0, 400);

            shareBody = [
                `**${icon} Score: ${s}/100** · *${lbl}*`,
                '',
                preview ? '> ' + preview : '',
            ].filter(Boolean).join('\n');
        } else {
            shareBody = (item.markdown || item.content || '').replace(/#{1,3} .+\n?/g, '').trim().slice(0, 600);
        }

        await addDoc(collection(window.db, 'community_posts'), {
          type: 'thought',
          title: shareTitle,
          body: shareBody,
          authorId: currentUser.uid,

          authorName,
          authorAvatar,
          authorUsername: p.username || (authorName.toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user'),
          likes: [],
          commentCount: 0,
          repostCount: 0,
          createdAt: serverTimestamp(),
          isSharedItem: true,
          originalItemId: item.id
        });

        if (typeof window.toast === 'function') window.toast('Posted to Community! 🌐');
      } catch (e) {
        console.error('[saved] Share community error:', e);
        if (typeof window.toast === 'function') window.toast('Error sharing', '❌');
      }
      close();
    }
  });
}

function shareToJournal(item) {
  if (typeof window.openJournalModal !== 'function') {
      if (typeof window.toast === 'function') window.toast('Journal unavailable', '❌');
      return;
  }

  // Pre-fill journal modal
  let jTitle = document.getElementById('journal-title');
  let jContent = document.getElementById('journal-content');
  let jTags = document.getElementById('journal-tags');

  if (jTitle) jTitle.value = 'Reflecting on: ' + (item.title || item.summaryTitle || 'My Report');
  if (jContent) {
      if (item.type === 'story') {
          const SM = {memoir:'Memoir', literary:'Literary', poetic:'Poetic', cinematic:'Cinematic', epistolary:'Epistolary', stream:'Stream', mythic:'Mythic', detective:'Self-Discovery'};
          const RL = {last7:'Last 7 Days', last30:'Last 30 Days', last90:'Last 3 Months', all:'All Time'};
          jContent.value = `STYLE: ${SM[item.style] || item.style || 'Standard'}\n` +
                           `RANGE: ${RL[item.range] || 'All'}\n` +
                           `THEMES: ${(item.themes || []).join(', ')}\n\n` +
                           (item.body || '');
      } else if (item.type === 'shadow') {
           jContent.value = `--- SHADOW ANALYSIS (Awareness: ${item.awarenessScore || 0}%) ---\n\n` +
                            (item.summaryText || '') + "\n\n" +
                            "--- DETECTED PATTERNS ---\n" +
                            (item.patterns || []).map(p => `${p.emoji || '🎭'} ${p.name} (${p.severity || 1}/5): ${p.description || ''}`).join('\n\n') +
                            "\n\n--- COMPASSIONATE REFRAMES ---\n" +
                            (item.reframes || []).map(r => `Shadow: "${r.shadow}"\nTruth: ${r.reframe}`).join('\n\n') +
                            "\n\n--- HIDDEN STRENGTHS ---\n" +
                            (item.strengths || []).join(', ') +
                            "\n\n--- FULL ANALYSIS ---\n" + (item.markdown || item.content || '');
      } else if (item.type === 'resume' || item.type === 'holistic-career') {
           const s = item.score || 0;
           const lbl = item.type === 'holistic-career'
              ? (s >= 80 ? 'Highly Aligned' : s >= 60 ? 'Moderately Aligned' : s >= 40 ? 'Partially Aligned' : 'Misaligned')
              : (s >= 75 ? 'Strong Match' : s >= 50 ? 'Moderate Match' : 'Needs Work');
           jContent.value = `SCORE: ${s}/100\n` +
                            `RESULT: ${lbl}\n\n` +
                            "--- FULL REPORT ---\n" + (item.markdown || item.content || '');
      } else {
          jContent.value = "--- REPORT SUMMARY ---\n" + (item.summaryText || '') + "\n\n--- FULL REPORT ---\n" + (item.markdown || item.content || '');
      }
  }
  if (jTags) jTags.value = 'saved-item, ' + item.type;

  // Set mood if available or just default
  if (typeof window.selectMood === 'function') {
      window.selectMood(4, '🙂'); // Good/Reflective
  }

  window.openJournalModal();
  if (typeof window.toast === 'function') window.toast('Pre-filled in Journal! 📓');
}

/* ── Filter / Search ────────────────────────────────────── */
window.savedFilter = function(tab) {
  activeTab = tab;
  document.querySelectorAll('.saved-tab').forEach(function(t) {
    var on = t.dataset.tab === tab;
    t.style.borderColor = on ? 'var(--accent)' : 'var(--border)';
    t.style.background = on ? 'rgba(79,142,247,.12)' : 'var(--surface)';
    t.style.color = on ? 'var(--accent)' : 'var(--muted)';
  });
  renderSaved();
};

window.savedSearchItems = function(q) {
  searchQuery = q.toLowerCase();
  renderSaved();
};

/* ════════════════════════════════════════════════════════════
   MODAL
════════════════════════════════════════════════════════════ */

window.savedModalOpen = function(id) {
  var item = savedItemsCache.find(function(i) { return i.id === id; });
  if (!item) return;

  var overlay = document.getElementById('saved-modal-overlay');
  var box = document.getElementById('saved-modal-box');
  if (!overlay || !box) return;

  var TC = {
    'resume': { icon: '📄', ib: 'rgba(79,142,247,.14)', ibr: 'rgba(79,142,247,.22)', badge: 'Resume', bc: 'var(--accent)', bb: 'rgba(79,142,247,.1)', bbr: 'rgba(79,142,247,.22)' },
    'story': { icon: '📖', ib: 'rgba(167,139,250,.14)', ibr: 'rgba(167,139,250,.22)', badge: 'Story', bc: 'var(--lavender)', bb: 'rgba(167,139,250,.1)', bbr: 'rgba(167,139,250,.22)' },
    'shadow': { icon: '🪞', ib: 'rgba(248,113,113,.14)', ibr: 'rgba(248,113,113,.22)', badge: 'Shadow', bc: 'var(--rose)', bb: 'rgba(248,113,113,.1)', bbr: 'rgba(248,113,113,.22)' },
    'holistic-career': { icon: '🔮', ib: 'rgba(167,139,250,.14)', ibr: 'rgba(167,139,250,.22)', badge: 'Career', bc: 'var(--accent)', bb: 'rgba(167,139,250,.1)', bbr: 'rgba(167,139,250,.22)' }
  };
  var tc = TC[item.type] || TC['resume'];

  var iconEl = document.getElementById('saved-modal-icon');
  iconEl.textContent = tc.icon;
  iconEl.style.background = tc.ib;
  iconEl.style.border = '1px solid ' + tc.ibr;

  document.getElementById('saved-modal-title').textContent =
    item.type === 'story' ? '"' + (item.title || 'My Life Story') + '"' :
    (item.title || item.summaryTitle || 'Saved Item');

  document.getElementById('saved-modal-meta').textContent =
    relTime(item.savedAt) + (item.subtitle ? ' · ' + item.subtitle : (item.type === 'shadow' ? ' · ' + (item.patterns || []).length + ' patterns' : ''));

  document.getElementById('saved-modal-badge').innerHTML =
    '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.54rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px;background:' + tc.bb + ';border:1px solid ' + tc.bbr + ';color:' + tc.bc + '">' + tc.badge + '</span>';

  document.getElementById('saved-modal-summary').innerHTML = buildModalSummary(item);
  document.getElementById('saved-modal-body').innerHTML = buildModalBody(item);
  document.getElementById('saved-modal-body').scrollTop = 0;

  var fh = '';
  if (item.type === 'story') {
    fh += '<button onclick="savedCopyStory(\'' + id + '\')" class="saved-view-btn" style="border-color:rgba(167,139,250,.28);color:var(--lavender)">📋 Copy Story</button>';
  }
  fh += '<button onclick="savedShareItem(\'' + id + '\')" class="saved-view-btn" style="border-color:rgba(45,212,191,.28);color:var(--teal)">↗ Share</button>';
  fh += '<button class="saved-delete-btn" onclick="savedDeleteItem(\'' + id + '\')">'
    + '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/></svg>Delete</button>';
  fh += '<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">'
    + new Date(item.savedAt).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) + '</span>';
  document.getElementById('saved-modal-footer').innerHTML = fh;

  overlay.style.display = 'flex';
  document.body.style.overflow = 'hidden';
  requestAnimationFrame(function() {
    requestAnimationFrame(function() {
      box.style.transform = 'scale(1) translateY(0)';
      box.style.opacity = '1';
    });
  });

  document._savedEsc = function(e) { if (e.key === 'Escape') savedModalClose(); };
  document.addEventListener('keydown', document._savedEsc);
};

window.savedModalClose = function(e) {
  if (e && e.target && e.target.id !== 'saved-modal-overlay') return;
  var overlay = document.getElementById('saved-modal-overlay');
  var box = document.getElementById('saved-modal-box');
  if (!overlay || !box) return;
  box.style.transform = 'scale(.94) translateY(18px)';
  box.style.opacity = '0';
  setTimeout(function() {
    overlay.style.display = 'none';
    document.body.style.overflow = '';
  }, 240);
  if (document._savedEsc) { document.removeEventListener('keydown', document._savedEsc); }
};

/* ── Modal content builders ──────────────────────────────– */
function buildModalSummary(item) {
  if (item.type === 'resume' || item.type === 'holistic-career') {
    var s = item.score || 0, ok = s >= 75, mid = s >= 50;
    var sc = ok ? 'var(--green)' : mid ? 'var(--amber)' : 'var(--rose)';
    var sb = ok ? 'rgba(52,211,153,.1)' : mid ? 'rgba(251,191,36,.1)' : 'rgba(248,113,113,.1)';
    var sr = ok ? 'rgba(52,211,153,.3)' : mid ? 'rgba(251,191,36,.3)' : 'rgba(248,113,113,.3)';
    var lbl = item.type === 'holistic-career'
      ? (s >= 80 ? 'Highly Aligned' : s >= 60 ? 'Moderately Aligned' : s >= 40 ? 'Partially Aligned' : 'Misaligned')
      : (ok ? 'Strong Match' : mid ? 'Moderate Match' : 'Needs Work');
    return '<div style="display:flex;align-items:center;gap:14px">'
      + '<span style="display:inline-flex;align-items:center;gap:5px;padding:5px 16px;border-radius:99px;font-family:\'JetBrains Mono\',monospace;font-size:.7rem;font-weight:700;background:' + sb + ';border:1px solid ' + sr + ';color:' + sc + '">'
      + '<span style="font-size:.9rem;font-weight:900">' + s + '</span><span style="opacity:.6">/100</span></span>'
      + '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.62rem;color:' + sc + ';text-transform:uppercase;letter-spacing:.08em">' + lbl + '</span>'
      + '</div>';
  }
  if (item.type === 'shadow') {
    var score = item.awarenessScore != null ? item.awarenessScore : '—';
    var CM = {rose: 'var(--rose)', amber: 'var(--amber)', lavender: 'var(--lavender)', teal: 'var(--teal)', accent: 'var(--accent)'};
    var BM = {rose: 'rgba(248,113,113,.1)', amber: 'rgba(251,191,36,.1)', lavender: 'rgba(167,139,250,.12)', teal: 'rgba(45,212,191,.1)', accent: 'rgba(79,142,247,.1)'};
    var RM = {rose: 'rgba(248,113,113,.25)', amber: 'rgba(251,191,36,.25)', lavender: 'rgba(167,139,250,.28)', teal: 'rgba(45,212,191,.25)', accent: 'rgba(79,142,247,.25)'};
    var badges = (item.patterns || []).slice(0, 5).map(function(p) {
      var c = CM[p.color] || 'var(--rose)', b = BM[p.color] || 'rgba(248,113,113,.1)', r = RM[p.color] || 'rgba(248,113,113,.25)';
      return '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.55rem;padding:3px 9px;border-radius:99px;background:' + b + ';color:' + c + ';border:1px solid ' + r + ';text-transform:uppercase;letter-spacing:.05em">' + esc(p.emoji || '') + ' ' + esc(p.name || '') + '</span>';
    }).join('');
    return '<div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">'
      + '<div style="text-align:center;flex-shrink:0">'
      + '<div style="font-size:1.7rem;font-weight:800;letter-spacing:-.04em;background:linear-gradient(135deg,var(--rose),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1">' + score + '%</div>'
      + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.5rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em">Awareness</div></div>'
      + (item.summaryText ? '<div style="flex:1;min-width:160px;font-family:\'Newsreader\',serif;font-size:.8rem;color:rgba(232,234,240,.7);line-height:1.55;font-weight:300">' + esc(item.summaryText) + '</div>' : '')
      + '</div>'
      + (badges ? '<div style="display:flex;gap:5px;flex-wrap:wrap;margin-top:10px">' + badges + '</div>' : '');
  }
  if (item.type === 'story') {
    var wc = (item.body || '').split(/\s+/).filter(Boolean).length;
    var SM = {memoir: '📝 Memoir', literary: '📚 Literary', poetic: '🌸 Poetic', cinematic: '🎬 Cinematic', epistolary: '✉️ Epistolary', stream: '🌊 Stream', mythic: '⚔️ Mythic', detective: '🔍 Self-Discovery'};
    var RL = {last7: 'Last 7 Days', last30: 'Last 30 Days', last90: 'Last 3 Months', all: 'All Time'};
    var themes = (item.themes || []).slice(0, 5).map(function(t) {
      return '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.55rem;padding:2px 8px;border-radius:99px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--lavender);text-transform:uppercase;letter-spacing:.06em">' + esc(t) + '</span>';
    }).join('');
    return '<div style="display:flex;align-items:center;gap:7px;flex-wrap:wrap">'
      + '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);background:rgba(255,255,255,.04);border:1px solid var(--border);padding:3px 9px;border-radius:6px">' + wc + ' words</span>'
      + (SM[item.style] ? '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);background:rgba(255,255,255,.04);border:1px solid var(--border);padding:3px 9px;border-radius:6px">' + esc(SM[item.style]) + '</span>' : '')
      + (RL[item.range] ? '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);background:rgba(255,255,255,.04);border:1px solid var(--border);padding:3px 9px;border-radius:6px">' + esc(RL[item.range]) + '</span>' : '')
      + themes + '</div>';
  }
  return '';
}

function buildModalBody(item) {
  if (item.type === 'resume') return '<div class="markdown-content">' + (typeof marked !== 'undefined' ? marked.parse(item.markdown || '') : esc(item.markdown || '')) + '</div>';
  if (item.type === 'holistic-career') return '<div class="markdown-content">' + (typeof marked !== 'undefined' ? marked.parse(item.content || '') : esc(item.content || '')) + '</div>';
  if (item.type === 'story') return '<div style="white-space:pre-wrap">' + esc(item.body || '') + '</div>';
  if (item.type === 'shadow') {
    var CM = {rose: 'var(--rose)', amber: 'var(--amber)', lavender: 'var(--lavender)', teal: 'var(--teal)', accent: 'var(--accent)'};
    var BM = {rose: 'rgba(248,113,113,.08)', amber: 'rgba(251,191,36,.08)', lavender: 'rgba(167,139,250,.1)', teal: 'rgba(45,212,191,.08)', accent: 'rgba(79,142,247,.08)'};
    var RM = {rose: 'rgba(248,113,113,.2)', amber: 'rgba(251,191,36,.2)', lavender: 'rgba(167,139,250,.22)', teal: 'rgba(45,212,191,.2)', accent: 'rgba(79,142,247,.2)'};
    var out = '', patterns = item.patterns || [];
    if (patterns.length) {
      out += '<div class="sm-label">Detected Patterns</div>';
      patterns.forEach(function(p) {
        var c = CM[p.color] || 'var(--rose)', b = BM[p.color] || 'rgba(248,113,113,.08)', r = RM[p.color] || 'rgba(248,113,113,.2)';
        var bars = Array.from({length: 5}, function(_, j) {
          return '<div style="flex:1;height:5px;border-radius:99px;background:' + (j < (p.severity || 1) ? c : 'rgba(255,255,255,.07)') + '"></div>';
        }).join('');
        out += '<div class="sm-pattern" style="border-color:' + r + ';background:' + b + '">'
          + '<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">'
          + '<span style="font-size:1.3rem">' + esc(p.emoji || '😔') + '</span>'
          + '<div style="font-size:.82rem;font-weight:700;color:' + c + ';flex:1">' + esc(p.name || '') + '</div>'
          + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.6rem;color:var(--muted)">' + (p.severity || 1) + '/5</div>'
          + '</div>'
          + '<div style="display:flex;gap:3px;margin-bottom:10px">' + bars + '</div>'
          + '<div style="font-size:.85rem;color:rgba(232,234,240,.75);line-height:1.65">' + esc(p.description || '') + '</div>'
          + '</div>';
      });
    }
    if ((item.reframes || []).length) {
      out += '<div class="sm-label">Compassionate Reframes</div>';
      (item.reframes || []).forEach(function(r) {
        out += '<div class="sm-reframe">'
          + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.52rem;color:var(--rose);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">Shadow → Truth</div>'
          + '<div style="font-size:.82rem;color:rgba(232,234,240,.6);font-style:italic;margin-bottom:6px">"' + esc(r.shadow || '') + '"</div>'
          + '<div style="font-size:.85rem;color:rgba(232,234,240,.88)">' + esc(r.reframe || '') + '</div>'
          + '</div>';
      });
    }
    if ((item.strengths || []).length) {
      out += '<div class="sm-label">Hidden Strengths</div><div style="display:flex;gap:7px;flex-wrap:wrap">';
      (item.strengths || []).forEach(function(s) {
        out += '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.6rem;padding:5px 12px;border-radius:99px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--lavender)">' + esc(s) + '</span>';
      });
      out += '</div>';
    }
    return out || '<div style="color:var(--muted);font-style:italic">No detailed data available.</div>';
  }
  return '';
}

/* ════════════════════════════════════════════════════════════
   CARD BUILDERS
════════════════════════════════════════════════════════════ */

var DEL_SVG = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/></svg>';
var EYE_SVG = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';

function viewBtn(id, label) {
  return '<button class="saved-view-btn" onclick="event.stopPropagation();savedModalOpen(\'' + id + '\')">'
    + EYE_SVG + esc(label) + '</button>';
}

function delBtn(id) {
  return '<button class="saved-delete-btn" onclick="event.stopPropagation();savedDeleteItem(\'' + id + '\')">'
    + DEL_SVG + 'Delete</button>';
}

function dateStr(iso) {
  return new Date(iso).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'});
}

function buildResumeCard(item) {
  var s = item.score || 0, ok = s >= 75, mid = s >= 50;
  var sc = ok ? 'var(--green)' : mid ? 'var(--amber)' : 'var(--rose)';
  var sb = ok ? 'rgba(52,211,153,.1)' : mid ? 'rgba(251,191,36,.1)' : 'rgba(248,113,113,.1)';
  var sr = ok ? 'rgba(52,211,153,.3)' : mid ? 'rgba(251,191,36,.3)' : 'rgba(248,113,113,.3)';
  var lbl = ok ? 'Strong Match' : mid ? 'Moderate Match' : 'Needs Work';
  var preview = (item.markdown || '').replace(/#{1,3} .+\n?/g, '').replace(/\*\*/g, '').replace(/\*/g, '').trim().slice(0, 220);
  return '<div class="saved" id="saved-' + item.id + '" style="border-color:rgba(79,142,247,.18)" onclick="savedModalOpen(\'' + item.id + '\')">'
    + '<div class="saved-header">'
    + '<div style="width:36px;height:36px;border-radius:10px;background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">📄</div>'
    + '<div style="flex:1;min-width:0"><div style="font-size:.82rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + esc(item.title || 'Resume Analysis') + '</div>'
    + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">' + esc(item.subtitle || '') + ' · ' + relTime(item.savedAt) + '</div></div>'
    + '<span class="saved-type-badge" style="background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);color:var(--accent)">Resume</span>'
    + '</div>'
    + '<div class="saved-body">'
    + '<div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">'
    + '<span class="saved-score-pill" style="background:' + sb + ';border:1px solid ' + sr + ';color:' + sc + '"><span style="font-size:.8rem;font-weight:900">' + s + '</span><span style="opacity:.6">/100</span></span>'
    + '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.6rem;color:' + sc + ';text-transform:uppercase;letter-spacing:.08em">' + lbl + '</span>'
    + '</div>'
    + (preview ? '<div style="font-family:var(--font-journal);font-size:.8rem;color:var(--muted);line-height:1.65;font-weight:300;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">' + esc(preview) + '…</div>' : '')
    + '</div>'
    + '<div class="saved-footer" onclick="event.stopPropagation()">' + viewBtn(item.id, 'View Report') 
    + '<button class="saved-view-btn" onclick="event.stopPropagation();savedShareItem(\'' + item.id + '\')" style="border-color:rgba(45,212,191,.2);color:var(--teal)">↗ Share</button>'
    + delBtn(item.id)
    + '<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">' + dateStr(item.savedAt) + '</span>'
    + '</div></div>';
}

function buildStoryCard(item) {
  var SM = {memoir: '📝 Memoir', literary: '📚 Literary', poetic: '🌸 Poetic', cinematic: '🎬 Cinematic', epistolary: '✉️ Epistolary', stream: '🌊 Stream', mythic: '⚔️ Mythic', detective: '🔍 Self-Discovery'};
  var RL = {last7: 'Last 7 Days', last30: 'Last 30 Days', last90: 'Last 3 Months', all: 'All Time'};
  var preview = (item.body || '').slice(0, 240);
  var wc = (item.body || '').split(/\s+/).filter(Boolean).length;
  var themes = (item.themes || []).slice(0, 3).map(function(t) {
    return '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.55rem;padding:2px 8px;border-radius:99px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--lavender);text-transform:uppercase;letter-spacing:.06em">' + esc(t) + '</span>';
  }).join('');
  return '<div class="saved" id="saved-' + item.id + '" style="border-color:rgba(167,139,250,.18)" onclick="savedModalOpen(\'' + item.id + '\')">'
    + '<div class="saved-header">'
    + '<div style="width:36px;height:36px;border-radius:10px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">📖</div>'
    + '<div style="flex:1;min-width:0"><div style="font-size:.82rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-style:italic">"' + esc(item.title || 'My Life Story') + '"</div>'
    + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">' + (SM[item.style] || item.style || '') + ' · ' + (RL[item.range] || '') + ' · ' + relTime(item.savedAt) + '</div></div>'
    + '<span class="saved-type-badge" style="background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--lavender)">Story</span>'
    + '</div>'
    + '<div class="saved-body">'
    + '<div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">'
    + '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);background:rgba(255,255,255,.04);border:1px solid var(--border);padding:3px 9px;border-radius:6px">' + wc + ' words</span>' + themes
    + '</div>'
    + '<div style="font-family:var(--font-journal);font-size:.85rem;color:rgba(232,234,240,.72);line-height:1.75;font-weight:300;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden">' + esc(preview) + (preview.length >= 240 ? '…' : '') + '</div>'
    + '</div>'
    + '<div class="saved-footer" onclick="event.stopPropagation()">' + viewBtn(item.id, 'Read Full Story')
    + '<button onclick="event.stopPropagation();savedShareItem(\'' + item.id + '\')" class="saved-view-btn" style="border-color:rgba(45,212,191,.2);color:var(--teal)">↗ Share</button>'
    + '<button onclick="event.stopPropagation();savedCopyStory(\'' + item.id + '\')" class="saved-view-btn" style="border-color:rgba(167,139,250,.25);color:var(--lavender)">📋 Copy</button>'
    + delBtn(item.id)
    + '<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">' + dateStr(item.savedAt) + '</span>'
    + '</div></div>';
}

function buildShadowCard(item) {
  var CM = {rose: 'var(--rose)', amber: 'var(--amber)', lavender: 'var(--lavender)', teal: 'var(--teal)', accent: 'var(--accent)'};
  var BM = {rose: 'rgba(248,113,113,.1)', amber: 'rgba(251,191,36,.1)', lavender: 'rgba(167,139,250,.12)', teal: 'rgba(45,212,191,.1)', accent: 'rgba(79,142,247,.1)'};
  var RM = {rose: 'rgba(248,113,113,.25)', amber: 'rgba(251,191,36,.25)', lavender: 'rgba(167,139,250,.28)', teal: 'rgba(45,212,191,.25)', accent: 'rgba(79,142,247,.25)'};
  var patterns = item.patterns || [];
  var score = item.awarenessScore != null ? item.awarenessScore : '—';
  var badges = patterns.slice(0, 4).map(function(p) {
    var c = CM[p.color] || 'var(--rose)', b = BM[p.color] || 'rgba(248,113,113,.1)', r = RM[p.color] || 'rgba(248,113,113,.25)';
    return '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.55rem;padding:3px 9px;border-radius:99px;background:' + b + ';color:' + c + ';border:1px solid ' + r + ';text-transform:uppercase;letter-spacing:.05em">' + esc(p.emoji || '') + ' ' + esc(p.name || '') + '</span>';
  }).join('');
  return '<div class="saved" id="saved-' + item.id + '" style="border-color:rgba(248,113,113,.18)" onclick="savedModalOpen(\'' + item.id + '\')">'
    + '<div class="saved-header">'
    + '<div style="width:36px;height:36px;border-radius:10px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">🪞</div>'
    + '<div style="flex:1;min-width:0"><div style="font-size:.82rem;font-weight:700;color:var(--text)">' + esc(item.summaryTitle || 'Shadow Analysis') + '</div>'
    + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">' + patterns.length + ' patterns · ' + relTime(item.savedAt) + '</div></div>'
    + '<span class="saved-type-badge" style="background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);color:var(--rose)">Shadow</span>'
    + '</div>'
    + '<div class="saved-body">'
    + '<div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">'
    + '<div style="text-align:center;flex-shrink:0">'
    + '<div style="font-size:1.8rem;font-weight:800;letter-spacing:-.04em;background:linear-gradient(135deg,var(--rose),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1">' + score + '%</div>'
    + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.5rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em">Awareness</div></div>'
    + '<div style="flex:1;font-family:var(--font-journal);font-size:.8rem;color:rgba(232,234,240,.7);line-height:1.6;font-weight:300">' + esc(item.summaryText || '') + '</div>'
    + '</div>'
    + '<div style="display:flex;gap:5px;flex-wrap:wrap">' + badges + '</div>'
    + '</div>'
    + '<div class="saved-footer" onclick="event.stopPropagation()">' + viewBtn(item.id, 'View Analysis') 
    + '<button class="saved-view-btn" onclick="event.stopPropagation();savedShareItem(\'' + item.id + '\')" style="border-color:rgba(45,212,191,.2);color:var(--teal)">↗ Share</button>'
    + delBtn(item.id)
    + '<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">' + dateStr(item.savedAt) + '</span>'
    + '</div></div>';
}

function buildHolisticCard(item) {
  var s = item.score || 0;
  var m = s >= 80 ? {color: 'var(--green)', bg: 'rgba(52,211,153,.1)', br: 'rgba(52,211,153,.3)', lbl: 'Highly Aligned'}
    : s >= 60 ? {color: 'var(--accent)', bg: 'rgba(167,139,250,.1)', br: 'rgba(167,139,250,.3)', lbl: 'Moderately Aligned'}
    : s >= 40 ? {color: 'var(--amber)', bg: 'rgba(251,191,36,.1)', br: 'rgba(251,191,36,.3)', lbl: 'Partially Aligned'}
    : {color: 'var(--rose)', bg: 'rgba(248,113,113,.1)', br: 'rgba(248,113,113,.3)', lbl: 'Misaligned'};
  var preview = (item.content || '').replace(/#{1,3} .+\n?/g, '').replace(/\*\*/g, '').replace(/\*/g, '').trim().slice(0, 240);
  return '<div class="saved" id="saved-' + item.id + '" style="border-color:rgba(167,139,250,.18)" onclick="savedModalOpen(\'' + item.id + '\')">'
    + '<div class="saved-header">'
    + '<div style="width:36px;height:36px;border-radius:10px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">🔮</div>'
    + '<div style="flex:1;min-width:0"><div style="font-size:.82rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + esc(item.title || 'Holistic Career Report') + '</div>'
    + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">' + relTime(item.savedAt) + '</div></div>'
    + '<span class="saved-type-badge" style="background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--accent)">Career</span>'
    + '</div>'
    + '<div class="saved-body">'
    + '<div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">'
    + '<span class="saved-score-pill" style="background:' + m.bg + ';border:1px solid ' + m.br + ';color:' + m.color + '"><span style="font-size:.8rem;font-weight:900">' + s + '</span><span style="opacity:.6">/100</span></span>'
    + '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.6rem;color:' + m.color + ';text-transform:uppercase;letter-spacing:.08em">' + m.lbl + '</span>'
    + '</div>'
    + (preview ? '<div style="font-family:var(--font-journal);font-size:.8rem;color:var(--muted);line-height:1.65;font-weight:300;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">' + esc(preview) + '…</div>' : '')
    + '</div>'
    + '<div class="saved-footer" onclick="event.stopPropagation()">' + viewBtn(item.id, 'View Report') 
    + '<button class="saved-view-btn" onclick="event.stopPropagation();savedShareItem(\'' + item.id + '\')" style="border-color:rgba(45,212,191,.2);color:var(--teal)">↗ Share</button>'
    + delBtn(item.id)
    + '<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">' + dateStr(item.savedAt) + '</span>'
    + '</div></div>';
}

function buildGenericCard(item) {
  var preview = (item.body || item.markdown || item.content || item.summaryText || '').slice(0, 200);
  return '<div class="saved" id="saved-' + item.id + '" onclick="savedModalOpen(\'' + item.id + '\')">'
    + '<div class="saved-header">'
    + '<div style="width:36px;height:36px;border-radius:10px;background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">🔖</div>'
    + '<div style="flex:1;min-width:0"><div style="font-size:.82rem;font-weight:700;color:var(--text)">' + esc(item.title || item.summaryTitle || 'Saved Item') + '</div>'
    + '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">' + esc(item.type || 'unknown') + ' · ' + relTime(item.savedAt) + '</div></div>'
    + '<span class="saved-type-badge" style="background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);color:var(--accent)">' + esc(item.type || 'item') + '</span>'
    + '</div>'
    + (preview ? '<div class="saved-body"><div style="font-family:var(--font-journal);font-size:.8rem;color:var(--muted);line-height:1.65;font-weight:300;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">' + esc(preview) + '</div></div>' : '')
    + '<div class="saved-footer" onclick="event.stopPropagation()">' + delBtn(item.id) + '</div></div>';
}

/* ── Helpers ─────────────────────────────────────────── */
function esc(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

function relTime(iso) {
  var d = Date.now() - new Date(iso).getTime();
  if (d < 60000) return 'just now';
  if (d < 3600000) return Math.floor(d / 60000) + 'm ago';
  if (d < 86400000) return Math.floor(d / 3600000) + 'h ago';
  return Math.floor(d / 86400000) + 'd ago';
}

function updateCounts(items) {
  var c = {all: items.length, resume: 0, story: 0, shadow: 0, 'holistic-career': 0};
  items.forEach(function(i) { if (c[i.type] != null) c[i.type]++; });
  document.querySelectorAll('.saved-tab-count').forEach(function(el) {
    el.textContent = c[el.dataset.tab] != null ? c[el.dataset.tab] : 0;
  });
}

function recheckEmpty() {
  var grid = document.getElementById('saved-grid'), empty = document.getElementById('saved-empty');
  if (!grid) return;
  if (!grid.querySelectorAll('.saved').length) { grid.style.display = 'none'; empty.style.display = 'block'; }
}

/* ── Main Render ─────────────────────────────────────── */
function renderSaved() {
  var grid = document.getElementById('saved-grid');
  var empty = document.getElementById('saved-empty');
  if (!grid || !empty) return;

  updateCounts(savedItemsCache);

  var filtered = savedItemsCache.filter(function(item) {
    if (activeTab !== 'all' && item.type !== activeTab) return false;
    if (searchQuery) {
      var hay = [item.title || '', item.subtitle || '', item.body || '', item.markdown || '', item.summaryTitle || '', item.summaryText || '', item.content || ''].join(' ').toLowerCase();
      if (hay.indexOf(searchQuery) === -1) return false;
    }
    return true;
  });

  if (!filtered.length) {
    grid.style.display = 'none';
    empty.style.display = 'block';
    return;
  }

  var html = filtered.map(function(item) {
    try {
      var t = (item.type || '').toLowerCase().trim();
      if (t === 'resume') return buildResumeCard(item);
      if (t === 'story') return buildStoryCard(item);
      if (t === 'shadow' || t === 'ai-insight') return buildShadowCard(item);
      if (t === 'holistic-career' || t === 'holistic_career' || t === 'career') return buildHolisticCard(item);
      return buildGenericCard(item);
    } catch(err) {
      console.warn('[saved] card build error for id=' + item.id, err);
      return buildGenericCard(item);
    }
  }).join('');

  grid.style.display = 'block';
  empty.style.display = 'none';
  grid.innerHTML = html;
}

window._savedRender = renderSaved;

/* ── Init ────────────────────────────────────────────── */
document.addEventListener('pageChanged', function(e) {
  if (e && e.detail === 'saved') renderSaved();
});

(function _patchNav() {
  if (typeof window.navigateTo !== 'function') { setTimeout(_patchNav, 150); return; }
  var _orig = window.navigateTo;
  window.navigateTo = function(page, event) {
    _orig(page, event);
    if (page === 'saved') setTimeout(renderSaved, 50);
  };
})();

})();
</script>
@endpush

<x-ai-chatbot />