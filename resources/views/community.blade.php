{{-- LIFEVAULT — community.blade.php --}}
<style>
:root{--bg:#0b0f1a;--surface:#111827;--surface2:#1a2235;--border:rgba(255,255,255,.07);--text:#e8eaf0;--muted:#6b7a99;--accent:#4f8ef7;--green:#34d399;--amber:#fbbf24;--rose:#f87171;--lavender:#a78bfa;--teal:#2dd4bf}
@keyframes pageIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
@keyframes heartBeat{0%{transform:scale(1)}40%{transform:scale(1.4)}70%{transform:scale(.9)}100%{transform:scale(1)}}
@keyframes overlayFadeIn{from{opacity:0}to{opacity:1}}
@keyframes expandCardIn{from{opacity:0;transform:scale(.88) translateY(24px)}to{opacity:1;transform:scale(1) translateY(0)}}
@keyframes upmIn{from{opacity:0;transform:scale(.93) translateY(16px)}to{opacity:1;transform:scale(1) translateY(0)}}
.page{display:none}.page.active{display:block;animation:pageIn .3s ease both}
.page-header{margin-bottom:32px;display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px}
.page-title{font-size:2rem;font-weight:800;letter-spacing:-.03em}
.page-subtitle{font-family:'Newsreader',serif;font-style:italic;font-size:.9rem;color:var(--muted);margin-top:4px;font-weight:300}
.btn{font-family:'Syne',sans-serif;font-size:.8rem;font-weight:600;padding:10px 18px;border-radius:10px;border:1px solid var(--border);background:var(--surface2);color:var(--text);cursor:pointer;transition:all .18s;display:inline-flex;align-items:center;gap:6px}
.btn:hover{background:var(--surface);border-color:rgba(79,142,247,.3);color:var(--accent)}
.btn-primary{background:var(--accent);border-color:var(--accent);color:white}
.btn-primary:hover{background:#3a7ae0;border-color:#3a7ae0;color:white}
.community-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px}
.comm-stat{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;text-align:center}
.comm-stat-val{font-size:1.8rem;font-weight:800;letter-spacing:-.04em;line-height:1;margin-bottom:6px}
.comm-stat-label{font-family:'JetBrains Mono',monospace;font-size:.6rem;text-transform:uppercase;letter-spacing:.12em;color:var(--muted)}
.feed-container{max-width:680px;margin:0 auto}
.feed-composer{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;margin-bottom:20px}
.composer-top{display:flex;gap:12px;margin-bottom:14px}
.composer-avatar{width:38px;height:38px;border-radius:50%;object-fit:cover;border:1.5px solid var(--border);flex-shrink:0}
.composer-input{flex:1;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;color:var(--text);font-family:'Newsreader',serif;font-size:.92rem;outline:none;resize:none;transition:border-color .2s;line-height:1.55;font-weight:300}
.composer-input:focus{border-color:var(--accent)}
.composer-input::placeholder{color:var(--muted)}
.composer-img-previews{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
.composer-img-preview{position:relative;width:80px;height:80px}
.composer-img-preview img{width:100%;height:100%;object-fit:cover;border-radius:8px;border:1px solid var(--border)}
.composer-img-remove{position:absolute;top:-6px;right:-6px;background:var(--rose);border:none;color:white;border-radius:50%;width:18px;height:18px;font-size:.6rem;cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:700}
.composer-bottom{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
.composer-actions{display:flex;gap:6px;flex-wrap:wrap}
.composer-type-btn{font-family:'JetBrains Mono',monospace;font-size:.62rem;padding:6px 12px;border-radius:8px;border:1px solid var(--border);background:var(--surface2);color:var(--muted);cursor:pointer;transition:all .18s;text-transform:uppercase;letter-spacing:.06em;display:inline-flex;align-items:center;gap:4px}
.composer-type-btn:hover,.composer-type-btn.active{border-color:var(--accent);color:var(--accent);background:rgba(79,142,247,.08)}
.feed-filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}
.feed-filter-btn{font-family:'JetBrains Mono',monospace;font-size:.62rem;padding:7px 14px;border-radius:99px;border:1px solid var(--border);background:var(--surface2);color:var(--muted);cursor:pointer;transition:all .18s;text-transform:uppercase;letter-spacing:.06em}
.feed-filter-btn:hover,.feed-filter-btn.active{background:rgba(79,142,247,.12);border-color:rgba(79,142,247,.35);color:var(--accent)}
.post-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;margin-bottom:16px;transition:border-color .2s,box-shadow .2s,transform .2s;animation:slideDown .3s ease both;cursor:pointer;user-select:none}
.post-card:hover{border-color:rgba(79,142,247,.3);box-shadow:0 4px 24px rgba(79,142,247,.1);transform:translateY(-2px)}
.post-card:active{transform:translateY(0)}
.post-header{display:flex;align-items:center;gap:12px;margin-bottom:14px}
.post-avatar{width:38px;height:38px;border-radius:50%;object-fit:cover;border:1.5px solid var(--border);flex-shrink:0}
.post-meta{flex:1;min-width:0}
.post-author-btn{background:none;border:none;color:var(--text);cursor:pointer;font-family:'Syne',sans-serif;font-size:.82rem;font-weight:700;padding:0;display:inline-flex;align-items:center;gap:6px;flex-wrap:wrap;transition:color .2s}
.post-author-btn:hover{color:var(--accent)}
.post-author-name{font-weight:700}
.post-author-username{font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);font-weight:400}
.post-type-badge{font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:2px 7px;border-radius:4px;font-weight:400}
.badge-journal{background:rgba(79,142,247,.15);color:var(--accent)}
.badge-task{background:rgba(52,211,153,.15);color:var(--green)}
.badge-goal{background:rgba(167,139,250,.15);color:var(--lavender)}
.post-time{font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)}
.post-title{font-size:.95rem;font-weight:700;letter-spacing:-.01em;margin-bottom:8px;line-height:1.3}
.post-body{font-family:'Newsreader',serif;font-size:.9rem;line-height:1.7;color:rgba(232,234,240,.75);font-weight:300;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden}
.post-read-more{font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--accent);cursor:pointer;margin-top:4px;display:inline-block}
.post-photos{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.post-photo{width:100px;height:100px;border-radius:10px;object-fit:cover;border:1px solid var(--border);cursor:pointer;transition:transform .2s}
.post-photo:hover{transform:scale(1.04);border-color:var(--accent)}
.post-tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}
.post-actions{display:flex;align-items:center;gap:4px;margin-top:16px;padding-top:14px;border-top:1px solid var(--border)}
.post-action-btn{display:flex;align-items:center;gap:6px;padding:7px 12px;border-radius:8px;border:none;background:transparent;color:var(--muted);cursor:pointer;font-family:'Syne',sans-serif;font-size:.75rem;font-weight:600;transition:all .18s}
.post-action-btn:hover{background:var(--surface2);color:var(--text)}
.post-action-btn.liked{color:var(--rose)}
.post-action-btn.liked .heart-icon{animation:heartBeat .4s ease}
.post-action-count{font-family:'JetBrains Mono',monospace;font-size:.68rem}
.post-delete-btn{margin-left:auto;padding:7px 12px;border-radius:8px;border:none;background:transparent;color:var(--muted);cursor:pointer;font-size:.75rem;transition:all .18s}
.post-delete-btn:hover{background:rgba(248,113,113,.1);color:var(--rose)}
.post-goal-bar{margin:10px 0;background:var(--surface2);border-radius:99px;height:6px;overflow:hidden}
.post-goal-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent),var(--lavender))}
.post-goal-meta{display:flex;justify-content:space-between;font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);margin-top:4px}
.tag{font-family:'JetBrains Mono',monospace;font-size:.58rem;padding:2px 8px;border-radius:4px;text-transform:uppercase;letter-spacing:.08em}
.loading-posts{text-align:center;padding:48px;color:var(--muted);font-family:'JetBrains Mono',monospace;font-size:.72rem}
.feed-empty{text-align:center;padding:60px 24px;color:var(--muted)}
.feed-empty .empty-icon{font-size:3rem;margin-bottom:16px}
.feed-empty .empty-title{font-size:1rem;font-weight:700;margin-bottom:8px}
.feed-empty .empty-sub{font-family:'Newsreader',serif;font-style:italic;font-size:.85rem;opacity:.7}

