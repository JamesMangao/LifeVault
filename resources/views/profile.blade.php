<style>
:root{--bg:#0b0f1a;--surface:#111827;--surface2:#1a2235;--border:rgba(255,255,255,.07);--text:#e8eaf0;--muted:#6b7a99;--accent:#4f8ef7;--green:#34d399;--amber:#fbbf24;--rose:#f87171;--lavender:#a78bfa;--teal:#2dd4bf;--glow:0 0 40px rgba(79,142,247,.15);--sidebar-width:260px}
@keyframes pageIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
@keyframes modalIn{from{opacity:0;transform:scale(.92) translateY(20px)}to{opacity:1;transform:scale(1) translateY(0)}}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
@keyframes heartBeat{0%{transform:scale(1)}40%{transform:scale(1.4)}70%{transform:scale(.9)}100%{transform:scale(1)}}
@keyframes overlayFadeIn{from{opacity:0}to{opacity:1}}
@keyframes expandCardIn{from{opacity:0;transform:scale(.88) translateY(24px)}to{opacity:1;transform:scale(1) translateY(0)}}
.page{display:none}.page.active{display:block;animation:pageIn .3s ease both}
.page-header{margin-bottom:32px;display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px}
.page-title{font-size:2rem;font-weight:800;letter-spacing:-.03em}
.page-subtitle{font-family:'Newsreader',serif;font-style:italic;font-size:.9rem;color:var(--muted);margin-top:4px;font-weight:300}
.card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:24px}
.btn{font-family:'Syne',sans-serif;font-size:.8rem;font-weight:600;padding:10px 18px;border-radius:10px;border:1px solid var(--border);background:var(--surface2);color:var(--text);cursor:pointer;transition:all .18s;display:inline-flex;align-items:center;gap:6px}
.btn:hover{background:var(--surface);border-color:rgba(79,142,247,.3);color:var(--accent)}
.btn-primary{background:var(--accent);border-color:var(--accent);color:white}
.btn-primary:hover{background:#3a7ae0;border-color:#3a7ae0;color:white}
.btn-sm{font-family:'Syne',sans-serif;font-size:.72rem;font-weight:600;padding:5px 11px;border-radius:7px;border:1px solid var(--border);background:var(--surface);color:var(--muted);cursor:pointer;transition:all .18s}
.btn-sm:hover{color:var(--accent);border-color:var(--accent)}
.form-group{margin-bottom:16px}
.form-label{display:block;font-family:'JetBrains Mono',monospace;font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:8px}
.form-input,.form-textarea{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;color:var(--text);font-family:'Syne',sans-serif;font-size:.85rem;outline:none;transition:border-color .2s,box-shadow .2s;box-sizing:border-box}
.form-input:focus,.form-textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(79,142,247,.12)}
.form-textarea{resize:vertical;min-height:90px;line-height:1.6;font-family:'Newsreader',serif;font-size:.95rem;font-weight:300}
.modal-backdrop{position:fixed;inset:0;background:rgba(11,15,26,.92);z-index:200;display:none;align-items:center;justify-content:center;padding:20px}
.modal-backdrop.open{display:flex}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:32px;max-width:520px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 40px 80px rgba(0,0,0,.5);animation:modalIn .3s cubic-bezier(.34,1.56,.64,1) both}
.modal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.modal-title{font-size:1.2rem;font-weight:800;letter-spacing:-.02em}
.modal-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:1.2rem;line-height:1;transition:color .2s;padding:4px}
.modal-close:hover{color:var(--rose)}
.post-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;margin-bottom:16px;transition:border-color .2s,box-shadow .2s;animation:slideDown .3s ease both;cursor:pointer}
.post-card:hover{border-color:rgba(79,142,247,.25);box-shadow:0 4px 20px rgba(79,142,247,.08)}
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
.loading-posts{text-align:center;padding:32px;color:var(--muted);font-family:'JetBrains Mono',monospace;font-size:.72rem}
.feed-empty{text-align:center;padding:60px 24px;color:var(--muted)}
.feed-empty .empty-icon{font-size:3rem;margin-bottom:16px}
.feed-empty .empty-title{font-size:1rem;font-weight:700;margin-bottom:8px}
.feed-empty .empty-sub{font-family:'Newsreader',serif;font-style:italic;font-size:.85rem;opacity:.7}
.post-expand-overlay{position:fixed;inset:0;background:rgba(11,15,26,0);z-index:300;display:none;align-items:center;justify-content:center;padding:24px}
.post-expand-overlay.open{display:flex;background:rgba(11,15,26,.92);animation:overlayFadeIn .3s ease both}
.post-expanded-card{background:var(--surface);border:1px solid rgba(79,142,247,.25);border-radius:20px;max-width:720px;width:100%;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 0 80px rgba(79,142,247,.12),0 40px 80px rgba(0,0,0,.6);animation:expandCardIn .38s cubic-bezier(.34,1.4,.64,1) both;overflow:hidden}
.post-expanded-header{padding:20px 24px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;background:linear-gradient(135deg,rgba(79,142,247,.06),rgba(167,139,250,.04))}
.post-expanded-body{padding:24px;overflow-y:auto;flex:1}
.post-expanded-footer{padding:14px 24px;border-top:1px solid var(--border);display:flex;align-items:center;gap:8px;flex-shrink:0;background:var(--surface2)}
.expanded-close-btn{background:var(--surface2);border:1px solid var(--border);color:var(--muted);cursor:pointer;font-size:.9rem;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:color .2s,background .2s,border-color .2s;flex-shrink:0}
.expanded-close-btn:hover{color:var(--rose);border-color:var(--rose);background:rgba(248,113,113,.08)}
.comment-item{display:flex;gap:10px;margin-bottom:12px;animation:slideDown .25s ease both}
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
.user-profile-modal{position:fixed;inset:0;background:rgba(11,15,26,.92);z-index:400;display:none;align-items:center;justify-content:center;padding:24px}
.user-profile-modal.open{display:flex;animation:overlayFadeIn .25s ease both}
.user-profile-card{background:var(--surface);border:1px solid rgba(79,142,247,.25);border-radius:20px;max-width:440px;width:100%;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 0 80px rgba(79,142,247,.12),0 40px 80px rgba(0,0,0,.6);animation:expandCardIn .35s cubic-bezier(.34,1.4,.64,1) both;overflow:visible}
.user-profile-cover{height:100px;flex-shrink:0;position:relative;overflow:hidden;border-radius:20px 20px 0 0}
.user-profile-cover::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 30% 50%,rgba(255,255,255,.15) 0%,transparent 60%)}
.user-profile-identity{padding:0 24px 20px;flex-shrink:0;overflow:visible}
.user-profile-avatar-wrap{margin-top:-40px;margin-bottom:10px;position:relative;z-index:2}
.user-profile-avatar{width:72px;height:72px;border-radius:50%;border:3px solid var(--surface);object-fit:cover;display:block;background:var(--surface2)}
.user-profile-name{font-size:1.1rem;font-weight:800;letter-spacing:-.02em}
.user-profile-username{font-family:'JetBrains Mono',monospace;font-size:.72rem;color:var(--accent);margin-top:3px}
.user-profile-bio{font-family:'Newsreader',serif;font-size:.85rem;color:rgba(232,234,240,.7);font-weight:300;line-height:1.6;margin-top:8px}
.user-profile-stats{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--border);margin-top:14px}
.user-profile-stat{padding:12px;text-align:center;border-right:1px solid var(--border)}
.user-profile-stat:last-child{border-right:none}
.user-profile-stat-val{font-size:1.2rem;font-weight:800;letter-spacing:-.03em;line-height:1;margin-bottom:3px}
.user-profile-stat-label{font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)}
.user-profile-scroll{overflow-y:auto;flex:1;padding:16px 24px;border-top:1px solid var(--border)}
.user-post-mini{padding:12px;background:var(--surface2);border-radius:10px;margin-bottom:8px;border:1px solid var(--border);cursor:pointer;transition:border-color .2s}
.user-post-mini:hover{border-color:rgba(79,142,247,.3)}
.user-post-mini-title{font-size:.8rem;font-weight:600;margin-bottom:4px}
.user-post-mini-body{font-family:'Newsreader',serif;font-size:.78rem;color:rgba(232,234,240,.6);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

/* ═══════════════════════════════════════════════════════
   PROFILE PAGE — LAYOUT FIXES
   (avatar circle + stats horizontal grid)
═══════════════════════════════════════════════════════ */
.profile-cover{height:180px;border-radius:16px 16px 0 0;position:relative;overflow:hidden;cursor:pointer;display:block;min-height:180px}
.profile-cover::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 30% 50%,rgba(255,255,255,.18) 0%,transparent 60%),radial-gradient(circle at 80% 20%,rgba(255,255,255,.1) 0%,transparent 50%)}
.profile-cover-edit{position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,.5);border:1px solid rgba(255,255,255,.15);color:var(--text);border-radius:8px;padding:6px 12px;font-size:.72rem;cursor:pointer;font-family:'Syne',sans-serif;font-weight:600;transition:background .2s;display:flex;align-items:center;gap:6px}
.profile-cover-edit:hover{background:rgba(79,142,247,.4)}
.profile-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:24px}
.profile-identity{padding:0 28px 24px;position:relative}
/* FIX: inline-block stops avatar from stretching full width */
.profile-avatar-wrap{position:relative;display:inline-block !important;margin-top:-44px;margin-bottom:12px;line-height:0}
/* FIX: strict avatar sizing overrides any global img{width:100%} */
.profile-avatar-large,#profile-avatar-large{width:88px !important;height:88px !important;min-width:88px !important;min-height:88px !important;max-width:88px !important;max-height:88px !important;border-radius:50% !important;object-fit:cover !important;display:block !important;flex-shrink:0 !important;border:3px solid var(--surface) !important;background:var(--surface2)}
.profile-avatar-edit-btn{position:absolute;bottom:2px;right:2px;width:26px;height:26px;border-radius:50%;background:var(--accent);border:2px solid var(--surface);color:white;font-size:.7rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s}
.profile-avatar-edit-btn:hover{background:#3a7ae0}
.profile-display-name{font-size:1.5rem;font-weight:800;letter-spacing:-.03em;line-height:1.1}
.profile-username{font-family:'JetBrains Mono',monospace;font-size:.78rem;color:var(--accent);margin-top:4px}
.profile-bio{font-family:'Newsreader',serif;font-size:.9rem;color:rgba(232,234,240,.7);font-weight:300;line-height:1.6;margin-top:10px;max-width:520px}
.profile-badges{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.profile-badge{font-family:'JetBrains Mono',monospace;font-size:.6rem;padding:3px 10px;border-radius:99px;text-transform:uppercase;letter-spacing:.08em;border:1px solid}
/* FIX: force horizontal 4-column grid for stats */
.profile-stats-row{display:grid !important;grid-template-columns:repeat(4,1fr) !important;border-top:1px solid var(--border);margin-top:20px}
.profile-stat{display:block !important;padding:16px;text-align:center;border-right:1px solid var(--border)}
.profile-stat:last-child{border-right:none}
/* FIX: display:block so val and label stack inside cell, not side-by-side */
.profile-stat-val{display:block !important;font-size:1.4rem;font-weight:800;letter-spacing:-.03em;line-height:1;margin-bottom:4px}
.profile-stat-label{display:block !important;font-family:'JetBrains Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)}
.profile-section-title{font-size:.78rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:var(--muted);margin-bottom:14px;display:flex;align-items:center;gap:8px}
.profile-section-title::after{content:'';flex:1;height:1px;background:var(--border)}
.avatar-picker-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin:12px 0}
.avatar-option{width:100%;aspect-ratio:1;border-radius:50%;border:2px solid var(--border);cursor:pointer;object-fit:cover;transition:border-color .2s,transform .2s}
.avatar-option:hover,.avatar-option.selected{border-color:var(--accent);transform:scale(1.08)}
.cover-preset-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:16px}
.cover-preset{height:48px;border-radius:8px;border:2px solid var(--border);cursor:pointer;transition:border-color .2s,transform .15s;position:relative;overflow:hidden}
.cover-preset::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 30% 50%,rgba(255,255,255,.2) 0%,transparent 60%)}
.cover-preset:hover,.cover-preset.selected{border-color:var(--accent);transform:scale(1.04)}
.cover-preset.selected::after{content:'✓';position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-weight:800;color:white;font-size:.9rem;background:rgba(79,142,247,.35)}
.hex-input-section{border-top:1px solid var(--border);padding-top:16px;margin-top:4px}
.hex-input-row{display:flex;gap:10px;align-items:center}
.hex-color-input{width:48px;height:48px;border:2px solid var(--border);border-radius:10px;cursor:pointer;padding:4px;background:var(--surface2);flex-shrink:0}
.hex-text-input{flex:1;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:'JetBrains Mono',monospace;font-size:.85rem;outline:none;transition:border-color .2s}
.hex-text-input:focus{border-color:var(--accent)}
.hex-apply-btn{background:var(--accent);border:none;color:white;padding:10px 16px;border-radius:10px;cursor:pointer;font-size:.75rem;font-weight:700;font-family:'Syne',sans-serif;white-space:nowrap}
.cover-live-preview{height:80px;border-radius:10px;margin-top:12px;border:1px solid var(--border);position:relative;overflow:hidden;transition:background .4s ease}
.cover-live-preview::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 25% 50%,rgba(255,255,255,.18) 0%,transparent 55%),radial-gradient(circle at 78% 22%,rgba(255,255,255,.1) 0%,transparent 45%)}
.cover-live-preview-label{position:absolute;bottom:8px;left:12px;font-family:'JetBrains Mono',monospace;font-size:.58rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.1em}
@media(max-width:768px){.profile-stats-row{grid-template-columns:repeat(2,1fr)!important}.profile-stat:nth-child(2){border-right:none}.profile-stat:nth-child(3){border-top:1px solid var(--border)}}
@media(max-width:600px){.cover-preset-grid{grid-template-columns:repeat(3,1fr)}}
</style>

