<!-- ═══════════════════════════════════════════════════════════════
     LIFEVAULT — ENHANCED SETTINGS PAGE
     Drop-in replacement for <div id="page-settings" class="page">
     Includes: Notifications, Privacy, Appearance, Security,
               Integrations, Data Management, Danger Zone
════════════════════════════════════════════════════════════════ -->

<div id="page-settings" class="page">

  <!-- ── Page Header ── -->
  <div class="s-page-header">
    <div class="s-header-left">
      <div class="s-header-eyebrow">SYSTEM</div>
      <h1 class="s-header-title">Settings</h1>
      <p class="s-header-sub">Manage your account, preferences, and privacy</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
      <div class="s-header-avatar-wrap" id="s-header-avatar-wrap">
        <img id="s-header-avatar" src="" alt="avatar" class="s-header-avatar">
        <div class="s-header-avatar-ring"></div>
      </div>
    </div>
  </div>

  <!-- ── Tab Nav ── -->
  <div class="s-tab-nav" role="tablist">
    <button class="s-tab active" data-tab="notifications" onclick="switchSettingsTab('notifications',this)">
      <span class="s-tab-icon">🔔</span><span>Notifications</span>
    </button>
    <button class="s-tab" data-tab="privacy" onclick="switchSettingsTab('privacy',this)">
      <span class="s-tab-icon">🔒</span><span>Privacy</span>
    </button>
    <button class="s-tab" data-tab="appearance" onclick="switchSettingsTab('appearance',this)">
      <span class="s-tab-icon">🎨</span><span>Appearance</span>
    </button>
    <button class="s-tab" data-tab="security" onclick="switchSettingsTab('security',this)">
      <span class="s-tab-icon">🛡️</span><span>Security</span>
    </button>
    <button class="s-tab" data-tab="data" onclick="switchSettingsTab('data',this)">
      <span class="s-tab-icon">🗄️</span><span>Data</span>
    </button>
    <div class="s-tab-save-wrap">
      <button id="s-save-btn" class="s-tab-save-btn" onclick="handleSave()">
        💾 Save Changes
      </button>
    </div>
  </div>

  <!-- ══════════════════════════════════════════════
       TAB: NOTIFICATIONS
  ═══════════════════════════════════════════════ -->
  <div class="s-tab-panel active" id="s-panel-notifications">

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(251,191,36,.12);border-color:rgba(251,191,36,.25)">🔔</div>
        <div>
          <div class="s-section-title">Journal & Activity</div>
          <div class="s-section-desc">Control when LifeVault nudges you</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Journal Reminders</div>
            <div class="s-toggle-sub">Daily nudge to write your journal entry</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="notif-journal" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Goal Milestones</div>
            <div class="s-toggle-sub">Celebrate when you hit a goal milestone</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="notif-goals" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Streak Alerts</div>
            <div class="s-toggle-sub">Notify before your writing streak breaks</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="notif-streak" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Weekly Digest</div>
            <div class="s-toggle-sub">Summary of your week every Sunday</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="notif-digest"><span class="s-slider"></span></label>
        </div>
      </div>
    </div>

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(79,142,247,.12);border-color:rgba(79,142,247,.25)">🌐</div>
        <div>
          <div class="s-section-title">Community</div>
          <div class="s-section-desc">Social interactions on your posts</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Community Activity</div>
            <div class="s-toggle-sub">Likes and comments on your posts</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="notif-community"><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">New Followers</div>
            <div class="s-toggle-sub">When someone starts following you</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="notif-followers"><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Trending Posts</div>
            <div class="s-toggle-sub">Highlights from the community feed</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="notif-trending"><span class="s-slider"></span></label>
        </div>
      </div>
    </div>

    <!-- Reminder Time -->
    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(45,212,191,.12);border-color:rgba(45,212,191,.25)">⏰</div>
        <div>
          <div class="s-section-title">Reminder Time</div>
          <div class="s-section-desc">When should we send your daily reminder?</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-time-picker-row">
          <div class="s-time-chips" id="notif-time-chips">
            <button class="s-time-chip" data-time="08:00" onclick="selectReminderTime('08:00',this)">8 AM</button>
            <button class="s-time-chip active" data-time="20:00" onclick="selectReminderTime('20:00',this)">8 PM</button>
            <button class="s-time-chip" data-time="12:00" onclick="selectReminderTime('12:00',this)">Noon</button>
            <button class="s-time-chip" data-time="21:00" onclick="selectReminderTime('21:00',this)">9 PM</button>
            <button class="s-time-chip" data-time="22:00" onclick="selectReminderTime('22:00',this)">10 PM</button>
          </div>
        </div>
        <div style="padding:12px 0 4px;display:flex;align-items:center;gap:10px">
          <span class="s-toggle-sub">Custom time:</span>
          <input type="time" id="notif-custom-time" value="20:00" class="s-time-input" onchange="onSettingChange()">
        </div>
      </div>
    </div>

  </div>

  <!-- ══════════════════════════════════════════════
       TAB: PRIVACY
  ═══════════════════════════════════════════════ -->
  <div class="s-tab-panel" id="s-panel-privacy">

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(167,139,250,.12);border-color:rgba(167,139,250,.25)">👤</div>
        <div>
          <div class="s-section-title">Profile Visibility</div>
          <div class="s-section-desc">Control who can see your content</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Public Profile</div>
            <div class="s-toggle-sub">Allow others to view your profile page</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="privacy-public" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Show in Community</div>
            <div class="s-toggle-sub">Your posts appear in the community feed</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="privacy-community" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Show Activity Status</div>
            <div class="s-toggle-sub">Let others see when you were last active</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="privacy-activity"><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Show Streak Count</div>
            <div class="s-toggle-sub">Display your writing streak on your profile</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="privacy-streak" checked><span class="s-slider"></span></label>
        </div>
      </div>
    </div>

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(52,211,153,.12);border-color:rgba(52,211,153,.25)">📓</div>
        <div>
          <div class="s-section-title">Journal Privacy</div>
          <div class="s-section-desc">Default sharing settings for new entries</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-radio-group" id="journal-default-privacy">
          <label class="s-radio-row">
            <input type="radio" name="journal-privacy" value="private" checked>
            <span class="s-radio-custom"></span>
            <div class="s-toggle-info">
              <div class="s-toggle-label">🔒 Private by default</div>
              <div class="s-toggle-sub">New entries are only visible to you</div>
            </div>
          </label>
          <label class="s-radio-row">
            <input type="radio" name="journal-privacy" value="friends">
            <span class="s-radio-custom"></span>
            <div class="s-toggle-info">
              <div class="s-toggle-label">👥 Followers only</div>
              <div class="s-toggle-sub">Visible to people who follow you</div>
            </div>
          </label>
          <label class="s-radio-row">
            <input type="radio" name="journal-privacy" value="public">
            <span class="s-radio-custom"></span>
            <div class="s-toggle-info">
              <div class="s-toggle-label">🌐 Public</div>
              <div class="s-toggle-sub">Anyone can discover your entries</div>
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- Blocked topics -->
    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(248,113,113,.12);border-color:rgba(248,113,113,.25)">🚫</div>
        <div>
          <div class="s-section-title">Content Filters</div>
          <div class="s-section-desc">Filter content in your community feed</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Sensitive Content Warning</div>
            <div class="s-toggle-sub">Show a blur overlay on flagged posts</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="filter-sensitive" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Hide Political Content</div>
            <div class="s-toggle-sub">Filter politically-tagged posts from feed</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="filter-political"><span class="s-slider"></span></label>
        </div>
      </div>
    </div>

  </div>

  <!-- ══════════════════════════════════════════════
       TAB: APPEARANCE
  ═══════════════════════════════════════════════ -->
  <div class="s-tab-panel" id="s-panel-appearance">

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(251,191,36,.12);border-color:rgba(251,191,36,.25)">🌓</div>
        <div>
          <div class="s-section-title">Theme</div>
          <div class="s-section-desc">Choose your visual experience</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-theme-grid" id="s-theme-grid">
          <button class="s-theme-card active" data-theme="dark" onclick="selectTheme('dark',this)">
            <div class="s-theme-preview s-theme-dark">
              <div class="s-theme-bar"></div>
              <div class="s-theme-lines"><div></div><div></div><div></div></div>
            </div>
            <span>Dark</span>
          </button>
          <button class="s-theme-card" data-theme="midnight" onclick="selectTheme('midnight',this)">
            <div class="s-theme-preview s-theme-midnight">
              <div class="s-theme-bar"></div>
              <div class="s-theme-lines"><div></div><div></div><div></div></div>
            </div>
            <span>Midnight</span>
          </button>
          <button class="s-theme-card" data-theme="forest" onclick="selectTheme('forest',this)">
            <div class="s-theme-preview s-theme-forest">
              <div class="s-theme-bar"></div>
              <div class="s-theme-lines"><div></div><div></div><div></div></div>
            </div>
            <span>Forest</span>
          </button>
          <button class="s-theme-card" data-theme="rose" onclick="selectTheme('rose',this)">
            <div class="s-theme-preview s-theme-rose">
              <div class="s-theme-bar"></div>
              <div class="s-theme-lines"><div></div><div></div><div></div></div>
            </div>
            <span>Rose</span>
          </button>
        </div>
      </div>
    </div>

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(79,142,247,.12);border-color:rgba(79,142,247,.25)">✍️</div>
        <div>
          <div class="s-section-title">Journal Font</div>
          <div class="s-section-desc">Typography for your journal entries</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-font-list" id="s-font-list">
          <label class="s-font-row active" data-font="Newsreader" onclick="selectFont('Newsreader',this)">
            <span class="s-font-preview" style="font-family:'Newsreader',serif">The quick brown fox jumps</span>
            <span class="s-font-name">Newsreader</span>
            <span class="s-font-check">✓</span>
          </label>
          <label class="s-font-row" data-font="Georgia" onclick="selectFont('Georgia',this)">
            <span class="s-font-preview" style="font-family:Georgia,serif">The quick brown fox jumps</span>
            <span class="s-font-name">Georgia</span>
            <span class="s-font-check">✓</span>
          </label>
          <label class="s-font-row" data-font="'Courier New'" onclick="selectFont('Courier New',this)">
            <span class="s-font-preview" style="font-family:'Courier New',monospace">The quick brown fox jumps</span>
            <span class="s-font-name">Courier New</span>
            <span class="s-font-check">✓</span>
          </label>
          <label class="s-font-row" data-font="Palatino" onclick="selectFont('Palatino',this)">
            <span class="s-font-preview" style="font-family:Palatino,serif">The quick brown fox jumps</span>
            <span class="s-font-name">Palatino</span>
            <span class="s-font-check">✓</span>
          </label>
        </div>
      </div>
    </div>

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(45,212,191,.12);border-color:rgba(45,212,191,.25)">🔠</div>
        <div>
          <div class="s-section-title">Font Size</div>
          <div class="s-section-desc">Journal entry reading size</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-slider-row">
          <span class="s-slider-label" style="font-size:.75rem">Aa</span>
          <input type="range" id="font-size-range" min="14" max="22" value="16" step="1" class="s-range" oninput="updateFontSizeLabel(this.value);onSettingChange()">
          <span class="s-slider-label" style="font-size:1.1rem">Aa</span>
          <span class="s-font-size-badge" id="font-size-badge">16px</span>
        </div>
        <div class="s-font-preview-live" id="font-preview-live" style="font-family:'Newsreader',serif;font-size:16px">
          "Today I stepped outside and felt the wind carry something away — a worry I'd been holding for weeks."
        </div>
      </div>
    </div>

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(167,139,250,.12);border-color:rgba(167,139,250,.25)">✨</div>
        <div>
          <div class="s-section-title">Visual Effects</div>
          <div class="s-section-desc">Animations and decorative elements</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Grid Background</div>
            <div class="s-toggle-sub">Subtle grid lines on the background</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="fx-grid" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Glow Effects</div>
            <div class="s-toggle-sub">Blue glow on cards and interactive elements</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="fx-glow" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Reduce Motion</div>
            <div class="s-toggle-sub">Minimize animations across the app</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="fx-reduce-motion"><span class="s-slider"></span></label>
        </div>
      </div>
    </div>

  </div>

  <!-- ══════════════════════════════════════════════
       TAB: SECURITY
  ═══════════════════════════════════════════════ -->
  <div class="s-tab-panel" id="s-panel-security">

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(52,211,153,.12);border-color:rgba(52,211,153,.25)">🔐</div>
        <div>
          <div class="s-section-title">Account Security</div>
          <div class="s-section-desc">Keep your vault safe</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-info-row">
          <div class="s-info-icon">✅</div>
          <div class="s-toggle-info">
            <div class="s-toggle-label">Google Sign-In</div>
            <div class="s-toggle-sub">Your account is secured with Google OAuth</div>
          </div>
          <span class="s-badge s-badge-green">Active</span>
        </div>
        <div class="s-info-row">
          <div class="s-info-icon">🔑</div>
          <div class="s-toggle-info">
            <div class="s-toggle-label">Session Management</div>
            <div class="s-toggle-sub">You're signed in on this device</div>
          </div>
          <button class="s-action-btn" onclick="signOutUser()">Sign Out</button>
        </div>
      </div>
    </div>

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(79,142,247,.12);border-color:rgba(79,142,247,.25)">🔔</div>
        <div>
          <div class="s-section-title">Login Alerts</div>
          <div class="s-section-desc">Get notified about account access</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">New Device Login</div>
            <div class="s-toggle-sub">Alert when a new device signs into your account</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="sec-new-device" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Suspicious Activity</div>
            <div class="s-toggle-sub">Notify if unusual behavior is detected</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="sec-suspicious" checked><span class="s-slider"></span></label>
        </div>
      </div>
    </div>

    <!-- Active Sessions (cosmetic) -->
    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(251,191,36,.12);border-color:rgba(251,191,36,.25)">📱</div>
        <div>
          <div class="s-section-title">Active Sessions</div>
          <div class="s-section-desc">Devices currently signed into your account</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-session-row">
          <div class="s-session-icon">🖥️</div>
          <div class="s-toggle-info">
            <div class="s-toggle-label">This device <span class="s-badge s-badge-green" style="margin-left:6px;font-size:.55rem">Current</span></div>
            <div class="s-toggle-sub">Last active: just now</div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- ══════════════════════════════════════════════
       TAB: DATA
  ═══════════════════════════════════════════════ -->
  <div class="s-tab-panel" id="s-panel-data">

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(79,142,247,.12);border-color:rgba(79,142,247,.25)">📦</div>
        <div>
          <div class="s-section-title">Export Your Data</div>
          <div class="s-section-desc">Download a complete copy of your LifeVault</div>
        </div>
      </div>
      <div class="s-card">
        <p class="s-prose">Your data includes all journal entries, tasks, goals, and profile info. Exported as a readable JSON file.</p>
        <div class="s-export-grid">
          <div class="s-export-item">
            <span class="s-export-icon">📓</span>
            <span class="s-export-label">Journal Entries</span>
          </div>
          <div class="s-export-item">
            <span class="s-export-icon">✅</span>
            <span class="s-export-label">Tasks</span>
          </div>
          <div class="s-export-item">
            <span class="s-export-icon">🎯</span>
            <span class="s-export-label">Goals</span>
          </div>
          <div class="s-export-item">
            <span class="s-export-icon">👤</span>
            <span class="s-export-label">Profile</span>
          </div>
        </div>
        <button class="s-btn s-btn-accent" onclick="exportUserData()" style="margin-top:16px">
          <span>📦</span> Export My Data
        </button>
      </div>
    </div>

    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(251,191,36,.12);border-color:rgba(251,191,36,.25)">🗑️</div>
        <div>
          <div class="s-section-title">Data Retention</div>
          <div class="s-section-desc">How long we keep your deleted content</div>
        </div>
      </div>
      <div class="s-card">
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Keep Deleted Entries 30 Days</div>
            <div class="s-toggle-sub">Recover journal entries within 30 days of deletion</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="data-soft-delete" checked><span class="s-slider"></span></label>
        </div>
        <div class="s-toggle-row">
          <div class="s-toggle-info">
            <div class="s-toggle-label">Auto-Backup to Export</div>
            <div class="s-toggle-sub">Monthly automatic data snapshot reminder</div>
          </div>
          <label class="s-toggle"><input type="checkbox" id="data-auto-backup"><span class="s-slider"></span></label>
        </div>
      </div>
    </div>

    <!-- Danger Zone -->
    <div class="s-section">
      <div class="s-section-header">
        <div class="s-section-icon-wrap" style="background:rgba(248,113,113,.12);border-color:rgba(248,113,113,.3)">⚠️</div>
        <div>
          <div class="s-section-title" style="color:var(--rose)">Danger Zone</div>
          <div class="s-section-desc">Irreversible account actions</div>
        </div>
      </div>
      <div class="s-card s-card-danger">
        <div class="s-danger-row">
          <div>
            <div class="s-toggle-label">Clear All Journal Entries</div>
            <div class="s-toggle-sub">Permanently delete all your journal entries. Cannot be undone.</div>
          </div>
          <button class="s-btn s-btn-danger-outline" onclick="window.toast && window.toast('This feature is coming soon.','⚠️')">Clear Journals</button>
        </div>
        <div class="s-danger-row" style="border-bottom:none">
          <div>
            <div class="s-toggle-label" style="color:var(--rose)">Delete Account</div>
            <div class="s-toggle-sub">Permanently remove your account and all data. 30-day grace period applies.</div>
          </div>
          <button class="s-btn s-btn-danger" onclick="confirmDeleteAccount()">Delete Account</button>
        </div>
      </div>
    </div>

  </div>


