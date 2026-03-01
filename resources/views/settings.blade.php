<div id="page-settings" class="page">
    <div class="settings-container">
        <div class="page-header">
            <div>
                <div class="page-title">Settings ⚙️</div>
                <div class="page-subtitle">Manage your account, privacy, and data</div>
            </div>
        </div>

        <!-- ── Notifications ── -->
        <div class="settings-section">
            <div class="settings-section-title">
                <span class="settings-section-icon">🔔</span> Notifications
            </div>
            <div class="settings-card">
                <div class="settings-toggle-row">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Journal Reminders</div>
                        <div class="settings-toggle-sub">Daily nudge to write your journal entry</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="notif-journal" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="settings-toggle-row">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Goal Milestones</div>
                        <div class="settings-toggle-sub">Get notified when you hit a goal milestone</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="notif-goals" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="settings-toggle-row">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Community Activity</div>
                        <div class="settings-toggle-sub">Likes and comments on your posts</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="notif-community">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- ── Privacy ── -->
        <div class="settings-section">
            <div class="settings-section-title">
                <span class="settings-section-icon">🔒</span> Privacy
            </div>
            <div class="settings-card">
                <div class="settings-toggle-row">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Public Profile</div>
                        <div class="settings-toggle-sub">Allow others to view your profile</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="privacy-public" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="settings-toggle-row">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Show in Community</div>
                        <div class="settings-toggle-sub">Your posts appear in the community feed</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="privacy-community" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- ── Data Management ── -->
        <div class="settings-section">
            <div class="settings-section-title">
                <span class="settings-section-icon">🗄️</span> Data Management
            </div>
            <div class="settings-card">
                <p style="font-family:'Newsreader',serif;font-style:italic;color:var(--muted);font-size:.9rem;margin-bottom:20px;margin-top:0">
                    Export or delete your personal data from LifeVault.
                </p>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <button class="btn" onclick="exportUserData()">📦 Export My Data</button>
                    <button class="btn settings-btn-danger" onclick="confirmDeleteAccount()">🗑️ Delete My Account</button>
                </div>
            </div>
        </div>

    </div><!-- /.settings-container -->

    <!-- ── Save Bar ── -->
    <div id="settings-save-bar" class="settings-save-bar">
        <div class="settings-save-bar-content">
            <span>You have unsaved changes.</span>
            <button class="btn btn-primary" onclick="handleSave()">Save Changes</button>
        </div>
    </div>
</div>

<style>
/* ── Settings Layout ── */
.settings-container {
    max-width: 680px;
    padding-bottom: 100px;
}

.settings-section {
    margin-bottom: 28px;
}

/* Title uses div not h2 so it inherits Syne like the rest of the app */
.settings-section-title {
    font-size: .95rem;
    font-weight: 700;
    letter-spacing: -.01em;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text);
    font-family: 'Syne', sans-serif;
}

.settings-section-icon {
    font-size: .85rem;
    width: 28px;
    height: 28px;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.settings-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 6px 20px;
}

/* ── Toggle rows ── */
.settings-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
}
.settings-toggle-row:last-child {
    border-bottom: none;
}

.settings-toggle-label {
    font-size: .84rem;
    font-weight: 600;
    margin-bottom: 3px;
    font-family: 'Syne', sans-serif;
    color: var(--text);
}
.settings-toggle-sub {
    font-size: .68rem;
    color: var(--muted);
    font-family: 'JetBrains Mono', monospace;
    line-height: 1.4;
}

/* ── Toggle Switch ── */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.toggle-slider {
    position: absolute;
    inset: 0;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 99px;
    cursor: pointer;
    transition: background .25s, border-color .25s;
}
.toggle-slider::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    left: 3px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--muted);
    border-radius: 50%;
    transition: transform .25s cubic-bezier(.34,1.56,.64,1), background .25s;
}
.toggle-switch input:checked + .toggle-slider {
    background: rgba(79,142,247,.18);
    border-color: var(--accent);
}
.toggle-switch input:checked + .toggle-slider::before {
    transform: translateX(20px) translateY(-50%);
    background: var(--accent);
}

/* ── Danger button ── */
.settings-btn-danger {
    border-color: rgba(248,113,113,.35) !important;
    color: var(--rose) !important;
}
.settings-btn-danger:hover {
    background: rgba(248,113,113,.1) !important;
    border-color: var(--rose) !important;
}

/* ── Save Bar ── */
.settings-save-bar {
    position: fixed;
    bottom: -80px;
    left: 260px; /* match sidebar width */
    right: 0;
    padding: 0 36px 20px;
    transition: bottom .3s cubic-bezier(.34,1.56,.64,1);
    z-index: 100;
    pointer-events: none;
}
.settings-save-bar.visible {
    bottom: 0;
}
.settings-save-bar-content {
    max-width: 680px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 8px 32px rgba(0,0,0,.35), 0 0 0 1px rgba(79,142,247,.1);
    pointer-events: auto;
    gap: 16px;
}
.settings-save-bar-content span {
    font-size: .82rem;
    font-weight: 600;
    color: var(--muted);
    font-family: 'Syne', sans-serif;
}

@media (max-width: 600px) {
    .settings-save-bar {
        left: 0;
        padding: 0 16px 16px;
    }
}
</style>

