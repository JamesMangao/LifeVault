{{--
  _post-expand.blade.php — Guest explore post expand overlay.
  Matches community.blade.php's post-expand-overlay exactly:
  same DOM structure, same CSS classes, same open/close pattern.
  Drop before </body> in explore.blade.php.
--}}

{{-- ═══════════ EXPANDED POST OVERLAY (matches community.blade.php) ═══════════ --}}
<div class="post-expand-overlay" id="post-expand-overlay-comm"
     onclick="if(event.target===this)closeExpandedPost()">
  <div class="post-expanded-card">

    <div class="post-expanded-header">
      <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
        <img id="exp-avatar" src=""
             style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:1.5px solid rgba(255,255,255,.1);flex-shrink:0"
             onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <div style="flex:1;min-width:0">
          <div id="exp-author-row"
               style="display:flex;align-items:center;gap:6px;flex-wrap:wrap"></div>
          <div id="exp-time"
               style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:#6b7a99;margin-top:2px"></div>
        </div>
      </div>
      <button class="expanded-close-btn" onclick="closeExpandedPost()">✕</button>
    </div>

    <div class="post-expanded-body" id="exp-body"></div>

    <div class="post-expanded-footer" id="exp-footer">
      <span style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:#4a5270;display:flex;align-items:center;gap:8px">
        🔒 <span>Sign in to like, comment & share</span>
      </span>
      <button class="btn btn-primary" onclick="window.location.href='/'"
              style="margin-left:auto;padding:7px 16px;font-size:.78rem">
        Sign In →
      </button>
    </div>

  </div>
</div>

<style>
/* ── Match community.blade.php expand overlay styles exactly ── */
@keyframes overlayFadeIn  { from{opacity:0}            to{opacity:1} }
@keyframes expandCardIn   {
    from { opacity:0; transform:scale(.88) translateY(24px); }
    to   { opacity:1; transform:scale(1)   translateY(0);    }
}

