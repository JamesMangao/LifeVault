{{-- LIFEVAULT — community.blade.php (COMPLETE) --}}
<style>
:root{--bg:#0b0f1a;--surface:#111827;--surface2:#1a2235;--border:rgba(255,255,255,.07);--text:#e8eaf0;--muted:#6b7a99;--accent:#4f8ef7;--green:#34d399;--amber:#fbbf24;--rose:#f87171;--lavender:#a78bfa;--teal:#2dd4bf}
@keyframes pageIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
@keyframes heartBeat{0%{transform:scale(1)}40%{transform:scale(1.4)}70%{transform:scale(.9)}100%{transform:scale(1)}}
@keyframes overlayFadeIn{from{opacity:0}to{opacity:1}}
@keyframes expandCardIn{from{opacity:0;transform:scale(.88) translateY(24px)}to{opacity:1;transform:scale(1) translateY(0)}}
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
.post-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;margin-bottom:16px;transition:border-color .2s,box-shadow .2s;animation:slideDown .3s ease both}
.post-card:hover{border-color:rgba(79,142,247,.25);box-shadow:0 4px 20px rgba(79,142,247,.08)}
.post-card-body{cursor:pointer}
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
/* Expanded post overlay */
.post-expand-overlay{position:fixed;inset:0;z-index:300;display:none;align-items:center;justify-content:center;padding:24px}
.post-expand-overlay.open{display:flex;background:rgba(11,15,26,.92);animation:overlayFadeIn .3s ease both}
.post-expanded-card{background:var(--surface);border:1px solid rgba(79,142,247,.25);border-radius:20px;max-width:720px;width:100%;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 0 80px rgba(79,142,247,.12),0 40px 80px rgba(0,0,0,.6);animation:expandCardIn .38s cubic-bezier(.34,1.4,.64,1) both;overflow:hidden}
.post-expanded-header{padding:20px 24px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;background:linear-gradient(135deg,rgba(79,142,247,.06),rgba(167,139,250,.04))}
.post-expanded-body{padding:24px;overflow-y:auto;flex:1}
.post-expanded-footer{padding:14px 24px;border-top:1px solid var(--border);display:flex;align-items:center;gap:8px;flex-shrink:0;background:var(--surface2)}
.expanded-close-btn{background:var(--surface2);border:1px solid var(--border);color:var(--muted);cursor:pointer;font-size:.9rem;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0}
.expanded-close-btn:hover{color:var(--rose);border-color:var(--rose);background:rgba(248,113,113,.08)}
/* Comments */
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
/* Share modal */
.comm-modal-backdrop{position:fixed;inset:0;background:rgba(11,15,26,.92);z-index:500;display:none;align-items:center;justify-content:center;padding:20px}
.comm-modal-backdrop.open{display:flex}
.comm-modal{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:32px;max-width:580px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 40px 80px rgba(0,0,0,.5)}
.comm-modal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.comm-modal-title{font-size:1.2rem;font-weight:800;letter-spacing:-.02em}
.comm-modal-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:1.2rem;line-height:1;transition:color .2s;padding:4px}
.comm-modal-close:hover{color:var(--rose)}
.form-group{margin-bottom:16px}
.form-label{display:block;font-family:'JetBrains Mono',monospace;font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:8px}
.comm-form-input,.comm-form-textarea{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;color:var(--text);font-family:'Syne',sans-serif;font-size:.85rem;outline:none;transition:border-color .2s;box-sizing:border-box}
.comm-form-input:focus,.comm-form-textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(79,142,247,.12)}
.comm-form-textarea{resize:vertical;min-height:120px;line-height:1.6;font-family:'Newsreader',serif;font-size:.95rem;font-weight:300}
@media(max-width:640px){.community-stats{grid-template-columns:1fr}.feed-container{max-width:100%}}
</style>

{{-- ═══════════ COMMUNITY PAGE ═══════════ --}}
<div id="page-community" class="page">
  <div class="page-header">
    <div>
      <div class="page-title">Community 🌐</div>
      <div class="page-subtitle">Share your journey · inspire others · grow together</div>
    </div>
    <button class="btn" onclick="openShareModal()" style="border-color:rgba(45,212,191,.3);color:var(--teal)">↗ Share Something</button>
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
          <button class="composer-type-btn" data-type="journal" onclick="openShareModal('journal')">📓 Journal</button>
          <button class="composer-type-btn" data-type="task" onclick="openShareModal('task')">✅ Task</button>
          <button class="composer-type-btn" data-type="goal" onclick="openShareModal('goal')">🎯 Goal</button>
          <label class="composer-type-btn" style="cursor:pointer" title="Add images">
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
    <div id="feed-list"></div>
  </div>