{{-- ═══════════ PROFILE PAGE ═══════════ --}}
<div id="page-profile" class="page">
  <div class="page-header">
    <div>
      <div class="page-title">Profile</div>
      <div class="page-subtitle">Your identity in LifeVault</div>
    </div>
    <button class="btn btn-primary" onclick="openEditProfileModal()">✎ Edit Profile</button>
  </div>

  <div class="profile-card">
    <div class="profile-cover" id="profile-cover-display">
      <button class="profile-cover-edit" onclick="openCoverModal()">🖼 Change Cover</button>
    </div>
    <div class="profile-identity">
      <div class="profile-avatar-wrap">
        <img id="profile-avatar-large" class="profile-avatar-large" src="" alt="Profile photo">
        <button class="profile-avatar-edit-btn" onclick="openAvatarModal()">✎</button>
      </div>
      <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div>
          <div class="profile-display-name" id="profile-display-name">—</div>
          <div class="profile-username" id="profile-username-display">@—</div>
        </div>
      </div>
      <div class="profile-bio" id="profile-bio-display">No bio yet.</div>
      <div id="profile-info-row" style="display:flex;flex-wrap:wrap;gap:4px;margin-top:10px;font-family:'JetBrains Mono',monospace;font-size:.68rem;color:var(--muted)"></div>
      <div class="profile-badges" id="profile-badges"></div>
      <div class="profile-stats-row">
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-journals" style="color:var(--accent)">0</span>
          <span class="profile-stat-label">Journals</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-tasks" style="color:var(--green)">0</span>
          <span class="profile-stat-label">Tasks</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-goals" style="color:var(--lavender)">0</span>
          <span class="profile-stat-label">Goals</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-posts" style="color:var(--teal)">0</span>
          <span class="profile-stat-label">Posts</span>
        </div>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:24px">
    <div class="profile-section-title">🌐 My Community Posts</div>
    <div id="profile-my-posts">
      <div class="loading-posts">Loading your posts…</div>
    </div>
  </div>
