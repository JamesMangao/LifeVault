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
           style="width:100%;box-sizing:border-box;padding:11px 14px 11px 38px;background:var(--surface);border:1.5px solid var(--border);border-radius:12px;color:var(--text);font-family:'Newsreader',serif;font-size:.9rem;font-weight:300;outline:none;transition:border-color .2s,box-shadow .2s"
           onfocus="this.style.borderColor='rgba(79,142,247,.5)';this.style.boxShadow='0 0 0 3px rgba(79,142,247,.08)'"
           onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
  </div>

  {{-- Empty State --}}
  <div id="saved-empty" style="text-align:center;padding:80px 24px">
    <div style="font-size:3.5rem;margin-bottom:16px;opacity:.25">🔖</div>
    <div style="font-size:.95rem;font-weight:700;color:var(--muted);margin-bottom:10px">Nothing saved yet</div>
    <div style="font-family:'Newsreader',serif;font-style:italic;font-size:.85rem;color:var(--muted);opacity:.6;max-width:360px;margin:0 auto 28px;line-height:1.7">
      Use the Save button in the Resume Analyzer, Life Story Generator, or Shadow Self Analyzer to bookmark your results here.
    </div>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      @foreach([
        ['var(--accent)',   'rgba(79,142,247,.1)',  'rgba(79,142,247,.25)',  '📄', 'Resume Analyzer', 'analyzer'],
        ['var(--lavender)', 'rgba(167,139,250,.1)', 'rgba(167,139,250,.25)', '📖', 'Life Story',       'life-story'],
        ['var(--rose)',     'rgba(248,113,113,.1)', 'rgba(248,113,113,.25)', '🪞', 'Shadow Self',      'shadow-self'],
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

<style>
@keyframes saved-in { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.saved-tab:hover { border-color:var(--accent)!important; color:var(--accent)!important; }
.saved-item { break-inside:avoid;margin-bottom:16px;background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;animation:saved-in .28s cubic-bezier(.4,0,.2,1) both;transition:transform .2s,box-shadow .2s,border-color .2s; }
.saved-item:hover { transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,.3); }
.saved-item-header { display:flex;align-items:center;gap:12px;padding:16px 18px 12px;border-bottom:1px solid var(--border); }
.saved-item-body { padding:16px 18px; }
.saved-item-footer { display:flex;align-items:center;gap:8px;padding:12px 18px;border-top:1px solid var(--border);background:rgba(255,255,255,.015); }
.saved-type-badge { font-family:'JetBrains Mono',monospace;font-size:.54rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:3px 9px;border-radius:99px; }
.saved-delete-btn { background:transparent;border:1px solid var(--border);color:var(--muted);padding:5px 10px;border-radius:8px;font-size:.7rem;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:5px; }
.saved-delete-btn:hover { border-color:var(--rose);color:var(--rose);background:rgba(248,113,113,.08); }
.saved-expand-btn { background:transparent;border:1px solid var(--border);color:var(--muted);padding:5px 12px;border-radius:8px;font-size:.7rem;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:5px; }
.saved-expand-btn:hover { border-color:var(--accent);color:var(--accent);background:rgba(79,142,247,.08); }
.saved-score-pill { display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:99px;font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:700; }
.saved-accordion { max-height:0;overflow:hidden;transition:max-height .35s cubic-bezier(.4,0,.2,1); }
.saved-accordion.open { max-height:600px; }
@media(min-width:900px)  { .saved-masonry { columns:2!important; } }
@media(min-width:1300px) { .saved-masonry { columns:3!important; } }
</style>

@push('scripts')
<script>
(function(){
'use strict';

// Re-use the bootstrap's storage helpers if available, otherwise define locally
var loadItems    = window.savedGetItems  || function(){ try{ return JSON.parse(localStorage.getItem('lifevault_saved_items'))||[]; }catch(e){ return []; } };
var persistItems = window.savedPersist   || function(items){ try{ localStorage.setItem('lifevault_saved_items', JSON.stringify(items)); }catch(e){} };

/* ── State ──────────────────────────────────────────────── */
var activeTab   = 'all';
var searchQuery = '';

/* ── Expose render so bootstrap can call it after save ── */
window._savedRender = renderSaved;

/* ── Re-define savedAddItem here with full render support ─ */
window.savedAddItem = function(item){
  var items   = loadItems();
  var newItem = Object.assign({}, item, {
    id: Date.now() + Math.random().toString(36).slice(2),
    savedAt: new Date().toISOString()
  });
  items.unshift(newItem);
  persistItems(items);
  renderSaved();
  if(typeof window.toast === 'function') window.toast('Saved to 🔖 Saved Items! ✨');
};

/* ── Filter tabs ─────────────────────────────────────────── */
window.savedFilter = function(tab){
  activeTab = tab;
  document.querySelectorAll('.saved-tab').forEach(function(t){
    var on = t.dataset.tab === tab;
    t.style.borderColor = on ? 'var(--accent)' : 'var(--border)';
    t.style.background  = on ? 'rgba(79,142,247,.12)' : 'var(--surface)';
    t.style.color       = on ? 'var(--accent)' : 'var(--muted)';
  });
  renderSaved();
};

/* ── Search ──────────────────────────────────────────────── */
window.savedSearchItems = function(q){
  searchQuery = q.toLowerCase();
  renderSaved();
};

/* ── Clear all ───────────────────────────────────────────── */
window.savedClearAll = function(){
  if(!confirm('Delete all saved items? This cannot be undone.')) return;
  persistItems([]);
  renderSaved();
};

/* ── Delete single ───────────────────────────────────────── */
window.savedDeleteItem = function(id){
  persistItems(loadItems().filter(function(i){ return i.id !== id; }));
  var el = document.getElementById('saved-item-' + id);
  if(el){
    el.style.transition = 'opacity .2s,transform .2s';
    el.style.opacity    = '0';
    el.style.transform  = 'scale(.95)';
    setTimeout(function(){ el.remove(); recheckEmpty(); }, 220);
  }
};

/* ── Accordion toggle ────────────────────────────────────── */
window.savedToggle = function(id){
  var acc  = document.getElementById('saved-acc-'  + id);
  var chev = document.getElementById('saved-chev-' + id);
  if(!acc) return;
  acc.classList.toggle('open');
  if(chev) chev.style.transform = acc.classList.contains('open') ? 'rotate(180deg)' : '';
};

/* ── Copy story ──────────────────────────────────────────── */
window.savedCopyStory = function(id){
  var item = loadItems().find(function(i){ return i.id === id; });
  if(!item) return;
  var text = '"' + item.title + '"\n\n' + item.body;
  if(navigator.clipboard){ navigator.clipboard.writeText(text).then(function(){ window.toast && window.toast('Copied! 📋'); }); }
  else { var ta=Object.assign(document.createElement('textarea'),{value:text}); document.body.appendChild(ta); ta.select(); document.execCommand('copy'); ta.remove(); window.toast && window.toast('Copied! 📋'); }
};

/* ── Tab counts ──────────────────────────────────────────── */
function updateCounts(items){
  var counts = { all:items.length, resume:0, story:0, shadow:0 };
  items.forEach(function(i){ if(counts[i.type]!=null) counts[i.type]++; });
  document.querySelectorAll('.saved-tab-count').forEach(function(el){
    el.textContent = counts[el.dataset.tab] != null ? counts[el.dataset.tab] : 0;
  });
}

/* ── Empty check ─────────────────────────────────────────── */
function recheckEmpty(){
  var grid  = document.getElementById('saved-grid');
  var empty = document.getElementById('saved-empty');
  if(!grid) return;
  if(!grid.querySelectorAll('.saved-item').length){
    grid.style.display  = 'none';
    empty.style.display = 'block';
  }
}

/* ── Helpers ─────────────────────────────────────────────── */
function esc(s){
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function relTime(iso){
  var d = Date.now() - new Date(iso).getTime();
  if(d < 60000)    return 'just now';
  if(d < 3600000)  return Math.floor(d/60000)+'m ago';
  if(d < 86400000) return Math.floor(d/3600000)+'h ago';
  return Math.floor(d/86400000)+'d ago';
}

/* ── Resume card ─────────────────────────────────────────── */
function buildResumeCard(item){
  var score = item.score || 0;
  var strong = score>=75, mid = score>=50;
  var scoreColor = strong?'var(--green)':mid?'var(--amber)':'var(--rose)';
  var scoreBg    = strong?'rgba(52,211,153,.1)':mid?'rgba(251,191,36,.1)':'rgba(248,113,113,.1)';
  var scoreBr    = strong?'rgba(52,211,153,.3)':mid?'rgba(251,191,36,.3)':'rgba(248,113,113,.3)';
  var lbl        = strong?'Strong Match':mid?'Moderate Match':'Needs Work';
  var preview    = (item.markdown||'').replace(/#{1,3} .+\n?/g,'').replace(/\*\*/g,'').replace(/\*/g,'').trim().slice(0,220);

  return '<div class="saved-item" id="saved-item-'+item.id+'" style="border-color:rgba(79,142,247,.18)">'
    +'<div class="saved-item-header">'
    +'<div style="width:36px;height:36px;border-radius:10px;background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">📄</div>'
    +'<div style="flex:1;min-width:0">'
    +'<div style="font-size:.82rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">'+esc(item.title||'Resume Analysis')+'</div>'
    +'<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">'+esc(item.subtitle||'')+' · '+relTime(item.savedAt)+'</div>'
    +'</div>'
    +'<span class="saved-type-badge" style="background:rgba(79,142,247,.1);border:1px solid rgba(79,142,247,.2);color:var(--accent)">Resume</span>'
    +'</div>'
    +'<div class="saved-item-body">'
    +'<div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">'
    +'<span class="saved-score-pill" style="background:'+scoreBg+';border:1px solid '+scoreBr+';color:'+scoreColor+'">'
    +'<span style="font-size:.8rem;font-weight:900">'+score+'</span><span style="opacity:.6">/100</span></span>'
    +'<span style="font-family:\'JetBrains Mono\',monospace;font-size:.6rem;color:'+scoreColor+';text-transform:uppercase;letter-spacing:.08em">'+lbl+'</span>'
    +'</div>'
    +(preview ? '<div style="font-family:\'Newsreader\',serif;font-size:.8rem;color:var(--muted);line-height:1.65;font-weight:300;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">'+esc(preview)+'…</div>' : '')
    +'</div>'
    +'<div class="saved-accordion" id="saved-acc-'+item.id+'">'
    +'<div style="padding:0 18px 16px;font-family:\'Newsreader\',serif;font-size:.82rem;line-height:1.75;color:rgba(232,234,240,.78);font-weight:300;white-space:pre-wrap;max-height:320px;overflow-y:auto">'+esc((item.markdown||'').slice(0,1200))+((item.markdown||'').length>1200?'…':'')+'</div>'
    +'</div>'
    +'<div class="saved-item-footer">'
    +'<button class="saved-expand-btn" onclick="savedToggle(\''+item.id+'\')">'
    +'<svg id="saved-chev-'+item.id+'" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform .25s"><polyline points="6 9 12 15 18 9"/></svg>View Report</button>'
    +'<button class="saved-delete-btn" onclick="savedDeleteItem(\''+item.id+'\')">'
    +'<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/></svg>Delete</button>'
    +'<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">'+new Date(item.savedAt).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})+'</span>'
    +'</div></div>';
}

/* ── Story card ──────────────────────────────────────────── */
function buildStoryCard(item){
  var styleMap = { memoir:'📝 Memoir', literary:'📚 Literary', poetic:'🌸 Poetic', cinematic:'🎬 Cinematic', epistolary:'✉️ Epistolary', stream:'🌊 Stream', mythic:'⚔️ Mythic', detective:'🔍 Self-Discovery' };
  var rangeLbl = { last7:'Last 7 Days', last30:'Last 30 Days', last90:'Last 3 Months', all:'All Time' };
  var preview  = (item.body||'').slice(0,240);
  var wc       = (item.body||'').split(/\s+/).filter(Boolean).length;
  var themes   = (item.themes||[]).slice(0,3).map(function(t){
    return '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.55rem;padding:2px 8px;border-radius:99px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--lavender);text-transform:uppercase;letter-spacing:.06em">'+esc(t)+'</span>';
  }).join('');

  return '<div class="saved-item" id="saved-item-'+item.id+'" style="border-color:rgba(167,139,250,.18)">'
    +'<div class="saved-item-header">'
    +'<div style="width:36px;height:36px;border-radius:10px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">📖</div>'
    +'<div style="flex:1;min-width:0">'
    +'<div style="font-size:.82rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-style:italic">"'+esc(item.title||'My Life Story')+'"</div>'
    +'<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">'+(styleMap[item.style]||item.style||'')+' · '+(rangeLbl[item.range]||'')+' · '+relTime(item.savedAt)+'</div>'
    +'</div>'
    +'<span class="saved-type-badge" style="background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--lavender)">Story</span>'
    +'</div>'
    +'<div class="saved-item-body">'
    +'<div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">'
    +'<span style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);background:rgba(255,255,255,.04);border:1px solid var(--border);padding:3px 9px;border-radius:6px">'+wc+' words</span>'+themes
    +'</div>'
    +'<div style="font-family:\'Newsreader\',serif;font-size:.85rem;color:rgba(232,234,240,.72);line-height:1.75;font-weight:300;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden">'+esc(preview)+(preview.length>=240?'…':'')+'</div>'
    +'</div>'
    +'<div class="saved-accordion" id="saved-acc-'+item.id+'">'
    +'<div style="padding:0 18px 16px;font-family:\'Newsreader\',serif;font-size:.88rem;line-height:1.9;color:rgba(232,234,240,.82);font-weight:300;white-space:pre-wrap;max-height:380px;overflow-y:auto">'+esc(item.body||'')+'</div>'
    +'</div>'
    +'<div class="saved-item-footer">'
    +'<button class="saved-expand-btn" onclick="savedToggle(\''+item.id+'\')">'
    +'<svg id="saved-chev-'+item.id+'" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform .25s"><polyline points="6 9 12 15 18 9"/></svg>Read Full Story</button>'
    +'<button onclick="savedCopyStory(\''+item.id+'\')" class="saved-expand-btn" style="border-color:rgba(167,139,250,.25);color:var(--lavender)">📋 Copy</button>'
    +'<button class="saved-delete-btn" onclick="savedDeleteItem(\''+item.id+'\')">'
    +'<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/></svg>Delete</button>'
    +'<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">'+new Date(item.savedAt).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})+'</span>'
    +'</div></div>';
}

/* ── Shadow card ─────────────────────────────────────────── */
function buildShadowCard(item){
  var colorMap = { rose:'var(--rose)', amber:'var(--amber)', lavender:'var(--lavender)', teal:'var(--teal)', accent:'var(--accent)' };
  var bgMap    = { rose:'rgba(248,113,113,.1)', amber:'rgba(251,191,36,.1)', lavender:'rgba(167,139,250,.12)', teal:'rgba(45,212,191,.1)', accent:'rgba(79,142,247,.1)' };
  var brMap    = { rose:'rgba(248,113,113,.25)', amber:'rgba(251,191,36,.25)', lavender:'rgba(167,139,250,.28)', teal:'rgba(45,212,191,.25)', accent:'rgba(79,142,247,.25)' };
  var patterns = item.patterns || [];
  var score    = item.awarenessScore != null ? item.awarenessScore : '—';

  var patternBadges = patterns.slice(0,4).map(function(p){
    var c = colorMap[p.color]||'var(--rose)', b = bgMap[p.color]||'rgba(248,113,113,.1)', r = brMap[p.color]||'rgba(248,113,113,.25)';
    return '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.55rem;padding:3px 9px;border-radius:99px;background:'+b+';color:'+c+';border:1px solid '+r+';text-transform:uppercase;letter-spacing:.05em">'+esc(p.emoji||'')+' '+esc(p.name||'')+'</span>';
  }).join('');

  var patternRows = patterns.map(function(p){
    var c = colorMap[p.color]||'var(--rose)';
    var bars = Array.from({length:5},function(_,j){ return '<div style="flex:1;height:4px;border-radius:99px;background:'+(j<(p.severity||1)?c:'var(--surface2)')+'"></div>'; }).join('');
    return '<div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px">'
      +'<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">'
      +'<span style="font-size:1rem">'+esc(p.emoji||'😔')+'</span>'
      +'<div style="font-size:.75rem;font-weight:700;color:'+c+';flex:1">'+esc(p.name||'')+'</div>'
      +'<div style="font-family:\'JetBrains Mono\',monospace;font-size:.55rem;color:var(--muted)">'+(p.severity||1)+'/5</div>'
      +'</div><div style="display:flex;gap:2px;margin-bottom:8px">'+bars+'</div>'
      +'<div style="font-family:\'Newsreader\',serif;font-size:.78rem;color:rgba(232,234,240,.7);line-height:1.6;font-weight:300">'+esc(p.description||'')+'</div>'
      +'</div>';
  }).join('');

  var reframeRows = (item.reframes||[]).map(function(r){
    return '<div style="background:rgba(52,211,153,.05);border:1px solid rgba(52,211,153,.15);border-radius:10px;padding:10px 14px">'
      +'<div style="font-family:\'JetBrains Mono\',monospace;font-size:.52rem;color:var(--rose);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Shadow → Truth</div>'
      +'<div style="font-family:\'Newsreader\',serif;font-size:.78rem;color:rgba(232,234,240,.65);font-style:italic;margin-bottom:4px">"'+esc(r.shadow||'')+'"</div>'
      +'<div style="font-family:\'Newsreader\',serif;font-size:.8rem;color:rgba(232,234,240,.85);font-weight:300">'+esc(r.reframe||'')+'</div>'
      +'</div>';
  }).join('');

  var strengthChips = (item.strengths||[]).map(function(s){
    return '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;padding:4px 10px;border-radius:99px;background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2);color:var(--lavender)">'+esc(s)+'</span>';
  }).join('');

  return '<div class="saved-item" id="saved-item-'+item.id+'" style="border-color:rgba(248,113,113,.18)">'
    +'<div class="saved-item-header">'
    +'<div style="width:36px;height:36px;border-radius:10px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">🪞</div>'
    +'<div style="flex:1;min-width:0">'
    +'<div style="font-size:.82rem;font-weight:700;color:var(--text)">'+esc(item.summaryTitle||'Shadow Analysis')+'</div>'
    +'<div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;color:var(--muted);margin-top:2px">'+patterns.length+' patterns · '+relTime(item.savedAt)+'</div>'
    +'</div>'
    +'<span class="saved-type-badge" style="background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);color:var(--rose)">Shadow</span>'
    +'</div>'
    +'<div class="saved-item-body">'
    +'<div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">'
    +'<div style="text-align:center;flex-shrink:0">'
    +'<div style="font-size:1.8rem;font-weight:800;letter-spacing:-.04em;background:linear-gradient(135deg,var(--rose),var(--lavender));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1">'+score+'%</div>'
    +'<div style="font-family:\'JetBrains Mono\',monospace;font-size:.5rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em">Awareness</div>'
    +'</div>'
    +'<div style="flex:1;font-family:\'Newsreader\',serif;font-size:.8rem;color:rgba(232,234,240,.7);line-height:1.6;font-weight:300">'+esc(item.summaryText||'')+'</div>'
    +'</div>'
    +'<div style="display:flex;gap:5px;flex-wrap:wrap">'+patternBadges+'</div>'
    +'</div>'
    +'<div class="saved-accordion" id="saved-acc-'+item.id+'">'
    +(patterns.length ? '<div style="padding:14px 18px 0"><div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:10px">Detected Patterns</div><div style="display:flex;flex-direction:column;gap:10px">'+patternRows+'</div></div>' : '')
    +((item.reframes||[]).length ? '<div style="padding:14px 18px 0"><div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:10px">Compassionate Reframes</div><div style="display:flex;flex-direction:column;gap:8px">'+reframeRows+'</div></div>' : '')
    +((item.strengths||[]).length ? '<div style="padding:14px 18px 16px"><div style="font-family:\'JetBrains Mono\',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:10px">Hidden Strengths</div><div style="display:flex;gap:6px;flex-wrap:wrap">'+strengthChips+'</div></div>' : '')
    +'</div>'
    +'<div class="saved-item-footer">'
    +'<button class="saved-expand-btn" onclick="savedToggle(\''+item.id+'\')">'
    +'<svg id="saved-chev-'+item.id+'" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform .25s"><polyline points="6 9 12 15 18 9"/></svg>View Analysis</button>'
    +'<button class="saved-delete-btn" onclick="savedDeleteItem(\''+item.id+'\')">'
    +'<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/></svg>Delete</button>'
    +'<span style="margin-left:auto;font-family:\'JetBrains Mono\',monospace;font-size:.54rem;color:var(--muted);opacity:.5">'+new Date(item.savedAt).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})+'</span>'
    +'</div></div>';
}