/* ── OVERLAYS: z-index 9000+ so they beat EVERYTHING in the layout ── */
.comm-post-overlay{position:fixed;inset:0;z-index:9000;display:none;align-items:center;justify-content:center;padding:24px;background:rgba(11,15,26,.92)}
.comm-post-overlay.open{display:flex;animation:overlayFadeIn .3s ease both}
.post-expanded-card{background:var(--surface);border:1px solid rgba(79,142,247,.25);border-radius:20px;max-width:720px;width:100%;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 0 80px rgba(79,142,247,.12),0 40px 80px rgba(0,0,0,.6);animation:expandCardIn .38s cubic-bezier(.34,1.4,.64,1) both;overflow:hidden}
.post-expanded-header{padding:20px 24px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;background:linear-gradient(135deg,rgba(79,142,247,.06),rgba(167,139,250,.04))}
.post-expanded-body{padding:24px;overflow-y:auto;flex:1}
.post-expanded-footer{padding:14px 24px;border-top:1px solid var(--border);display:flex;align-items:center;gap:8px;flex-shrink:0;background:var(--surface2)}
.comm-exp-close{background:var(--surface2);border:1px solid var(--border);color:var(--muted);cursor:pointer;font-size:.9rem;width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0}
.comm-exp-close:hover{color:var(--rose);border-color:var(--rose);background:rgba(248,113,113,.08)}
.comm-upm-overlay{position:fixed;inset:0;z-index:9100;background:rgba(11,15,26,.92);display:none;align-items:center;justify-content:center;padding:24px}
.comm-upm-overlay.open{display:flex;animation:overlayFadeIn .25s ease both}
.comm-upm-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;max-width:480px;width:100%;max-height:88vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 0 60px rgba(79,142,247,.1),0 40px 80px rgba(0,0,0,.6);animation:upmIn .35s cubic-bezier(.34,1.4,.64,1) both}
.comm-upm-scrollbody{overflow-y:auto;flex:1}
.comm-upm-cover{height:120px;border-radius:20px 20px 0 0;background:linear-gradient(135deg,#0d1b2a,#1b3a5c);position:relative;flex-shrink:0;overflow:visible}
.comm-upm-close{position:absolute;top:12px;right:12px;background:rgba(11,15,26,.7);border:1px solid rgba(255,255,255,.1);color:var(--muted);border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:.85rem;display:flex;align-items:center;justify-content:center;transition:all .2s;backdrop-filter:blur(4px)}
.comm-upm-close:hover{color:var(--rose);border-color:var(--rose)}
.comm-upm-identity{padding:44px 24px 24px}
.comm-upm-avatar{width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--surface);position:absolute;bottom:-36px;left:24px;background:var(--surface2);z-index:2}
.comm-upm-name{font-size:1.2rem;font-weight:800;margin-top:10px;letter-spacing:-.02em}
.comm-upm-username{font-family:'JetBrains Mono',monospace;font-size:.68rem;color:var(--muted);margin-top:2px}
.comm-upm-bio{font-family:'Newsreader',serif;font-size:.88rem;color:rgba(232,234,240,.7);margin-top:10px;line-height:1.6;font-weight:300}
.comm-upm-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:18px}
.comm-upm-stat{background:var(--surface2);border-radius:10px;padding:12px;text-align:center;border:1px solid var(--border)}
.comm-upm-stat-val{font-size:1.4rem;font-weight:800;line-height:1;margin-bottom:4px}
.comm-upm-stat-label{font-family:'JetBrains Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)}
.comm-upm-posts{padding:0 24px 24px;border-top:1px solid var(--border);margin-top:4px}
.comm-upm-posts-title{font-family:'JetBrains Mono',monospace;font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);padding:16px 0 12px}
.comm-upm-post-item{padding:10px 12px;background:var(--surface2);border-radius:10px;margin-bottom:8px;cursor:pointer;border:1px solid var(--border);transition:border-color .2s,transform .15s}
.comm-upm-post-item:hover{border-color:rgba(79,142,247,.35);transform:translateX(3px)}
.comm-upm-post-title{font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px}
.comm-upm-post-meta{font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)}
.comm-share-backdrop{position:fixed;inset:0;background:rgba(11,15,26,.92);z-index:9200;display:none;align-items:center;justify-content:center;padding:20px}
.comm-share-backdrop.open{display:flex}
.comm-share-modal{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:32px;max-width:580px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 40px 80px rgba(0,0,0,.5)}
.comm-share-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.comm-share-title{font-size:1.2rem;font-weight:800;letter-spacing:-.02em}
.comm-share-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:1.2rem;line-height:1;transition:color .2s;padding:4px}
.comm-share-close:hover{color:var(--rose)}
.form-group{margin-bottom:16px}
.form-label{display:block;font-family:'JetBrains Mono',monospace;font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:8px}
.comm-form-input,.comm-form-textarea{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;color:var(--text);font-family:'Syne',sans-serif;font-size:.85rem;outline:none;transition:border-color .2s;box-sizing:border-box}
.comm-form-input:focus,.comm-form-textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(79,142,247,.12)}
.comm-form-textarea{resize:vertical;min-height:120px;line-height:1.6;font-family:'Newsreader',serif;font-size:.95rem;font-weight:300}
.comment-item{display:flex;gap:10px;margin-bottom:12px}
.comment-avatar{width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1px solid var(--border)}
.comment-bubble{background:var(--surface2);border-radius:0 10px 10px 10px;padding:10px 14px;flex:1}
.comment-author{font-size:.72rem;font-weight:700;margin-bottom:3px;display:flex;align-items:center;justify-content:space-between}
.comment-text{font-family:'Newsreader',serif;font-size:.82rem;line-height:1.5;color:rgba(232,234,240,.8);font-weight:300}
.comment-del{background:none;border:none;color:var(--muted);cursor:pointer;font-size:.68rem;opacity:0;transition:opacity .2s,color .2s;padding:2px}
.comment-bubble:hover .comment-del{opacity:1}
.comment-del:hover{color:var(--rose)}
.comment-input-row{display:flex;gap:10px;align-items:center;margin-top:10px}
.comment-input{flex:1;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:9px 14px;color:var(--text);font-family:'Newsreader',serif;font-size:.82rem;outline:none;transition:border-color .2s;font-weight:300}
.comment-input:focus{border-color:var(--accent)}
.comment-submit{background:var(--accent);border:none;color:white;padding:9px 14px;border-radius:10px;cursor:pointer;font-size:.75rem;font-weight:700;font-family:'Syne',sans-serif}
@media(max-width:640px){.community-stats{grid-template-columns:1fr}.feed-container{max-width:100%}}
</style>

