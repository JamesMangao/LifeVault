<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Community — LifeVault</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;1,6..72,300;1,6..72,400&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
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
            background: linear-gradient(135deg,#4f8ef7,#a78bfa);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent; text-decoration: none;
            display: flex; align-items: center; gap: 12px;
        }
        .explore-container { max-width: 800px; margin: 100px auto 40px; padding: 0 20px; }
        .explore-header { text-align: center; margin-bottom: 48px; }
        .explore-title { font-size: 2.8rem; font-weight: 800; letter-spacing: -.04em; margin-bottom: 12px; }
        .explore-subtitle { font-family: 'Newsreader', serif; font-style: italic; font-size: 1.1rem; color: #8892b0; font-weight: 300; }
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
        .post-body  { font-family:'Newsreader',serif; font-size:.9rem; line-height:1.7; color:rgba(232,234,240,.75); font-weight:300; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
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
            font-family: 'Syne', sans-serif; cursor: pointer; transition: all .2s;
            text-decoration: none; display: inline-block; box-shadow: 0 4px 20px rgba(124,58,237,.3);
        }
        .lv-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(124,58,237,.5); }
    </style>
</head>
<body>

    <nav class="explore-nav">
        <a href="/" class="explore-logo">
            <img src="{{ asset('logo.png') }}" alt="LifeVault Logo" style="height:32px;width:auto;display:block;">
            LifeVault
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

        function renderExplorePost(p) {
            const pid    = p.id;
            const handle = getHandle(p);
            const bc     = TYPE_BADGE_CLASS[p.type] || 'badge-journal';
            const badge  = TYPE_BADGES[p.type]      || p.type;

            let bodyHtml = '';
            if (p.type === 'goal') {
                bodyHtml =
                    `<div class="post-goal-bar"><div class="post-goal-fill" style="width:${p.progress||0}%"></div></div>
                     <div class="post-goal-meta"><span>🎯</span><span>${p.progress||0}% complete</span></div>
                     ${p.body ? `<div class="post-body" style="margin-top:8px">${esc(p.body)}</div>` : ''}`;
            } else {
                bodyHtml = `<div class="post-body">${esc(p.body || '')}</div>`;
            }

            // NOTE: onclick is on the card root via data-post-id delegation below,
            // NOT as an inline attribute — this avoids the race condition where
            // openExpandedPost fires before window.explorePosts is populated.
            return `
<div class="post-card" data-post-id="${pid}">
    <div class="post-header">
        <img src="${esc(p.authorAvatar || '')}"
             class="post-avatar"
             onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
        <div class="post-meta">
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                <span class="post-author-name">${esc(p.authorName || 'Anonymous')}</span>
                <span class="post-author-username">@${esc(handle)}</span>
                <span class="post-type-badge ${bc}">${esc(badge)}</span>
            </div>
            <div class="post-time">${relativeTime(p.createdAt)}</div>
        </div>
    </div>
    ${p.title ? `<div class="post-title">${esc(p.title)}</div>` : ''}
    ${bodyHtml}
    <div class="post-actions">
        <button class="post-action-btn" onclick="event.stopPropagation();window.location.href='/'">
            🤍 <span class="post-action-count">${(p.likes||[]).length||0}</span>
        </button>
        <button class="post-action-btn" onclick="event.stopPropagation();window.location.href='/'">
            💬 <span class="post-action-count">${p.commentCount||0}</span>
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
            if (e.target.closest('.post-action-btn, .guest-lock')) return;
            const pid = card.getAttribute('data-post-id');
            if (pid && typeof window.openExpandedPost === 'function') {
                window.openExpandedPost(pid);
            }
        });

        loadExploreFeed();
    </script>
</body>
</html>