<script>
(function () {
    // Guard: only show save bar after settings have fully loaded
    let _settingsReady = false;

    // ── Wait for Firebase to be ready ──────────────────────────
    function waitForFirebase(cb) {
        if (window.db && window.auth && window._fbFS) {
            cb();
        } else {
            setTimeout(() => waitForFirebase(cb), 80);
        }
    }

    // ── Load settings from Firestore ──────────────────────────
    async function loadUserSettings() {
      if (!window.currentUser) return; // Prevent race condition on initial load
        const user = window.auth.currentUser;
        if (!user) return;

        // Attach listeners first but guard with _settingsReady flag
        _attachChangeListeners();

        try {
            const { getDoc, doc } = window._fbFS;
            const snap = await getDoc(doc(window.db, 'settings', user.uid));

            if (snap.exists()) {
                const s = snap.data();
                if (s.notifications) {
                    _setToggle('notif-journal',   s.notifications.journal   ?? true);
                    _setToggle('notif-goals',     s.notifications.goals     ?? true);
                    _setToggle('notif-community', s.notifications.community ?? false);
                }
                if (s.privacy) {
                    _setToggle('privacy-public',    s.privacy.public    ?? true);
                    _setToggle('privacy-community', s.privacy.community ?? true);
                }
            }
        } catch (e) {
            console.warn('Settings load error:', e.message);
        }

        // Only NOW allow the save bar to appear on toggle changes
        _settingsReady = true;
        // Make sure save bar is hidden on initial load
        _hideSaveBar();
    }

    // Directly set .checked without triggering any events
    function _setToggle(id, value) {
        const el = document.getElementById(id);
        if (el) el.checked = Boolean(value);
    }

    function _attachChangeListeners() {
        document.querySelectorAll('#page-settings .toggle-switch input').forEach(el => {
            el.addEventListener('change', () => {
                // Only show save bar if initial load is complete
                if (_settingsReady) _showSaveBar();
            });
        });
    }

    // ── Save bar ──────────────────────────────────────────────
    function _showSaveBar() {
        document.getElementById('settings-save-bar').classList.add('visible');
    }
    function _hideSaveBar() {
        document.getElementById('settings-save-bar').classList.remove('visible');
    }

    // ── Save settings ─────────────────────────────────────────
    async function saveUserSettings() {
        const user = window.auth.currentUser;
        if (!user) {
            window.toast && window.toast('You must be logged in.', '⚠️');
            return false;
        }

        const data = {
            notifications: {
                journal:   document.getElementById('notif-journal').checked,
                goals:     document.getElementById('notif-goals').checked,
                community: document.getElementById('notif-community').checked,
            },
            privacy: {
                public:    document.getElementById('privacy-public').checked,
                community: document.getElementById('privacy-community').checked,
            }
        };

        try {
            const { doc, setDoc } = window._fbFS;
            await setDoc(doc(window.db, 'settings', user.uid), data, { merge: true });
            window.toast && window.toast('Settings saved!', '✅');
            return true;
        } catch (e) {
            console.error('Save error:', e);
            window.toast && window.toast('Error saving settings.', '🔥');
            return false;
        }
    }

    // ── Export data ───────────────────────────────────────────
    window.exportUserData = async function () {
        const user = window.auth.currentUser;
        if (!user) { window.toast && window.toast('Not logged in.', '⚠️'); return; }
        window.toast && window.toast('Gathering your data…', '📦');

        try {
            const { getDocs, getDoc, collection, doc } = window._fbFS;
            const uid = user.uid;

            const [profileSnap, settingsSnap, jSnap, tSnap, gSnap] = await Promise.all([
                getDoc(doc(window.db, 'users', uid)),
                getDoc(doc(window.db, 'settings', uid)),
                getDocs(collection(window.db, 'users', uid, 'journals')),
                getDocs(collection(window.db, 'users', uid, 'tasks')),
                getDocs(collection(window.db, 'users', uid, 'goals')),
            ]);

            const exportData = {
                exportedAt: new Date().toISOString(),
                user: {
                    uid:         user.uid,
                    email:       user.email,
                    displayName: user.displayName,
                    photoURL:    user.photoURL,
                },
                profile:  profileSnap.exists()  ? profileSnap.data()  : null,
                settings: settingsSnap.exists() ? settingsSnap.data() : null,
                collections: {
                    journals: jSnap.docs.map(d => ({ id: d.id, ...d.data() })),
                    tasks:    tSnap.docs.map(d => ({ id: d.id, ...d.data() })),
                    goals:    gSnap.docs.map(d => ({ id: d.id, ...d.data() })),
                }
            };

            const blob = new Blob([JSON.stringify(exportData, null, 2)], { type: 'application/json' });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href     = url;
            a.download = `lifevault-data-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            window.toast && window.toast('Export complete!', '✅');
        } catch (e) {
            console.error(e);
            window.toast && window.toast('Export failed. See console.', '🔥');
        }
    };

    // ── Delete account ────────────────────────────────────────
    window.confirmDeleteAccount = async function () {
        if (!confirm('Are you absolutely sure? This cannot be undone.')) return;
        const user = window.auth.currentUser;
        if (!user) return;
        try {
            await user.delete();
            window.toast && window.toast('Account deleted.', '🗑️');
        } catch (e) {
            console.error(e);
            window.toast && window.toast('Error: ' + e.message, '🔥');
        }
    };

    // ── handleSave (called by onclick in HTML) ────────────────
    window.handleSave = async function () {
        const ok = await saveUserSettings();
        if (ok) _hideSaveBar();
    };

    // ── Boot: wait for Firebase then load ────────────────────
    waitForFirebase(() => {
        const unsub = window.auth.onAuthStateChanged(user => {
            if (user) {
                loadUserSettings();
                unsub();
            }
        });
    });
})();
</script>