{{-- PAGE --}}
<div id="page-community" class="page">
  <div class="page-header">
    <div>
      <div class="page-title">Community 🌐</div>
      <div class="page-subtitle">Share your journey · inspire others · grow together</div>
    </div>
    <button class="btn" onclick="commOpenShare()" style="border-color:rgba(45,212,191,.3);color:var(--teal)">↗ Share Something</button>
  </div>
  <div class="community-stats">
    <div class="comm-stat"><div class="comm-stat-val" id="comm-stat-posts" style="color:var(--accent)">—</div><div class="comm-stat-label">Total Posts</div></div>
    <div class="comm-stat"><div class="comm-stat-val" id="comm-stat-members" style="color:var(--teal)">—</div><div class="comm-stat-label">Members</div></div>
    <div class="comm-stat"><div class="comm-stat-val" id="comm-stat-likes" style="color:var(--rose)">—</div><div class="comm-stat-label">Likes Given</div></div>
  </div>
  <div class="feed-container">
    <div class="feed-composer">
      <div class="composer-top">
        <img class="composer-avatar" id="composer-avatar" src="" alt="" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <textarea class="composer-input" id="composer-text" placeholder="Share a thought, update, or inspiration…" rows="3"></textarea>
      </div>
      <div class="composer-img-previews" id="composer-img-previews" style="display:none"></div>
      <div class="composer-bottom">
        <div class="composer-actions">
          <button class="composer-type-btn active" data-type="thought" onclick="setComposerType('thought')">💭 Thought</button>
          <button class="composer-type-btn" data-type="journal" onclick="commOpenShare('journal')">📓 Journal</button>
          <button class="composer-type-btn" data-type="task" onclick="commOpenShare('task')">✅ Task</button>
          <button class="composer-type-btn" data-type="goal" onclick="commOpenShare('goal')">🎯 Goal</button>
          <label class="composer-type-btn" style="cursor:pointer">
            🖼 Photo
            <input type="file" id="composer-photo-input" multiple accept="image/*" style="display:none" onchange="handleComposerPhotos(event)">
          </label>
        </div>
        <button class="btn btn-primary" onclick="postThought()" style="padding:8px 18px">Post</button>
      </div>
    </div>
    <div class="feed-filters">
      <button class="feed-filter-btn active" onclick="filterFeed('all',this)">All</button>
      <button class="feed-filter-btn" onclick="filterFeed('thought',this)">💭 Thoughts</button>
      <button class="feed-filter-btn" onclick="filterFeed('journal',this)">📓 Journals</button>
      <button class="feed-filter-btn" onclick="filterFeed('task',this)">✅ Tasks</button>
      <button class="feed-filter-btn" onclick="filterFeed('goal',this)">🎯 Goals</button>
      <button class="feed-filter-btn" onclick="filterFeed('mine',this)">👤 Mine</button>
    </div>
    <div id="feed-list"><div class="loading-posts">Loading community posts…</div></div>
  </div>
</div>

{{-- EXPANDED POST OVERLAY --}}
<div class="comm-post-overlay" id="comm-post-overlay" onclick="if(event.target===this)commClosePost()">
  <div class="post-expanded-card">
    <div class="post-expanded-header">
      <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
        <img id="comm-exp-avatar" src="" style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:1.5px solid var(--border);flex-shrink:0" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <div style="flex:1;min-width:0">
          <div id="comm-exp-author-row" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap"></div>
          <div id="comm-exp-time" style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted);margin-top:2px"></div>
        </div>
      </div>
      <button class="comm-exp-close" onclick="commClosePost()">✕</button>
    </div>
    <div class="post-expanded-body" id="comm-exp-body"></div>
    <div class="post-expanded-footer" id="comm-exp-footer"></div>
  </div>
</div>

{{-- USER PROFILE MODAL --}}
<div class="comm-upm-overlay" id="comm-upm-overlay" onclick="if(event.target===this)commCloseUpm()">
  <div class="comm-upm-card">
    <div class="comm-upm-cover" id="comm-upm-cover">
      <button class="comm-upm-close" onclick="commCloseUpm()">✕</button>
      <img class="comm-upm-avatar" id="comm-upm-avatar" src="" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
    </div>
    <div class="comm-upm-identity">
      <div class="comm-upm-name" id="comm-upm-name">Loading…</div>
      <div class="comm-upm-username" id="comm-upm-username"></div>
      <div class="comm-upm-bio" id="comm-upm-bio"></div>
      <div class="comm-upm-stats">
        <div class="comm-upm-stat"><div class="comm-upm-stat-val" id="comm-upm-stat-posts" style="color:var(--accent)">—</div><div class="comm-upm-stat-label">Posts</div></div>
        <div class="comm-upm-stat"><div class="comm-upm-stat-val" id="comm-upm-stat-likes" style="color:var(--rose)">—</div><div class="comm-upm-stat-label">Likes</div></div>
        <div class="comm-upm-stat"><div class="comm-upm-stat-val" id="comm-upm-stat-joined" style="font-size:.85rem;color:var(--teal)">—</div><div class="comm-upm-stat-label">Joined</div></div>
      </div>
    </div>
    <div class="comm-upm-posts">
      <div class="comm-upm-posts-title">Recent Posts</div>
      <div id="comm-upm-posts-list"></div>
    </div>
  </div>
