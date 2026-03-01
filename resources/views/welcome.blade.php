+56+0<0.!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LifeVault — Your Personal Space</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;1,6..72,300;1,6..72,400&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

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

        // Hugging Face API Configuration
        // 🔑 IMPORTANT: Replace with your actual Hugging Face API token
        // Get it from: https://huggingface.co/settings/tokens
        window.huggingfaceApiKey = "{{ config('services.huggingface.api_key', 'YOUR_HUGGINGFACE_API_TOKEN_HERE') }}";
    </script>

    <style>
        /* Profile Modal */
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
            background: var(--bg-deep);
            border-radius: 16px;
            border: 1px solid var(--border);
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            cursor: default;
            animation: modal-in .2s ease-out;
        }

        /* Journal Entry Expansion */
        .entry-preview {
            -webkit-line-clamp: 3;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            font-size: .8rem;
            color: var(--muted);
            line-height: 1.6;
            margin-top: 8px;
            cursor: pointer;
        }
        .entry-preview.expanded {
            -webkit-line-clamp: unset;
            overflow: visible;
        }
        .journal-entry .entry-expand-hint {
            display: none;
            text-align: right;
            font-size: .65rem;
            color: var(--muted-dark);
            margin-top: 8px;
            font-style: italic;
        }
        .journal-entry:hover .entry-expand-hint {
            display: block;
        }
    </style>
</head>
<body>

    <div id="user-profile-modal" class="user-profile-modal-backdrop">
        <div class="user-profile-modal">
            {{-- Content will be injected by JS --}}
        </div>
    </div>

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
            @include('settings')
            @include('profile')
        </div>
    </div>

    {{-- User Profile Modal --}}
<div id="user-profile-modal" class="user-profile-modal-backdrop">
    <div class="user-profile-modal">
        {{-- Content will be injected by JS --}}
    </div>
</div>

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

    <script src="{{ asset('js/app.js') }}?v={{ time() }}" type="module"></script>
    @stack('scripts')

</body>
</html>