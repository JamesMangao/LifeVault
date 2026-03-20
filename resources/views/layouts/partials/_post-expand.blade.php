<!DOCTYPE html>
<!--
  ═══════════════════════════════════════════════════════════════════
  POST EXPAND OVERLAY for EXPLORE PAGE (GUEST-FRIENDLY)
  ───────────────────────────────────────────────────────────────────
  • View-only (no auth actions: likes/comments/edit/delete)
  • Self-contained: own styles + JS, no app.css/app.js deps
  • Mobile-first responsive
  • Long bodies auto-truncate with "Read more"
  • Photos zoomable
  • Exposes: window.openExpandedPost(id), window.closeExpandedPost()
  • Drop before </body> in explore.blade.php
  ═══════════════════════════════════════════════════════════════════
-->

<style id="gexp-styles">

  /* ── Keyframes ─────────────────────────────────────────── */
  @keyframes gexpOverlayIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes gexpCardIn { 
    from { opacity: 0; transform: translateY(28px) scale(.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* ── Overlay ───────────────────────────────────────────── */
  #gexp-overlay {
    position: fixed !important; inset: 0 !important; z-index: 99998 !important;
    display: none !important; align-items: center; justify-content: center;
    padding: 20px !important; cursor: pointer; box-sizing: border-box !important;
  }
  #gexp-overlay.gexp-open {
    display: flex !important; background: rgba(5,6,15,.94) !important;
    backdrop-filter: blur(24px) !important; animation: gexpOverlayIn .25s ease !important;
  }

  /* ── Card ───────────────────────────────────────────────── */
  #gexp-card {
    position: relative !important; background: rgba(13,17,23,.98) !important;
    border: 1px solid rgba(255,255,255,.08) !important; border-radius: 24px !important;
    max-width: 650px !important; width: 100% !important; max-height: 92vh !important;
    display: flex; flex-direction: column; overflow: hidden !important;
    cursor: default !important; box-shadow: 
      0 0 0 1px rgba(255,255,255,.06),
      0 0 60px rgba(0,0,0,.9),
      inset 0 1px 0 rgba(255,255,255,.03) !important;
    animation: gexpCardIn .4s cubic-bezier(.22,1,.36,1) !important;
  }
  #gexp-card::before {
    content: &#39;&#39;; position: absolute; top: 0; left: 0; right: 0;
    height: 1px; z-index: 1; background: linear-gradient(90deg,
      transparent, rgba(79,142,247,.7) 35%, rgba(167,139,250,.8) 65%, transparent);
  }

  /* ── Header ─────────────────────────────────────────────── */
  #gexp-head {
    padding: 28px 30px 22px !important; border-bottom: 1px solid rgba(255,255,255,.06) !important;
    display: flex; align-items: flex-start; justify-content: space-between; gap: 18px;
    background: linear-gradient(155deg, rgba(79,142,247,.06) 0%, transparent 70%);
  }
  #gexp-head-left { flex: 1; min-width: 0; }
  #gexp-author-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
  #gexp-avatar {
    width: 44px !important; height: 44px !important; border-radius: 50% !important;
    object-fit: cover !important; border: 2px solid rgba(255,255,255,.15) !important;
    flex-shrink: 0 !important;
  }
  #gexp-author-name {
    font-family: &#39;Syne&#39;, sans-serif !important; font-size: 1.1rem !important; font-weight: 800 !important;
    color: rgba(232,234,240,.97) !important; cursor: pointer;
  }
  #gexp-author-handle {
    font-family: &#39;JetBrains Mono&#39;, monospace !important; font-size: .7rem !important; color: rgba(174,184,210,.6) !important;
  }
  #gexp-badge {
    font-size: .75rem !important; font-weight: 700 !important; padding: 4px 10px !important;
    border-radius: 20px !important; background: rgba(79,142,247,.12) !important;
    border: 1px solid rgba(79,142,247,.3) !important; color: #7ab4ff !important;
  }
  #gexp-time {
    font-family: &#39;JetBrains Mono&#39;, monospace !important; font-size: .65rem !important;
    color: rgba(174,184,210,.4) !important; letter-spacing: .08em !important; text-transform: uppercase !important;
  }
  #gexp-close { 
    width: 36px !important; height: 36px !important; border-radius: 10px !important;
    border: 1px solid rgba(255,255,255,.08) !important; background: rgba(255,255,255,.03) !important;
    color: rgba(174,184,210,.4) !important; cursor: pointer; font-size: 1rem !important;
    display: flex; align-items: center; justify-content: center; transition: all .2s !important;
  }
  #gexp-close:hover {
    background: rgba(248,113,113,.12) !important; color: #f87171 !important;
    transform: rotate(90deg) scale(1.05) !important;
  }

  /* ── Body ───────────────────────────────────────────────── */
  #gexp-body {
    flex: 1 !important; overflow-y: auto !important; padding: 30px !important;
    scrollbar-width: thin !important; scrollbar-color: rgba(255,255,255,.1) transparent !important;
  }
  #gexp-body::-webkit-scrollbar { width: 5px; }
  #gexp-body::-webkit-scrollbar-track { background: transparent; }
  #gexp-body::-webkit-scrollbar-thumb { 
    background: rgba(255,255,255,.12) !important; border-radius: 3px !important;
  }
  #gexp-title {
    font-family: &#39;Syne&#39;, sans-serif !important; font-size: 1.4rem !important; font-weight: 800 !important;
    line-height: 1.25 !important; margin-bottom: 18px !important; color: rgba(232,234,240,.97) !important;
  }
  #gexp-body-text {
    font-family: &#39;Newsreader&#39;, serif !important; font-size: 1.05rem !important; line-height: 1.85 !important;
    color: rgba(232,234,240,.82) !important; white-space: pre-wrap !important; word-break: break-word !important;
    font-weight: 300 !important;
  }
  #gexp-read-more {
    font-family: &#39;JetBrains Mono&#39;, monospace !important; font-size: .7rem !important; color: #7ab4ff !important;
    cursor: pointer !important; margin-top: 8px !important; display: inline-block !important;
    padding: 4px 12px !important; border-radius: 6px !important;
    background: rgba(79,142,247,.08) !important; transition: all .2s !important;
  }
  #gexp-read-more:hover { background: rgba(79,142,247,.15) !important; transform: translateX(4px) !important; }
  #gexp-photos {
    display: grid !important; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)) !important;
    gap: 12px !important; margin-top: 24px !important;
  }
  #gexp-photos img {
    width: 100% !important; height: 140px !important; border-radius: 14px !important;
    object-fit: cover !important; border: 1px solid rgba(255,255,255,.08) !important;
    cursor: pointer !important; transition: all .25s !important;
  }
  #gexp-photos img:hover {
    transform: scale(1.05) !important; border-color: rgba(79,142,247,.4) !important;
    box-shadow: 0 12px 32px rgba(0,0,0,.6) !important;
  }
  #gexp-tags {
    display: flex !important; gap: 8px !important; flex-wrap: wrap !important; margin-top: 22px !important;
    padding-top: 18px !important; border-top: 1px solid rgba(255,255,255,.05) !important;
  }
  #gexp-tags span {
    font-family: &#39;JetBrains Mono&#39;, monospace !important; font-size: .65rem !important; font-weight: 700 !important;
    letter-spacing: .12em !important; text-transform: uppercase !important;
    padding: 6px 12px !important; border-radius: 8px !important;
    background: rgba(79,142,247,.1) !important; border: 1px solid rgba(79,142,247,.25) !important;
    color: rgba(79,142,247,.95) !important;
  }
  #gexp-goal-bar {
    height: 8px !important; background: rgba(255,255,255,.06) !important; border-radius: 4px !important;
    overflow: hidden !important; margin: 12px 0 !important;
  }
  #gexp-goal-fill {
    height: 100% !important; background: linear-gradient(90deg, #4f8ef7, #a78bfa) !important;
    border-radius: 4px !important; transition: width .4s ease !important;
  }

  /* ── Footer (view stats only) ───────────────────────────── */
  #gexp-footer {
    padding: 18px 30px 24px !important; border-top: 1px solid rgba(255,255,255,.05) !important;
    display: flex !important; align-items: center !important; gap: 12px !important;
    background: rgba(0,0,0,.25) !important; font-family: &#39;JetBrains Mono&#39;, monospace !important;
    font-size: .68rem !important; color: rgba(174,184,210,.35) !important;
  }

  /* ── Mobile ─────────────────────────────────────────────── */
  @media (max-width: 768px) {
    #gexp-overlay { padding: 12px !important; }
    #gexp-head, #gexp-body, #gexp-footer { padding-left: 20px !important; padding-right: 20px !important; }
    #gexp-title { font-size: 1.2rem !important; }
    #gexp-photos { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)) !important; }
    #gexp-photos img { height: 110px !important; }
  }
  @media (max-width: 480px) {
    #gexp-photos { grid-template-columns: repeat(2, 1fr) !important; }
  }