</div>

{{-- ═══════════ EXPANDED POST OVERLAY ═══════════ --}}
<div class="post-expand-overlay" id="post-expand-overlay-comm" onclick="if(event.target===this)closeExpandedPost()">
  <div class="post-expanded-card">
    <div class="post-expanded-header">
      <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
        <img id="exp-avatar" src="" style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:1.5px solid var(--border);flex-shrink:0">
        <div style="flex:1;min-width:0">
          <div id="exp-author-row" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap"></div>
          <div id="exp-time" style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted);margin-top:2px"></div>
        </div>
      </div>
      <button class="expanded-close-btn" onclick="closeExpandedPost()">✕</button>
    </div>
    <div class="post-expanded-body" id="exp-body"></div>
    <div class="post-expanded-footer" id="exp-footer"></div>
  </div>
</div>

{{-- ═══════════ SHARE MODAL ═══════════ --}}
<div class="comm-modal-backdrop" id="share-modal" onclick="if(event.target===this)closeShareModal()">
  <div class="comm-modal">
    <div class="comm-modal-header">
      <div class="comm-modal-title" id="share-modal-title">↗ Share Something</div>
      <button class="comm-modal-close" onclick="closeShareModal()">✕</button>
    </div>
    <div id="share-modal-body"></div>
  </div>
</div>

