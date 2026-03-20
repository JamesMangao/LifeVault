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
        .explore-logo { font-size: 1.25rem; font-weight: 800; letter-spacing: -.03em; background: linear-gradient(135deg,#4f8ef7,#a78bfa); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none; }
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
        .join-desc { font-size: .95rem; color: #8892b0; margin-bottom: 24px; max-width: 500px; margin-left: auto; margin-right: auto; }
        
        #feed-list { display: flex; flex-direction: column; gap: 20px; }
        .post-card { background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); border-radius: 20px; padding: 24px; transition: transform .3s, border-color .3s; }
        .post-card:hover { transform: translateY(-4px); border-color: rgba(79,142,247,.3); background: rgba(255,255,255,.05); }

        .lv-btn-primary { 
            background: #4f8ef7; color: white; border: none; padding: 12px 24px; 
            border-radius: 12px; font-weight: 700; font-family: 'Syne', sans-serif; 
            cursor: pointer; transition: all .2s; text-decoration: none; display: inline-block;
        }
        .lv-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,142,247,.4); }

        /* Override app.css for public view */
        .post-action-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .guest-lock-msg { font-size: .7rem; color: #fb7185; margin-top: 8px; font-family: 'JetBrains Mono', monospace; display: none; }
        .post-action-btn:hover .guest-lock-msg { display: block; }
    </style>
</head>
<body>

    <nav class="explore-nav">
        <a href="/" class="explore-logo">LifeVault</a>
        <div class="nav-right">
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
            <div class="loading-posts">✨ Summoning stories from the vault...</div>
        </div>
    </div>

    @include('components.ai-chatbot')
    @include('layouts.partials._post-expand')

    <!-- Firebase & App Logic -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore-compat.js"></script>
    <script>
        window.firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.app_id') }}",
            measurementId: "{{ config('services.firebase.measurement_id') }}"
        };
        firebase.initializeApp(window.firebaseConfig);
        const db = firebase.firestore();
        
        // Define some requirements from app.js to avoid errors
        window.isGuestMode = true;
        window.currentUser = null;
        
        function esc(t){ if(!t)return ''; const d=document.createElement('div'); d.textContent=t; return d.innerHTML; }
        function relativeTime(ts){
            if(!ts) return 'just now';
            const s=Math.floor((Date.now()-ts.toMillis())/1000);
            if(s<60) return 'just now';
            if(s<3600) return Math.floor(s/60)+'m ago';
            if(s<86400) return Math.floor(s/3600)+'h ago';
            return Math.floor(s/86400)+'d ago';
        }

        const TYPE_BADGE_CLASS = { journal:'badge-journal', task:'badge-task', goal:'badge-goal' };
        const TYPE_BADGES = { journal:'Journal', task:'Task', goal:'Goal' };

        function renderExplorePost(p){
            const pid = p.id;
            const timeAgo = relativeTime(p.createdAt);
            const bc = TYPE_BADGE_CLASS[p.type]||'badge-journal';
            const handle = p.authorName ? p.authorName.toLowerCase().replace(/\s+/g, '') : 'user';

            let bodyContent = `<div class="post-body">${esc(p.body||'')}</div>`;
            if(p.type==='goal'){
                bodyContent = `<div class="post-goal-bar"><div class="post-goal-fill" style="width:${p.progress||0}%"></div></div>
                               <div class="post-goal-meta"><span>🎯</span><span>${p.progress||0}% complete</span></div>
                               ${p.body ? `<div class="post-body" style="margin-top:10px">${esc(p.body)}</div>` : ''}`;
            }

            return `
<div class="post-card" data-post-id="${pid}" onclick="if(typeof openExpandedPost==='function') openExpandedPost('${pid}');">
                    <div class="post-header">
                        <img src="${p.authorAvatar||'https://ui-avatars.com/api/?name=U'}" class="post-avatar">
                        <div class="post-meta">
                            <div style="display:flex;align-items:center;gap:6px">
                                <span class="post-author-name">${esc(p.authorName||'Anonymous')}</span>
                                <span class="post-author-username">@${handle}</span>
                                <span class="post-type-badge ${bc}">${TYPE_BADGES[p.type]||p.type}</span>
                            </div>
                            <div class="post-time">${timeAgo}</div>
                        </div>
                    </div>
                    ${p.title ? `<div class="post-title">${esc(p.title)}</div>` : ''}
                    ${bodyContent}
                    <div class="post-actions" style="margin-top:16px; padding-top:14px; border-top: 1px solid rgba(255,255,255,0.05)">
                        <button class="post-action-btn" onclick="window.location.href='/'">
                            🤍 <span class="post-action-count">${(p.likes||[]).length||0}</span>
                        </button>
                        <button class="post-action-btn" onclick="window.location.href='/'">
                            💬 <span class="post-action-count">${p.commentCount||0}</span>
                        </button>
                        <span style="margin-left:auto; font-size:.65rem; color:#4a5270; font-family:'JetBrains Mono',monospace">🔒 Sign in to interact</span>
                    </div>
                </div>
            `;
        }

        async function loadExploreFeed(){
            const feedList = document.getElementById('feed-list');
            try {
                // Fetch public posts from community_posts collection
                // Removed orderBy to avoid "missing index" errors for guests
                const snap = await db.collection('community_posts')
                                   .limit(30)
                                   .get();
                
                if(snap.empty){
                    feedList.innerHTML = '<div class="feed-empty">Nothing here yet...</div>';
                    return;
                }
                
        window.explorePosts = snap.docs.map(doc => ({id: doc.id, ...doc.data()}));
        feedList.innerHTML = snap.docs.map(doc => renderExplorePost({id: doc.id, ...doc.data()})).join('');
            } catch(e) {
                console.error(e);
                feedList.innerHTML = '<div class="feed-empty">Check Firestore rules or connection.</div>';
            }
        }

        loadExploreFeed();
    </script>
</body>
</html>
