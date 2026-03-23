<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Community — LifeVault</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;1,6..72,300;1,6..72,400&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        body { background: #05060f; color: #e8eaf0; overflow-x: hidden; }
        .explore-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 20px 40px; background: rgba(5,6,15,.8);
            backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,.05);
            display: flex; justify-content: space-between; align-items: center;
        }
        .explore-logo {
            font-size: 1.25rem; font-weight: 800; letter-spacing: -.03em;
            text-decoration: none;
            display: flex; align-items: center; gap: 12px;
        }
        .explore-container { max-width: 800px; margin: 100px auto 40px; padding: 0 20px; }
        .explore-header { text-align: center; margin-bottom: 48px; }
        .explore-title { font-size: 2.8rem; font-weight: 800; letter-spacing: -.04em; margin-bottom: 12px; }
        .explore-subtitle { font-family: var(--font-journal); font-style: italic; font-size: 1.1rem; color: #8892b0; font-weight: 300; }
        .join-nudge {
            background: linear-gradient(135deg, rgba(79,142,247,.1), rgba(167,139,250,.1));
            border: 1px solid rgba(79,142,247,.2); border-radius: 24px;
            padding: 40px; text-align: center; margin-bottom: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,.3);
        }
        .join-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 12px; }
        .join-desc  { font-size: .95rem; color: #8892b0; margin-bottom: 24px; max-width: 500px; margin-left: auto; margin-right: auto; }
        #feed-list  { display: flex; flex-direction: column; gap: 20px; }

        /* ── post cards — match community.blade.php exactly ── */
        .post-card {
            background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.07);
            border-radius: 16px; padding: 20px;
            transition: transform .3s, border-color .3s, box-shadow .2s;
            cursor: pointer;
        }
        .post-card:hover {
            transform: translateY(-3px);
            border-color: rgba(79,142,247,.28);
            box-shadow: 0 8px 32px rgba(79,142,247,.1);
        }
        .post-header { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
        .post-avatar { width:38px; height:38px; border-radius:50%; object-fit:cover; border:1.5px solid rgba(255,255,255,.08); flex-shrink:0; }
        .post-meta   { flex:1; min-width:0; }
        .post-author-name     { font-weight:700; font-size:.82rem; color:#e8eaf0; }
        .post-author-username { font-family:'JetBrains Mono',monospace; font-size:.62rem; color:#6b7a99; }
        .post-type-badge { font-family:'JetBrains Mono',monospace; font-size:.55rem; text-transform:uppercase; letter-spacing:.1em; padding:2px 7px; border-radius:4px; font-weight:400; }
        .badge-journal { background:rgba(79,142,247,.15); color:#4f8ef7; }
        .badge-task    { background:rgba(52,211,153,.15);  color:#34d399; }
        .badge-goal    { background:rgba(167,139,250,.15); color:#a78bfa; }
        .post-time  { font-family:'JetBrains Mono',monospace; font-size:.6rem; color:#6b7a99; }
        .post-title { font-size:.95rem; font-weight:700; margin-bottom:8px; line-height:1.3; color:#e8eaf0; }
        .post-body  { font-family:var(--font-journal); font-size:var(--font-size-journal); line-height:1.7; color:rgba(232,234,240,.75); font-weight:300; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
        .markdown-content p{margin-bottom:.8rem}
        .markdown-content strong{color:rgba(232,234,240,.95);font-weight:700}
        .markdown-content em{color:rgba(174,184,210,.8);font-style:italic}
        .markdown-content blockquote{border-left:3px solid var(--accent);margin:10px 0;padding:8px 14px;background:rgba(79,142,247,.06);border-radius:0 8px 8px 0;font-style:italic;color:rgba(232,234,240,.7)}
        .markdown-content code{background:rgba(255,255,255,.06);padding:2px 6px;border-radius:4px;font-family:'JetBrains Mono',monospace;font-size:.82em}
        .markdown-content ul{padding-left:1.2rem;margin-bottom:.8rem}
        .markdown-content li{margin-bottom:.3rem}
        .post-read-more{font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--accent);cursor:pointer;margin-top:4px;display:inline-block}
        .post-photos{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
        .post-photo{width:100px;height:100px;border-radius:10px;object-fit:cover;border:1px solid rgba(255,255,255,.1);cursor:pointer;transition:transform .2s}
        .post-photo:hover{transform:scale(1.04);border-color:#4f8ef7}
        .post-tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}
        .tag{font-family:'JetBrains Mono',monospace;font-size:.58rem;padding:2px 8px;border-radius:4px;text-transform:uppercase;letter-spacing:.08em}
        .post-goal-bar  { background:rgba(255,255,255,.06); border-radius:99px; height:6px; overflow:hidden; margin:8px 0; }
        .post-goal-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#4f8ef7,#a78bfa); }
        .post-goal-meta { display:flex; justify-content:space-between; font-family:'JetBrains Mono',monospace; font-size:.62rem; color:#6b7a99; margin-top:4px; }
        .post-actions { display:flex; align-items:center; gap:4px; margin-top:16px; padding-top:14px; border-top:1px solid rgba(255,255,255,.05); }
        .post-action-btn { display:flex; align-items:center; gap:6px; padding:7px 12px; border-radius:8px; border:none; background:transparent; color:#6b7a99; cursor:pointer; font-family:'Syne',sans-serif; font-size:.75rem; font-weight:600; transition:all .18s; }
        .post-action-btn:hover { background:rgba(255,255,255,.04); color:#e8eaf0; }
        .post-action-count { font-family:'JetBrains Mono',monospace; font-size:.68rem; }
        .guest-lock { margin-left:auto; font-size:.65rem; color:#4a5270; font-family:'JetBrains Mono',monospace; display:flex; align-items:center; gap:4px; }

        .loading-posts { text-align:center; padding:48px; color:#6b7a99; font-family:'JetBrains Mono',monospace; font-size:.72rem; }
        .feed-empty    { text-align:center; padding:60px 24px; color:#6b7a99; }

        .lv-btn-primary {
            background: linear-gradient(135deg,#7c3aed,#4338ca); color: white; border: none;
            padding: 12px 24px; border-radius: 12px; font-weight: 700;
            cursor: pointer; transition: all .2s;
            text-decoration: none; display: inline-block; box-shadow: 0 4px 20px rgba(124,58,237,.3);
        }
        .lv-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(124,58,237,.5); }

        /* Auth Prompt Modal */
        .auth-prompt-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(5,6,15,.85); backdrop-filter: blur(8px);
            z-index: 1000; display: none; align-items: center; justify-content: center;
            opacity: 0; transition: opacity .25s ease;
        }
        .auth-prompt-overlay.show { display: flex; opacity: 1; }
        .auth-prompt-modal {
            background: #131929; border: 1px solid rgba(255,255,255,.07);
            border-radius: 24px; width: 90%; max-width: 400px; padding: 32px;
            text-align: center; box-shadow: 0 24px 60px rgba(0,0,0,.4);
            transform: scale(.95) translateY(10px); transition: all .25s cubic-bezier(.34,1.56,.64,1);
        }
        .auth-prompt-overlay.show .auth-prompt-modal { transform: scale(1) translateY(0); }
        .auth-icon { font-size: 3rem; margin-bottom: 16px; display: inline-block; }
        .auth-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 12px; color: #f0f2ff; letter-spacing: -.02em; }
        .auth-text { font-size: .95rem; color: #8892b0; margin-bottom: 28px; line-height: 1.5; }
        .auth-actions { display: flex; gap: 12px; }
        .auth-btn-cancel {
            flex: 1; padding: 12px; border-radius: 12px; font-weight: 600;
            background: rgba(255,255,255,.05); color: #8892b0; border: none;
            cursor: pointer; transition: all .15s;
        }
        .auth-btn-cancel:hover { background: rgba(255,255,255,.1); color: #e8eaf0; }
        .auth-btn-confirm {
            flex: 1; padding: 12px; border-radius: 12px; font-weight: 700;
            background: linear-gradient(135deg, #7c3aed, #4338ca); color: #fff; border: none;
            cursor: pointer; transition: all .2s; box-shadow: 0 4px 15px rgba(124,58,237,.25);
        }
        .auth-btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(124,58,237,.4); }
    </style>
</head>
<body>

    <div class="auth-prompt-overlay" id="auth-prompt" onclick="closeAuthPrompt(event)">
        <div class="auth-prompt-modal" onclick="event.stopPropagation()">
            <div class="auth-icon">🚀</div>
            <h3 class="auth-title">Sign in to LifeVault</h3>
            <p class="auth-text" id="auth-prompt-text">You need to sign in to do that.</p>
            <div class="auth-actions">
                <button class="auth-btn-cancel" onclick="closeAuthPrompt()">Cancel</button>
                <button class="auth-btn-confirm" onclick="window.location.href='/'">Sign In →</button>
            </div>
        </div>
    </div>

    <nav class="explore-nav">
        <a href="/" class="explore-logo">
            <img src="{{ asset('logo.png') }}" alt="LifeVault Logo" style="height:32px;width:auto;display:block;">
            <span class="sidebar-logo-wordmark">
                <span class="sidebar-logo-life">Life</span><span class="sidebar-logo-vault">Vault</span>
            </span>
        </a>
        <div>
            <button class="lv-btn-primary" onclick="window.location.href='/'">Sign In →</button>
        </div>
    </nav>

    <div class="explore-container">
        <header class="explore-header">
            <h1 class="explore-title">The Community Feed</h1>
            <p class="explore-subtitle">Real people, real growth, shared journeys.</p>
        </header>

        <div class="join-nudge">
            <h2 class="join-title">Join the LifeVault Community</h2>
            <p class="join-desc">Sign in to share your journey, like posts, and connect with others growing together.</p>
            <button class="lv-btn-primary" onclick="window.location.href='/'">Start Your Vault →</button>
        </div>

        <div id="feed-list">
            <div class="loading-posts">✨ Summoning stories from the vault…</div>
        </div>
    </div>

    @include('layouts.partials._post-expand')

    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore-compat.js"></script>
    <script>
        window.firebaseConfig = {
            apiKey:            "{{ config('services.firebase.api_key') }}",
            authDomain:        "{{ config('services.firebase.auth_domain') }}",
            projectId:         "{{ config('services.firebase.project_id') }}",
            storageBucket:     "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId:             "{{ config('services.firebase.app_id') }}",
            measurementId:     "{{ config('services.firebase.measurement_id') }}"
        };
        firebase.initializeApp(window.firebaseConfig);
        const db = firebase.firestore();

        window.isGuestMode  = true;
        window.currentUser  = null;
        window.explorePosts = [];   // populated by loadExploreFeed()

        const TYPE_BADGES      = { journal:'Journal', task:'Task', goal:'Goal', thought:'Thought' };
        const TYPE_BADGE_CLASS = { journal:'badge-journal', task:'badge-task', goal:'badge-goal', thought:'badge-journal' };

        function esc(t) {
            const d = document.createElement('div'); d.textContent = t; return d.innerHTML;
        }
        function relativeTime(ts) {
            if (!ts) return 'just now';
            const date = (ts && typeof ts.toDate === 'function') ? ts.toDate() : new Date(ts);
            const s = Math.floor((Date.now() - date.getTime()) / 1000);
            if (s < 60)    return 'just now';
            if (s < 3600)  return Math.floor(s / 60) + 'm ago';
            if (s < 86400) return Math.floor(s / 3600) + 'h ago';
            return Math.floor(s / 86400) + 'd ago';
        }
        function getHandle(p) {
            return p.authorUsername ||
                   (p.authorName || 'user').toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) ||
                   'user';
        }

        function requireSignIn(actionText) {
            var msg = actionText ? `Sign in to ${actionText} and connect with the community.` : 'Join LifeVault to unlock this feature.';
            document.getElementById('auth-prompt-text').textContent = msg;
            var overlay = document.getElementById('auth-prompt');
            overlay.style.display = 'flex';
            // Force reflow
            void overlay.offsetWidth;
            overlay.classList.add('show');
        }

        function closeAuthPrompt(e) {
            if (e && e.target !== document.getElementById('auth-prompt')) return;
            var overlay = document.getElementById('auth-prompt');
            overlay.classList.remove('show');
            setTimeout(() => { overlay.style.display = 'none'; }, 250);
        }

        function toggleReadMore(pid) {
            const el = document.getElementById('post-body-' + pid);
            if (!el) return;
            if (el.style.webkitLineClamp === '4') {
                el.style.webkitLineClamp = 'unset';
                event.target.textContent = 'Read less ↑';
            } else {
                el.style.webkitLineClamp = '4';
                event.target.textContent = 'Read more ↓';
            }
        }


        function renderExplorePost(p) {
            const pid    = p.id;
            const handle = getHandle(p);
            const bc     = TYPE_BADGE_CLASS[p.type] || 'badge-journal';
            const badge  = TYPE_BADGES[p.type]      || p.type;
            const timeAgo = relativeTime(p.createdAt);

            const origForRepost = p.isRepost && p.originalPostId && window.explorePosts
                ? window.explorePosts.find(x => x.id === p.originalPostId) : null;
            const repostAv = origForRepost ? (origForRepost.authorAvatar || '') : (p.originalAuthorAvatar || '');
            const repostHtml = p.isRepost
                ? `<div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:#34d399;margin-bottom:10px;display:flex;align-items:center;gap:6px">🔁 reposted from <img src="${esc(repostAv)}" style="width:16px;height:16px;border-radius:50%;object-fit:cover" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'"> <span>${esc(p.originalAuthorName || 'Anonymous')}</span></div>` : '';

            let bodyHtml = '';
            if (p.type === 'goal') {
                bodyHtml = `<div class="post-goal-bar"><div class="post-goal-fill" style="width:${p.progress||0}%"></div></div>
                  <div class="post-goal-meta"><span>${p.categoryIcon||'🎯'}</span><span>${p.progress||0}% complete</span></div>
                  ${p.body ? `<div class="post-body">${esc(p.body)}</div>` : ''}`;
            } else if (p.type === 'task') {
                bodyHtml = `<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                  <span>${p.priorityIcon||'✅'}</span>
                  <span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:#8892b0;text-transform:uppercase">${p.priority||''} priority</span>
                  ${p.done ? `<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:#34d399">· Done ✓</span>` : ''}
                </div>
                ${p.body ? `<div class="post-body">${esc(p.body)}</div>` : ''}`;
            } else {
                const long = (p.body || '').length > 300;
                const bodyRendered = p.isSharedItem && typeof marked !== 'undefined'
                    ? marked.parse(p.body || '')
                    : esc(p.body || '');
                bodyHtml = `<div class="post-body markdown-content" id="post-body-${pid}" style="display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden">${bodyRendered}</div>
                  ${long ? `<span class="post-read-more" onclick="event.stopPropagation();toggleReadMore('${pid}')">Read more ↓</span>` : ''}
                  ${p.moodEmoji ? `<div style="margin-top:8px;font-size:.8rem;color:#8892b0;font-family:'JetBrains Mono',monospace">feeling ${p.moodEmoji}</div>` : ''}
                  ${p.photoUrls?.length ? `<div class="post-photos" onclick="event.stopPropagation()">${p.photoUrls.map(u => `<img src="${esc(u)}" class="post-photo" onclick="event.stopPropagation();requireSignIn('view this photo')">`).join('')}</div>` : ''}
                  ${p.tags?.length ? `<div class="post-tags" onclick="event.stopPropagation()">${p.tags.map(t => `<span class="tag" style="background:rgba(79,142,247,.12);color:#4f8ef7">${esc(t)}</span>`).join('')}</div>` : ''}`;
            }

            return `
<div class="post-card" data-post-id="${pid}">
    <div class="post-header">
        <img src="${esc(p.authorAvatar || '')}"
             class="post-avatar"
             style="cursor:pointer" onclick="event.stopPropagation();requireSignIn('view this profile')"
             onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <div class="post-meta">
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;cursor:pointer" onclick="event.stopPropagation();requireSignIn('view this profile')">
                <span class="post-author-name">${esc(p.authorName || 'Anonymous')}</span>
                <span class="post-author-username">@${esc(handle)}</span>
                <span class="post-type-badge ${bc}">${esc(badge)}</span>
            </div>
            <div class="post-time">${timeAgo}</div>
        </div>
    </div>
    ${p.title ? `<div class="post-title">${esc(p.title)}</div>` : ''}
    ${repostHtml}${bodyHtml}
    <div class="post-actions">
        <button class="post-action-btn" onclick="event.stopPropagation();requireSignIn('like this post')">
            🤍 <span class="post-action-count">${(p.likes||[]).length||''}</span>
        </button>
        <button class="post-action-btn" onclick="event.stopPropagation();requireSignIn('comment on this post')">
            💬 <span class="post-action-count">${p.commentCount||0}</span>
        </button>
        <button class="post-action-btn" onclick="event.stopPropagation();requireSignIn('repost this')">
            🔁 <span class="post-action-count">${p.repostCount||''}</span>
        </button>
        <span class="guest-lock">🔒 Sign in to interact</span>
    </div>
</div>`;
        }

        async function loadExploreFeed() {
            const feedList = document.getElementById('feed-list');
            try {
                const snap = await db.collection('community_posts').limit(30).get();
                if (snap.empty) {
                    feedList.innerHTML = '<div class="feed-empty">Nothing here yet — be the first to join!</div>';
                    return;
                }
                // Populate BEFORE rendering so openExpandedPost() can find posts
                window.explorePosts = snap.docs.map(doc => ({ id: doc.id, ...doc.data() }));
                feedList.innerHTML  = window.explorePosts.map(renderExplorePost).join('');
            } catch(e) {
                console.error(e);
                feedList.innerHTML = '<div class="feed-empty">Could not load posts. Check Firestore rules.</div>';
            }
        }

        // Delegation on #feed-list — fires AFTER explorePosts is populated
        document.getElementById('feed-list').addEventListener('click', function(e) {
            const card = e.target.closest('.post-card[data-post-id]');
            if (!card) return;
            // Ignore action buttons
            if (e.target.closest('.post-action-btn, .guest-lock, .post-photo, .post-author-name')) return;
            // If they click on the post body to expand, also prompt them to log in
            if (e.target.closest('.post-read-more')) return; // let read more happen inline
            
            requireSignIn('view the full post details');
        });

        loadExploreFeed();
    </script>
</body>
</html>