<script>
(function(){
  const TYPE_BADGES      = {thought:'💭 Thought',journal:'📓 Journal',task:'✅ Task',goal:'🎯 Goal'};
  const TYPE_BADGE_CLASS = {thought:'badge-journal',journal:'badge-journal',task:'badge-task',goal:'badge-goal'};

  function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
  function relativeTime(date){
    if(!date)return '';
    const diff=Date.now()-new Date(date).getTime(),mins=Math.floor(diff/60000);
    if(mins<1)return 'just now';if(mins<60)return mins+'m ago';
    const hrs=Math.floor(mins/60);if(hrs<24)return hrs+'h ago';
    const days=Math.floor(hrs/24);if(days<7)return days+'d ago';
    return new Date(date).toLocaleDateString('en-US',{month:'short',day:'numeric'});
  }
  function getHandle(p){return p.authorUsername||(p.authorName||'anonymous').toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user'}
  function waitForFirebase(cb){if(window.db&&window.auth&&window._fbFS)cb();else setTimeout(()=>waitForFirebase(cb),80)}

  let _currentFilter='all', _composerType='thought', _composerPhotos=[], _expandedPostId=null;



  /* ── filterFeed ── */
  window.filterFeed=function(type,btn){
    _currentFilter=type;
    document.querySelectorAll('.feed-filter-btn').forEach(b=>b.classList.remove('active'));
    if(btn) btn.classList.add('active');
    window.renderFeed();
  };

  /* ── composer helpers ── */
  window.setComposerType=function(type){
    _composerType=type;
    document.querySelectorAll('.composer-type-btn').forEach(b=>b.classList.toggle('active',b.dataset.type===type));
  };

  window.handleComposerPhotos=function(e){
    const files=Array.from(e.target.files); if(!files.length) return;
    const preview=document.getElementById('composer-img-previews'); preview.style.display='flex';
    files.forEach(file=>{
      const reader=new FileReader();
      reader.onload=ev=>{
        _composerPhotos.push(ev.target.result);
        const div=document.createElement('div'); div.className='composer-img-preview';
        div.innerHTML=`<img src="${ev.target.result}">
          <button class="composer-img-remove"
                  onclick="removeComposerPhoto(${_composerPhotos.length-1},this.parentElement)">✕</button>`;
        preview.appendChild(div);
      };
      reader.readAsDataURL(file);
    });
  };

  window.removeComposerPhoto=function(idx,el){
    _composerPhotos.splice(idx,1); el.remove();
    if(!_composerPhotos.length) document.getElementById('composer-img-previews').style.display='none';
  };

  /* ── postThought ── */
  window.postThought=async function(){
    const text=document.getElementById('composer-text').value.trim();
    if(!text&&!_composerPhotos.length){ window.toast?.('Write something first!','💭'); return; }
    const cu=window.currentUser; if(!cu) return;
    const p=window.userProfile||{};
    const authorName=p.displayName||cu.displayName||'Anonymous';
    const authorAvatar=p.avatarUrl||cu.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
    try{
      const{addDoc,collection,serverTimestamp}=window._fbFS;
      await addDoc(collection(window.db,'community_posts'),{
        type:_composerType,body:text,authorId:cu.uid,authorName,authorAvatar,
        authorUsername:p.username||(authorName.toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user'),
        likes:[],commentCount:0,repostCount:0,photoUrls:_composerPhotos,createdAt:serverTimestamp(),
      });
      document.getElementById('composer-text').value='';
      _composerPhotos=[];
      const prev=document.getElementById('composer-img-previews'); prev.innerHTML=''; prev.style.display='none';
      window.toast?.('Posted! ✨','🌐');
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ── toggleLike ── */
  window.toggleLike=async function(postId){
    const cu=window.currentUser; if(!cu) return;
    const post=(window.feedPosts||[]).find(p=>p.id===postId); if(!post) return;
    const liked=(post.likes||[]).includes(cu.uid);
    try{
      const{updateDoc,doc,arrayUnion,arrayRemove}=window._fbFS;
      await updateDoc(doc(window.db,'community_posts',postId),{likes:liked?arrayRemove(cu.uid):arrayUnion(cu.uid)});
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ── toggleReadMore ── */
  window.toggleReadMore=function(postId){
    const el=document.getElementById('post-body-'+postId); if(!el) return;
    const expanded=el.style.webkitLineClamp==='unset';
    el.style.webkitLineClamp=expanded?'4':'unset';
    el.style.overflow=expanded?'hidden':'visible';
    const btn=el.nextElementSibling;
    if(btn&&btn.classList.contains('post-read-more')) btn.textContent=expanded?'Read more ↓':'Show less ↑';
  };

  /* ── viewPhoto ── */
  window.viewPhoto=function(url){
    const overlay=document.createElement('div');
    overlay.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:9999999;display:flex;align-items:center;justify-content:center;cursor:pointer';
    overlay.innerHTML=`<img src="${esc(url)}" style="max-width:90vw;max-height:90vh;border-radius:12px;object-fit:contain">`;
    overlay.onclick=()=>overlay.remove();
    document.body.appendChild(overlay);
  };

  /* ── deletePost ── */
  window.deletePost=async function(postId){
    if(!confirm('Delete this post?')) return;
    try{
      const{deleteDoc,doc}=window._fbFS;
      await deleteDoc(doc(window.db,'community_posts',postId));
      window.toast?.('Post deleted','🗑️');
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ── repost ── */
  window.repost=async function(postId){
    const cu=window.currentUser; if(!cu) return;
    const original=(window.feedPosts||[]).find(p=>p.id===postId); if(!original) return;
    if(original.authorId===cu.uid){ window.toast?.('Cannot repost your own post','⚠️'); return; }
    const p=window.userProfile||{};
    const authorName=p.displayName||cu.displayName||'Anonymous';
    const authorAvatar=p.avatarUrl||cu.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
    try{
      const{addDoc,collection,serverTimestamp,updateDoc,doc,increment}=window._fbFS;
      await addDoc(collection(window.db,'community_posts'),{
        type:original.type,title:original.title,body:original.body,
        authorId:cu.uid,authorName,authorAvatar,
        authorUsername:p.username||(authorName.toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user'),
        isRepost:true,originalPostId:postId,
        originalAuthorName:original.authorName,originalAuthorAvatar:original.authorAvatar,
        likes:[],commentCount:0,repostCount:0,createdAt:serverTimestamp(),
      });
      await updateDoc(doc(window.db,'community_posts',postId),{repostCount:increment(1)});
      window.toast?.('Reposted! 🔁','🌐');
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ─────────────────────────────────────────────
     openExpandedPost
     Works for ALL post types: thought, journal,
     task, goal. Triggered by the card root click.
  ───────────────────────────────────────────── */
  window.openExpandedPost=function(postId){
    const post=(window.feedPosts||[]).find(p=>p.id===postId); if(!post) return;
    _expandedPostId=postId;
    const cu=window.currentUser, isOwn=post.authorId===cu?.uid, liked=(post.likes||[]).includes(cu?.uid);
    const bc=TYPE_BADGE_CLASS[post.type]||'badge-journal', handle=getHandle(post);

    /* header */
    document.getElementById('exp-avatar').src=post.authorAvatar||'';
    document.getElementById('exp-time').textContent=
      post.createdAt
        ? new Date(post.createdAt).toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'})
        : '';
    document.getElementById('exp-author-row').innerHTML=`
      <button class="post-author-btn"
              onclick="closeExpandedPost();setTimeout(()=>openUserProfileModal('${esc(post.authorId)}'),200)">
        <span class="post-author-name">${esc(post.authorName||'Anonymous')}</span>
        <span class="post-author-username">@${esc(handle)}</span>
      </button>
      <span class="post-type-badge ${bc}">${TYPE_BADGES[post.type]||post.type}</span>
      ${isOwn?`<span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:var(--muted);
                            padding:2px 6px;border-radius:4px;background:var(--surface2)">you</span>`:''}`;

    /* body */
    let bodyHtml='';

    if(post.title)
      bodyHtml+=`<div style="font-size:1.1rem;font-weight:800;letter-spacing:-.02em;
                              margin-bottom:14px;line-height:1.3">${esc(post.title)}</div>`;

    if(post.type==='goal'){
      bodyHtml+=`
        <div style="background:var(--surface2);border-radius:99px;height:8px;overflow:hidden;margin-bottom:6px">
          <div style="height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent),var(--lavender));
                      width:${post.progress||0}%"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-family:'JetBrains Mono',monospace;
                    font-size:.65rem;color:var(--muted);margin-bottom:14px">
          <span>${post.categoryIcon||'🎯'} ${esc(post.title||'')}</span>
          <span>${post.progress||0}% complete</span>
        </div>
        ${post.body?`<div style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.75;
                                 color:rgba(232,234,240,.8);font-weight:300">${esc(post.body)}</div>`:''}`;

    } else if(post.type==='task'){
      bodyHtml+=`
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
          <span>${post.priorityIcon||'✅'}</span>
          <span style="font-size:.75rem;font-family:'JetBrains Mono',monospace;
                       color:var(--muted);text-transform:uppercase">${post.priority||''} priority</span>
          ${post.done?`<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;
                                    color:var(--green)">· Done ✓</span>`:''}
        </div>
        ${post.body?`<div style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.75;
                                 color:rgba(232,234,240,.8);font-weight:300">${esc(post.body)}</div>`:''}`;

    } else {
      /* thought / journal */
      if(post.moodEmoji)
        bodyHtml+=`<div style="margin-bottom:12px;font-size:.8rem;color:var(--muted);
                                font-family:'JetBrains Mono',monospace">feeling ${post.moodEmoji}</div>`;

      bodyHtml+=`<div style="font-family:'Newsreader',serif;font-size:1rem;line-height:1.85;
                              color:rgba(232,234,240,.85);font-weight:300;
                              white-space:pre-wrap;word-break:break-word">${esc(post.body||'')}</div>`;

      if(post.photoUrls?.length)
        bodyHtml+=`<div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:16px">
          ${post.photoUrls.map(u=>`<img src="${esc(u)}"
               style="width:120px;height:120px;border-radius:10px;object-fit:cover;
                      border:1px solid var(--border);cursor:pointer;transition:transform .2s"
               onmouseover="this.style.transform='scale(1.04)'"
               onmouseout="this.style.transform=''"
               onclick="viewPhoto('${esc(u)}')">`).join('')}
        </div>`;

      if(post.tags?.length)
        bodyHtml+=`<div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:14px">
          ${post.tags.map(t=>`<span class="tag"
               style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}
        </div>`;
    }

    /* comments section */
    const myAv=window.userProfile?.avatarUrl||cu?.photoURL||
      `https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff`;
    bodyHtml+=`
      <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border)">
        <div id="exp-comments-list" style="margin-bottom:12px">
          <div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted)">
            Loading comments…
          </div>
        </div>
        <div class="comment-input-row">
          <img src="${esc(myAv)}"
               style="width:28px;height:28px;border-radius:50%;object-fit:cover;
                      border:1px solid var(--border);flex-shrink:0">
          <input class="comment-input" id="exp-comment-input"
                 placeholder="Write a comment…"
                 onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();submitComment('${postId}')}">
          <button class="comment-submit" onclick="submitComment('${postId}')">↵</button>
        </div>
      </div>`;

    const bodyEl=document.getElementById('exp-body');
    if(bodyEl){ bodyEl.innerHTML=bodyHtml; bodyEl.scrollTop=0; }

    /* footer actions */
    document.getElementById('exp-footer').innerHTML=`
      <button class="post-action-btn ${liked?'liked':''}" id="exp-like-btn"
              onclick="toggleLikeExpanded('${postId}')">
        <span class="heart-icon">${liked?'❤️':'🤍'}</span>
        <span id="exp-like-count" class="post-action-count">${(post.likes||[]).length||''}</span>
      </button>
      <button class="post-action-btn" onclick="repost('${postId}')">
        🔁 <span class="post-action-count">${post.repostCount||''}</span>
      </button>
      ${isOwn?`<button class="post-action-btn"
                       style="margin-left:auto;color:var(--rose)"
                       onclick="deletePost('${postId}');closeExpandedPost()">
                 🗑️ Delete
               </button>`:''}`;

    document.getElementById('post-expand-overlay-comm').classList.add('open');
    document.body.style.overflow='hidden';
    loadComments(postId);
  };

  /* ── closeExpandedPost ── */
  window.closeExpandedPost=function(){
    document.getElementById('post-expand-overlay-comm').classList.remove('open');
    document.body.style.overflow='';
    _expandedPostId=null;
  };

  /* ── loadComments ── */
  async function loadComments(postId){
    const listEl=document.getElementById('exp-comments-list'); if(!listEl) return;
    try{
      const{getDocs,query,collection,orderBy}=window._fbFS;
      const snap=await getDocs(query(collection(window.db,'community_posts',postId,'comments'),orderBy('createdAt','asc')));
      const comments=snap.docs.map(d=>({id:d.id,...d.data(),createdAt:d.data().createdAt?.toDate()}));
      if(!comments.length){
        listEl.innerHTML=`<div style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted)">No comments yet. Be first!</div>`;
        return;
      }
      const cu=window.currentUser;
      listEl.innerHTML=comments.map(c=>`
        <div class="comment-item">
          <img src="${esc(c.authorAvatar||'')}" class="comment-avatar"
               onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
          <div class="comment-bubble">
            <div class="comment-author">
              <span>${esc(c.authorName||'Anonymous')}</span>
              ${c.authorId===cu?.uid
                ? `<button class="comment-del" onclick="deleteComment('${postId}','${c.id}')">✕</button>`
                : ''}
            </div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);margin-bottom:4px">
              ${c.createdAt
                ? new Date(c.createdAt).toLocaleDateString('en-US',{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'})
                : ''}
            </div>
            <div class="comment-text">${esc(c.text)}</div>
          </div>
        </div>`).join('');
    }catch(err){
      const listEl=document.getElementById('exp-comments-list');
      if(listEl) listEl.innerHTML=`<div style="font-size:.72rem;color:var(--muted)">Could not load comments.</div>`;
    }
  }

  /* ── submitComment ── */
  window.submitComment=async function(postId){
    const input=document.getElementById('exp-comment-input');
    const text=input?.value.trim(); if(!text) return; input.value='';
    const cu=window.currentUser; if(!cu) return;
    const p=window.userProfile||{};
    const authorName=p.displayName||cu.displayName||'Anonymous';
    const authorAvatar=p.avatarUrl||cu.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
    try{
      const{addDoc,collection,serverTimestamp,updateDoc,doc,increment}=window._fbFS;
      await addDoc(collection(window.db,'community_posts',postId,'comments'),{
        text,authorId:cu.uid,authorName,authorAvatar,createdAt:serverTimestamp()
      });
      await updateDoc(doc(window.db,'community_posts',postId),{commentCount:increment(1)});
      loadComments(postId);
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ── deleteComment ── */
  window.deleteComment=async function(postId,commentId){
    try{
      const{deleteDoc,doc,updateDoc,increment}=window._fbFS;
      await deleteDoc(doc(window.db,'community_posts',postId,'comments',commentId));
      await updateDoc(doc(window.db,'community_posts',postId),{commentCount:increment(-1)});
      loadComments(postId);
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ── toggleLikeExpanded ── */
  window.toggleLikeExpanded=async function(postId){
    const post=(window.feedPosts||[]).find(p=>p.id===postId); if(!post) return;
    const cu=window.currentUser, liked=(post.likes||[]).includes(cu?.uid);
    try{
      const{updateDoc,doc,arrayUnion,arrayRemove}=window._fbFS;
      await updateDoc(doc(window.db,'community_posts',postId),{
        likes:liked?arrayRemove(cu.uid):arrayUnion(cu.uid)
      });
      const btn=document.getElementById('exp-like-btn');
      const cnt=document.getElementById('exp-like-count');
      if(btn) btn.classList.toggle('liked',!liked);
      if(cnt){
        const n=liked
          ? Math.max(0,(parseInt(cnt.textContent)||0)-1)
          : (parseInt(cnt.textContent)||0)+1;
        cnt.textContent=n||'';
      }
      const hrt=btn?.querySelector('.heart-icon');
      if(hrt) hrt.textContent=liked?'🤍':'❤️';
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ── openShareModal ── */
  window.openShareModal=function(type){
    const modal=document.getElementById('share-modal'); if(!modal) return;
    const t=type||'journal';
    document.getElementById('share-modal-title').textContent=`↗ Share ${t.charAt(0).toUpperCase()+t.slice(1)}`;
    let bodyHtml='';

    if(t==='journal'){
      bodyHtml=`
        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" class="comm-form-input" id="sm-title" placeholder="Entry title...">
        </div>
        <div class="form-group">
          <label class="form-label">Content</label>
          <textarea class="comm-form-textarea" id="sm-body"
                    placeholder="What happened, what you felt…" style="min-height:140px"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Mood Emoji <span style="opacity:.5">(optional)</span></label>
          <input type="text" class="comm-form-input" id="sm-mood" placeholder="😊 🌧 🔥" style="max-width:120px">
        </div>
        <div class="form-group">
          <label class="form-label">Tags <span style="opacity:.5">(comma separated)</span></label>
          <input type="text" class="comm-form-input" id="sm-tags" placeholder="work, school, growth">
        </div>`;

    } else if(t==='task'){
      bodyHtml=`
        <div class="form-group">
          <label class="form-label">Task Title</label>
          <input type="text" class="comm-form-input" id="sm-title" placeholder="What did you accomplish?">
        </div>
        <div class="form-group">
          <label class="form-label">Details <span style="opacity:.5">(optional)</span></label>
          <textarea class="comm-form-textarea" id="sm-body"
                    placeholder="More context…" style="min-height:90px"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Priority</label>
          <select class="comm-form-input" id="sm-priority">
            <option value="low">🟢 Low</option>
            <option value="medium" selected>🟡 Medium</option>
            <option value="high">🔴 High</option>
          </select>
        </div>
        <div class="form-group" style="display:flex;align-items:center;gap:10px">
          <input type="checkbox" id="sm-done" style="width:16px;height:16px">
          <label for="sm-done" style="font-size:.85rem;cursor:pointer">Mark as completed</label>
        </div>`;

    } else if(t==='goal'){
      bodyHtml=`
        <div class="form-group">
          <label class="form-label">Goal Title</label>
          <input type="text" class="comm-form-input" id="sm-title" placeholder="What are you working towards?">
        </div>
        <div class="form-group">
          <label class="form-label">Description <span style="opacity:.5">(optional)</span></label>
          <textarea class="comm-form-textarea" id="sm-body"
                    placeholder="Why this goal matters…" style="min-height:90px"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Progress (0–100%)</label>
          <input type="range" id="sm-progress" min="0" max="100" value="0"
                 oninput="document.getElementById('sm-progress-val').textContent=this.value+'%'"
                 style="width:100%">
          <span id="sm-progress-val"
                style="font-family:'JetBrains Mono',monospace;font-size:.72rem;color:var(--accent)">0%</span>
        </div>
        <div class="form-group">
          <label class="form-label">Category</label>
          <select class="comm-form-input" id="sm-category">
            <option value="health">💪 Health</option>
            <option value="learn">📚 Learning</option>
            <option value="finance">💰 Finance</option>
            <option value="career">💼 Career</option>
            <option value="personal" selected>🌱 Personal</option>
          </select>
        </div>`;
    }

    bodyHtml+=`
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button class="btn" onclick="closeShareModal()">Cancel</button>
        <button class="btn btn-primary" onclick="submitShare('${t}')">Share ↗</button>
      </div>`;

    document.getElementById('share-modal-body').innerHTML=bodyHtml;
    modal.classList.add('open');
  };

  /* ── closeShareModal ── */
  window.closeShareModal=function(){
    document.getElementById('share-modal')?.classList.remove('open');
  };

  /* ── submitShare ── */
  window.submitShare=async function(type){
    const cu=window.currentUser; if(!cu) return;
    const p=window.userProfile||{};
    const authorName=p.displayName||cu.displayName||'Anonymous';
    const authorAvatar=p.avatarUrl||cu.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
    const title=document.getElementById('sm-title')?.value.trim()||'';
    const body=document.getElementById('sm-body')?.value.trim()||'';
    if(!title&&!body){ window.toast?.('Add some content first!','⚠️'); return; }

    const post={
      type,title,body,authorId:cu.uid,authorName,authorAvatar,
      authorUsername:p.username||(authorName.toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user'),
      likes:[],commentCount:0,repostCount:0
    };

    if(type==='journal'){
      post.moodEmoji=document.getElementById('sm-mood')?.value.trim()||'';
      post.tags=(document.getElementById('sm-tags')?.value||'').split(',').map(t=>t.trim()).filter(Boolean);
    }
    if(type==='task'){
      const pri=document.getElementById('sm-priority')?.value||'medium';
      const icons={low:'🟢',medium:'🟡',high:'🔴'};
      post.priority=pri; post.priorityIcon=icons[pri]||'✅';
      post.done=document.getElementById('sm-done')?.checked||false;
    }
    if(type==='goal'){
      const cat=document.getElementById('sm-category')?.value||'personal';
      const icons={health:'💪',learn:'📚',finance:'💰',career:'💼',personal:'🌱',other:'⭐'};
      post.progress=parseInt(document.getElementById('sm-progress')?.value)||0;
      post.category=cat; post.categoryIcon=icons[cat]||'🎯';
    }

    try{
      const{addDoc,collection,serverTimestamp}=window._fbFS;
      post.createdAt=serverTimestamp();
      await addDoc(collection(window.db,'community_posts'),post);
      closeShareModal();
      window.toast?.('Shared! ✨','🌐');
    }catch(e){ window.toast?.('Error: '+e.message,'❌'); }
  };

  /* ── keyboard shortcuts ── */
  document.addEventListener('keydown',e=>{
    if(e.key!=='Escape') return;
    if(document.getElementById('post-expand-overlay-comm')?.classList.contains('open')){
      closeExpandedPost(); return;
    }
    if(document.getElementById('share-modal')?.classList.contains('open')){
      closeShareModal();
    }
  });

  /* ── OVERRIDE renderPostCard ───────────────────────────────
     app.js's renderPostCard does not put onclick on the card.
     It relies on initExpandableCards delegation, which mis-routes
     journal-type community posts to openExpandedJournal().
     
     We override window.renderPostCard here so every card gets
     onclick="window.openExpandedPost(id)" directly on the root
     div. This bypasses delegation entirely — no routing bugs.
     
     We also override window.renderFeed so it calls our version.
  ────────────────────────────────────────────────────────── */
  function _communityRenderPostCard(p){
    if(!window.currentUser) return '';
    const cu=window.currentUser;
    const isOwn=p.authorId===cu.uid, liked=(p.likes||[]).includes(cu.uid);
    const timeAgo=relativeTime(p.createdAt), bc=TYPE_BADGE_CLASS[p.type]||'badge-journal';
    const handle=getHandle(p), pid=p.id;

    const repost=p.isRepost
      ? `<div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--teal);margin-bottom:10px;display:flex;align-items:center;gap:6px">🔁 reposted from <img src="${esc(p.originalAuthorAvatar||'')}" style="width:16px;height:16px;border-radius:50%;object-fit:cover"> <span>${esc(p.originalAuthorName||'')}</span></div>` : '';

    let body='';
    if(p.type==='goal'){
      body=`<div class="post-goal-bar"><div class="post-goal-fill" style="width:${p.progress||0}%"></div></div>
        <div class="post-goal-meta"><span>${p.categoryIcon||'🎯'}</span><span>${p.progress||0}% complete</span></div>
        ${p.body?`<div class="post-body">${esc(p.body)}</div>`:''}`;
    } else if(p.type==='task'){
      body=`<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
          <span>${p.priorityIcon||'✅'}</span>
          <span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--muted);text-transform:uppercase">${p.priority||''} priority</span>
          ${p.done?`<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--green)">· Done ✓</span>`:''}
        </div>
        ${p.body?`<div class="post-body">${esc(p.body)}</div>`:''}`;
    } else {
      const long=(p.body||'').length>300;
      body=`<div class="post-body" id="post-body-${pid}"${long?' style="display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden"':''}>${esc(p.body||'')}</div>
        ${long?`<span class="post-read-more" onclick="event.stopPropagation();toggleReadMore('${pid}')">Read more ↓</span>`:''}
        ${p.moodEmoji?`<div style="margin-top:8px;font-size:.8rem;color:var(--muted);font-family:'JetBrains Mono',monospace">feeling ${p.moodEmoji}</div>`:''}
        ${p.photoUrls?.length?`<div class="post-photos" onclick="event.stopPropagation()">${p.photoUrls.map(u=>`<img src="${esc(u)}" class="post-photo" onclick="event.stopPropagation();viewPhoto('${esc(u)}')">`).join('')}</div>`:''}
        ${p.tags?.length?`<div class="post-tags" onclick="event.stopPropagation()">${p.tags.map(t=>`<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}</div>`:''}`;
    }

    // onclick is on the whole card. Action buttons stop propagation inline.
    return `<div class="post-card" id="post-card-${pid}" data-post-id="${pid}"
        onclick="(function(e){if(!e.target.closest('.post-action-btn,.post-delete-btn,.post-author-btn,.post-read-more,.post-photos,.post-tags')){window.openExpandedPost('${pid}')};})(event)"
        style="cursor:pointer">
      <div class="post-header">
        <img src="${esc(p.authorAvatar||'')}" class="post-avatar" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <div class="post-meta">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
            <button class="post-author-btn" onclick="event.stopPropagation();openUserProfileModal('${p.authorId}')">
              <span class="post-author-name">${esc(p.authorName||'Anonymous')}</span>
              <span class="post-author-username">@${esc(handle)}</span>
            </button>
            <span class="post-type-badge ${bc}">${TYPE_BADGES[p.type]||p.type}</span>
            ${isOwn?`<span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:var(--muted);padding:2px 6px;border-radius:4px;background:var(--surface2)">you</span>`:''}
          </div>
          <div class="post-time">${timeAgo}</div>
        </div>
        ${isOwn?`<button class="post-delete-btn" onclick="event.stopPropagation();deletePost('${pid}')">🗑️</button>`:''}
      </div>
      ${p.title?`<div class="post-title">${esc(p.title)}</div>`:''}
      ${repost}${body}
      <div class="post-actions">
        <button class="post-action-btn ${liked?'liked':''}" onclick="event.stopPropagation();toggleLike('${pid}')">
          <span class="heart-icon">${liked?'❤️':'🤍'}</span>
          <span class="post-action-count">${(p.likes||[]).length||''}</span>
        </button>
        <button class="post-action-btn" onclick="event.stopPropagation();window.openExpandedPost('${pid}')">
          💬 <span class="post-action-count" id="comment-count-${pid}">${p.commentCount||0}</span>
        </button>
        <button class="post-action-btn" onclick="event.stopPropagation();repost('${pid}')">
          🔁 <span class="post-action-count">${p.repostCount||''}</span>
        </button>
      </div>
    </div>`;
  }

  function _communityRenderFeed(){
    const feedList=document.getElementById('feed-list'); if(!feedList) return;
    if(!Array.isArray(window.feedPosts)){
      if(typeof showSkeleton==='function')
        showSkeleton(feedList,5,'<div class="post-card" style="height:120px;margin:8px 0;opacity:.4"></div>');
      return;
    }
    const cu=window.currentUser;
    let posts=[...window.feedPosts];
    if(_currentFilter==='mine') posts=posts.filter(p=>p.authorId===cu?.uid);
    else if(_currentFilter!=='all') posts=posts.filter(p=>p.type===_currentFilter);
    if(!posts.length){
      feedList.innerHTML=`<div class="feed-empty"><div class="empty-icon">🌱</div><div class="empty-title">Nothing here yet</div><div class="empty-sub">Be the first to share something!</div></div>`;
      return;
    }
    feedList.innerHTML=posts.map(p=>_communityRenderPostCard(p)).join('');
    // Update stats
    const all=window.feedPosts;
    const $=id=>document.getElementById(id);
    if($('comm-stat-posts'))   $('comm-stat-posts').textContent=all.length;
    if($('comm-stat-likes'))   $('comm-stat-likes').textContent=all.reduce((s,p)=>s+(p.likes?.length||0),0);
    if($('comm-stat-members')) $('comm-stat-members').textContent=new Set(all.map(p=>p.authorId)).size;
  }

  // Install overrides — do it now and retry to survive load-order races
  function _installOverrides(){
    window.renderPostCard = _communityRenderPostCard;
    window.renderFeed     = _communityRenderFeed;
  }
  _installOverrides();
  setTimeout(_installOverrides, 0);
  setTimeout(_installOverrides, 300);
  setTimeout(_installOverrides, 800);

  /* ── MutationObserver: patch cards after ANY render ───────
     app.js's renderFeed() is module-scoped and always fires,
     overwriting our cards. Instead of fighting it, we watch
     #feed-list with a MutationObserver and immediately add
     onclick to every .post-card that doesn't have one.
     This runs after every render regardless of who triggered it.
  ────────────────────────────────────────────────────────── */
  function _patchCards(root){
    (root||document).querySelectorAll('.post-card[data-post-id]').forEach(card=>{
      const pid=card.getAttribute('data-post-id');
      if(!pid) return;
      // Already patched this card
      if(card.dataset.clickPatched==='1') return;
      card.dataset.clickPatched='1';
      card.style.cursor='pointer';
      card.addEventListener('click',function(e){
        // Ignore clicks on action buttons, author button, photos, tags, read-more
        if(e.target.closest('.post-action-btn,.post-delete-btn,.post-author-btn,.post-read-more,.post-photos,.post-tags')) return;
        if(typeof window.openExpandedPost==='function') window.openExpandedPost(pid);
      });
    });
  }

  function _startObserver(){
    const feedList=document.getElementById('feed-list');
    if(!feedList) return;
    _patchCards(feedList); // patch existing cards immediately
    new MutationObserver(()=>_patchCards(feedList))
      .observe(feedList,{childList:true,subtree:true});
  }

  // Start observer as soon as DOM is ready
  if(document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded',_startObserver);
  } else {
    _startObserver();
    setTimeout(_startObserver,200); // retry in case feed-list not yet in DOM
  }

})();
</script>