</div><!-- /#page-settings -->


<!-- ═══════════════════════════════════════════════════════════
     STYLES
════════════════════════════════════════════════════════════ -->
<style>
/* ── Page header ─────────────────────────────────────────── */
.s-page-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 28px;
  gap: 16px;
}
.s-header-eyebrow {
  font-family: 'JetBrains Mono', monospace;
  font-size: .58rem;
  letter-spacing: .2em;
  color: var(--accent);
  margin-bottom: 6px;
  opacity: .7;
}
.s-header-title {
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: -.04em;
  line-height: 1;
  margin: 0 0 6px;
}
.s-header-sub {
  font-family: 'Newsreader', serif;
  font-style: italic;
  font-size: .88rem;
  color: var(--muted);
  font-weight: 300;
}
.s-header-avatar-wrap {
  position: relative;
  flex-shrink: 0;
}
.s-header-avatar {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--accent);
  display: block;
}
.s-header-avatar-ring {
  position: absolute;
  inset: -4px;
  border-radius: 50%;
  border: 1px solid rgba(79,142,247,.3);
  pointer-events: none;
}

/* ── Tab nav ────────────────────────────────────────────── */
.s-tab-nav {
  display: flex;
  gap: 4px;
  margin-bottom: 24px;
  border-bottom: 1px solid var(--border);
  padding-bottom: 0;
  overflow-x: auto;
  scrollbar-width: none;
}
.s-tab-nav::-webkit-scrollbar { display: none; }
.s-tab {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 10px 16px;
  border: none;
  background: transparent;
  color: var(--muted);
  font-family: 'Syne', sans-serif;
  font-size: .78rem;
  font-weight: 600;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  margin-bottom: -1px;
  transition: color .2s, border-color .2s;
  white-space: nowrap;
  border-radius: 0;
}
.s-tab:hover { color: var(--text); }
.s-tab.active { color: var(--accent); border-bottom-color: var(--accent); }
.s-tab-icon { font-size: .9rem; }