</div>

{{-- ═══════════ POST EXPAND OVERLAY ═══════════ --}}
<div class="post-expand-overlay" id="post-expand-overlay">
  <div class="post-expanded-card">
    <div class="post-expanded-header">
      <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
        <img id="pexp-avatar" src="" style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:1.5px solid var(--border);flex-shrink:0">
        <div style="flex:1;min-width:0">
          <div id="pexp-author-row" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap"></div>
          <div id="pexp-time" style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted);margin-top:2px"></div>
        </div>
      </div>
      <button class="expanded-close-btn" onclick="closeExpandedPost()">✕</button>
    </div>
    <div class="post-expanded-body" id="pexp-body"></div>
    <div class="post-expanded-footer" id="pexp-footer"></div>
  </div>
</div>

{{-- ═══════════ USER PROFILE POPUP ═══════════ --}}
<div class="user-profile-modal" id="user-profile-modal">
  <div class="user-profile-card">
    <div class="user-profile-cover" id="upm-cover"></div>
    <div class="user-profile-identity">
      <div class="user-profile-avatar-wrap">
        <img class="user-profile-avatar" id="upm-avatar" src="" alt="">
      </div>
      <div class="user-profile-name" id="upm-name"></div>
      <div class="user-profile-username" id="upm-username"></div>
      <div class="user-profile-bio" id="upm-bio"></div>
      <div class="user-profile-info" id="upm-info" style="display:flex;flex-wrap:wrap;gap:4px;margin-top:8px;font-family:'JetBrains Mono',monospace;font-size:.63rem;color:var(--muted)"></div>
      <div class="user-profile-stats">
        <div class="user-profile-stat"><div class="user-profile-stat-val" id="upm-posts" style="color:var(--teal)">—</div><div class="user-profile-stat-label">Posts</div></div>
        <div class="user-profile-stat"><div class="user-profile-stat-val" id="upm-likes" style="color:var(--rose)">—</div><div class="user-profile-stat-label">Likes</div></div>
        <div class="user-profile-stat"><div class="user-profile-stat-val" id="upm-joined" style="color:var(--muted)">—</div><div class="user-profile-stat-label">Joined</div></div>
      </div>
    </div>
    <div class="user-profile-scroll" id="upm-posts-list">
      <div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted);padding:8px">Loading posts…</div>
    </div>
    <div style="padding:14px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;background:var(--surface2)">
      <button class="btn btn-primary" onclick="closeUserProfileModal()">Close</button>
    </div>
  </div>
</div>