/* ── Render ──────────────────────────────────────────────── */
function renderSaved(){
  var all   = loadItems();
  var grid  = document.getElementById('saved-grid');
  var empty = document.getElementById('saved-empty');
  if(!grid) return;

  updateCounts(all);

  var filtered = all.filter(function(item){
    if(activeTab !== 'all' && item.type !== activeTab) return false;
    if(searchQuery){
      var hay = [item.title||'',item.subtitle||'',item.body||'',item.markdown||'',item.summaryTitle||'',item.summaryText||''].join(' ').toLowerCase();
      if(hay.indexOf(searchQuery) === -1) return false;
    }
    return true;
  });

  if(!filtered.length){
    grid.style.display  = 'none';
    empty.style.display = 'block';
    return;
  }

  grid.style.display  = 'block';
  empty.style.display = 'none';
  grid.innerHTML = filtered.map(function(item){
    if(item.type === 'resume') return buildResumeCard(item);
    if(item.type === 'story')  return buildStoryCard(item);
    if(item.type === 'shadow') return buildShadowCard(item);
    return '';
  }).join('');
}

/* ── Init ────────────────────────────────────────────────── */
renderSaved();
document.addEventListener('pageChanged', function(e){
  if(e.detail === 'saved') renderSaved();
});

})();
</script>
@endpush