</div>

{{-- SHARE MODAL --}}
<div class="comm-share-backdrop" id="comm-share-backdrop" onclick="if(event.target===this)commCloseShare()">
  <div class="comm-share-modal">
    <div class="comm-share-header">
      <div class="comm-share-title" id="comm-share-title">↗ Share Something</div>
      <button class="comm-share-close" onclick="commCloseShare()">✕</button>
    </div>
    <div id="comm-share-body"></div>
  </div>
</div>

<script>
/* ═══════════════════════════════════════════════════════════════
   ALL COMMUNITY FUNCTIONS — unique "comm" prefix to avoid ANY
   conflicts with app.js or other blade files
═══════════════════════════════════════════════════════════════ */

/* ── CLOSE FUNCTIONS: top-level, no IIFE, immediately available ── */
function commClosePost() {
  document.getElementById('comm-post-overlay').classList.remove('open');
  document.body.style.overflow = '';
}
function commCloseUpm() {
  document.getElementById('comm-upm-overlay').classList.remove('open');
  document.body.style.overflow = '';
}
function commCloseShare() {
  document.getElementById('comm-share-backdrop').classList.remove('open');
}

/* expose on window too */
window.commClosePost  = commClosePost;
window.commCloseUpm   = commCloseUpm;
window.commCloseShare = commCloseShare;

/* ── ESC key ── */
document.addEventListener('keydown', function(e) {
  if (e.key !== 'Escape') return;
  if (document.getElementById('comm-upm-overlay').classList.contains('open'))    { commCloseUpm();   return; }
  if (document.getElementById('comm-post-overlay').classList.contains('open'))   { commClosePost();  return; }
  if (document.getElementById('comm-share-backdrop').classList.contains('open')) { commCloseShare(); return; }
});