</style>

<!-- Overlay DOM -->
<div id="gexp-overlay">
  <div id="gexp-card">
    <!-- Header -->
    <div id="gexp-head">
      <div id="gexp-head-left">
        <div id="gexp-author-row"></div>
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
          <span id="gexp-time"></span>
        </div>
      </div>
      <button id="gexp-close" title="Close (Esc)">✕</button>
    </div>

    <!-- Body -->
    <div id="gexp-body">
      <div id="gexp-title"></div>
      <div id="gexp-body-text"></div>
      <span id="gexp-read-more" style="display: none;">Read more ↓</span>
      <div id="gexp-photos" style="display: none;"></div>
      <div id="gexp-goal-bar" style="display: none;">
        <div id="gexp-goal-fill"></div>
      </div>
      <div id="gexp-tags" style="display: none;"></div>
    </div>

    <!-- Footer -->
    <div id="gexp-footer">
      👁️ <span id="gexp-view-count">Viewing</span>
    </div>
  </div>
</div>

<script>
(function() {
  &#39;use strict&#39;;

  // Reuse relativeTime from explore page
  window.relativeTime = window.relativeTime || function(ts) {
    if(!ts) return &#39;just now&#39;;
    const s = Math.floor((Date.now() - new Date(ts).getTime()) / 1000);
    if(s < 60) return &#39;just now&#39;;
    if(s < 3600) return Math.floor(s/60) + &#39;m ago&#39;;
    if(s < 86400) return Math.floor(s/3600) + &#39;h ago&#39;;
    return Math.floor(s/86400) + &#39;d ago&#39;;
  };

  function el(id) { return document.getElementById(id); }
  function esc(s) { 
    const d = document.createElement(&#39;div&#39;); 
    d.textContent = s; 
    return d.innerHTML; 
  }

  // viewPhoto fallback
  window.viewPhoto = window.viewPhoto || function(url) {
    const modal = document.createElement(&#39;div&#39;);
    modal.style.cssText = &#39;position:fixed;inset:0;background:rgba(0,0,0,.95);z-index:99999;display:flex;align-items:center;justify-content:center;cursor:pointer;padding:20px;&#39;;
    modal.innerHTML = `<img src="${esc(url)}" style="max-width:95%;max-height:95%;border-radius:12px;"><button style="position:absolute;top:24px;right:28px;background:none;border:none;color:white;font-size:2.5rem;cursor:pointer;">×</button>`;
    modal.onclick = e => { if(e.target === modal) modal.remove(); };
    modal.lastElementChild.onclick = () => modal.remove();
    document.body.appendChild(modal);
  };

  window.openExpandedPost = function(postId) {
    const posts = window.explorePosts || []; // assume global from explore
    const post = posts.find(p => p.id === postId);
    if(!post) {
      console.warn(&#39;[gexp] Post not found:&#39;, postId);
      return;
    }

    window._gexpPostId = postId;

    // Header
    el(&#39;gexp-avatar&#39;).src = post.authorAvatar || &#39;https://ui-avatars.com/api/?name=U&amp;background=4f8ef7&amp;color=fff&#39;;
    el(&#39;gexp-time&#39;).textContent = relativeTime(post.createdAt);
    
    const handle = post.authorUsername || (post.authorName || &#39;user&#39;).toLowerCase().replace(/[^a-z0-9_]/g, &#39;&#39;);
    el(&#39;gexp-author-row&#39;).innerHTML = 
      `<span style="display: flex; align-items: center; gap: 8px;">
        <span style="font-weight: 700; font-size: .95rem;">${esc(post.authorName || &#39;Anonymous&#39;)}</span>
        <span style="font-family: \'JetBrains Mono\', monospace; font-size: .68rem; opacity: .6;">@${handle}</span>
       </span>
       <span id="gexp-badge">${post.type?.charAt(0).toUpperCase() + post.type?.slice(1) || &#39;Post&#39;}</span>`;

    // Body
    if(post.title) el(&#39;gexp-title&#39;).textContent = post.title;
    else el(&#39;gexp-title&#39;).style.display = &#39;none&#39;;

    let bodyText = post.body || &#39;&#39;;
    const isLong = bodyText.length > 400;
    el(&#39;gexp-body-text&#39;).innerHTML = isLong 
      ? esc(bodyText.slice(0, 400)) + &#39;...&#39;
      : esc(bodyText);

    // Read more toggle
    const readMore = el(&#39;gexp-read-more&#39;);
    if(isLong) {
      readMore.style.display = &#39;inline-block&#39;;
      readMore.onclick = function(e) {
        e.stopPropagation();
        el(&#39;gexp-body-text&#39;).innerHTML = esc(bodyText);
        readMore.style.display = &#39;none&#39;;
      };
    } else readMore.style.display = &#39;none&#39;;

    // Goal progress
    const goalBar = el(&#39;gexp-goal-bar&#39;);
    if(post.type === &#39;goal&#39; &amp;&amp; post.progress) {
      goalBar.style.display = &#39;block&#39;;
      el(&#39;gexp-goal-fill&#39;).style.width = post.progress + &#39;%&#39;;
    } else goalBar.style.display = &#39;none&#39;;

    // Photos
    const photosDiv = el(&#39;gexp-photos&#39;);
    const photos = post.photoUrls || [];
    if(photos.length) {
      photosDiv.style.display = &#39;grid&#39;;
      photosDiv.innerHTML = photos.map(u => 
        `<img src="${esc(u)}" loading="lazy" onclick="viewPhoto('${esc(u)}')">`
      ).join(&#39;&#39;);
    } else photosDiv.style.display = &#39;none&#39;;

    // Tags
    const tagsDiv = el(&#39;gexp-tags&#39;);
    const tags = post.tags || [];
    if(tags.length) {
      tagsDiv.style.display = &#39;flex&#39;;
      tagsDiv.innerHTML = tags.map(t => `<span>${esc(t)}</span>`).join(&#39;&#39;);
    } else tagsDiv.style.display = &#39;none&#39;;

    // Open
    el(&#39;gexp-overlay&#39;).classList.add(&#39;gexp-open&#39;);
    document.body.style.overflow = &#39;hidden&#39;;
    el(&#39;gexp-body&#39;).scrollTop = 0;
  };

  window.closeExpandedPost = function() {
    const overlay = el(&#39;gexp-overlay&#39;);
    if(!overlay?.classList.contains(&#39;gexp-open&#39;)) return;
    overlay.style.opacity = &#39;0&#39;;
    setTimeout(() => {
      overlay.classList.remove(&#39;gexp-open&#39;);
      document.body.style.overflow = &#39;&#39;;
    }, 200);
  };

  // Event listeners
  el(&#39;gexp-overlay&#39;).onclick = e => { if(e.target === el(&#39;gexp-overlay&#39;)) closeExpandedPost(); };
  el(&#39;gexp-close&#39;).onclick = closeExpandedPost;
  
  document.addEventListener(&#39;keydown&#39;, e => {
    if(e.key === &#39;Escape&#39;) closeExpandedPost();
  });
})();
</script>