.post-expand-overlay {
    position: fixed; inset: 0; z-index: 300;
    display: none; align-items: center; justify-content: center; padding: 24px;
}
.post-expand-overlay.open {
    display: flex;
    background: rgba(11,15,26,.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    animation: overlayFadeIn .3s ease both;
}
.post-expanded-card {
    background: #111827;
    border: 1px solid rgba(79,142,247,.25);
    border-radius: 20px;
    max-width: 720px; width: 100%; max-height: 90vh;
    display: flex; flex-direction: column;
    box-shadow: 0 0 80px rgba(79,142,247,.12), 0 40px 80px rgba(0,0,0,.6);
    animation: expandCardIn .38s cubic-bezier(.34,1.4,.64,1) both;
    overflow: hidden;
}
.post-expanded-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid rgba(255,255,255,.07);
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
    background: linear-gradient(135deg, rgba(79,142,247,.06), rgba(167,139,250,.04));
}
.post-expanded-body {
    padding: 24px; overflow-y: auto; flex: 1;
    scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.08) transparent;
}
.post-expanded-body::-webkit-scrollbar       { width: 4px; }
.post-expanded-body::-webkit-scrollbar-track { background: transparent; }
.post-expanded-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 2px; }
.post-expanded-footer {
    padding: 14px 24px; flex-shrink: 0;
    border-top: 1px solid rgba(255,255,255,.06);
    display: flex; align-items: center; gap: 8px;
    background: rgba(26,34,53,.6);
}
.expanded-close-btn {
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
    color: #6b7a99; cursor: pointer; font-size: .9rem;
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s; flex-shrink: 0;
}
.expanded-close-btn:hover {
    color: #f87171; border-color: #f87171;
    background: rgba(248,113,113,.08);
    transform: rotate(90deg);
}
/* post-type badges (same as community) */
.post-type-badge { font-family:'JetBrains Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.1em;padding:2px 7px;border-radius:4px;font-weight:400 }
.badge-journal   { background:rgba(79,142,247,.15); color:#4f8ef7 }
.badge-task      { background:rgba(52,211,153,.15); color:#34d399 }
.badge-goal      { background:rgba(167,139,250,.15);color:#a78bfa }
/* post body text */
.exp-body-text {
    font-family: 'Newsreader', serif; font-size: 1rem; line-height: 1.85;
    color: rgba(232,234,240,.82); white-space: pre-wrap; word-break: break-word;
    font-weight: 300;
}
/* photo grid */
.exp-photos {
    display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px;
}
.exp-photos img {
    width: 120px; height: 120px; border-radius: 10px;
    object-fit: cover; border: 1px solid rgba(255,255,255,.08);
    cursor: pointer; transition: transform .2s, border-color .2s;
}
.exp-photos img:hover { transform: scale(1.04); border-color: #4f8ef7; }
/* tags */
.exp-tags { display:flex; gap:6px; flex-wrap:wrap; margin-top:14px; }
.exp-tags span {
    font-family:'JetBrains Mono',monospace; font-size:.58rem;
    padding:2px 8px; border-radius:4px; text-transform:uppercase; letter-spacing:.08em;
    background:rgba(79,142,247,.12); color:#4f8ef7;
}
/* goal bar */
.exp-goal-bar { background:rgba(255,255,255,.06); border-radius:99px; height:6px; overflow:hidden; margin:10px 0; }
.exp-goal-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#4f8ef7,#a78bfa); transition:width .6s cubic-bezier(.23,1,.32,1); }

@media(max-width:640px) {
    .post-expand-overlay   { padding: 12px; }
    .post-expanded-header,
    .post-expanded-body,
    .post-expanded-footer  { padding-left: 16px; padding-right: 16px; }
}
</style>

<script>
(function(){
    'use strict';

    var TYPE_BADGES      = {thought:'💭 Thought', journal:'📓 Journal', task:'✅ Task', goal:'🎯 Goal'};
    var TYPE_BADGE_CLASS = {thought:'badge-journal', journal:'badge-journal', task:'badge-task', goal:'badge-goal'};

    function esc(s){
        var d = document.createElement('div');
        d.textContent = String(s || '');
        return d.innerHTML;
    }

    function relativeTime(ts) {
        if (!ts) return '';
        // Handle Firestore Timestamps (have .toDate()) and plain dates/strings
        var date = (ts && typeof ts.toDate === 'function') ? ts.toDate() : new Date(ts);
        var diff = Date.now() - date.getTime();
        var mins = Math.floor(diff / 60000);
        if (mins < 1)  return 'just now';
        if (mins < 60) return mins + 'm ago';
        var hrs = Math.floor(mins / 60);
        if (hrs  < 24) return hrs  + 'h ago';
        var days = Math.floor(hrs / 24);
        if (days < 7)  return days + 'd ago';
        return date.toLocaleDateString('en-US', {month:'short', day:'numeric'});
    }

    function getHandle(p) {
        return p.authorUsername ||
               (p.authorName || 'anonymous').toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) ||
               'user';
    }

    /* ─── viewPhoto ─── */
    window.viewPhoto = window.viewPhoto || function(url) {
        var overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999999;display:flex;align-items:center;justify-content:center;cursor:pointer;padding:20px;';
        var img = document.createElement('img');
        img.src = url;
        img.style.cssText = 'max-width:95%;max-height:95%;border-radius:12px;object-fit:contain;';
        overlay.appendChild(img);
        overlay.onclick = function(e) { if (e.target === overlay || e.target === img) overlay.remove(); };
        document.body.appendChild(overlay);
    };

    /* ─── openExpandedPost ─── */
    window.openExpandedPost = function(postId) {
        // Works for both explore (window.explorePosts) and community (window.feedPosts)
        var posts = window.explorePosts || window.feedPosts || [];
        var post  = posts.find(function(p){ return p.id === postId; });
        if (!post) { console.warn('[post-expand] Post not found:', postId); return; }

        var handle = getHandle(post);
        var bc     = TYPE_BADGE_CLASS[post.type] || 'badge-journal';
        var badge  = TYPE_BADGES[post.type] || post.type;

        /* — Header — */
        var avatar = document.getElementById('exp-avatar');
        if (avatar) avatar.src = post.authorAvatar || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(post.authorName || 'U') + '&background=4f8ef7&color=fff');

        var timeEl = document.getElementById('exp-time');
        if (timeEl) {
            var ts = post.createdAt;
            if (ts && typeof ts.toDate === 'function') {
                // Full date for expanded view, matching community
                timeEl.textContent = ts.toDate().toLocaleDateString('en-US', {
                    weekday:'short', month:'short', day:'numeric',
                    year:'numeric', hour:'2-digit', minute:'2-digit'
                });
            } else if (ts) {
                timeEl.textContent = relativeTime(ts);
            } else {
                timeEl.textContent = '';
            }
        }

        var authorRow = document.getElementById('exp-author-row');
        if (authorRow) {
            authorRow.innerHTML =
                '<span style="font-family:\'Syne\',sans-serif;font-size:.9rem;font-weight:800;color:rgba(232,234,240,.97)">' + esc(post.authorName || 'Anonymous') + '</span>' +
                '<span style="font-family:\'JetBrains Mono\',monospace;font-size:.62rem;color:#6b7a99">@' + esc(handle) + '</span>' +
                '<span class="post-type-badge ' + bc + '">' + badge + '</span>';
        }

        /* — Body — */
        var bodyHtml = '';

        if (post.title) {
            bodyHtml += '<div style="font-size:1.1rem;font-weight:800;letter-spacing:-.02em;margin-bottom:14px;line-height:1.3;color:rgba(232,234,240,.97)">' + esc(post.title) + '</div>';
        }

        if (post.type === 'goal') {
            bodyHtml +=
                '<div class="exp-goal-bar"><div class="exp-goal-fill" style="width:' + (post.progress || 0) + '%"></div></div>' +
                '<div style="display:flex;justify-content:space-between;font-family:\'JetBrains Mono\',monospace;font-size:.65rem;color:#6b7a99;margin-bottom:14px">' +
                    '<span>' + esc(post.categoryIcon || '🎯') + ' ' + esc(post.title || '') + '</span>' +
                    '<span>' + (post.progress || 0) + '% complete</span>' +
                '</div>' +
                (post.body ? '<div class="exp-body-text">' + esc(post.body) + '</div>' : '');

        } else if (post.type === 'task') {
            bodyHtml +=
                '<div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">' +
                    '<span>' + esc(post.priorityIcon || '✅') + '</span>' +
                    '<span style="font-size:.72rem;font-family:\'JetBrains Mono\',monospace;color:#6b7a99;text-transform:uppercase">' + esc(post.priority || '') + ' priority</span>' +
                    (post.done ? '<span style="font-size:.72rem;font-family:\'JetBrains Mono\',monospace;color:#34d399">· Done ✓</span>' : '') +
                '</div>' +
                (post.body ? '<div class="exp-body-text">' + esc(post.body) + '</div>' : '');

        } else {
            // thought / journal — same layout as community openExpandedPost
            if (post.moodEmoji) {
                bodyHtml += '<div style="margin-bottom:12px;font-size:.8rem;color:#6b7a99;font-family:\'JetBrains Mono\',monospace">feeling ' + esc(post.moodEmoji) + '</div>';
            }

            bodyHtml += '<div class="exp-body-text">' + esc(post.body || '') + '</div>';

            if (post.photoUrls && post.photoUrls.length) {
                bodyHtml += '<div class="exp-photos">' +
                    post.photoUrls.map(function(u){
                        return '<img src="' + esc(u) + '" loading="lazy"' +
                               ' onclick="event.stopPropagation();viewPhoto(\'' + esc(u) + '\')"' +
                               ' onerror="this.style.display=\'none\'">';
                    }).join('') +
                '</div>';
            }

            if (post.tags && post.tags.length) {
                bodyHtml += '<div class="exp-tags">' +
                    post.tags.map(function(t){ return '<span>' + esc(t) + '</span>'; }).join('') +
                '</div>';
            }
        }

        /* stat line matching community footer feel */
        var likeCount    = (post.likes || []).length || 0;
        var commentCount = post.commentCount || 0;
        bodyHtml +=
            '<div style="display:flex;gap:16px;margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.05);' +
                         'font-family:\'JetBrains Mono\',monospace;font-size:.65rem;color:#4a5270">' +
                '<span>🤍 ' + likeCount    + ' like'    + (likeCount    !== 1 ? 's' : '') + '</span>' +
                '<span>💬 ' + commentCount + ' comment' + (commentCount !== 1 ? 's' : '') + '</span>' +
            '</div>';

        var bodyEl = document.getElementById('exp-body');
        if (bodyEl) { bodyEl.innerHTML = bodyHtml; bodyEl.scrollTop = 0; }

        /* — Open — */
        var overlay = document.getElementById('post-expand-overlay-comm');
        if (overlay) {
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    };

    /* ─── closeExpandedPost ─── */
    window.closeExpandedPost = function() {
        var overlay = document.getElementById('post-expand-overlay-comm');
        if (!overlay || !overlay.classList.contains('open')) return;
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    };

    /* ─── keyboard close ─── */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') window.closeExpandedPost();
    });

    /* ─── patch explore cards after loadExploreFeed populates DOM ───
       explore.blade.php calls renderExplorePost() which puts
       onclick="if(typeof openExpandedPost==='function') openExpandedPost('id')"
       directly on the card div — that already works, but we also
       attach a MutationObserver so dynamically injected cards get
       the same delegation treatment as community.blade.php uses.
    ─────────────────────────────────────────────────────────────── */
    function _patchExploreCards(root) {
        (root || document).querySelectorAll('.post-card[data-post-id]').forEach(function(card) {
            if (card.dataset.clickPatched === '1') return;
            card.dataset.clickPatched = '1';
            card.style.cursor = 'pointer';
            var pid = card.getAttribute('data-post-id');
            card.addEventListener('click', function(e) {
                if (e.target.closest('.post-action-btn,.post-author-btn,.post-read-more,.post-photos,.post-tags')) return;
                if (window.isGuestMode && typeof requireSignIn === 'function') {
                    requireSignIn('view the full post details');
                    return;
                }
                window.openExpandedPost(pid);
            });
        });
    }

    function _startExploreObserver() {
        var feedList = document.getElementById('feed-list');
        if (!feedList) { setTimeout(_startExploreObserver, 100); return; }
        _patchExploreCards(feedList);
        new MutationObserver(function(){ _patchExploreCards(feedList); })
            .observe(feedList, {childList: true, subtree: true});
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', _startExploreObserver);
    } else {
        _startExploreObserver();
    }

})();
</script>