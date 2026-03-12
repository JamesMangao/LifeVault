<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LifeVault — Your Personal Space</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;1,6..72,300;1,6..72,400&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">

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
    </script>


</head>
<body>



    <div id="loading">
        <div class="loader-logo">LifeVault</div>
        <div class="loader-bar"></div>
    </div>

    <div id="auth-screen">
        <div class="auth-card">
            <div class="auth-logo">LifeVault</div>
            <p class="auth-tagline">Your private, encrypted, personal space.</p>
            <div class="auth-features">
                <div class="auth-feature"><div class="auth-feature-icon" style="background:rgba(167,139,250,.15);color:var(--lavender)">📓</div> Journal everything</div>
                <div class="auth-feature"><div class="auth-feature-icon" style="background:rgba(52,211,153,.15);color:var(--green)">✅</div> Manage your tasks</div>
                <div class="auth-feature"><div class="auth-feature-icon" style="background:rgba(251,191,36,.15);color:var(--amber)">🎯</div> Track your goals</div>
            </div>
            <button class="google-btn" id="google-login-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" class="google-icon">
                Sign in with Google
            </button>
            <p class="privacy-note">Your data is yours. We'll never read or sell it.</p>
        </div>
    </div>

    <div id="app">
        @include('layouts.partials._sidebar')
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

        <div class="main">
            @include('dashboard')
            @include('journal')
            @include('tasks')
            @include('goals')
            @include('insights')
            @include('community')
            @include('analyzer')
            @include('shadow-self')
            @include('life-story')
            @include('holistic-career')
            @include('settings')
            @include('profile')
            @include('saved')
        </div>
    </div>

    {{-- User Profile Modal --}}
    @include('layouts.partials.user-profile-modal')

<style>
.user-profile-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(11, 15, 26, .9);
    z-index: 300;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    cursor: pointer;
}
.user-profile-modal-backdrop.open {
    display: flex;
}
.user-profile-modal {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    max-width: 480px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 40px 80px rgba(0,0,0,.5);
    animation: modalIn .3s cubic-bezier(.34,1.56,.64,1) both;
    cursor: default;
    padding: 0; /* Remove padding to allow cover to be flush */
}
.user-profile-modal .profile-card {
    border: none;
    background: transparent;
    margin-bottom: 0;
}
.user-profile-modal .profile-cover {
    height: 140px;
    border-radius: 20px 20px 0 0;
}
.user-profile-modal .profile-identity {
    padding: 0 24px 24px;
}
.user-profile-modal .profile-avatar-large {
    width: 80px;
    height: 80px;
    margin-top: -40px;
}
.user-profile-modal .profile-display-name {
    font-size: 1.3rem;
}
.user-profile-modal .profile-section-title {
    padding: 0 24px;
}
.user-profile-modal .profile-recent-activity {
    padding: 0 24px 24px;
}
.user-profile-modal .profile-cover-edit,
.user-profile-modal .profile-avatar-edit-btn {
    display: none; /* Hide edit buttons in the modal */
}
</style>

    @include('layouts.partials.modals')
    @include('layouts.partials._toast')
    @include('layouts.partials._journal-expand')

    <script>
// Early-define savedAddItem so save buttons work on any page
// before saved.blade.php's @push('scripts') has fired.
(function () {
    var LS_KEY = 'lifevault_saved_items';

    function loadItems() {
        try { return JSON.parse(localStorage.getItem(LS_KEY)) || []; } catch (e) { return []; }
    }

    function persistItems(items) {
        try { localStorage.setItem(LS_KEY, JSON.stringify(items)); } catch (e) {}
    }

    window.savedAddItem = function (item) {
        var items = loadItems();
        var newItem = Object.assign({}, item, {
            id: Date.now() + Math.random().toString(36).slice(2),
            savedAt: new Date().toISOString()
        });
        items.unshift(newItem);
        persistItems(items);

        // If saved page is already mounted and has registered its renderer, call it
        if (typeof window._savedRender === 'function') window._savedRender();

        if (typeof window.toast === 'function') window.toast('Saved to 🔖 Saved Items! ✨');
    };
})();
</script>

{{-- Then your existing lines follow: --}}
<script src="{{ asset('js/app.js') }}?v={{ time() }}" type="module"></script>
<script src="{{ asset('js/profile-popup.js') }}?v={{ time() }}"></script>
@stack('scripts')

  <!-- profile popup markup -->
  <div id="profile-popup">
    <div class="pp-header">
      <img class="pp-avatar" id="pp-avatar" src="" alt="">
      <div style="min-width:0">
        <div class="pp-name" id="pp-name" style="color: var(--text);"></div>
        <div class="pp-email" id="pp-email"></div>
      </div>
    </div>
    <div class="pp-menu">
      <div class="pp-item" style="color: var(--text);" onclick="navigateTo('profile');closeProfilePopup()"><span class="pp-icon">👤</span> View Profile</div>
      <div class="pp-item" style="color: var(--text);" onclick="navigateTo('settings');closeProfilePopup()"><span class="pp-icon">⚙️</span> Settings</div>
      <div class="pp-item" style="color: var(--text);" onclick="exportAsJSON();closeProfilePopup()"><span class="pp-icon">💾</span> Export Backup</div>
      <div class="pp-divider"></div>
      <div class="pp-item pp-item--danger" onclick="closeProfilePopup();signOutUser()"><span class="pp-icon">⏻</span> Sign Out</div>
    </div>
  </div>
</body>
</html>