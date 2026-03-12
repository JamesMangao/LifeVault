{{--
  ═══════════════════════════════════════════════════════════════════
  JOURNAL EXPAND OVERLAY — SELF-CONTAINED (Nuclear Option)
  ───────────────────────────────────────────────────────────────────
  • Injects its own <style> tag — does NOT depend on app.css
  • Creates its own DOM via JS — no stale HTML structure issues
  • All specificity fights won with IDs + !important on color only
  • Drop anywhere before </body>. Replace ALL previous overlay HTML.
  • Exposes: window.openExpandedJournal(id), window.closeJournalExpand()
  ═══════════════════════════════════════════════════════════════════
--}}

<style id="jx-overlay-styles">

  /* ── Keyframes ─────────────────────────────────────────── */
  @keyframes jxOverlayIn {
    from { opacity: 0; }
    to   { opacity: 1; }
  }
  @keyframes jxCardIn {
    from { opacity: 0; transform: translateY(28px) scale(.95); }
    to   { opacity: 1; transform: translateY(0)    scale(1);   }
  }

  /* ── Overlay backdrop ──────────────────────────────────── */
  #jx-overlay {
    position:         fixed         !important;
    inset:            0             !important;
    z-index:          99999         !important;
    display:          none          !important;
    align-items:      center        !important;
    justify-content:  center        !important;
    padding:          24px          !important;
    cursor:           pointer       !important;
    box-sizing:       border-box    !important;
  }
  #jx-overlay.jx-open {
    display:          flex                             !important;
    background:       rgba(5, 8, 16, .93)             !important;
    backdrop-filter:  blur(22px) saturate(1.5)        !important;
    -webkit-backdrop-filter: blur(22px) saturate(1.5) !important;
    animation:        jxOverlayIn .22s ease both      !important;
  }

  /* ── Card container ────────────────────────────────────── */
  #jx-card {
    position:         relative                       !important;
    background:       #0d1117                        !important;
    border:           1px solid rgba(255,255,255,.1) !important;
    border-radius:    22px                           !important;
    max-width:        700px                          !important;
    width:            100%                           !important;
    max-height:       90vh                           !important;
    display:          flex                           !important;
    flex-direction:   column                         !important;
    overflow:         hidden                         !important;
    cursor:           default                        !important;
    box-sizing:       border-box                     !important;
    box-shadow:
      0 0 0 1px rgba(255,255,255,.04),
      0 40px 100px rgba(0,0,0,.85),
      0 0 80px rgba(79,142,247,.07)                  !important;
    animation:        jxCardIn .38s cubic-bezier(.22,1.4,.55,1) both !important;
  }

  /* shimmer line at top of card */
  #jx-card::before {
    content:    ''                                                                   !important;
    position:   absolute                                                             !important;
    top:        0                                                                    !important;
    left:       0                                                                    !important;
    right:      0                                                                    !important;
    height:     1px                                                                  !important;
    z-index:    1                                                                    !important;
    background: linear-gradient(90deg,
      transparent,
      rgba(79,142,247,.65) 30%,
      rgba(167,139,250,.75) 65%,
      transparent)                                                                   !important;
  }

  /* ── Header ────────────────────────────────────────────── */
  #jx-head {
    padding:          26px 28px 20px                             !important;
    border-bottom:    1px solid rgba(255,255,255,.06)            !important;
    display:          flex                                        !important;
    align-items:      flex-start                                  !important;
    justify-content:  space-between                              !important;
    gap:              16px                                        !important;
    flex-shrink:      0                                          !important;
    background:       linear-gradient(155deg,
                        rgba(79,142,247,.055) 0%,
                        transparent 60%)                         !important;
    box-sizing:       border-box                                  !important;
  }
  #jx-head-left {
    flex:             1         !important;
    min-width:        0         !important;
  }
  #jx-eyebrow {
    display:          flex             !important;
    align-items:      center           !important;
    gap:              10px             !important;
    margin-bottom:    11px             !important;
  }
  #jx-mood {
    font-size:        .95rem                         !important;
    width:            32px                           !important;
    height:           32px                           !important;
    display:          inline-flex                    !important;
    align-items:      center                         !important;
    justify-content:  center                         !important;
    border-radius:    9px                            !important;
    background:       rgba(255,255,255,.05)          !important;
    border:           1px solid rgba(255,255,255,.09)!important;
    flex-shrink:      0                              !important;
  }
  #jx-date {
    font-family:      'JetBrains Mono', monospace    !important;
    font-size:        .6rem                          !important;
    font-weight:      500                            !important;
    letter-spacing:   .06em                          !important;
    text-transform:   uppercase                      !important;
    color:            rgba(174,184,210,.5)           !important;
  }
  #jx-title {
    font-family:      'Syne', sans-serif             !important;
    font-size:        1.45rem                        !important;
    font-weight:      800                            !important;
    letter-spacing:   -.025em                        !important;
    line-height:      1.2                            !important;
    color:            rgba(232,234,240,.97)          !important;
    margin:           0                              !important;
    word-break:       break-word                     !important;
  }
  #jx-close-btn {
    flex-shrink:      0                              !important;
    width:            32px                           !important;
    height:           32px                           !important;
    min-width:        32px                           !important;
    border-radius:    9px                            !important;
    border:           1px solid rgba(255,255,255,.09)!important;
    background:       rgba(255,255,255,.04)          !important;
    color:            rgba(174,184,210,.45)          !important;
    cursor:           pointer                        !important;
    display:          flex                           !important;
    align-items:      center                         !important;
    justify-content:  center                         !important;
    font-size:        .85rem                         !important;
    margin-top:       2px                            !important;
    transition:       all .18s ease                  !important;
    line-height:      1                              !important;
  }
  #jx-close-btn:hover {
    background:       rgba(248,113,113,.1)           !important;
    border-color:     rgba(248,113,113,.35)          !important;
    color:            #f87171                        !important;
    transform:        rotate(90deg)                  !important;
  }

  /* ── Body ──────────────────────────────────────────────── */
  #jx-body {
    flex:             1                              !important;
    overflow-y:       auto                           !important;
    padding:          28px 30px                      !important;
    box-sizing:       border-box                     !important;
    scrollbar-width:  thin                           !important;
    scrollbar-color:  rgba(255,255,255,.07) transparent !important;
  }
  #jx-body::-webkit-scrollbar          { width: 4px; }
  #jx-body::-webkit-scrollbar-track    { background: transparent; }
  #jx-body::-webkit-scrollbar-thumb    { background: rgba(255,255,255,.08); border-radius: 2px; }

  /* Content text — explicit rgba beats .page * { color: var(--text) !important } */
  #jx-content {
    font-family:      'Newsreader', Georgia, serif   !important;
    font-size:        1.02rem                        !important;
    line-height:      1.9                            !important;
    font-weight:      300                            !important;
    white-space:      pre-wrap                       !important;
    word-break:       break-word                     !important;
    margin:           0                              !important;
    color:            rgba(232,234,240,.76)          !important;
  }

  /* ── Photos ────────────────────────────────────────────── */
  #jx-photos {
    display:                  grid                              !important;
    grid-template-columns:    repeat(auto-fill, minmax(130px,1fr)) !important;
    gap:                      10px                             !important;
    margin-top:               24px                             !important;
  }
  #jx-photos img {
    width:            100%                           !important;
    height:           130px                          !important;
    border-radius:    12px                           !important;
    object-fit:       cover                          !important;
    border:           1px solid rgba(255,255,255,.07)!important;
    cursor:           pointer                        !important;
    display:          block                          !important;
    transition:       transform .2s, box-shadow .2s, border-color .2s !important;
  }
  #jx-photos img:hover {
    transform:        scale(1.04)                                       !important;
    box-shadow:       0 8px 28px rgba(0,0,0,.5)                         !important;
    border-color:     rgba(79,142,247,.45)                              !important;
  }

  /* ── Tags ──────────────────────────────────────────────── */
  #jx-tags {
    display:          flex                           !important;
    gap:              6px                            !important;
    flex-wrap:        wrap                           !important;
    margin-top:       20px                           !important;
    padding-top:      16px                           !important;
    border-top:       1px solid rgba(255,255,255,.05)!important;
  }
  #jx-tags span {
    font-family:      'JetBrains Mono', monospace   !important;
    font-size:        .57rem                         !important;
    font-weight:      600                            !important;
    letter-spacing:   .1em                           !important;
    text-transform:   uppercase                      !important;
    padding:          4px 10px                       !important;
    border-radius:    6px                            !important;
    background:       rgba(79,142,247,.08)           !important;
    border:           1px solid rgba(79,142,247,.2)  !important;
    color:            rgba(79,142,247,.9)            !important;
  }

  /* ── Footer ────────────────────────────────────────────── */
  #jx-footer {
    flex-shrink:      0                              !important;
    padding:          14px 28px                      !important;
    border-top:       1px solid rgba(255,255,255,.05)!important;
    display:          flex                           !important;
    align-items:      center                         !important;
    gap:              8px                            !important;
    background:       rgba(0,0,0,.3)                 !important;
    box-sizing:       border-box                     !important;
  }
  #jx-wc {
    font-family:      'JetBrains Mono', monospace    !important;
    font-size:        .57rem                         !important;
    color:            rgba(174,184,210,.28)           !important;
    padding:          4px 10px                       !important;
    border-radius:    6px                            !important;
    border:           1px solid rgba(255,255,255,.05)!important;
    margin-right:     auto                           !important;
  }
  #jx-edit-btn {
    font-family:      'Syne', sans-serif             !important;
    font-size:        .78rem                         !important;
    font-weight:      700                            !important;
    padding:          8px 18px                       !important;
    border-radius:    10px                           !important;
    border:           1px solid rgba(79,142,247,.3)  !important;
    background:       rgba(79,142,247,.1)            !important;
    color:            #7ab4ff                        !important;
    cursor:           pointer                        !important;
    display:          inline-flex                    !important;
    align-items:      center                         !important;
    gap:              7px                            !important;
    line-height:      1                              !important;
    transition:       all .18s                       !important;
  }
  #jx-edit-btn:hover {
    background:       rgba(79,142,247,.2)            !important;
    border-color:     rgba(79,142,247,.55)           !important;
    color:            #b8d4ff                        !important;
    transform:        translateY(-1px)               !important;
    box-shadow:       0 4px 16px rgba(79,142,247,.18)!important;
  }
  #jx-del-btn {
    font-family:      'Syne', sans-serif             !important;
    font-size:        .78rem                         !important;
    font-weight:      600                            !important;
    padding:          8px 18px                       !important;
    border-radius:    10px                           !important;
    border:           1px solid rgba(255,255,255,.06)!important;
    background:       transparent                    !important;
    color:            rgba(174,184,210,.3)           !important;
    cursor:           pointer                        !important;
    display:          inline-flex                    !important;
    align-items:      center                         !important;
    gap:              7px                            !important;
    line-height:      1                              !important;
    transition:       all .18s                       !important;
  }
  #jx-del-btn:hover {
    background:       rgba(248,113,113,.09)          !important;
    border-color:     rgba(248,113,113,.3)           !important;
    color:            #f87171                        !important;
  }

  /* ── Mobile ────────────────────────────────────────────── */
  @media (max-width: 640px) {
    #jx-overlay { padding: 10px !important; }
    #jx-head    { padding: 18px 18px 14px !important; }
    #jx-body    { padding: 18px !important; }
    #jx-footer  { padding: 12px 18px !important; flex-wrap: wrap !important; }
    #jx-title   { font-size: 1.1rem !important; }
    #jx-photos img { height: 100px !important; }
  }