/* ── Tab panels ─────────────────────────────────────────── */
.s-tab-panel { display: none; max-width: 680px; padding-bottom: 120px; }
.s-tab-panel.active { display: block; animation: s-panelIn .25s ease both; }
@keyframes s-panelIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Section ────────────────────────────────────────────── */
.s-section { margin-bottom: 24px; }
.s-section-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}
.s-section-icon-wrap {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  border: 1px solid;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .9rem;
  flex-shrink: 0;
}
.s-section-title {
  font-size: .9rem;
  font-weight: 700;
  letter-spacing: -.01em;
  line-height: 1.2;
}
.s-section-desc {
  font-size: .68rem;
  color: var(--muted);
  font-family: 'JetBrains Mono', monospace;
  margin-top: 2px;
}

/* ── Card ───────────────────────────────────────────────── */
.s-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 4px 20px;
  transition: border-color .2s;
}
.s-card:focus-within { border-color: rgba(79,142,247,.3); }
.s-card-danger {
  border-color: rgba(248,113,113,.2);
  padding: 4px 20px;
}
.s-card-danger:hover { border-color: rgba(248,113,113,.4); }

/* ── Toggle row ─────────────────────────────────────────── */
.s-toggle-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 15px 0;
  border-bottom: 1px solid var(--border);
}
.s-toggle-row:last-child { border-bottom: none; }
.s-toggle-info { flex: 1; min-width: 0; }
.s-toggle-label {
  font-size: .83rem;
  font-weight: 600;
  color: var(--text);
  font-family: 'Syne', sans-serif;
  margin-bottom: 3px;
}
.s-toggle-sub {
  font-size: .67rem;
  color: var(--muted);
  font-family: 'JetBrains Mono', monospace;
  line-height: 1.4;
}

