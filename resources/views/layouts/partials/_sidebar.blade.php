<div id="hamburger-btn" class="hamburger" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </div>

<div id="sidebar" class="sidebar">
    <button class="sidebar-close-btn" onclick="closeSidebar()">✕</button>
    <div class="sidebar-logo">LifeVault</div>

    <div class="nav-section-label">Overview</div>
    <div class="nav-item active" data-page="dashboard" onclick="navigateTo('dashboard', event)"><span class="nav-icon">🏠</span> Dashboard</div>
    

    <div class="nav-section-label">Tools</div>
    <div class="nav-item" data-page="journal" onclick="navigateTo('journal', event)"><span class="nav-icon">📓</span> Journal</div>
    <div class="nav-item" data-page="tasks" onclick="navigateTo('tasks', event)"><span class="nav-icon">✅</span> Tasks</div>
    <div class="nav-item" data-page="goals" onclick="navigateTo('goals', event)"><span class="nav-icon">🎯</span> Goals</div>
    <div class="nav-item" data-page="insights" onclick="navigateTo('insights', event)"><span class="nav-icon">📈</span> Insights</div>
    
    <div class="nav-section-label">Social</div>
    <div class="nav-item" data-page="community" onclick="navigateTo('community', event)">
        <span class="nav-icon">🧑‍🤝‍🧑</span> Community
        <div id="new-posts-dot"></div>
    </div>
    <div class="nav-item" data-page="profile" onclick="navigateTo('profile', event)"><span class="nav-icon">👤</span> Profile</div>
    <div class="nav-section-label">AI Tools</div>
    <div class="nav-item" data-page="analyzer" onclick="navigateTo('analyzer', event)"><span class="nav-icon">🔎</span> Resume Analyzer</div>
    <div class="nav-item" data-page="shadow-self" onclick="navigateTo('shadow-self', event)"><span class="nav-icon">👤</span> Shadow Self Analyzer</div>
    <div class="nav-item" data-page="life-story" onclick="navigateTo('life-story', event)"><span class="nav-icon">📖</span> Life Story Generator</div>

    <div class="nav-section-label">System</div>
    <div class="nav-item" data-page="settings" onclick="navigateTo('settings', event)"><span class="nav-icon">⚙️</span> Settings</div>

    <div class="sidebar-bottom">
        <div class="user-card">
                    <img id="user-avatar" src="" alt="User" class="user-avatar">
                    <div class="user-info">
                        <div id="user-name" class="user-name"></div>
                        <div id="user-email" class="user-email"></div>
                    </div>
                    <button class="signout-btn" onclick="signOutUser()">⍈</button>
                </div>
    </div>
</div>