{{-- ═══════════ EDIT PROFILE MODAL ═══════════ --}}
<div class="modal-backdrop" id="edit-profile-modal">
  <div class="modal" style="max-width:560px">
    <div class="modal-header">
      <div class="modal-title">✎ Edit Profile</div>
      <button class="modal-close" onclick="closeModal('edit-profile-modal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-input" id="edit-fullname" placeholder="Your full name...">
    </div>
    <div class="form-group">
      <label class="form-label">Username</label>
      <div style="position:relative">
        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-family:'JetBrains Mono',monospace;font-size:.85rem">@</span>
        <input type="text" class="form-input" id="edit-username" placeholder="yourhandle" style="padding-left:28px">
      </div>
      <div style="font-size:.68rem;color:var(--muted);margin-top:5px;font-family:'JetBrains Mono',monospace">Lowercase letters, numbers, underscores only</div>
    </div>
    <div class="form-group">
      <label class="form-label">Bio</label>
      <textarea class="form-textarea" id="edit-bio" placeholder="Tell the community a bit about yourself…"></textarea>
    </div>
    <div class="form-group">
      <label class="form-label">Location <span style="opacity:.5">(optional)</span></label>
      <input type="text" class="form-input" id="edit-location" placeholder="City, Country...">
    </div>
    <div class="form-group">
      <label class="form-label">Website <span style="opacity:.5">(optional)</span></label>
      <input type="text" class="form-input" id="edit-website" placeholder="https://...">
    </div>
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
      <button class="btn" onclick="closeModal('edit-profile-modal')">Cancel</button>
      <button class="btn btn-primary" onclick="saveProfile()">Save Profile</button>
    </div>
  </div>
</div>

{{-- ═══════════ AVATAR MODAL ═══════════ --}}
<div class="modal-backdrop" id="avatar-modal">
  <div class="modal" style="max-width:480px">
    <div class="modal-header">
      <div class="modal-title">🖼 Change Avatar</div>
      <button class="modal-close" onclick="closeModal('avatar-modal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">Upload your own</label>
      <input type="file" id="avatar-upload" accept="image/*" style="color:var(--muted);font-size:.8rem;display:block">
      <div style="font-size:.68rem;color:var(--muted);margin-top:4px;font-family:'JetBrains Mono',monospace">Auto-compressed to ~80KB</div>
    </div>
    <div class="form-group">
      <label class="form-label">Or pick a preset</label>
      <div class="avatar-picker-grid" id="avatar-preset-grid"></div>
    </div>
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
      <button class="btn" onclick="closeModal('avatar-modal')">Cancel</button>
      <button class="btn btn-primary" onclick="saveAvatar()">Apply Avatar</button>
    </div>
  </div>
</div>

{{-- ═══════════ COVER MODAL ═══════════ --}}
<div class="modal-backdrop" id="cover-modal">
  <div class="modal" style="max-width:520px">
    <div class="modal-header">
      <div class="modal-title">🖼 Change Cover</div>
      <button class="modal-close" onclick="closeModal('cover-modal')">✕</button>
    </div>
    <div class="form-group">
      <label class="form-label">Preset Gradients</label>
      <div class="cover-preset-grid" id="cover-preset-grid"></div>
    </div>
    <div class="hex-input-section">
      <label class="form-label" style="display:block;margin-bottom:10px">🎨 Custom Color — enter a hex code to auto-generate a gradient</label>
      <div class="hex-input-row">
        <input type="color" class="hex-color-input" id="hex-color-wheel" title="Pick a color">
        <input type="text" class="hex-text-input" id="hex-text-input" placeholder="#4f8ef7" maxlength="7">
        <button class="hex-apply-btn" onclick="applyHexCover()">Apply ✓</button>
      </div>
      <div class="cover-live-preview" id="cover-live-preview">
        <span class="cover-live-preview-label">Live Preview</span>
      </div>
      <div style="font-size:.65rem;color:var(--muted);font-family:'JetBrains Mono',monospace;margin-top:8px;opacity:.7">The gradient is auto-generated: your color is blended with dark tones to keep the cover elegant.</div>
    </div>
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px">
      <button class="btn" onclick="closeModal('cover-modal')">Cancel</button>
      <button class="btn btn-primary" onclick="saveCover()">Apply Cover</button>
    </div>
  </div>
</div>