/* ── Toggle switch ──────────────────────────────────────── */
.s-toggle {
  position: relative;
  width: 42px;
  height: 23px;
  flex-shrink: 0;
  cursor: pointer;
  display: inline-block;
}
.s-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.s-slider {
  position: absolute;
  inset: 0;
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 99px;
  transition: background .22s, border-color .22s;
}
.s-slider::before {
  content: '';
  position: absolute;
  width: 15px;
  height: 15px;
  left: 3px;
  top: 50%;
  transform: translateY(-50%);
  background: #5a6a8a;
  border-radius: 50%;
  transition: transform .22s cubic-bezier(.34,1.56,.64,1), background .22s;
  box-shadow: 0 1px 3px rgba(0,0,0,.35);
}
.s-toggle:hover .s-slider { border-color: rgba(79,142,247,.5); }
.s-toggle input:checked + .s-slider {
  background: rgba(79,142,247,.16);
  border-color: var(--accent);
}
.s-toggle input:checked + .s-slider::before {
  transform: translateX(19px) translateY(-50%);
  background: var(--accent);
  box-shadow: 0 0 10px rgba(79,142,247,.5);
}

/* ── Radio rows ─────────────────────────────────────────── */
.s-radio-group { display: flex; flex-direction: column; }
.s-radio-row {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 0;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  transition: background .15s;
}
.s-radio-row:last-child { border-bottom: none; }
.s-radio-row input { position: absolute; opacity: 0; }
.s-radio-custom {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: 2px solid var(--border);
  flex-shrink: 0;
  position: relative;
  transition: border-color .2s;
}
.s-radio-row:has(input:checked) .s-radio-custom {
  border-color: var(--accent);
}
.s-radio-row:has(input:checked) .s-radio-custom::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  width: 8px;
  height: 8px;
  background: var(--accent);
  border-radius: 50%;
}
.s-radio-row:has(input:checked) .s-toggle-label { color: var(--accent); }

/* ── Theme cards ────────────────────────────────────────── */
.s-theme-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  padding: 12px 0;
}
.s-theme-card {
  background: var(--surface2);
  border: 2px solid var(--border);
  border-radius: 12px;
  padding: 10px;
  cursor: pointer;
  font-family: 'JetBrains Mono', monospace;
  font-size: .62rem;
  color: var(--muted);
  text-align: center;
  transition: border-color .2s, transform .2s, color .2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}