</style>

{{-- The overlay DOM — built once, reused on every open --}}
<div id="jx-overlay">
  <div id="jx-card">

    {{-- Header --}}
    <div id="jx-head">
      <div id="jx-head-left">
        <div id="jx-eyebrow">
          <span id="jx-mood"></span>
          <span id="jx-date"></span>
        </div>
        <h2 id="jx-title"></h2>
      </div>
      <button id="jx-close-btn" title="Close (Esc)">✕</button>
    </div>

    {{-- Body --}}
    <div id="jx-body">
      <p  id="jx-content"></p>
      <div id="jx-photos" style="display:none"></div>
      <div id="jx-tags"   style="display:none"></div>
    </div>

    {{-- Footer --}}
    <div id="jx-footer">
      <button id="jx-edit-btn">✎ Edit Entry</button>
      <span   id="jx-wc"></span>
      <button id="jx-del-btn">🗑 Delete</button>
    </div>

  </div>
</div>

<script>
(function () {
  'use strict';

  /* ── helpers ────────────────────────────────────────────── */
  function fmt(d) {
    if (!d) return '';
    try {
      return new Date(d).toLocaleDateString('en-US', {
        weekday: 'long', month: 'long', day: 'numeric',
        year: 'numeric', hour: '2-digit', minute: '2-digit'
      });
    } catch (e) { return String(d); }
  }
  function esc(s) {
    return String(s || '')
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function wordCount(t) {
    return t ? t.trim().split(/\s+/).filter(Boolean).length : 0;
  }
  function el(id) { return document.getElementById(id); }

  /* ── open ───────────────────────────────────────────────── */
  window.openExpandedJournal = function (id) {
    var journals = window.journals || [];
    var entry = journals.find(function (j) { return j.id === id; });
    if (!entry) {
      console.warn('[jx-overlay] No journal found with id:', id);
      return;
    }

    window._expandedJournalId = id;

    /* title */
    el('jx-title').textContent = entry.title || 'Untitled';

    /* date */
    el('jx-date').textContent = fmt(entry.createdAt || entry.created_at || entry.date || '');

    /* mood */
    var moodEl = el('jx-mood');
    if (entry.moodEmoji || entry.mood_emoji) {
      moodEl.textContent    = entry.moodEmoji || entry.mood_emoji;
      moodEl.style.display  = 'inline-flex';
    } else {
      moodEl.textContent    = '';
      moodEl.style.display  = 'none';
    }

    /* content */
    var text = entry.content || entry.body || '';
    el('jx-content').textContent = text;

    /* word count */
    var n = wordCount(text);
    el('jx-wc').textContent = n + (n === 1 ? ' word' : ' words');

    /* photos */
    var photosEl  = el('jx-photos');
    var photoUrls = entry.photoUrls || entry.photo_urls || entry.photos || [];
    if (photoUrls.length) {
      photosEl.style.display = 'grid';
      photosEl.innerHTML = photoUrls.map(function (u) {
        var s = esc(u);
        return '<img src="' + s + '" loading="lazy" '
          + 'onclick="(typeof viewPhoto===\'function\'?viewPhoto:window.open)(\''+s+'\')"'
          + ' alt="Journal photo">';
      }).join('');
    } else {
      photosEl.style.display = 'none';
      photosEl.innerHTML     = '';
    }

    /* tags */
    var tagsEl  = el('jx-tags');
    var tags    = entry.tags || [];
    if (tags.length) {
      tagsEl.style.display = 'flex';
      tagsEl.innerHTML = tags.map(function (t) {
        return '<span>' + esc(t) + '</span>';
      }).join('');
    } else {
      tagsEl.style.display = 'none';
      tagsEl.innerHTML     = '';
    }

    /* buttons */
    el('jx-edit-btn').onclick = function () {
      closeJournalExpand();
      if (typeof openJournalModal === 'function') openJournalModal(id);
      else if (typeof editJournal  === 'function') editJournal(id);
    };
    el('jx-del-btn').onclick = function () {
      closeJournalExpand();
      if (typeof delJournal    === 'function') delJournal(id);
      else if (typeof deleteJournal === 'function') deleteJournal(id);
    };

    /* show */
    el('jx-overlay').classList.add('jx-open');
    document.body.style.overflow = 'hidden';
    el('jx-body').scrollTop      = 0;
  };

  /* ── close ──────────────────────────────────────────────── */
  window.closeJournalExpand = function () {
    var overlay = el('jx-overlay');
    if (!overlay || !overlay.classList.contains('jx-open')) return;
    overlay.style.opacity    = '0';
    overlay.style.transition = 'opacity .17s ease';
    setTimeout(function () {
      overlay.classList.remove('jx-open');
      overlay.style.opacity    = '';
      overlay.style.transition = '';
      document.body.style.overflow = '';
      window._expandedJournalId    = null;
    }, 170);
  };

  /* ── aliases — keep any existing callers working ────────── */
  window.openJournalExpand      = window.openExpandedJournal;
  window.closeExpandedJournal   = window.closeJournalExpand;

  /* ── backdrop click ─────────────────────────────────────── */
  el('jx-overlay').addEventListener('click', function (e) {
    if (e.target === el('jx-overlay')) window.closeJournalExpand();
  });
  el('jx-close-btn').addEventListener('click', window.closeJournalExpand);

  /* ── escape key ─────────────────────────────────────────── */
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    var overlay = el('jx-overlay');
    if (overlay && overlay.classList.contains('jx-open')) {
      e.stopPropagation();
      window.closeJournalExpand();
    }
  });

})();
</script>