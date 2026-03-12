<style>
/* ══ USER PROFILE MODAL ══════════════════════════════════════ */
.user-profile-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(11, 15, 26, .92);
    z-index: 500;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    cursor: pointer;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}
.user-profile-modal-backdrop.open {
    display: flex;
    animation: overlayFadeIn .25s ease both;
}
.upm-modal {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    max-width: 460px;
    width: 100%;
    max-height: 88vh;
    overflow-y: auto;
    box-shadow: 0 40px 80px rgba(0,0,0,.6), 0 0 60px rgba(79,142,247,.08);
    animation: modalIn .3s cubic-bezier(.34,1.56,.64,1) both;
    cursor: default;
    /* Custom scrollbar */
    scrollbar-width: thin;
    scrollbar-color: var(--border) transparent;
}
.upm-modal::-webkit-scrollbar { width: 4px; }
.upm-modal::-webkit-scrollbar-track { background: transparent; }
.upm-modal::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }
@keyframes modalIn {
    from { opacity: 0; transform: scale(.88) translateY(16px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
/* Cover */
.upm-cover {
    height: 130px;
    border-radius: 20px 20px 0 0;
    background: linear-gradient(135deg,#0d1b2a,#1b3a5c,#0d1b2a);
    position: relative;
    flex-shrink: 0;
}
/* Identity section */
.upm-identity {
    padding: 0 22px 20px;
    position: relative;
}
.upm-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-top: -36px;
    margin-bottom: 10px;
}
.upm-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--surface);
    background: var(--surface2);
    display: block;
}
.upm-name {
    font-family: 'Syne', sans-serif;
    font-size: 1.15rem;
    font-weight: 800;
    letter-spacing: -.02em;
    color: var(--text);
    line-height: 1.2;
    margin-bottom: 3px;
}
.upm-username {
    font-family: 'JetBrains Mono', monospace;
    font-size: .65rem;
    color: var(--muted);
    margin-bottom: 8px;
}
.upm-bio {
    font-family: 'Newsreader', serif;
    font-size: .88rem;
    color: rgba(232,234,240,.7);
    line-height: 1.6;
    font-weight: 300;
    font-style: italic;
    margin-bottom: 10px;
}
/* Stats row */
.upm-stats {
    display: flex;
    gap: 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    margin: 16px 0;
}
.upm-stat {
    flex: 1;
    text-align: center;
    padding: 12px 8px;
    border-right: 1px solid var(--border);
}
.upm-stat:last-child { border-right: none; }
.upm-stat-val {
    font-family: 'Syne', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1;
    margin-bottom: 4px;
}
.upm-stat-label {
    font-family: 'JetBrains Mono', monospace;
    font-size: .55rem;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--muted);
}
/* Posts list */
.upm-posts-section {
    padding: 0 22px 8px;
}
.upm-section-label {
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    text-transform: uppercase;
    letter-spacing: .14em;
    color: var(--muted);
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border);
}
.upm-post-item {
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,.04);
}
.upm-post-item:last-child { border-bottom: none; }
.upm-post-title {
    font-size: .82rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 3px;
    line-height: 1.3;
}
.upm-post-meta {
    font-family: 'JetBrains Mono', monospace;
    font-size: .58rem;
    color: var(--muted);
}
/* Footer */
.upm-footer {
    padding: 14px 22px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    background: var(--surface2);
    border-radius: 0 0 20px 20px;
    flex-shrink: 0;
}
</style>

{{-- Shared user profile popup modal --}}
<div class="user-profile-modal-backdrop" id="user-profile-modal" onclick="event.target===this && closeUserProfileModal()">
    <div class="upm-modal">

        {{-- Cover --}}
        <div class="upm-cover" id="upm-cover"></div>

        {{-- Identity --}}
        <div class="upm-identity">
            <div class="upm-avatar-wrap">
                <img class="upm-avatar" id="upm-avatar" src="" alt=""
                     onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
            </div>
            <div class="upm-name" id="upm-name"><span class="skeleton-text" style="width:120px;height:1.2em;display:inline-block"></span></div>
            <div class="upm-username" id="upm-username"><span class="skeleton-text" style="width:80px;height:1em;display:inline-block"></span></div>
            <div class="upm-bio" id="upm-bio"><span class="skeleton-text" style="width:100%;height:1em;display:inline-block"></span></div>
            <div id="upm-info"
                 style="display:flex;flex-wrap:wrap;gap:6px;font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted)">
            </div>

            {{-- Stats --}}
            <div class="upm-stats">
                <div class="upm-stat">
                    <div class="upm-stat-val" id="upm-posts" style="color:var(--teal)">—</div>
                    <div class="upm-stat-label">Posts</div>
                </div>
                <div class="upm-stat">
                    <div class="upm-stat-val" id="upm-likes" style="color:var(--rose)">—</div>
                    <div class="upm-stat-label">Likes</div>
                </div>
                <div class="upm-stat">
                    <div class="upm-stat-val" id="upm-joined" style="color:var(--muted)">—</div>
                    <div class="upm-stat-label">Joined</div>
                </div>
            </div>
        </div>

        {{-- Recent posts --}}
        <div class="upm-posts-section">
            <div class="upm-section-label">Recent Posts</div>
            <div id="upm-posts-list">
                <!-- skeleton items inserted via JS on open -->
            </div>
        </div>

        {{-- Footer --}}
        <div class="upm-footer">
            <button class="btn btn-primary" onclick="closeUserProfileModal()"
                    style="font-family:'Syne',sans-serif;font-size:.78rem;font-weight:700;padding:9px 22px;border-radius:10px;border:none;background:var(--accent);color:white;cursor:pointer">
                Close
            </button>
        </div>

    </div>
</div>

<script>
// Fix escape key handler — use the correct modal ID "user-profile-modal" not "upm-overlay"
document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    const upm = document.getElementById('user-profile-modal');
    if (upm && upm.classList.contains('open')) {
        e.stopPropagation();
        window.closeUserProfileModal && window.closeUserProfileModal();
    }
});

// Ensure closeUserProfileModal is always defined (safety fallback)
if (typeof window.closeUserProfileModal !== 'function') {
    window.closeUserProfileModal = function() {
        const modal = document.getElementById('user-profile-modal');
        if (modal) modal.classList.remove('open');
        document.body.style.overflow = '';
    };
}
</script>