.s-theme-card:hover { border-color: rgba(79,142,247,.4); transform: translateY(-2px); }
.s-theme-card.active { border-color: var(--accent); color: var(--accent); }
.s-theme-preview {
  width: 100%;
  height: 44px;
  border-radius: 6px;
  overflow: hidden;
  position: relative;
}
.s-theme-bar {
  height: 10px;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
}
.s-theme-lines {
  position: absolute;
  bottom: 6px;
  left: 6px;
  right: 6px;
  display: flex;
  flex-direction: column;
  gap: 3px;
}
.s-theme-lines div { height: 2px; border-radius: 2px; background: rgba(255,255,255,.15); }
.s-theme-lines div:first-child { width: 70%; }
.s-theme-dark  { background: #0b0f1a; }
.s-theme-dark .s-theme-bar { background: #111827; }
.s-theme-midnight { background: #0f0c29; }
.s-theme-midnight .s-theme-bar { background: #1a1640; }
.s-theme-forest { background: #071a0e; }
.s-theme-forest .s-theme-bar { background: #0d2e18; }
.s-theme-rose { background: #1a0a0a; }
.s-theme-rose .s-theme-bar { background: #2d1212; }

/* ── Font list ──────────────────────────────────────────── */
.s-font-list { display: flex; flex-direction: column; }
.s-font-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 0;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  transition: background .15s;
}
.s-font-row:last-child { border-bottom: none; }
.s-font-preview {
  flex: 1;
  font-size: .9rem;
  color: rgba(232,234,240,.75);
  font-style: italic;
}
.s-font-name {
  font-family: 'JetBrains Mono', monospace;
  font-size: .62rem;
  color: var(--muted);
  flex-shrink: 0;
}
.s-font-check {
  color: var(--accent);
  font-size: .8rem;
  opacity: 0;
  transition: opacity .2s;
  flex-shrink: 0;
}
.s-font-row.active .s-font-check { opacity: 1; }
.s-font-row.active .s-font-preview { color: var(--text); }
.s-font-row.active .s-font-name { color: var(--accent); }

/* ── Slider row ─────────────────────────────────────────── */
.s-slider-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 0 8px;
}
.s-slider-label { color: var(--muted); flex-shrink: 0; }
.s-range {
  flex: 1;
  accent-color: var(--accent);
  height: 4px;
  cursor: pointer;
}
.s-font-size-badge {
  font-family: 'JetBrains Mono', monospace;
  font-size: .65rem;
  color: var(--accent);
  background: rgba(79,142,247,.12);
  border: 1px solid rgba(79,142,247,.25);
  padding: 3px 8px;
  border-radius: 6px;
  flex-shrink: 0;
}
.s-font-preview-live {
  font-style: italic;
  color: rgba(232,234,240,.6);
  line-height: 1.7;
  font-weight: 300;
  padding: 12px 0;
  border-top: 1px solid var(--border);
  transition: font-size .2s;
}

/* ── Info rows ──────────────────────────────────────────── */
.s-info-row {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 15px 0;
  border-bottom: 1px solid var(--border);
}
.s-info-row:last-child { border-bottom: none; }
.s-info-icon { font-size: 1.1rem; flex-shrink: 0; }
.s-session-row {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 15px 0;
}
.s-session-icon { font-size: 1.2rem; flex-shrink: 0; }

/* ── Badges ─────────────────────────────────────────────── */
.s-badge {
  font-family: 'JetBrains Mono', monospace;
  font-size: .6rem;
  text-transform: uppercase;
  letter-spacing: .08em;
  padding: 3px 8px;
  border-radius: 5px;
  font-weight: 600;
  flex-shrink: 0;
}
.s-badge-green {
  background: rgba(52,211,153,.15);
  color: var(--green);
  border: 1px solid rgba(52,211,153,.25);
}

/* ── Buttons ────────────────────────────────────────────── */
.s-btn {
  font-family: 'Syne', sans-serif;
  font-size: .78rem;
  font-weight: 600;
  padding: 8px 16px;
  border-radius: 9px;
  border: 1px solid var(--border);
  background: var(--surface2);
  color: var(--text);
  cursor: pointer;
  transition: all .18s;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  white-space: nowrap;
  flex-shrink: 0;
}
.s-btn:hover { border-color: rgba(79,142,247,.4); color: var(--accent); background: rgba(79,142,247,.06); }
.s-btn-accent { background: var(--accent); border-color: var(--accent); color: white; }
.s-btn-accent:hover { background: #3a7ae0; border-color: #3a7ae0; color: white; }
.s-btn-ghost { background: transparent; border-color: var(--border); color: var(--muted); }
.s-btn-ghost:hover { color: var(--text); border-color: rgba(255,255,255,.15); background: var(--surface2); }
.s-btn-save {
  background: linear-gradient(135deg, rgba(79,142,247,.18), rgba(45,212,191,.18));
  border-color: rgba(79,142,247,.45);
  color: var(--accent);
  font-weight: 700;
  letter-spacing: .02em;
  padding: 9px 20px;
  border-radius: 10px;
  box-shadow: 0 0 0 0 rgba(79,142,247,0);
  transition: all .22s;
}
.s-btn-save:hover {
  background: linear-gradient(135deg, rgba(79,142,247,.28), rgba(45,212,191,.28));
  border-color: rgba(79,142,247,.7);
  color: #a8cbff;
  transform: translateY(-1px);
  box-shadow: 0 4px 18px rgba(79,142,247,.25);
}
.s-action-btn {
  font-family: 'Syne', sans-serif;
  font-size: .72rem;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--surface2);
  color: var(--muted);
  cursor: pointer;
  transition: all .18s;
  flex-shrink: 0;
}
.s-action-btn:hover { color: var(--rose); border-color: var(--rose); }
.s-btn-danger-outline {
  border-color: rgba(248,113,113,.3);
  color: var(--rose);
  background: transparent;
}
.s-btn-danger-outline:hover { background: rgba(248,113,113,.1); border-color: var(--rose); color: var(--rose); }
.s-btn-danger {
  background: rgba(248,113,113,.12);
  border-color: rgba(248,113,113,.4);
  color: var(--rose);
}
.s-btn-danger:hover { background: rgba(248,113,113,.22); border-color: var(--rose); color: var(--rose); }

/* ── Danger rows ────────────────────────────────────────── */
.s-danger-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 16px 0;
  border-bottom: 1px solid rgba(248,113,113,.12);
}

/* ── Export grid ────────────────────────────────────────── */
.s-prose {
  font-family: 'Newsreader', serif;
  font-style: italic;
  font-size: .88rem;
  color: var(--muted);
  line-height: 1.6;
  margin: 12px 0 16px;
}
.s-export-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
}
.s-export-item {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 12px 8px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
}
.s-export-icon { font-size: 1.2rem; }
.s-export-label { font-family: 'JetBrains Mono', monospace; font-size: .58rem; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; }

/* ── Time picker ────────────────────────────────────────── */
.s-time-picker-row { padding: 12px 0 4px; }
.s-time-chips { display: flex; gap: 8px; flex-wrap: wrap; }
.s-time-chip {
  font-family: 'JetBrains Mono', monospace;
  font-size: .65rem;
  padding: 6px 14px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--surface2);
  color: var(--muted);
  cursor: pointer;
  transition: all .18s;
  text-transform: uppercase;
  letter-spacing: .08em;
}
.s-time-chip:hover { border-color: rgba(79,142,247,.4); color: var(--accent); }
.s-time-chip.active { border-color: var(--accent); background: rgba(79,142,247,.12); color: var(--accent); }
.s-time-input {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 6px 10px;
  color: var(--text);
  font-family: 'JetBrains Mono', monospace;
  font-size: .75rem;
  outline: none;
  transition: border-color .2s;
}
.s-time-input:focus { border-color: var(--accent); }

/* ── Save bar ───────────────────────────────────────────── */
.s-save-bar {
  position: fixed;
  bottom: -90px;
  left: 260px;
  right: 0;
  padding: 0 36px 20px;
  transition: bottom .35s cubic-bezier(.34,1.3,.64,1);
  z-index: 150;
  pointer-events: none;
}
.s-save-bar.visible { bottom: 0; }
.s-save-bar-inner {
  max-width: 680px;
  background: rgba(17,24,39,.95);
  border: 1px solid rgba(79,142,247,.25);
  border-radius: 14px;
  padding: 13px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 8px 40px rgba(0,0,0,.5), 0 0 0 1px rgba(79,142,247,.06), 0 0 60px rgba(79,142,247,.08);
  pointer-events: auto;
  backdrop-filter: blur(16px);
  font-family: 'Syne', sans-serif;
  font-size: .82rem;
  font-weight: 600;
  color: var(--muted);
}
.s-save-dot {
  width: 7px;
  height: 7px;
  background: var(--amber);
  border-radius: 50%;
  flex-shrink: 0;
  animation: s-pulse 1.8s ease-in-out infinite;
}
@keyframes s-pulse {
  0%,100% { opacity: 1; transform: scale(1); }
  50% { opacity: .5; transform: scale(.7); }
}

/* ── Save btn in tab nav ─────────────────────────────────── */
.s-tab-save-wrap {
  margin-left: auto;
  display: flex;
  align-items: center;
  padding-bottom: 1px;
  flex-shrink: 0;
}
.s-tab-save-btn {
  font-family: 'Syne', sans-serif;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .02em;
  padding: 7px 16px;
  border-radius: 8px;
  border: 1px solid rgba(79,142,247,.35);
  background: linear-gradient(135deg, rgba(79,142,247,.14), rgba(45,212,191,.14));
  color: var(--accent);
  cursor: pointer;
  transition: all .2s;
  white-space: nowrap;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.s-tab-save-btn:hover {
  background: linear-gradient(135deg, rgba(79,142,247,.26), rgba(45,212,191,.26));
  border-color: rgba(79,142,247,.6);
  color: #a8cbff;
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(79,142,247,.2);
}
.s-tab-save-btn:disabled {
  opacity: .55;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

/* ── Save btn loading state ─────────────────────────────── */
#s-save-btn:disabled {
  opacity: .65;
  cursor: not-allowed;
  transform: none !important;
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 768px) {
  .s-theme-grid { grid-template-columns: repeat(2, 1fr); }
  .s-export-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .s-save-bar { left: 0; padding: 0 12px 12px; }
  .s-save-bar-inner { flex-wrap: wrap; gap: 10px; }
  .s-save-bar-inner > div { width: 100%; }
  .s-save-bar-inner > div .s-btn { flex: 1; justify-content: center; }
  .s-theme-grid { grid-template-columns: repeat(2, 1fr); }
  .s-export-grid { grid-template-columns: repeat(2, 1fr); }
  .s-tab { padding: 10px 10px; font-size: .72rem; }
  .s-tab span:last-child { display: none; }
  .s-tab-icon { font-size: 1rem; }
}
</style>


<!-- ═══════════════════════════════════════════════════════════
     SCRIPT
════════════════════════════════════════════════════════════ -->
<script>
(function () {
  'use strict';

  const $ = id => document.getElementById(id);

  /* ── Show / hide are now no-ops — button is always visible ── */
  function showBar() {}
  function hideBar() {
    const btn = $('s-save-btn');
    if (btn) { btn.disabled = false; btn.innerHTML = '💾 Save Changes'; }
  }

  /* ── Tab switching ─────────────────────────────────────── */
  window.switchSettingsTab = function(tab, btn) {
    document.querySelectorAll('.s-tab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.s-tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    $('s-panel-' + tab)?.classList.add('active');
  };

  /* ── Appearance helpers ────────────────────────────────── */
  window.selectTheme = function(theme, el) {
    document.querySelectorAll('.s-theme-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    // Use the global applyTheme if available, otherwise define it
    if (window.applyTheme) {
      window.applyTheme(theme);
    }
    onSettingChange();
  };

  window.selectFont = function(font, el) {
    document.querySelectorAll('.s-font-row').forEach(r => r.classList.remove('active'));
    el.classList.add('active');
    const preview = $('font-preview-live');
    if (preview) preview.style.fontFamily = font + ', serif';
    onSettingChange();
  };

  window.updateFontSizeLabel = function(val) {
    const badge   = $('font-size-badge');
    const preview = $('font-preview-live');
    if (badge)   badge.textContent      = val + 'px';
    if (preview) preview.style.fontSize = val + 'px';
  };

  window.selectReminderTime = function(time, el) {
    document.querySelectorAll('.s-time-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    const inp = $('notif-custom-time');
    if (inp) inp.value = time;
    onSettingChange();
  };

  /* ── Change gate ───────────────────────────────────────── */
  let _acceptingChanges = false;

  window.onSettingChange = function() {
    if (_acceptingChanges) showBar();
  };

  /* ── Silent toggle setter (replaces node to skip events) ── */
  function silentSet(id, value) {
    const el = $(id);
    if (!el) return;
    // Temporarily disable the change gate, set value directly
    const prev = _acceptingChanges;
    _acceptingChanges = false;
    el.checked = Boolean(value);
    _acceptingChanges = prev;
  }

  /* ── Attach change listeners to all inputs ─────────────── */
  let _listenersAttached = false;
  
  function attachListeners() {
    if (_listenersAttached) return; // Prevent duplicate listener attachment
    
    document.querySelectorAll(
      '#page-settings input[type="checkbox"], #page-settings input[type="radio"]'
    ).forEach(el => {
      // Remove any existing listener first to avoid duplicates
      el.removeEventListener('change', _onInputChange);
      el.addEventListener('change', _onInputChange);
    });
    
    _listenersAttached = true;
  }

  function _onInputChange() {
    if (_acceptingChanges) showBar();
  }

  /* ── Snapshot current UI state ─────────────────────────── */
  let _savedState = null;

  function currentState() {
    const journalPrivacyEl = document.querySelector('input[name="journal-privacy"]:checked');
    return {
      notifJournal:    $('notif-journal')?.checked    ?? true,
      notifGoals:      $('notif-goals')?.checked      ?? true,
      notifStreak:     $('notif-streak')?.checked     ?? true,
      notifDigest:     $('notif-digest')?.checked     ?? false,
      notifCommunity:  $('notif-community')?.checked  ?? false,
      notifFollowers:  $('notif-followers')?.checked  ?? false,
      notifTrending:   $('notif-trending')?.checked   ?? false,
      notifTime:       $('notif-custom-time')?.value  ?? '20:00',
      privPublic:      $('privacy-public')?.checked   ?? true,
      privCommunity:   $('privacy-community')?.checked ?? true,
      privActivity:    $('privacy-activity')?.checked ?? false,
      privStreak:      $('privacy-streak')?.checked   ?? true,
      filterSensitive: $('filter-sensitive')?.checked ?? true,
      filterPolitical: $('filter-political')?.checked ?? false,
      fxGrid:          $('fx-grid')?.checked          ?? true,
      fxGlow:          $('fx-glow')?.checked          ?? true,
      fxReduceMotion:  $('fx-reduce-motion')?.checked ?? false,
      secNewDevice:    $('sec-new-device')?.checked   ?? true,
      secSuspicious:   $('sec-suspicious')?.checked   ?? true,
      dataSoftDelete:  $('data-soft-delete')?.checked ?? true,
      dataAutoBackup:  $('data-auto-backup')?.checked ?? false,
      journalPrivacy:  journalPrivacyEl?.value ?? 'private',
      theme:    document.querySelector('.s-theme-card.active')?.dataset.theme ?? 'dark',
      font:     document.querySelector('.s-font-row.active')?.dataset.font    ?? 'Newsreader',
      fontSize: $('font-size-range')?.value ?? '16',
    };
  }

  /* ── Firebase wait ─────────────────────────────────────── */
  function waitForFirebase() {
    return new Promise(resolve => {
      const check = () => (window.db && window.auth && window._fbFS) ? resolve() : setTimeout(check, 80);
      check();
    });
  }

  /* ── Load settings from Firestore ─────────────────────── */
  async function load() {
    // Only load if the settings page is visible
    const settingsPage = document.getElementById('page-settings');
    if (!settingsPage || !settingsPage.classList.contains('active')) {
      return; // Don't load if settings page isn't visible
    }

    _acceptingChanges = false;
    hideBar();

    // Sync avatar in header
    const av = window.auth?.currentUser?.photoURL
      || 'https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff';
    const img = $('s-header-avatar');
    if (img) img.src = av;

    await waitForFirebase();
    const user = window.auth?.currentUser;
    if (!user) return;

    try {
      const { getDoc, doc } = window._fbFS;
      const snap = await getDoc(doc(window.db, 'settings', user.uid));

      if (snap.exists()) {
        const s   = snap.data();
        const n   = s.notifications || {};
        const p   = s.privacy       || {};
        const a   = s.appearance    || {};
        const sec = s.security      || {};
        const d   = s.data          || {};

        silentSet('notif-journal',   n.journal   ?? true);
        silentSet('notif-goals',     n.goals     ?? true);
        silentSet('notif-streak',    n.streak    ?? true);
        silentSet('notif-digest',    n.digest    ?? false);
        silentSet('notif-community', n.community ?? false);
        silentSet('notif-followers', n.followers ?? false);
        silentSet('notif-trending',  n.trending  ?? false);
        if ($('notif-custom-time') && n.time) $('notif-custom-time').value = n.time;

        // Restore reminder time chip selection
        if (n.time) {
          const timeChip = document.querySelector(`.s-time-chip[data-time="${n.time}"]`);
          if (timeChip) {
            document.querySelectorAll('.s-time-chip').forEach(c => c.classList.remove('active'));
            timeChip.classList.add('active');
          }
        }

        silentSet('privacy-public',    p.public    ?? true);
        silentSet('privacy-community', p.community ?? true);
        silentSet('privacy-activity',  p.activity  ?? false);
        silentSet('privacy-streak',    p.streak    ?? true);
        silentSet('filter-sensitive',  p.filterSensitive ?? true);
        silentSet('filter-political',  p.filterPolitical ?? false);

        // Restore journal privacy selection
        if (p.journalPrivacy) {
          const privInput = document.querySelector(`input[name="journal-privacy"][value="${p.journalPrivacy}"]`);
          if (privInput) privInput.checked = true;
        }

        silentSet('fx-grid',          a.grid         ?? true);
        silentSet('fx-glow',          a.glow         ?? true);
        silentSet('fx-reduce-motion', a.reduceMotion ?? false);
        if (a.fontSize && $('font-size-range')) {
          $('font-size-range').value = a.fontSize;
          updateFontSizeLabel(a.fontSize);
        }

        // Restore theme selection
        if (a.theme) {
          const themeCard = document.querySelector(`.s-theme-card[data-theme="${a.theme}"]`);
          if (themeCard) {
            document.querySelectorAll('.s-theme-card').forEach(c => c.classList.remove('active'));
            themeCard.classList.add('active');
          }
          // Apply the theme to the document
          if (window.applyTheme) window.applyTheme(a.theme);
        }

        // Restore font selection
        if (a.font) {
          const fontRow = document.querySelector(`.s-font-row[data-font="${a.font}"]`);
          if (fontRow) {
            document.querySelectorAll('.s-font-row').forEach(r => r.classList.remove('active'));
            fontRow.classList.add('active');
            const preview = $('font-preview-live');
            if (preview) preview.style.fontFamily = a.font + ', serif';
          }
        }

        silentSet('sec-new-device',  sec.newDevice  ?? true);
        silentSet('sec-suspicious',  sec.suspicious ?? true);

        silentSet('data-soft-delete', d.softDelete ?? true);
        silentSet('data-auto-backup', d.autoBackup ?? false);
      }
    } catch (e) {
      console.warn('Settings load error:', e.message);
    }

    _savedState = currentState();
    attachListeners();

    // Apply the current theme (default or loaded)
    const currentTheme = document.querySelector('.s-theme-card.active')?.dataset.theme || 'dark';
    if (window.applyTheme) window.applyTheme(currentTheme);

    // Two rAF frames to let the browser finish rendering before we
    // open the gate — prevents the load itself from triggering showBar
    requestAnimationFrame(() => requestAnimationFrame(() => {
      _acceptingChanges = true;
      hideBar();
    }));
  }

  /* ── Save settings to Firestore ────────────────────────── */
  async function save() {
    const user = window.auth?.currentUser;
    if (!user) {
      window.toast?.('You must be logged in.', '⚠️');
      return false;
    }

    const s = currentState();
    const data = {
      notifications: {
        journal:   s.notifJournal,
        goals:     s.notifGoals,
        streak:    s.notifStreak,
        digest:    s.notifDigest,
        community: s.notifCommunity,
        followers: s.notifFollowers,
        trending:  s.notifTrending,
        time:      s.notifTime,
      },
      privacy: {
        public:          s.privPublic,
        community:       s.privCommunity,
        activity:        s.privActivity,
        streak:          s.privStreak,
        filterSensitive: s.filterSensitive,
        filterPolitical: s.filterPolitical,
        journalPrivacy:  s.journalPrivacy,
      },
      appearance: {
        theme:        s.theme,
        font:         s.font,
        fontSize:     s.fontSize,
        grid:         s.fxGrid,
        glow:         s.fxGlow,
        reduceMotion: s.fxReduceMotion,
      },
      security: {
        newDevice:  s.secNewDevice,
        suspicious: s.secSuspicious,
      },
      data: {
        softDelete: s.dataSoftDelete,
        autoBackup: s.dataAutoBackup,
      },
    };

    try {
      const { doc, setDoc, updateDoc } = window._fbFS;
      await Promise.all([
        setDoc(doc(window.db, 'settings', user.uid), data, { merge: true }),
        updateDoc(doc(window.db, 'users', user.uid), {
          isPublic:        data.privacy.public,
          showInCommunity: data.privacy.community,
        }).catch(() => {}),
      ]);

      // Keep in-memory profile in sync
      if (window.userProfile) {
        window.userProfile.isPublic        = data.privacy.public;
        window.userProfile.showInCommunity = data.privacy.community;
      }

      // Snapshot the newly-saved state so discard works correctly
      _savedState = currentState();

      window.toast?.('Settings saved! ✨', '✅');
      return true;
    } catch (e) {
      console.error('Settings save error:', e);
      window.toast?.('Error saving settings.', '🔥');
      return false;
    }
  }

  /* ── Discard — restore last saved state ────────────────── */
  function discard() {
    if (!_savedState) return;
    _acceptingChanges = false;

    const s = _savedState;
    silentSet('notif-journal',    s.notifJournal);
    silentSet('notif-goals',      s.notifGoals);
    silentSet('notif-streak',     s.notifStreak);
    silentSet('notif-digest',     s.notifDigest);
    silentSet('notif-community',  s.notifCommunity);
    silentSet('notif-followers',  s.notifFollowers);
    silentSet('notif-trending',   s.notifTrending);
    if ($('notif-custom-time')) $('notif-custom-time').value = s.notifTime;

    // Restore reminder time chip
    const reminderChip = document.querySelector(`.s-time-chip[data-time="${s.notifTime}"]`);
    if (reminderChip) {
      document.querySelectorAll('.s-time-chip').forEach(c => c.classList.remove('active'));
      reminderChip.classList.add('active');
    }

    silentSet('privacy-public',    s.privPublic);
    silentSet('privacy-community', s.privCommunity);
    silentSet('privacy-activity',  s.privActivity);
    silentSet('privacy-streak',    s.privStreak);
    silentSet('filter-sensitive',  s.filterSensitive);
    silentSet('filter-political',  s.filterPolitical);

    // Restore journal privacy
    const privInput = document.querySelector(`input[name="journal-privacy"][value="${s.journalPrivacy}"]`);
    if (privInput) privInput.checked = true;

    silentSet('fx-grid',          s.fxGrid);
    silentSet('fx-glow',          s.fxGlow);
    silentSet('fx-reduce-motion', s.fxReduceMotion);

    // Restore font size and preview
    if ($('font-size-range')) {
      $('font-size-range').value = s.fontSize;
      updateFontSizeLabel(s.fontSize);
    }

    // Restore theme
    const themeCard = document.querySelector(`.s-theme-card[data-theme="${s.theme}"]`);
    if (themeCard) {
      document.querySelectorAll('.s-theme-card').forEach(c => c.classList.remove('active'));
      themeCard.classList.add('active');
    }
    // Apply the theme
    if (window.applyTheme) window.applyTheme(s.theme);

    // Restore font
    const fontRow = document.querySelector(`.s-font-row[data-font="${s.font}"]`);
    if (fontRow) {
      document.querySelectorAll('.s-font-row').forEach(r => r.classList.remove('active'));
      fontRow.classList.add('active');
      const preview = $('font-preview-live');
      if (preview) preview.style.fontFamily = s.font + ', serif';
    }

    silentSet('sec-new-device',   s.secNewDevice);
    silentSet('sec-suspicious',   s.secSuspicious);
    silentSet('data-soft-delete', s.dataSoftDelete);
    silentSet('data-auto-backup', s.dataAutoBackup);

    requestAnimationFrame(() => requestAnimationFrame(() => {
      _acceptingChanges = true;
      hideBar();
    }));

    window.toast?.('Changes discarded', '↩️');
  }

  /* ── Public API ────────────────────────────────────────── */
  window.renderSettingsPage      = () => load();
  window._settingsModule         = { load, save, hideBar };
  window._settingsDiscardChanges = () => {}; // no-op — discard button removed

  window.handleSave = async function() {
    const btn = $('s-save-btn');

    // Prevent double-submit
    if (btn && btn.disabled) return;

    // Loading state
    if (btn) { btn.disabled = true; btn.innerHTML = '⏳ Saving…'; }

    try {
      const ok = await save();
      if (ok) {
        hideBar(); // resets label + disabled state
      } else {
        if (btn) { btn.disabled = false; btn.innerHTML = '💾 Save Changes'; }
      }
    } catch (err) {
      console.error('handleSave error:', err);
      if (btn) { btn.disabled = false; btn.innerHTML = '💾 Save Changes'; }
    }
  };

  /* ── Boot: wait for auth then load ────────────────────── */
  let _settingsInitialized = false;
  
  waitForFirebase().then(() => {
    if (!_settingsInitialized) {
      _settingsInitialized = true;
      window.auth.onAuthStateChanged(user => {
        if (user) load();
        else hideBar();
      });
    }
  });

})();
</script>