/* ═══════════════════════════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════════════════════════ */
(function() {
  'use strict';

  const TYPE_BADGES      = {thought:'💭 Thought',journal:'📓 Journal',task:'✅ Task',goal:'🎯 Goal'};
  const TYPE_BADGE_CLASS = {thought:'badge-journal',journal:'badge-journal',task:'badge-task',goal:'badge-goal'};

  function esc(s) {
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }
  function relTime(date) {
    if (!date) return '';
    const diff = Date.now() - new Date(date).getTime(), m = Math.floor(diff/60000);
    if (m < 1) return 'just now';
    if (m < 60) return m+'m ago';
    const h = Math.floor(m/60);
    if (h < 24) return h+'h ago';
    const d = Math.floor(h/24);
    if (d < 7) return d+'d ago';
    return new Date(date).toLocaleDateString('en-US',{month:'short',day:'numeric'});
  }
  function getHandle(p) {
    return p.authorUsername || (p.authorName||'anonymous').toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20) || 'user';
  }
  function waitFB(cb) {
    if (window.db && window.auth && window._fbFS) cb();
    else setTimeout(() => waitFB(cb), 80);
  }
  function myInfo() {
    const cu = window.currentUser, p = window.userProfile||{};
    const authorName     = p.displayName || cu?.displayName || 'Anonymous';
    const authorAvatar   = p.avatarUrl || cu?.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
    const authorUsername = p.username || (authorName.toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user');
    return {authorName, authorAvatar, authorUsername};
  }

  let _filter = 'all', _compType = 'thought', _compPhotos = [];

  /* ══ RENDER POST CARD ══ */
  function renderCard(p) {
    if (!window.currentUser) return '';
    const isOwn  = p.authorId === window.currentUser.uid;
    const liked  = (p.likes||[]).includes(window.currentUser.uid);
    const bc     = TYPE_BADGE_CLASS[p.type] || 'badge-journal';
    const handle = getHandle(p);
    const repostBadge = p.isRepost
      ? `<div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--teal);margin-bottom:10px;display:flex;align-items:center;gap:6px">
           🔁 reposted from <img src="${esc(p.originalAuthorAvatar||'')}" style="width:16px;height:16px;border-radius:50%"> <span>${esc(p.originalAuthorName||'')}</span>
         </div>` : '';
    let body = '';
    if (p.type === 'goal') {
      body = `<div class="post-goal-bar"><div class="post-goal-fill" style="width:${p.progress||0}%"></div></div>
        <div class="post-goal-meta"><span>${p.categoryIcon||'🎯'}</span><span>${p.progress||0}% complete</span></div>
        ${p.body ? `<div class="post-body" style="margin-top:8px">${esc(p.body)}</div>` : ''}`;
    } else if (p.type === 'task') {
      body = `<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
          <span>${p.priorityIcon||'✅'}</span>
          <span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--muted);text-transform:uppercase">${p.priority||''} priority</span>
          ${p.done ? `<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--green)">· Done ✓</span>` : ''}
        </div>${p.body ? `<div class="post-body">${esc(p.body)}</div>` : ''}`;
    } else {
      body = `<div class="post-body" id="pbody-${p.id}">${esc(p.body||'')}</div>
        ${(p.body||'').length>300 ? `<span class="post-read-more" onclick="event.stopPropagation();commToggleRead('${p.id}')">Read more ↓</span>` : ''}
        ${p.moodEmoji ? `<div style="margin-top:8px;font-size:.8rem;color:var(--muted);font-family:'JetBrains Mono',monospace">feeling ${p.moodEmoji}</div>` : ''}
        ${p.photoUrls?.length ? `<div class="post-photos">${p.photoUrls.map(u=>`<img src="${esc(u)}" class="post-photo" onclick="event.stopPropagation();commViewPhoto('${esc(u)}')">`).join('')}</div>` : ''}
        ${p.tags?.length ? `<div class="post-tags">${p.tags.map(t=>`<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}</div>` : ''}`;
    }
    return `<div class="post-card" onclick="commOpenPost('${p.id}')">
      <div class="post-header">
        <img src="${esc(p.authorAvatar||'')}" class="post-avatar" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <div class="post-meta">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
            <button class="post-author-btn" onclick="event.stopPropagation();commOpenUpm('${p.authorId}')">
              <span class="post-author-name">${esc(p.authorName||'Anonymous')}</span>
              <span class="post-author-username">@${esc(handle)}</span>
            </button>
            <span class="post-type-badge ${bc}">${TYPE_BADGES[p.type]||p.type}</span>
            ${isOwn ? `<span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:var(--muted);padding:2px 6px;border-radius:4px;background:var(--surface2)">you</span>` : ''}
          </div>
          <div class="post-time">${relTime(p.createdAt)}</div>
        </div>
        ${isOwn ? `<button class="post-delete-btn" onclick="event.stopPropagation();commDeletePost('${p.id}')">🗑️</button>` : ''}
      </div>
      ${p.title ? `<div class="post-title">${esc(p.title)}</div>` : ''}
      ${repostBadge}${body}
      <div class="post-actions" onclick="event.stopPropagation()">
        <button class="post-action-btn ${liked?'liked':''}" onclick="commToggleLike('${p.id}')">
          <span class="heart-icon">${liked?'❤️':'🤍'}</span>
          <span class="post-action-count">${(p.likes||[]).length||''}</span>
        </button>
        <button class="post-action-btn" onclick="commOpenPost('${p.id}')">
          💬 <span class="post-action-count" id="ccount-${p.id}">${p.commentCount||0}</span>
        </button>
        <button class="post-action-btn" onclick="commRepost('${p.id}')">
          🔁 <span class="post-action-count">${p.repostCount||''}</span>
        </button>
      </div>
    </div>`;
  }
  window.renderPostCard = renderCard;

  /* ══ RENDER FEED ══ */
  window.renderFeed = function() {
    const el = document.getElementById('feed-list');
    if (!el) return;
    const cu = window.currentUser;
    const {authorName:n, authorAvatar:av} = myInfo();
    let posts = (window.feedPosts||[]).map(p => p.authorId===cu?.uid ? {...p,authorName:n,authorAvatar:av} : p);
    if (_filter === 'mine')      posts = posts.filter(p => p.authorId === cu?.uid);
    else if (_filter !== 'all')  posts = posts.filter(p => p.type === _filter);
    if (!posts.length) {
      el.innerHTML = `<div class="feed-empty"><div class="empty-icon">🌱</div><div class="empty-title">Nothing here yet</div>
        <div class="empty-sub">${_filter==='mine'?"You haven't shared anything yet.":'Be the first to post!'}</div></div>`;
      return;
    }
    el.innerHTML = posts.map(p => renderCard(p)).join('');
    const all = window.feedPosts||[];
    const $ = id => document.getElementById(id);
    if ($('comm-stat-posts'))   $('comm-stat-posts').textContent   = all.length;
    if ($('comm-stat-likes'))   $('comm-stat-likes').textContent   = all.reduce((s,p)=>s+(p.likes?.length||0),0);
    if ($('comm-stat-members')) $('comm-stat-members').textContent = new Set(all.map(p=>p.authorId)).size;
  };

  window.filterFeed = function(type, btn) {
    _filter = type;
    document.querySelectorAll('.feed-filter-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    window.renderFeed();
  };

  /* ══ COMPOSER ══ */
  window.setComposerType = function(type) {
    _compType = type;
    document.querySelectorAll('.composer-type-btn').forEach(b => b.classList.toggle('active', b.dataset.type===type));
  };
  window.handleComposerPhotos = function(e) {
    const files = Array.from(e.target.files); if (!files.length) return;
    const prev = document.getElementById('composer-img-previews'); prev.style.display = 'flex';
    files.forEach(file => {
      const r = new FileReader();
      r.onload = ev => {
        _compPhotos.push(ev.target.result);
        const d = document.createElement('div'); d.className = 'composer-img-preview';
        d.innerHTML = `<img src="${ev.target.result}"><button class="composer-img-remove" onclick="commRmPhoto(${_compPhotos.length-1},this.parentElement)">✕</button>`;
        prev.appendChild(d);
      };
      r.readAsDataURL(file);
    });
  };
  window.commRmPhoto = function(i, el) {
    _compPhotos.splice(i,1); el.remove();
    if (!_compPhotos.length) document.getElementById('composer-img-previews').style.display = 'none';
  };
  window.postThought = async function() {
    const text = document.getElementById('composer-text').value.trim();
    if (!text && !_compPhotos.length) { window.toast?.('Write something first!','💭'); return; }
    const cu = window.currentUser; if (!cu) return;
    const {authorName,authorAvatar,authorUsername} = myInfo();
    try {
      const {addDoc,collection,serverTimestamp} = window._fbFS;
      await addDoc(collection(window.db,'community_posts'), {
        type:_compType, body:text, authorId:cu.uid, authorName, authorAvatar, authorUsername,
        likes:[], commentCount:0, repostCount:0, photoUrls:_compPhotos, createdAt:serverTimestamp(),
      });
      document.getElementById('composer-text').value = '';
      _compPhotos = [];
      const prev = document.getElementById('composer-img-previews'); prev.innerHTML=''; prev.style.display='none';
      window.toast?.('Posted! ✨','🌐');
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ══ LIKE / DELETE / REPOST ══ */
  window.commToggleLike = async function(postId) {
    const cu = window.currentUser; if (!cu) return;
    const post = (window.feedPosts||[]).find(p=>p.id===postId); if (!post) return;
    const liked = (post.likes||[]).includes(cu.uid);
    try {
      const {updateDoc,doc,arrayUnion,arrayRemove} = window._fbFS;
      await updateDoc(doc(window.db,'community_posts',postId), {likes: liked ? arrayRemove(cu.uid) : arrayUnion(cu.uid)});
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };
  window.commToggleRead = function(postId) {
    const el = document.getElementById('pbody-'+postId); if (!el) return;
    const exp = el.style.webkitLineClamp === 'unset';
    el.style.webkitLineClamp = exp ? '4' : 'unset';
    el.style.overflow = exp ? 'hidden' : 'visible';
    const btn = el.nextElementSibling;
    if (btn?.classList.contains('post-read-more')) btn.textContent = exp ? 'Read more ↓' : 'Show less ↑';
  };
  window.commViewPhoto = function(url) {
    const ov = document.createElement('div');
    ov.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:99999;display:flex;align-items:center;justify-content:center;cursor:pointer';
    ov.innerHTML = `<img src="${url}" style="max-width:90vw;max-height:90vh;border-radius:12px;object-fit:contain">`;
    ov.onclick = () => ov.remove();
    document.body.appendChild(ov);
  };
  window.commDeletePost = async function(postId) {
    if (!confirm('Delete this post?')) return;
    try {
      const {deleteDoc,doc} = window._fbFS;
      await deleteDoc(doc(window.db,'community_posts',postId));
      window.toast?.('Post deleted','🗑️');
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };
  window.commRepost = async function(postId) {
    const cu = window.currentUser; if (!cu) return;
    const orig = (window.feedPosts||[]).find(p=>p.id===postId); if (!orig) return;
    if (orig.authorId === cu.uid) { window.toast?.('Cannot repost your own post','⚠️'); return; }
    const {authorName,authorAvatar,authorUsername} = myInfo();
    try {
      const {addDoc,collection,serverTimestamp,updateDoc,doc,increment} = window._fbFS;
      await addDoc(collection(window.db,'community_posts'), {
        type:orig.type, title:orig.title||'', body:orig.body||'',
        authorId:cu.uid, authorName, authorAvatar, authorUsername,
        isRepost:true, originalPostId:postId,
        originalAuthorName:orig.authorName, originalAuthorAvatar:orig.authorAvatar,
        likes:[], commentCount:0, repostCount:0, createdAt:serverTimestamp(),
      });
      await updateDoc(doc(window.db,'community_posts',postId), {repostCount:increment(1)});
      window.toast?.('Reposted! 🔁','🌐');
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ══ OPEN EXPANDED POST ══ */
  window.commOpenPost = function(postId) {
    const post = (window.feedPosts||[]).find(p=>p.id===postId); if (!post) return;
    window._commExpandedPostId = postId;
    const cu = window.currentUser, isOwn = post.authorId===cu?.uid, liked = (post.likes||[]).includes(cu?.uid);
    const bc = TYPE_BADGE_CLASS[post.type]||'badge-journal', handle = getHandle(post);

    document.getElementById('comm-exp-avatar').src = post.authorAvatar||'';
    document.getElementById('comm-exp-time').textContent = post.createdAt
      ? new Date(post.createdAt).toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'})
      : '';
    document.getElementById('comm-exp-author-row').innerHTML =
      `<button class="post-author-btn" onclick="commClosePost();setTimeout(()=>commOpenUpm('${esc(post.authorId)}'),200)">
        <span class="post-author-name">${esc(post.authorName||'Anonymous')}</span>
        <span class="post-author-username">@${esc(handle)}</span>
      </button>
      <span class="post-type-badge ${bc}">${TYPE_BADGES[post.type]||post.type}</span>
      ${isOwn?`<span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:var(--muted);padding:2px 6px;border-radius:4px;background:var(--surface2)">you</span>`:''}`;

    let bodyHtml = '';
    if (post.title) bodyHtml += `<div style="font-size:1.1rem;font-weight:800;letter-spacing:-.02em;margin-bottom:14px">${esc(post.title)}</div>`;
    if (post.type === 'goal') {
      bodyHtml += `<div style="background:var(--surface2);border-radius:99px;height:8px;overflow:hidden;margin-bottom:6px">
        <div style="height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent),var(--lavender));width:${post.progress||0}%"></div></div>
        <div style="display:flex;justify-content:space-between;font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted);margin-bottom:14px">
          <span>${post.categoryIcon||'🎯'}</span><span>${post.progress||0}% complete</span></div>
        ${post.body?`<div style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.75;color:rgba(232,234,240,.8);font-weight:300">${esc(post.body)}</div>`:''}`;
    } else if (post.type === 'task') {
      bodyHtml += `<div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
        <span>${post.priorityIcon||'✅'}</span>
        <span style="font-size:.75rem;font-family:'JetBrains Mono',monospace;color:var(--muted);text-transform:uppercase">${post.priority||''} priority</span>
        ${post.done?`<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--green)">· Done ✓</span>`:''}
        </div>${post.body?`<div style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.75;color:rgba(232,234,240,.8);font-weight:300">${esc(post.body)}</div>`:''}`;
    } else {
      if (post.moodEmoji) bodyHtml += `<div style="margin-bottom:12px;font-size:.8rem;color:var(--muted);font-family:'JetBrains Mono',monospace">feeling ${post.moodEmoji}</div>`;
      bodyHtml += `<div style="font-family:'Newsreader',serif;font-size:1rem;line-height:1.85;color:rgba(232,234,240,.85);font-weight:300;white-space:pre-wrap;word-break:break-word">${esc(post.body||'')}</div>`;
      if (post.photoUrls?.length) bodyHtml += `<div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:16px">${post.photoUrls.map(u=>`<img src="${esc(u)}" style="width:120px;height:120px;border-radius:10px;object-fit:cover;border:1px solid var(--border);cursor:pointer" onclick="commViewPhoto('${esc(u)}')">`).join('')}</div>`;
      if (post.tags?.length) bodyHtml += `<div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:14px">${post.tags.map(t=>`<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}</div>`;
    }
    const myAv = window.userProfile?.avatarUrl||cu?.photoURL||`https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff`;
    bodyHtml += `<div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border)">
      <div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:14px">Comments</div>
      <div id="comm-comments-list" style="margin-bottom:14px"><div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted)">Loading…</div></div>
      <div class="comment-input-row">
        <img src="${esc(myAv)}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid var(--border);flex-shrink:0" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <input class="comment-input" id="comm-comment-input" placeholder="Write a comment…"
          onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();commSubmitComment('${postId}')}">
        <button class="comment-submit" onclick="commSubmitComment('${postId}')">↵</button>
      </div>
    </div>`;
    document.getElementById('comm-exp-body').innerHTML = bodyHtml;
    document.getElementById('comm-exp-footer').innerHTML =
      `<button class="post-action-btn ${liked?'liked':''}" id="comm-exp-like-btn" onclick="commToggleLikeExp('${postId}')">
        <span class="heart-icon">${liked?'❤️':'🤍'}</span>
        <span id="comm-exp-like-count" class="post-action-count">${(post.likes||[]).length||''}</span>
      </button>
      <button class="post-action-btn" onclick="commRepost('${postId}')">🔁 <span class="post-action-count">${post.repostCount||''}</span></button>
      ${isOwn?`<button class="post-action-btn" style="margin-left:auto;color:var(--rose)" onclick="commDeletePost('${postId}');commClosePost()">🗑️ Delete</button>`:''}`;

    document.getElementById('comm-post-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    commLoadComments(postId);
  };

  /* ══ COMMENTS ══ */
  async function commLoadComments(postId) {
    const el = document.getElementById('comm-comments-list'); if (!el) return;
    try {
      const {getDocs,query,collection,orderBy} = window._fbFS;
      const snap = await getDocs(query(collection(window.db,'community_posts',postId,'comments'), orderBy('createdAt','asc')));
      const comments = snap.docs.map(d=>({id:d.id,...d.data(),createdAt:d.data().createdAt?.toDate()}));
      const cu = window.currentUser;
      if (!comments.length) {
        el.innerHTML = `<div style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);text-align:center;padding:12px">No comments yet. Be first!</div>`;
        return;
      }
      el.innerHTML = comments.map(c=>`
        <div class="comment-item">
          <img src="${esc(c.authorAvatar||'')}" class="comment-avatar" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
          <div class="comment-bubble">
            <div class="comment-author">
              <span>${esc(c.authorName||'Anonymous')}</span>
              ${c.authorId===cu?.uid?`<button class="comment-del" onclick="commDeleteComment('${postId}','${c.id}')">✕</button>`:''}
            </div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);margin-bottom:4px">${relTime(c.createdAt)}</div>
            <div class="comment-text">${esc(c.text)}</div>
          </div>
        </div>`).join('');
    } catch(err) { el.innerHTML = `<div style="font-size:.72rem;color:var(--muted)">Could not load comments.</div>`; }
  }
  window.commSubmitComment = async function(postId) {
    const input = document.getElementById('comm-comment-input');
    const text = input?.value.trim(); if (!text) return; input.value = '';
    const cu = window.currentUser; if (!cu) return;
    const {authorName,authorAvatar} = myInfo();
    try {
      const {addDoc,collection,serverTimestamp,updateDoc,doc,increment} = window._fbFS;
      await addDoc(collection(window.db,'community_posts',postId,'comments'), {text,authorId:cu.uid,authorName,authorAvatar,createdAt:serverTimestamp()});
      await updateDoc(doc(window.db,'community_posts',postId), {commentCount:increment(1)});
      commLoadComments(postId);
      const c = document.getElementById('ccount-'+postId);
      if (c) c.textContent = (parseInt(c.textContent)||0)+1;
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };
  window.commDeleteComment = async function(postId, commentId) {
    try {
      const {deleteDoc,doc,updateDoc,increment} = window._fbFS;
      await deleteDoc(doc(window.db,'community_posts',postId,'comments',commentId));
      await updateDoc(doc(window.db,'community_posts',postId), {commentCount:increment(-1)});
      commLoadComments(postId);
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };
  window.commToggleLikeExp = async function(postId) {
    const post = (window.feedPosts||[]).find(p=>p.id===postId); if (!post) return;
    const cu = window.currentUser, liked = (post.likes||[]).includes(cu?.uid);
    try {
      const {updateDoc,doc,arrayUnion,arrayRemove} = window._fbFS;
      await updateDoc(doc(window.db,'community_posts',postId), {likes: liked ? arrayRemove(cu.uid) : arrayUnion(cu.uid)});
      const btn = document.getElementById('comm-exp-like-btn'), cnt = document.getElementById('comm-exp-like-count');
      if (btn) btn.classList.toggle('liked', !liked);
      if (cnt) cnt.textContent = (liked ? Math.max(0,(parseInt(cnt.textContent)||0)-1) : (parseInt(cnt.textContent)||0)+1) || '';
      const hrt = btn?.querySelector('.heart-icon'); if (hrt) hrt.textContent = liked ? '🤍' : '❤️';
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ══ USER PROFILE MODAL ══ */
  window.commOpenUpm = async function(userId) {
    if (!userId) return;
    const ov = document.getElementById('comm-upm-overlay'); if (!ov) return;
    document.getElementById('comm-upm-name').textContent = 'Loading…';
    document.getElementById('comm-upm-username').textContent = '';
    document.getElementById('comm-upm-bio').textContent = '';
    document.getElementById('comm-upm-avatar').src = 'https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff';
    document.getElementById('comm-upm-cover').style.background = 'linear-gradient(135deg,#0d1b2a,#1b3a5c)';
    document.getElementById('comm-upm-stat-posts').textContent = '—';
    document.getElementById('comm-upm-stat-likes').textContent = '—';
    document.getElementById('comm-upm-stat-joined').textContent = '—';
    document.getElementById('comm-upm-posts-list').innerHTML = `<div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted);text-align:center;padding:16px">Loading…</div>`;
    ov.classList.add('open');
    document.body.style.overflow = 'hidden';
    try {
      const {getDoc,doc,getDocs,collection,query,where,orderBy,limit} = window._fbFS;
      let userData = null;
      try {
        const snap = await getDoc(doc(window.db,'users',userId,'profile','data'));
        if (snap.exists()) userData = snap.data();
      } catch(_) {}
      if (!userData) {
        const snap = await getDoc(doc(window.db,'users',userId));
        if (snap.exists()) userData = snap.data();
      }
      if (!userData) {
        document.getElementById('comm-upm-name').textContent = 'User not found';
        document.getElementById('comm-upm-posts-list').innerHTML = '<div style="text-align:center;padding:24px;font-size:2rem">🤷</div>';
        return;
      }
      if (userData.isPublic === false) {
        document.getElementById('comm-upm-name').textContent = 'Private Profile';
        document.getElementById('comm-upm-bio').textContent = 'This user keeps things private.';
        document.getElementById('comm-upm-posts-list').innerHTML = '<div style="text-align:center;padding:24px;font-size:2rem">🔒</div>';
        return;
      }
      const av = userData.avatarUrl||userData.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(userData.displayName||'U')}&background=4f8ef7&color=fff`;
      document.getElementById('comm-upm-avatar').src = av;
      document.getElementById('comm-upm-cover').style.background = userData.coverGradient||'linear-gradient(135deg,#0d1b2a,#1b3a5c)';
      document.getElementById('comm-upm-name').textContent = userData.displayName||'Anonymous';
      document.getElementById('comm-upm-username').textContent = userData.username ? '@'+userData.username : '';
      document.getElementById('comm-upm-bio').textContent = userData.bio||'No bio yet.';
      if (userData.joinedAt) {
        const d = userData.joinedAt.toDate ? userData.joinedAt.toDate() : new Date(userData.joinedAt);
        document.getElementById('comm-upm-stat-joined').textContent = d.toLocaleDateString('en-US',{month:'short',year:'numeric'});
      }
      let postsSnap;
      try {
        postsSnap = await getDocs(query(collection(window.db,'community_posts'), where('authorId','==',userId), orderBy('createdAt','desc'), limit(10)));
      } catch(ie) {
        postsSnap = await getDocs(query(collection(window.db,'community_posts'), where('authorId','==',userId), limit(10)));
      }
      const posts = postsSnap.docs.map(d=>({id:d.id,...d.data()}));
      document.getElementById('comm-upm-stat-posts').textContent = posts.length;
      document.getElementById('comm-upm-stat-likes').textContent = posts.reduce((a,p)=>a+(p.likes?.length||0),0);
      const listEl = document.getElementById('comm-upm-posts-list');
      listEl.innerHTML = !posts.length
        ? `<div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted);text-align:center;padding:16px">No posts yet.</div>`
        : posts.map(p=>`
          <div class="comm-upm-post-item" onclick="commCloseUpm();setTimeout(()=>commOpenPost('${p.id}'),200)">
            <div class="comm-upm-post-title">${esc(p.title||(p.body||'').substring(0,60)+'…'||'Post')}</div>
            <div class="comm-upm-post-meta">${relTime(p.createdAt?.toDate?.()??p.createdAt)} · ❤️ ${p.likes?.length||0} · 💬 ${p.commentCount||0}</div>
          </div>`).join('');
    } catch(err) {
      console.error('UPM error:',err);
      document.getElementById('comm-upm-name').textContent = 'Error loading profile';
    }
  };

  /* ══ SHARE MODAL ══ */
  window.commOpenShare = function(type) {
    const t = type||'thought';
    document.getElementById('comm-share-title').textContent = t==='thought' ? '↗ Share a Thought' : `↗ Share a ${t.charAt(0).toUpperCase()+t.slice(1)}`;
    let html = '';
    if (t === 'thought') {
      html = `<div class="form-group"><label class="form-label">What's on your mind?</label>
        <textarea class="comm-form-textarea" id="sm-body" placeholder="Share your thoughts…" style="min-height:120px"></textarea></div>`;
    } else if (t === 'journal') {
      html = `<div class="form-group"><label class="form-label">Title</label><input type="text" class="comm-form-input" id="sm-title" placeholder="Entry title…"></div>
        <div class="form-group"><label class="form-label">Content</label><textarea class="comm-form-textarea" id="sm-body" placeholder="What happened, what you felt…" style="min-height:140px"></textarea></div>
        <div class="form-group"><label class="form-label">Mood Emoji</label><input type="text" class="comm-form-input" id="sm-mood" placeholder="😊 🌧 🔥" style="max-width:120px"></div>
        <div class="form-group"><label class="form-label">Tags (comma separated)</label><input type="text" class="comm-form-input" id="sm-tags" placeholder="work, school, growth"></div>`;
    } else if (t === 'task') {
      html = `<div class="form-group"><label class="form-label">Task</label><input type="text" class="comm-form-input" id="sm-title" placeholder="What did you accomplish?"></div>
        <div class="form-group"><label class="form-label">Details</label><textarea class="comm-form-textarea" id="sm-body" placeholder="More context…" style="min-height:90px"></textarea></div>
        <div class="form-group"><label class="form-label">Priority</label>
          <select class="comm-form-input" id="sm-priority"><option value="low">🟢 Low</option><option value="medium" selected>🟡 Medium</option><option value="high">🔴 High</option></select></div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px">
          <input type="checkbox" id="sm-done" style="width:16px;height:16px">
          <label for="sm-done" style="font-size:.85rem;cursor:pointer">Mark as completed</label></div>`;
    } else if (t === 'goal') {
      html = `<div class="form-group"><label class="form-label">Goal</label><input type="text" class="comm-form-input" id="sm-title" placeholder="What are you working towards?"></div>
        <div class="form-group"><label class="form-label">Description</label><textarea class="comm-form-textarea" id="sm-body" placeholder="Why this goal matters…" style="min-height:90px"></textarea></div>
        <div class="form-group"><label class="form-label">Progress — <span id="sm-pval" style="color:var(--accent)">0%</span></label>
          <input type="range" id="sm-progress" min="0" max="100" value="0" oninput="document.getElementById('sm-pval').textContent=this.value+'%'" style="width:100%;accent-color:var(--accent)"></div>
        <div class="form-group"><label class="form-label">Category</label>
          <select class="comm-form-input" id="sm-category">
            <option value="health">💪 Health</option><option value="learn">📚 Learning</option>
            <option value="finance">💰 Finance</option><option value="career">💼 Career</option>
            <option value="personal" selected>🌱 Personal</option></select></div>`;
    }
    html += `<div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
      <button class="btn" onclick="commCloseShare()">Cancel</button>
      <button class="btn btn-primary" onclick="commSubmitShare('${t}')">Share ↗</button></div>`;
    document.getElementById('comm-share-body').innerHTML = html;
    document.getElementById('comm-share-backdrop').classList.add('open');
  };

  window.commSubmitShare = async function(type) {
    const cu = window.currentUser; if (!cu) return;
    const {authorName,authorAvatar,authorUsername} = myInfo();
    const title = document.getElementById('sm-title')?.value.trim()||'';
    const body  = document.getElementById('sm-body')?.value.trim()||'';
    if (!title && !body) { window.toast?.('Add some content first!','⚠️'); return; }
    const post = {type, title, body, authorId:cu.uid, authorName, authorAvatar, authorUsername, likes:[], commentCount:0, repostCount:0};
    if (type==='journal') {
      post.moodEmoji = document.getElementById('sm-mood')?.value.trim()||'';
      post.tags = (document.getElementById('sm-tags')?.value||'').split(',').map(t=>t.trim()).filter(Boolean);
    }
    if (type==='task') {
      const pri = document.getElementById('sm-priority')?.value||'medium';
      post.priority=pri; post.priorityIcon={low:'🟢',medium:'🟡',high:'🔴'}[pri]||'✅';
      post.done = document.getElementById('sm-done')?.checked||false;
    }
    if (type==='goal') {
      const cat = document.getElementById('sm-category')?.value||'personal';
      post.progress=parseInt(document.getElementById('sm-progress')?.value)||0;
      post.category=cat; post.categoryIcon={health:'💪',learn:'📚',finance:'💰',career:'💼',personal:'🌱',other:'⭐'}[cat]||'🎯';
    }
    try {
      const {addDoc,collection,serverTimestamp} = window._fbFS;
      post.createdAt = serverTimestamp();
      await addDoc(collection(window.db,'community_posts'), post);
      commCloseShare(); window.toast?.('Shared! ✨','🌐');
    } catch(e) { window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ══ FIREBASE REALTIME ══ */
  waitFB(() => {
    const unsub = window.auth.onAuthStateChanged(user => {
      if (!user) return;
      window.currentUser = user;
      const {collection,query,orderBy,onSnapshot} = window._fbFS;
      onSnapshot(
        query(collection(window.db,'community_posts'), orderBy('createdAt','desc')),
        snap => {
          window.feedPosts = snap.docs.map(d=>({id:d.id,...d.data(),createdAt:d.data().createdAt?.toDate()}));
          window.renderFeed();
          if (typeof window.renderProfilePage === 'function') window.renderProfilePage();
        },
        err => console.error('Feed error:',err.message)
      );
      unsub();
    });
  });
})();
</script>