<script>
(function () {
  /* ── Constants ── */
  const COVER_PRESETS = [
    'linear-gradient(135deg,#0d1b2a,#1b3a5c,#0d1b2a)',
    'linear-gradient(135deg,#0f0c29,#302b63,#24243e)',
    'linear-gradient(135deg,#093028,#237a57,#093028)',
    'linear-gradient(135deg,#1a0533,#3d1a7a,#11998e)',
    'linear-gradient(135deg,#200122,#6f0000,#200122)',
    'linear-gradient(135deg,#141e30,#243b55,#141e30)',
    'linear-gradient(135deg,#1f1c2c,#4a4580,#1f1c2c)',
    'linear-gradient(135deg,#0b0f1a,#1e3a5f,#0b0f1a)',
  ];
  const AVATAR_PRESETS = [
    'https://api.dicebear.com/7.x/bottts/svg?seed=lv1&backgroundColor=4f8ef7',
    'https://api.dicebear.com/7.x/bottts/svg?seed=lv2&backgroundColor=a78bfa',
    'https://api.dicebear.com/7.x/bottts/svg?seed=lv3&backgroundColor=34d399',
    'https://api.dicebear.com/7.x/bottts/svg?seed=lv4&backgroundColor=f87171',
    'https://api.dicebear.com/7.x/bottts/svg?seed=lv5&backgroundColor=fbbf24',
    'https://api.dicebear.com/7.x/personas/svg?seed=lv1',
    'https://api.dicebear.com/7.x/personas/svg?seed=lv2',
    'https://api.dicebear.com/7.x/personas/svg?seed=lv3',
    'https://api.dicebear.com/7.x/personas/svg?seed=lv4',
    'https://api.dicebear.com/7.x/personas/svg?seed=lv5',
  ];
  const TYPE_BADGES      = {thought:'💭 Thought',journal:'📓 Journal',task:'✅ Task',goal:'🎯 Goal'};
  const TYPE_BADGE_CLASS = {thought:'badge-journal',journal:'badge-journal',task:'badge-task',goal:'badge-goal'};

  /* ── State ── */
  let _selectedAvatarUrl = '';
  let _selectedCoverData = null;
  let _expandedPostId    = null;

  /* ── Helpers ── */
  function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
  function fmtDate(d){if(!d)return '';return new Date(d).toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'})}
  function relativeTime(date){
    if(!date)return '';
    const diff=Date.now()-new Date(date).getTime(),mins=Math.floor(diff/60000);
    if(mins<1)return 'just now';if(mins<60)return mins+'m ago';
    const hrs=Math.floor(mins/60);if(hrs<24)return hrs+'h ago';
    const days=Math.floor(hrs/24);if(days<7)return days+'d ago';
    return new Date(date).toLocaleDateString('en-US',{month:'short',day:'numeric'});
  }
  function getUsernameFromPost(p){return p.authorUsername||(p.authorName||'anonymous').toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user'}
  function calcStreak(){
    const j=window.journals||[];if(!j.length)return 0;
    const uniq=[...new Set(j.map(e=>{const d=new Date(e.createdAt);return `${d.getFullYear()}-${d.getMonth()}-${d.getDate()}`}))].sort().reverse();
    let streak=0,cur=new Date();
    for(const s of uniq){const[y,m,d]=s.split('-').map(Number);const entry=new Date(y,m,d);const diff=(new Date(cur.getFullYear(),cur.getMonth(),cur.getDate())-entry)/86400000;if(diff<=1){streak++;cur=entry;}else break;}
    return streak;
  }
  function getCurrentUser(){return window.currentUser||window.auth?.currentUser||null}
  function currentAvatarUrl(){const p=window.userProfile||{},cu=getCurrentUser();return p.avatarUrl||cu?.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(p.displayName||cu?.displayName||'U')}&background=4f8ef7&color=fff`}

  /* ── Sync post author data ── */
  async function syncPostAuthorData(newName,newAvatarUrl){
    const cu=getCurrentUser();if(!cu)return;
    try{
      const{collection,query,where,getDocs,doc,writeBatch}=window._fbFS;
      if(typeof writeBatch!=='function'){console.warn('[Profile] writeBatch unavailable, skipping batch sync');return;}
      const snap=await getDocs(query(collection(window.db,'community_posts'),where('authorId','==',cu.uid)));
      if(snap.empty)return;
      const BATCH=450;let batch=writeBatch(window.db),count=0;
      for(const ds of snap.docs){
        batch.update(doc(window.db,'community_posts',ds.id),{authorName:newName,authorAvatar:newAvatarUrl});
        count++;if(count%BATCH===0){await batch.commit();batch=writeBatch(window.db);}
      }
      if(count%BATCH!==0)await batch.commit();
      if(Array.isArray(window.feedPosts))window.feedPosts.forEach(p=>{if(p.authorId===cu.uid){p.authorName=newName;p.authorAvatar=newAvatarUrl;}});
      if(typeof window.renderFeed==='function')window.renderFeed();
      if(typeof window.renderProfilePage==='function')window.renderProfilePage();
    }catch(e){console.warn('[Profile] Sync failed:',e.message)}
  }

  /* ── applyProfileToUI ── */
  window.applyProfileToUI=function(){
    const p=window.userProfile||{},cu=getCurrentUser(),av=currentAvatarUrl();
    const $=id=>document.getElementById(id);
    if($('user-name'))   $('user-name').textContent=p.displayName||cu?.displayName||'—';
    if($('user-avatar')) $('user-avatar').src=av;
    if($('composer-avatar'))$('composer-avatar').src=av;
    if($('profile-avatar-large'))    $('profile-avatar-large').src=av;
    if($('profile-display-name'))    $('profile-display-name').textContent=p.displayName||'—';
    if($('profile-username-display'))$('profile-username-display').textContent='@'+(p.username||'—');
    if($('profile-bio-display'))     $('profile-bio-display').textContent=p.bio||'No bio yet.';
    if($('profile-cover-display'))   $('profile-cover-display').style.background=p.coverGradient||COVER_PRESETS[0];
    const journals=window.journals||[],tasks=window.tasks||[],goals=window.goals||[],feedPosts=window.feedPosts||[];
    const badges=[];
    if(journals.length>=1)             badges.push({l:'📓 Writer',      c:'rgba(79,142,247,.2)',   b:'rgba(79,142,247,.4)'});
    if(tasks.filter(t=>t.done).length) badges.push({l:'✅ Doer',        c:'rgba(52,211,153,.15)',  b:'rgba(52,211,153,.4)'});
    if(goals.length>=1)                badges.push({l:'🎯 Goal-setter', c:'rgba(167,139,250,.15)', b:'rgba(167,139,250,.4)'});
    if(calcStreak()>=3)                badges.push({l:'🔥 On a streak', c:'rgba(251,191,36,.15)',  b:'rgba(251,191,36,.4)'});
    if($('profile-badges'))$('profile-badges').innerHTML=badges.map(b=>`<span class="profile-badge" style="background:${b.c};border-color:${b.b};color:var(--text)">${b.l}</span>`).join('');
    if($('pstat-journals'))$('pstat-journals').textContent=journals.length;
    if($('pstat-tasks'))   $('pstat-tasks').textContent=tasks.length;
    if($('pstat-goals'))   $('pstat-goals').textContent=goals.length;
    if($('pstat-posts'))   $('pstat-posts').textContent=feedPosts.filter(pp=>pp.authorId===cu?.uid).length;
    const infoEl=$('profile-info-row');
    if(infoEl){const parts=[];if(p.location)parts.push(`📍 ${esc(p.location)}`);if(p.website)parts.push(`🔗 <a href="${esc(p.website)}" target="_blank" style="color:var(--accent);text-decoration:none">${esc(p.website.replace(/^https?:\/\//,''))}</a>`);if(p.joinedAt)parts.push(`🗓 Joined ${new Date(p.joinedAt).toLocaleDateString('en-US',{month:'long',year:'numeric'})}`);infoEl.innerHTML=parts.join('<span style="margin:0 8px;opacity:.3">·</span>')}
  };

  /* ── renderProfilePage ── */
  window.renderProfilePage=function(){
    window.applyProfileToUI();
    const cu=getCurrentUser(),feedPosts=window.feedPosts||[];
    const cName=window.userProfile?.displayName||cu?.displayName||'Anonymous',cAv=currentAvatarUrl();
    const myPosts=feedPosts.filter(p=>p.authorId===cu?.uid).map(p=>({...p,authorName:cName,authorAvatar:cAv}));
    const postsEl=document.getElementById('profile-my-posts');if(!postsEl)return;
    if(!myPosts.length){
      postsEl.innerHTML=`<div class="feed-empty" style="padding:32px"><div class="empty-icon">🌐</div><div class="empty-title">No posts yet</div><div class="empty-sub">Share something with the community!</div><button class="btn btn-primary" style="margin-top:16px" onclick="window.navigateTo&&window.navigateTo('community',event)">Go to Community</button></div>`;
    }else{
      postsEl.innerHTML=myPosts.map(p=>renderPostCard(p)).join('');
      if(window.initExpandableCards)window.initExpandableCards(postsEl);
    }
  };

  /* ── Post card ── */
  function renderPostCard(p){
    const cu=getCurrentUser(),isOwn=p.authorId===cu?.uid,liked=(p.likes||[]).includes(cu?.uid);
    const timeAgo=relativeTime(p.createdAt),bc=TYPE_BADGE_CLASS[p.type]||'badge-journal',handle=getUsernameFromPost(p);
    const repost=p.isRepost?`<div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--teal);margin-bottom:10px;display:flex;align-items:center;gap:6px">🔁 reposted from <img src="${esc(p.originalAuthorAvatar||'')}" style="width:16px;height:16px;border-radius:50%;object-fit:cover"><span>${esc(p.originalAuthorName||'')}</span></div>`:'';
    let body='';
    if(p.type==='goal'){body=`<div class="post-goal-bar"><div class="post-goal-fill" style="width:${p.progress||0}%"></div></div><div class="post-goal-meta"><span>${p.categoryIcon||'🎯'}</span><span>${p.progress||0}% complete</span></div>${p.body?`<div class="post-body">${esc(p.body)}</div>`:''}`;}
    else if(p.type==='task'){body=`<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px"><span>${p.priorityIcon||'✅'}</span><span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--muted);text-transform:uppercase">${p.priority||''} priority</span>${p.done?`<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--green)">· Done ✓</span>`:''}</div>${p.body?`<div class="post-body">${esc(p.body)}</div>`:''}`;}
    else{const long=(p.body||'').length>300;body=`<div class="post-body" id="post-body-${p.id}">${esc(p.body||'')}</div>${long?`<span class="post-read-more" onclick="event.stopPropagation();window.toggleReadMore&&window.toggleReadMore('${p.id}')">Read more ↓</span>`:''} ${p.moodEmoji?`<div style="margin-top:8px;font-size:.8rem;color:var(--muted);font-family:'JetBrains Mono',monospace">feeling ${p.moodEmoji}</div>`:''} ${p.photoUrls?.length?`<div class="post-photos">${p.photoUrls.map(u=>`<img src="${esc(u)}" class="post-photo" onclick="event.stopPropagation();window.viewPhoto&&window.viewPhoto('${esc(u)}')">`).join('')}</div>`:''} ${p.tags?.length?`<div class="post-tags">${p.tags.map(t=>`<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}</div>`:''}`;}
    return `<div class="post-card" data-post-id="${p.id}" data-expand="${p.id}">
      <div class="post-header">
        <img src="${esc(p.authorAvatar||'https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff')}" class="post-avatar" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <div class="post-meta">
          <div>
            <button class="post-author-btn" onclick="event.stopPropagation();window.openUserProfileModal&&window.openUserProfileModal('${esc(p.authorId)}')">
              <span class="post-author-name">${esc(p.authorName||'Anonymous')}</span>
              <span class="post-author-username">@${esc(handle)}</span>
            </button>
            <span class="post-type-badge ${bc}">${TYPE_BADGES[p.type]||p.type}</span>
            ${isOwn?`<span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:var(--muted);padding:2px 6px;border-radius:4px;background:var(--surface2)">you</span>`:''}
          </div>
          <div class="post-time">${timeAgo}</div>
        </div>
        ${isOwn?`<button class="post-delete-btn" onclick="event.stopPropagation();window.deletePost&&window.deletePost('${p.id}')">🗑️</button>`:''}
      </div>
      ${p.title?`<div class="post-title">${esc(p.title)}</div>`:''}
      ${repost}${body}
      <div class="post-actions" onclick="event.stopPropagation()">
        <button class="post-action-btn ${liked?'liked':''}" onclick="window.toggleLike&&window.toggleLike('${p.id}')"><span class="heart-icon">${liked?'❤️':'🤍'}</span><span class="post-action-count">${(p.likes||[]).length||''}</span></button>
        <button class="post-action-btn" onclick="event.stopPropagation();window.toggleComments&&window.toggleComments('${p.id}')">💬 <span class="post-action-count" id="comment-count-${p.id}">${p.commentCount||0}</span></button>
        <button class="post-action-btn" onclick="window.repost&&window.repost('${p.id}')">🔁 <span class="post-action-count">${p.repostCount||''}</span></button>
      </div>
    </div>`;
  }

  /* ── Firebase ── */
  function waitForFirebase(cb){if(window.db&&window.auth&&window._fbFS)cb();else setTimeout(()=>waitForFirebase(cb),80)}
  async function setUserProfile(data){const cu=getCurrentUser();if(!cu)return;const{setDoc,doc}=window._fbFS;await setDoc(doc(window.db,'users',cu.uid,'profile','data'),data,{merge:true})}
  async function loadUserProfile(){
    const cu=getCurrentUser();if(!cu)return;
    try{
      const{getDoc,doc,setDoc}=window._fbFS;
      const snap=await getDoc(doc(window.db,'users',cu.uid,'profile','data'));
      if(snap.exists()){window.userProfile=snap.data();}
      else{window.userProfile={displayName:cu.displayName||'',username:(cu.displayName||'user').toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user',bio:'',location:'',website:'',avatarUrl:cu.photoURL||'',coverGradient:COVER_PRESETS[0],joinedAt:new Date().toISOString()};await setDoc(doc(window.db,'users',cu.uid,'profile','data'),window.userProfile,{merge:true});}
      window.applyProfileToUI();
    }catch(e){console.warn('Profile load:',e.message)}
  }

  /* ── Edit profile ── */
  window.openEditProfileModal=function(){
    const p=window.userProfile||{},cu=getCurrentUser();
    document.getElementById('edit-fullname').value=p.displayName||cu?.displayName||'';
    document.getElementById('edit-username').value=p.username||'';
    document.getElementById('edit-bio').value=p.bio||'';
    document.getElementById('edit-location').value=p.location||'';
    document.getElementById('edit-website').value=p.website||'';
    document.getElementById('edit-profile-modal').classList.add('open');
    setTimeout(()=>document.getElementById('edit-fullname').focus(),100);
  };
  window.saveProfile=async function(){
    const fullName=document.getElementById('edit-fullname').value.trim();
    const rawUser=document.getElementById('edit-username').value.trim().toLowerCase().replace(/[^a-z0-9_]/g,'');
    if(!fullName){window.toast?.('Name cannot be empty','⚠️');return}
    if(!rawUser){window.toast?.('Username cannot be empty','⚠️');return}
    window.userProfile={...(window.userProfile||{}),displayName:fullName,username:rawUser,bio:document.getElementById('edit-bio').value.trim(),location:document.getElementById('edit-location').value.trim(),website:document.getElementById('edit-website').value.trim()};
    try{await setUserProfile(window.userProfile);window.applyProfileToUI();closeModal('edit-profile-modal');window.toast?.('Profile updated! ✨','👤');await syncPostAuthorData(fullName,currentAvatarUrl());}
    catch(e){window.toast?.('Error: '+e.message,'❌')}
  };

  /* ── Avatar ── */
  window.openAvatarModal=function(){
    const p=window.userProfile||{},cu=getCurrentUser();
    _selectedAvatarUrl=p.avatarUrl||cu?.photoURL||'';
    document.getElementById('avatar-preset-grid').innerHTML=AVATAR_PRESETS.map((url,i)=>`<img src="${url}" class="avatar-option ${_selectedAvatarUrl===url?'selected':''}" onclick="selectAvatarPreset('${url}',this)" alt="Avatar ${i+1}">`).join('');
    const fi=document.getElementById('avatar-upload');fi.value='';
    fi.onchange=async e=>{const f=e.target.files[0];if(!f)return;window.toast?.('Compressing…','📷');try{_selectedAvatarUrl=await _compressImageTo(f,80*1024);window.toast?.('Avatar ready!','✅')}catch{window.toast?.('Could not read image','❌')}};
    document.getElementById('avatar-modal').classList.add('open');
  };
  window.selectAvatarPreset=function(url,el){_selectedAvatarUrl=url;document.querySelectorAll('.avatar-option').forEach(o=>o.classList.remove('selected'));el.classList.add('selected')};
  window.saveAvatar=async function(){
    if(!_selectedAvatarUrl){window.toast?.('Pick an avatar first','⚠️');return}
    window.userProfile={...(window.userProfile||{}),avatarUrl:_selectedAvatarUrl};
    try{await setUserProfile(window.userProfile);window.applyProfileToUI();closeModal('avatar-modal');window.toast?.('Avatar updated!','🖼');await syncPostAuthorData(window.userProfile.displayName||getCurrentUser()?.displayName||'Anonymous',_selectedAvatarUrl);}
    catch(e){window.toast?.('Error: '+e.message,'❌')}
  };

  /* ── Cover ── */
  window.openCoverModal=function(){
    const p=window.userProfile||{};_selectedCoverData={value:p.coverGradient||COVER_PRESETS[0]};
    document.getElementById('cover-preset-grid').innerHTML=COVER_PRESETS.map(g=>`<div class="cover-preset ${p.coverGradient===g?'selected':''}" style="background:${g}" onclick="selectCoverPreset('${g}',this)"></div>`).join('');
    document.getElementById('cover-live-preview').style.background=_selectedCoverData.value;
    document.getElementById('hex-text-input').value='';document.getElementById('hex-color-wheel').value='#4f8ef7';
    const wheel=document.getElementById('hex-color-wheel'),tin=document.getElementById('hex-text-input');
    wheel.oninput=()=>{tin.value=wheel.value;_previewHex(wheel.value)};
    tin.oninput=()=>{const h=tin.value.trim();if(/^#[0-9a-fA-F]{6}$/.test(h)){wheel.value=h;_previewHex(h)}};
    document.getElementById('cover-modal').classList.add('open');
  };
  function _hexRgb(hex){return{r:parseInt(hex.slice(1,3),16),g:parseInt(hex.slice(3,5),16),b:parseInt(hex.slice(5,7),16)}}
  function _darken(hex,f){const{r,g,b}=_hexRgb(hex);const d=v=>Math.round(Math.max(0,v*f)).toString(16).padStart(2,'0');return`#${d(r)}${d(g)}${d(b)}`}
  function _hexGrad(hex){return`linear-gradient(135deg,${_darken(hex,.18)},${_darken(hex,.55)},${_darken(hex,.1)})`}
  function _previewHex(hex){const g=_hexGrad(hex);document.getElementById('cover-live-preview').style.background=g;document.querySelectorAll('.cover-preset').forEach(p=>p.classList.remove('selected'));_selectedCoverData={value:g}}
  window.applyHexCover=function(){let h=document.getElementById('hex-text-input').value.trim();if(/^#[0-9a-fA-F]{3}$/.test(h))h='#'+h[1]+h[1]+h[2]+h[2]+h[3]+h[3];if(!/^#[0-9a-fA-F]{6}$/.test(h))h=document.getElementById('hex-color-wheel').value;_previewHex(h);window.toast?.('Preview updated!','🎨')};
  window.selectCoverPreset=function(g,el){_selectedCoverData={value:g};document.querySelectorAll('.cover-preset').forEach(p=>p.classList.remove('selected'));el.classList.add('selected');document.getElementById('cover-live-preview').style.background=g;document.getElementById('hex-text-input').value=''};
  window.saveCover=async function(){
    if(!_selectedCoverData)return;
    window.userProfile={...(window.userProfile||{}),coverGradient:_selectedCoverData.value};
    try{await setUserProfile(window.userProfile);const c=document.getElementById('profile-cover-display');if(c)c.style.background=_selectedCoverData.value;closeModal('cover-modal');window.toast?.('Cover updated!','🖼')}
    catch(e){window.toast?.('Error: '+e.message,'❌')}
  };

  /* ── Image compression ── */
  function _compressImageTo(file,targetBytes){
    return new Promise((resolve,reject)=>{
      const reader=new FileReader();reader.onerror=reject;
      reader.onload=ev=>{const img=new Image();img.onerror=reject;img.onload=()=>{
        const canvas=document.createElement('canvas'),ctx=canvas.getContext('2d');
        let maxDim=400,quality=0.85,result='';
        for(let i=0;i<8;i++){let w=img.width,h=img.height;if(w>h){if(w>maxDim){h=Math.round(h*maxDim/w);w=maxDim}}else{if(h>maxDim){w=Math.round(w*maxDim/h);h=maxDim}}canvas.width=w;canvas.height=h;ctx.clearRect(0,0,w,h);ctx.drawImage(img,0,0,w,h);result=canvas.toDataURL('image/jpeg',quality);if(result.length*0.75<=targetBytes)break;if(quality>0.3)quality-=0.12;else{maxDim=Math.round(maxDim*0.75);quality=0.7}}
        resolve(result)};img.src=ev.target.result};reader.readAsDataURL(file)});
  }

  /* ── Modal helpers ── */
  window.closeModal=function(id){document.getElementById(id)?.classList.remove('open')};
  document.querySelectorAll('.modal-backdrop').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open')}));

  /* ── User profile popup ── */
  window.openUserProfileModal=async function(authorId){
    if(!authorId)return;
    const modal=document.getElementById('user-profile-modal');modal.classList.add('open');document.body.style.overflow='hidden';
    document.getElementById('upm-name').textContent='Loading…';
    ['upm-username','upm-bio'].forEach(id=>{const el=document.getElementById(id);if(el)el.textContent=''});
    document.getElementById('upm-info').innerHTML='';
    ['upm-posts','upm-likes','upm-joined'].forEach(id=>{const el=document.getElementById(id);if(el)el.textContent='—'});
    document.getElementById('upm-posts-list').innerHTML='<div style="font-family:\'JetBrains Mono\',monospace;font-size:.65rem;color:var(--muted);padding:8px">Loading…</div>';
    const feedPosts=window.feedPosts||[],authorPosts=feedPosts.filter(p=>p.authorId===authorId);
    const totalLikes=authorPosts.reduce((s,p)=>s+(p.likes?.length||0),0),sample=authorPosts[0];
    let displayName=sample?.authorName||'Anonymous',username=getUsernameFromPost(sample||{authorId}),bio='',location='',website='';
    let avatarUrl=sample?.authorAvatar||`https://ui-avatars.com/api/?name=${encodeURIComponent(displayName)}&background=4f8ef7&color=fff`,coverGradient=COVER_PRESETS[0],joinedAt='—';
    try{const{getDoc,doc}=window._fbFS;const snap=await getDoc(doc(window.db,'users',authorId,'profile','data'));if(snap.exists()){const pr=snap.data();if(pr.displayName)displayName=pr.displayName;if(pr.username)username=pr.username;if(pr.bio)bio=pr.bio;if(pr.location)location=pr.location;if(pr.website)website=pr.website;if(pr.avatarUrl)avatarUrl=pr.avatarUrl;if(pr.coverGradient)coverGradient=pr.coverGradient;if(pr.joinedAt)joinedAt=new Date(pr.joinedAt).toLocaleDateString('en-US',{month:'short',year:'numeric'})}}catch(_){}
    document.getElementById('upm-cover').style.background=coverGradient;
    document.getElementById('upm-avatar').src=avatarUrl;
    document.getElementById('upm-name').textContent=displayName;
    document.getElementById('upm-username').textContent='@'+username;
    document.getElementById('upm-bio').textContent=bio;
    document.getElementById('upm-posts').textContent=authorPosts.length;
    document.getElementById('upm-likes').textContent=totalLikes;
    document.getElementById('upm-joined').textContent=joinedAt;
    const infoParts=[];if(location)infoParts.push(`📍 ${esc(location)}`);if(website)infoParts.push(`🔗 <a href="${esc(website)}" target="_blank" style="color:var(--accent);text-decoration:none">${esc(website.replace(/^https?:\/\//,''))}</a>`);
    document.getElementById('upm-info').innerHTML=infoParts.join('<span style="margin:0 8px;opacity:.3">·</span>');
    const postsEl=document.getElementById('upm-posts-list');
    if(!authorPosts.length){postsEl.innerHTML=`<div style="text-align:center;font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted);padding:8px">No public posts yet.</div>`}
    else{postsEl.innerHTML=`<div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:10px">Recent Posts</div>`+authorPosts.slice(0,10).map(p=>`<div class="user-post-mini" onclick="closeUserProfileModal();setTimeout(()=>window.openExpandedPost&&window.openExpandedPost('${p.id}'),200)"><div style="display:flex;align-items:center;gap:6px;margin-bottom:6px"><span class="post-type-badge ${TYPE_BADGE_CLASS[p.type]||'badge-journal'}" style="font-size:.55rem">${TYPE_BADGES[p.type]||p.type}</span><span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted)">${relativeTime(p.createdAt)}</span></div>${p.title?`<div class="user-post-mini-title">${esc(p.title)}</div>`:''}<div class="user-post-mini-body">${esc(p.body||'')}</div><div style="display:flex;gap:10px;margin-top:6px;font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)"><span>❤️ ${(p.likes||[]).length}</span><span>💬 ${p.commentCount||0}</span></div></div>`).join('')}
  };
  window.closeUserProfileModal=function(){document.getElementById('user-profile-modal').classList.remove('open');document.body.style.overflow=''};
  document.getElementById('user-profile-modal').addEventListener('click',e=>{if(e.target===document.getElementById('user-profile-modal'))window.closeUserProfileModal()});

  /* ── Expand post ── */
  window.closeExpandedPost=function(){document.getElementById('post-expand-overlay').classList.remove('open');document.body.style.overflow='';_expandedPostId=null};
  document.getElementById('post-expand-overlay').addEventListener('click',e=>{if(e.target===document.getElementById('post-expand-overlay'))window.closeExpandedPost()});

  /* ── Escape key ── */
  document.addEventListener('keydown',e=>{
    if(e.key!=='Escape')return;
    if(document.getElementById('user-profile-modal').classList.contains('open')){window.closeUserProfileModal();return}
    if(document.getElementById('post-expand-overlay').classList.contains('open')){window.closeExpandedPost()}
  });

  /* ── Silent backfill ── */
  async function backfillIfNeeded(){
    await new Promise(r=>setTimeout(r,2500));
    const cu=getCurrentUser();if(!cu||!window.userProfile)return;
    const cName=window.userProfile.displayName||cu.displayName||'Anonymous',cAv=currentAvatarUrl();
    const myPosts=(window.feedPosts||[]).filter(p=>p.authorId===cu.uid);
    if(myPosts.some(p=>p.authorName!==cName||p.authorAvatar!==cAv)){await syncPostAuthorData(cName,cAv)}
  }

  /* ── Boot ── */
  waitForFirebase(()=>{
    const unsub=window.auth.onAuthStateChanged(user=>{
      if(user){window.currentUser=user;loadUserProfile();backfillIfNeeded();unsub()}
    });
  });
})();
</script>