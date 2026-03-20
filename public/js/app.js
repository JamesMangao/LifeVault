import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup, onAuthStateChanged, signOut as fbSignOut }
  from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import {
  getFirestore, collection, addDoc, getDocs, getDoc, doc, setDoc, updateDoc, deleteDoc,
  query, orderBy, limit, where, arrayUnion, arrayRemove, increment, serverTimestamp, onSnapshot, Timestamp, writeBatch
}
  from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

// Make Firebase instances globally available
let auth, db;

// Initialize Firebase
const fbApp = initializeApp(window.firebaseConfig);
auth = getAuth(fbApp);
db = getFirestore(fbApp);

window.auth = auth;
window.db = db;
window._fbFS = { doc, setDoc, getDoc, updateDoc, deleteDoc, addDoc, getDocs, collection, query, where, orderBy, serverTimestamp, onSnapshot, writeBatch };

let currentUser = null;
let journals = [], tasks = [], goals = [];
let editJournalId = null, editTaskId = null, editGoalId = null;
let photoUrls = [];
let moodVal = 3, moodEmoji = '😐';
let draggedId = null;
let expandedJournalId = null;
let currentFeedFilter = 'all';
let feedPosts = [];
let feedUnsubscribe = null;
let composerType = 'thought';
let userProfile = {};
let selectedAvatarUrl = '';
let selectedCoverData = null;

const MOTIVATIONS = ["Every small step counts 🚀", "Progress, not perfection 🎯", "You're stronger than you think 💪", "Today is full of possibilities ✨", "Be kind to yourself 💖", "Your future self will thank you 🙏", "You've got this! 🔥", "Growth happens outside comfort zones 🌱"];
const CAT_ICONS = { health: '💪', learn: '📚', finance: '💰', career: '💼', personal: '🌱', other: '⭐' };
const COLORS = ['var(--accent)', 'var(--lavender)', 'var(--teal)', 'var(--green)', 'var(--amber)', 'var(--rose)'];
const M_EMOJI = { 1: '😢', 2: '😔', 3: '😐', 4: '🙂', 5: '😄' };
const TYPE_BADGES = { thought: '💭 Thought', journal: '📓 Journal', task: '✅ Task', goal: '🎯 Goal' };
const TYPE_BADGE_CLASS = { thought: 'badge-journal', journal: 'badge-journal', task: 'badge-task', goal: 'badge-goal' };

const COVER_PRESETS = [
  'linear-gradient(135deg,#0d1b2a,#1b3a5c,#0d1b2a)',
  'linear-gradient(135deg,#0f0c29,#302b63,#24243e)',
  'linear-gradient(135deg,#093028,#237a57,#093028)',
  'linear-gradient(135deg,#1a0533,#3d1a7a,#11998e)',
  'linear-gradient(135deg,#200122,#6f0000,#200122)',
  'linear-gradient(135deg,#141e30,#243b55,#141e30)',
  'linear-gradient(135deg,#1f1c2c,#4a4580,#1f1c2c)',
  'linear-gradient(135deg,#0b0f1a,#1e3a5f,#0b0f1a)',
];

const AVATAR_PRESETS = [
  'https://api.dicebear.com/7.x/bottts/svg?seed=lv1&backgroundColor=4f8ef7',
  'https://api.dicebear.com/7.x/bottts/svg?seed=lv2&backgroundColor=a78bfa',
  'https://api.dicebear.com/7.x/bottts/svg?seed=lv3&backgroundColor=34d399',
  'https://api.dicebear.com/7.x/bottts/svg?seed=lv4&backgroundColor=f87171',
  'https://api.dicebear.com/7.x/bottts/svg?seed=lv5&backgroundColor=fbbf24',
  'https://api.dicebear.com/7.x/personas/svg?seed=lv1',
  'https://api.dicebear.com/7.x/personas/svg?seed=lv2',
  'https://api.dicebear.com/7.x/personas/svg?seed=lv3',
  'https://api.dicebear.com/7.x/personas/svg?seed=lv4',
  'https://api.dicebear.com/7.x/personas/svg?seed=lv5',
];

/* ══ MOBILE SIDEBAR ══════════════════════════════════════════ */
window.toggleSidebar = () => {
  const s = document.getElementById('sidebar');
  const o = document.getElementById('sidebar-overlay');
  const h = document.getElementById('hamburger-btn');
  const isOpen = s.classList.contains('open');
  s.classList.toggle('open', !isOpen);
  o.classList.toggle('open', !isOpen);
  h.classList.toggle('open', !isOpen);
};
window.closeSidebar = () => {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('open');
  document.getElementById('hamburger-btn').classList.remove('open');
};

/* ══ INIT ════════════════════════════════════════════════════ */
window.addEventListener('load', () => {
  setTimeout(() => {
    document.getElementById('loading').classList.add('hidden');
    onAuthStateChanged(auth, async user => {
      if (user) {
        window.isGuestMode = false;
        document.body.classList.remove('guest-access');
        const proceed = await _deletionGuard.checkOnLogin(user);
        if (!proceed) return;
        currentUser = user;
        window.currentUser = user;
        showApp(user);
        loadAll();
      }
      else {
        if (!window.isGuestMode) showAuth();
      }
    });
    setTimeout(() => {
      const authEl = document.getElementById('auth-screen');
      const app = document.getElementById('app');
      if (authEl.style.display === 'none' && app.style.display === 'none') showAuth();
    }, 6000);
  }, 1200);
});

function showAuth() {
  document.getElementById('auth-screen').style.display = 'flex';
  document.getElementById('app').style.display = 'none';
}

/* ══ SKELETON / OPTIMISTIC UI HELPERS ═══════════════════════════
   - .skeleton CSS classes provide animated placeholders (added in app.css)
   - use showSkeleton(container,count,template) to render placeholder items
   - optimisticAction(fn,renderRevert) wraps async calls for immediate UI updates
   - progressIllusion(element,start,finish) animates a fake progress bar
*/

window.showSkeleton = function (container, count = 1, template = null) {
  if (!container) return;
  const tpl = template || '<div class="skeleton-text"></div>';
  container.innerHTML = Array(count).fill(tpl).join('');
};

window.hideSkeleton = function (container) {
  if (!container) return;
  container.innerHTML = '';
};

/**
 * Call an async function while applying an optimistic UI change immediately.
 * @param {Function} fn - async function returning a promise
 * @param {Function} apply - invoked before fn to mutate UI optimistically
 * @param {Function} revert - invoked if fn rejects to roll back UI
 */
window.optimisticUpdate = async function (fn, apply, revert) {
  try {
    if (apply) apply();
    const result = await fn();
    return result;
  } catch (err) {
    if (revert) revert(err);
    throw err;
  }
};

// simple progress illusion helper; call start() to add running class,
// and finish() when operation completes.
window.progressIllusion = function (el) {
  return {
    start() { el.classList.add('running'); },
    finish() { el.classList.remove('running'); }
  };
};


window.applyTheme = function (theme) {
  const root = document.documentElement;
  const themes = {
    dark: {
      bg: '#0b0f1a', surface: '#111827', surface2: '#1a2235',
      border: 'rgba(255,255,255,0.07)', text: '#e8eaf0', muted: '#6b7a99',
      accent: '#4f8ef7', green: '#34d399', amber: '#fbbf24', rose: '#f87171',
      lavender: '#a78bfa', teal: '#2dd4bf', glow: '0 0 40px rgba(79,142,247,0.15)'
    },
    midnight: {
      bg: '#0f0c29', surface: '#1a1640', surface2: '#252050',
      border: 'rgba(255,255,255,0.08)', text: '#e8eaf0', muted: '#7a6fa8',
      accent: '#6366f1', green: '#34d399', amber: '#fbbf24', rose: '#f87171',
      lavender: '#a78bfa', teal: '#2dd4bf', glow: '0 0 40px rgba(99,102,241,0.15)'
    },
    forest: {
      bg: '#071a0e', surface: '#0d2e18', surface2: '#1a3d26',
      border: 'rgba(255,255,255,0.08)', text: '#e8eaf0', muted: '#6b8a6f',
      accent: '#22c55e', green: '#34d399', amber: '#fbbf24', rose: '#f87171',
      lavender: '#a78bfa', teal: '#2dd4bf', glow: '0 0 40px rgba(34,197,94,0.15)'
    },
    rose: {
      bg: '#1a0a0a', surface: '#2d1212', surface2: '#3d1a1a',
      border: 'rgba(255,255,255,0.08)', text: '#e8eaf0', muted: '#8a6b6b',
      accent: '#f87171', green: '#34d399', amber: '#fbbf24', rose: '#f87171',
      lavender: '#a78bfa', teal: '#2dd4bf', glow: '0 0 40px rgba(248,113,113,0.15)'
    }
  };
  const selectedTheme = themes[theme] || themes.dark;
  Object.keys(selectedTheme).forEach(key => {
    root.style.setProperty(`--${key}`, selectedTheme[key]);
  });
};

async function loadUserTheme(userId) {
  try {
    const snap = await getDoc(doc(db, 'settings', userId));
    if (snap.exists()) {
      const settings = snap.data();
      const theme = settings.appearance?.theme || 'dark';
      window.applyTheme(theme);
    }
  } catch (e) {
    console.warn('Theme load error:', e.message);
  }
}

function showApp(user) {
  document.getElementById('auth-screen').style.display = 'none';
  document.getElementById('app').style.display = 'block';
  document.getElementById('user-name').textContent = user.displayName || 'User';
  document.getElementById('user-email').textContent = user.email;
  const av = user.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.displayName || 'U')}&background=4f8ef7&color=fff`;
  document.getElementById('user-avatar').src = av;
  document.getElementById('composer-avatar').src = av;
  updateGreeting();
  _maybeShowWelcomeBackBanner();
  _checkAndShowDeletionBanner(user.uid);
  ensureUserProfileExists(user);
  loadUserProfile();
  loadUserTheme(user.uid);
  restoreLastPage();
}

async function ensureUserProfileExists(user) {
  if (!user) return;
  const userRef = doc(db, 'users', user.uid);
  try {
    const userSnap = await getDoc(userRef);
    if (!userSnap.exists()) {
      await setDoc(userRef, {
        displayName: user.displayName || 'Anonymous',
        email: user.email,
        photoURL: user.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.displayName || 'U')}&background=4f8ef7&color=fff`,
        username: (user.displayName || 'user').toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user',
        bio: 'Just joined LifeVault!',
        isPublic: true,
        joinedAt: serverTimestamp()
      });
    }
  } catch (error) {
    console.error("Error ensuring user profile exists:", error);
  }
}

/* ══ AUTH ════════════════════════════════════════════════════ */
document.getElementById('google-login-btn').onclick = async () => {
  try { await signInWithPopup(auth, new GoogleAuthProvider()); }
  catch (e) { toast(e.message || 'Login failed', '❌'); }
};
window.signOutUser = async () => {
  if (!confirm('Sign out?')) return;
  if (feedUnsubscribe) feedUnsubscribe();
  await fbSignOut(auth);
};

/* ══ LOAD ════════════════════════════════════════════════════ */
async function loadAll() {
  if (!currentUser) return;
  const uid = currentUser.uid;

  // show skeleton placeholders for lists while we fetch
  ['journal-list', 'tasks-high', 'tasks-med', 'tasks-low', 'goals-list'].forEach(id => {
    const el = document.getElementById(id);
    if (el) showSkeleton(el, 3, '<div class="skeleton-text" style="height:32px;margin:6px 0"></div>');
  });

  try {
    const [j, t, g] = await Promise.all([
      getDocs(query(collection(db, 'users', uid, 'journals'), orderBy('createdAt', 'desc'))),
      getDocs(query(collection(db, 'users', uid, 'tasks'), orderBy('createdAt', 'desc'))),
      getDocs(query(collection(db, 'users', uid, 'goals'), orderBy('createdAt', 'desc')))
    ]);
    journals = j.docs.map(d => ({ id: d.id, ...d.data(), createdAt: d.data().createdAt?.toDate() }));
    tasks = t.docs.map(d => ({ id: d.id, ...d.data(), createdAt: d.data().createdAt?.toDate() }));
    goals = g.docs.map(d => ({ id: d.id, ...d.data(), createdAt: d.data().createdAt?.toDate() }));


    window.journals = journals;
    window.tasks = tasks;
    window.goals = goals;

    // Expose data for Insights page (exact names required)
    window.journalEntries = journals;
    window.tasks = tasks;
    window.goals = goals;

    // Trigger insights re-render
    window.dispatchEvent(new Event('insightsRefresh'));


    subscribeFeed();
    renderAll();
  } catch (e) { toast('Load error: ' + e.message, '❌'); }
}

function updateGreeting() {
  const h = new Date().getHours();
  const gEl = document.getElementById('greeting-time');
  if (gEl) {
    gEl.textContent = h < 12 ? 'morning' : h < 18 ? 'afternoon' : 'evening';
    // if CSS is stale/hasn't reloaded, force the theme colour anyway
    gEl.style.color = 'var(--text)';
  }
  const dateEl = document.getElementById('today-date');
  if (dateEl) dateEl.textContent = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  const motEl = document.getElementById('daily-motivation');
  if (motEl) motEl.textContent = MOTIVATIONS[Math.floor(Math.random() * MOTIVATIONS.length)];
}

/* ══ NAVIGATION ══════════════════════════════════════════════ */
window.navigateTo = (page, event) => {
  if (event) event.stopPropagation();

  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));

  const pageEl = document.getElementById('page-' + page);
  if (pageEl) {
    pageEl.classList.add('active');
  } else {
    console.warn('navigateTo: no page element for', page);
  }

  const navEl = document.querySelector(`.nav-item[data-page="${page}"]`);
  if (navEl) {
    navEl.classList.add('active');
  }

  localStorage.setItem('lifeVaultLastPage', page);

  closeSidebar();
  if (page === 'insights') renderInsights();
  if (page === 'community') {
    subscribeFeed();
    const dot = document.getElementById('new-posts-dot');
    if (dot) dot.style.display = 'none';
  }
  if (page === 'profile') renderProfilePage();
  if (page === 'settings') _settingsModule.load();
  if (page === 'shadow-self' || page === 'life-story') initializeLearningResources();
};


function restoreLastPage() {
  const lastPage = localStorage.getItem('lifeVaultLastPage') || 'dashboard';
  navigateTo(lastPage);
}

/* ══════════════════════════════════════════════════════════════
   SETTINGS MODULE
══════════════════════════════════════════════════════════════ */
const _settingsModule = (() => {
  let _listenersAttached = false;
  let _acceptingChanges = false;

  function _showBar() { document.getElementById('settings-save-bar')?.classList.add('visible'); }
  function _hideBar() { document.getElementById('settings-save-bar')?.classList.remove('visible'); }

  function _setToggle(id, value) {
    const el = document.getElementById(id);
    if (!el) return;
    const clone = el.cloneNode(true);
    clone.checked = Boolean(value);
    el.parentNode.replaceChild(clone, el);
  }

  function _attachListeners() {
    _listenersAttached = false;
    if (_listenersAttached) return;
    _listenersAttached = true;
    document.querySelectorAll('#page-settings .toggle-switch input').forEach(el => {
      el.addEventListener('change', () => { if (_acceptingChanges) _showBar(); });
    });
  }

  async function load() {
    const user = auth?.currentUser;
    if (!user) return;
    _acceptingChanges = false;
    try {
      const snap = await getDoc(doc(db, 'settings', user.uid));
      if (snap.exists()) {
        const s = snap.data();
        if (s.notifications) {
          _setToggle('notif-journal', s.notifications.journal ?? true);
          _setToggle('notif-goals', s.notifications.goals ?? true);
          _setToggle('notif-community', s.notifications.community ?? false);
        }
        if (s.privacy) {
          _setToggle('privacy-public', s.privacy.public ?? true);
          _setToggle('privacy-community', s.privacy.community ?? true);
        }
      }
    } catch (e) {
      console.warn('Settings load error:', e.message);
    }
    _attachListeners();
    requestAnimationFrame(() => requestAnimationFrame(() => {
      _acceptingChanges = true;
      _hideBar();
    }));
  }

  async function save() {
    const user = auth?.currentUser;
    if (!user) { toast('You must be logged in.', '⚠️'); return false; }
    const data = {
      notifications: {
        journal: document.getElementById('notif-journal')?.checked ?? true,
        goals: document.getElementById('notif-goals')?.checked ?? true,
        community: document.getElementById('notif-community')?.checked ?? false,
      },
      privacy: {
        public: document.getElementById('privacy-public')?.checked ?? true,
        community: document.getElementById('privacy-community')?.checked ?? true,
      }
    };
    try {
      await Promise.all([
        setDoc(doc(db, 'settings', user.uid), data, { merge: true }),
        updateDoc(doc(db, 'users', user.uid), {
          isPublic: data.privacy.public,
          showInCommunity: data.privacy.community,
        })
      ]);
      if (userProfile) {
        userProfile.isPublic = data.privacy.public;
        userProfile.showInCommunity = data.privacy.community;
      }
      toast('Settings saved!', '✅');
      return true;
    } catch (e) {
      console.error('Save error:', e);
      toast('Error saving settings.', '🔥');
      return false;
    }
  }

  return { load, save, hideBar: _hideBar };
})();

window.handleSave = async () => {
  const ok = await _settingsModule.save();
  if (ok) _settingsModule.hideBar();
};
window.renderSettingsPage = () => _settingsModule.load();

/* ══════════════════════════════════════════════════════════════
   EXPORT
══════════════════════════════════════════════════════════════ */
window.exportUserData = async () => {
  const user = auth?.currentUser;
  if (!user) { toast('Not logged in.', '⚠️'); return; }
  toast('Gathering your data…', '📦');
  try {
    const uid = user.uid;
    const [profileSnap, settingsSnap, jSnap, tSnap, gSnap] = await Promise.all([
      getDoc(doc(db, 'users', uid)),
      getDoc(doc(db, 'settings', uid)),
      getDocs(collection(db, 'users', uid, 'journals')),
      getDocs(collection(db, 'users', uid, 'tasks')),
      getDocs(collection(db, 'users', uid, 'goals')),
    ]);
    const exportData = {
      exportedAt: new Date().toISOString(),
      user: { uid: user.uid, email: user.email, displayName: user.displayName, photoURL: user.photoURL },
      profile: profileSnap.exists() ? profileSnap.data() : null,
      settings: settingsSnap.exists() ? settingsSnap.data() : null,
      collections: {
        journals: jSnap.docs.map(d => ({ id: d.id, ...d.data() })),
        tasks: tSnap.docs.map(d => ({ id: d.id, ...d.data() })),
        goals: gSnap.docs.map(d => ({ id: d.id, ...d.data() })),
      }
    };
    const blob = new Blob([JSON.stringify(exportData, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `lifevault-data-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a); a.click();
    document.body.removeChild(a); URL.revokeObjectURL(url);
    toast('Export complete!', '✅');
  } catch (e) {
    console.error(e);
    toast('Export failed. See console.', '🔥');
  }
};
window.exportAsJSON = window.exportUserData;

/* ══════════════════════════════════════════════════════════════
   ACCOUNT DELETION — 30-day grace period
══════════════════════════════════════════════════════════════ */
const DELETION_GRACE_DAYS = 30;

const _deletionGuard = (() => {
  async function _hardDelete(user) {
    const uid = user.uid;
    try {
      const batch = writeBatch(db);
      const [jSnap, tSnap, gSnap] = await Promise.all([
        getDocs(collection(db, 'users', uid, 'journals')),
        getDocs(collection(db, 'users', uid, 'tasks')),
        getDocs(collection(db, 'users', uid, 'goals')),
      ]);
      jSnap.docs.forEach(d => batch.delete(d.ref));
      tSnap.docs.forEach(d => batch.delete(d.ref));
      gSnap.docs.forEach(d => batch.delete(d.ref));
      batch.delete(doc(db, 'users', uid));
      batch.delete(doc(db, 'settings', uid));
      batch.delete(doc(db, 'deletion_queue', uid));
      await batch.commit();
      await user.delete();
    } catch (e) {
      console.error('Hard delete error:', e);
      if (e.code === 'auth/requires-recent-login') await fbSignOut(auth);
    }
  }

  async function checkOnLogin(user) {
    try {
      const snap = await getDoc(doc(db, 'deletion_queue', user.uid));
      if (!snap.exists()) return true;
      const { scheduledAt } = snap.data();
      const scheduledDate = scheduledAt?.toDate ? scheduledAt.toDate() : new Date(scheduledAt);
      const msElapsed = Date.now() - scheduledDate.getTime();
      const daysElapsed = msElapsed / (1000 * 60 * 60 * 24);
      if (daysElapsed >= DELETION_GRACE_DAYS) {
        await _hardDelete(user);
        showAuth();
        _showExpiredBanner();
        return false;
      }
      await deleteDoc(doc(db, 'deletion_queue', user.uid));
      await updateDoc(doc(db, 'users', user.uid), {
        deletionScheduledAt: null,
        deletionPending: false
      }).catch(() => { });
      window._showDeletionCancelledBanner = true;
      return true;
    } catch (e) {
      console.error('Deletion check error:', e);
      return true;
    }
  }

  function _showExpiredBanner() {
    const el = document.createElement('div');
    el.style.cssText = `position:fixed;inset:0;background:var(--bg,#0d1117);display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:99999;padding:32px;text-align:center;`;
    el.innerHTML = `<div style="font-size:3rem;margin-bottom:16px">🗑️</div><div style="font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;margin-bottom:10px;color:#e8eaf0">Account Permanently Deleted</div><div style="font-family:'Newsreader',serif;font-size:.95rem;color:#6b7280;max-width:380px;line-height:1.7">Your 30-day grace period expired and your account has been permanently removed.</div>`;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 6000);
  }

  return { checkOnLogin };
})();

window.confirmDeleteAccount = async () => {
  const user = auth?.currentUser;
  if (!user) return;
  const confirmed = await _showDeleteConfirmModal();
  if (!confirmed) return;
  try {
    const scheduledAt = Timestamp.now();
    const deleteDate = new Date(Date.now() + DELETION_GRACE_DAYS * 24 * 60 * 60 * 1000);
    await setDoc(doc(db, 'deletion_queue', user.uid), {
      uid: user.uid, email: user.email, displayName: user.displayName || '',
      scheduledAt, deleteAfter: Timestamp.fromDate(deleteDate),
    });
    await updateDoc(doc(db, 'users', user.uid), {
      deletionPending: true, deletionScheduledAt: scheduledAt,
    }).catch(() => { });
    toast('Account scheduled for deletion. You have 30 days to change your mind.', '🗑️');
    if (feedUnsubscribe) feedUnsubscribe();
    await fbSignOut(auth);
  } catch (e) {
    console.error(e);
    toast('Error scheduling deletion: ' + e.message, '🔥');
  }
};

window.cancelAccountDeletion = async () => {
  const user = auth?.currentUser;
  if (!user) return;
  try {
    await deleteDoc(doc(db, 'deletion_queue', user.uid));
    await updateDoc(doc(db, 'users', user.uid), {
      deletionPending: false, deletionScheduledAt: null,
    }).catch(() => { });
    _hideDeletionBanner();
    toast('Account deletion cancelled. Welcome back! 🎉', '✅');
  } catch (e) {
    toast('Error cancelling deletion: ' + e.message, '🔥');
  }
};

function _showDeletionBanner(daysLeft) {
  document.getElementById('deletion-banner')?.remove();
  const banner = document.createElement('div');
  banner.id = 'deletion-banner';
  banner.style.cssText = `position:fixed;top:0;left:0;right:0;z-index:9000;background:linear-gradient(90deg,rgba(239,68,68,.15),rgba(239,68,68,.08));border-bottom:1px solid rgba(239,68,68,.35);padding:10px 20px;display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:wrap;backdrop-filter:blur(8px);`;
  banner.innerHTML = `<span style="font-size:.85rem;font-family:'Syne',sans-serif;color:#fca5a5;font-weight:600">🗑️ Your account is scheduled for deletion in <strong>${daysLeft} day${daysLeft === 1 ? '' : 's'}</strong>.</span><button onclick="cancelAccountDeletion()" style="background:rgba(239,68,68,.2);border:1px solid rgba(239,68,68,.5);color:#fca5a5;font-family:'Syne',sans-serif;font-size:.75rem;font-weight:700;padding:5px 14px;border-radius:8px;cursor:pointer;">Cancel Deletion</button>`;
  document.body.prepend(banner);
  const appEl = document.getElementById('app');
  if (appEl) appEl.style.paddingTop = (appEl.style.paddingTop || '0px') === '0px' ? (banner.offsetHeight + 'px') : appEl.style.paddingTop;
}

function _hideDeletionBanner() {
  const banner = document.getElementById('deletion-banner');
  if (banner) {
    const appEl = document.getElementById('app');
    if (appEl) appEl.style.paddingTop = '';
    banner.remove();
  }
}

async function _checkAndShowDeletionBanner(uid) {
  try {
    const snap = await getDoc(doc(db, 'deletion_queue', uid));
    if (!snap.exists()) return;
    const { scheduledAt } = snap.data();
    const scheduledDate = scheduledAt?.toDate ? scheduledAt.toDate() : new Date(scheduledAt);
    const msLeft = (scheduledDate.getTime() + DELETION_GRACE_DAYS * 24 * 60 * 60 * 1000) - Date.now();
    const daysLeft = Math.max(1, Math.ceil(msLeft / (1000 * 60 * 60 * 24)));
    _showDeletionBanner(daysLeft);
  } catch (e) { /* silent */ }
}

function _maybeShowWelcomeBackBanner() {
  if (!window._showDeletionCancelledBanner) return;
  window._showDeletionCancelledBanner = false;
  const banner = document.createElement('div');
  banner.style.cssText = `position:fixed;top:0;left:0;right:0;z-index:9000;background:linear-gradient(90deg,rgba(52,211,153,.15),rgba(52,211,153,.08));border-bottom:1px solid rgba(52,211,153,.35);padding:12px 20px;display:flex;align-items:center;justify-content:center;gap:10px;backdrop-filter:blur(8px);`;
  banner.innerHTML = `<span style="font-size:.85rem;font-family:'Syne',sans-serif;color:#6ee7b7;font-weight:600">🎉 Welcome back! Your account deletion has been automatically cancelled.</span><button onclick="this.parentElement.remove()" style="background:none;border:none;color:#6ee7b7;cursor:pointer;font-size:1.1rem;padding:0 4px;">×</button>`;
  document.body.prepend(banner);
  setTimeout(() => banner.remove(), 8000);
}

function _showDeleteConfirmModal() {
  return new Promise(resolve => {
    document.getElementById('delete-confirm-modal')?.remove();
    const overlay = document.createElement('div');
    overlay.id = 'delete-confirm-modal';
    overlay.style.cssText = `position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:99998;display:flex;align-items:center;justify-content:center;padding:24px;backdrop-filter:blur(4px);`;
    overlay.innerHTML = `<div style="background:var(--surface,#161b22);border:1px solid rgba(239,68,68,.4);border-radius:20px;padding:32px;max-width:440px;width:100%;box-shadow:0 24px 64px rgba(0,0,0,.6);"><div style="font-size:2.5rem;text-align:center;margin-bottom:16px">⚠️</div><div style="font-family:'Syne',sans-serif;font-size:1.15rem;font-weight:800;text-align:center;margin-bottom:10px;color:#e8eaf0">Delete Account?</div><div style="font-family:'Newsreader',serif;font-size:.9rem;color:#9ca3af;text-align:center;line-height:1.7;margin-bottom:8px">Your account will be <strong style="color:#fca5a5">scheduled for deletion</strong>. You have <strong style="color:#fbbf24">30 days</strong> to log back in and cancel.</div><div style="background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);border-radius:10px;padding:12px 16px;margin-bottom:24px;font-family:'JetBrains Mono',monospace;font-size:.72rem;color:#fcd34d;line-height:1.6;">✦ All your data is preserved during the 30-day window.<br>✦ Simply sign back in at any time to cancel.<br>✦ After 30 days, all data is permanently deleted.</div><div style="display:flex;gap:10px;"><button id="del-cancel-btn" style="flex:1;padding:12px;border-radius:10px;border:1px solid var(--border,#30363d);background:transparent;color:#9ca3af;font-family:'Syne',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer;">Keep My Account</button><button id="del-confirm-btn" style="flex:1;padding:12px;border-radius:10px;border:1px solid rgba(239,68,68,.5);background:rgba(239,68,68,.15);color:#fca5a5;font-family:'Syne',sans-serif;font-size:.85rem;font-weight:700;cursor:pointer;">Schedule Deletion</button></div></div>`;
    document.body.appendChild(overlay);
    document.getElementById('del-cancel-btn').onclick = () => { overlay.remove(); resolve(false); };
    document.getElementById('del-confirm-btn').onclick = () => { overlay.remove(); resolve(true); };
    overlay.addEventListener('click', e => { if (e.target === overlay) { overlay.remove(); resolve(false); } });
  });
}

/* ══════════════════════════════════════════════════════════════
   JOURNAL EXPAND
   ── Updated to use #jx-overlay and #jx-* element IDs
   ── Matches journal-expand-overlay-FINAL.blade.php
══════════════════════════════════════════════════════════════ */
window.openExpandedJournal = id => {
  const e = journals.find(j => j.id === id);
  if (!e) return;
  expandedJournalId = id;

  // ── populate new jx-* elements ──────────────────────────────
  document.getElementById('jx-title').textContent = e.title || 'Untitled';
  document.getElementById('jx-date').textContent = fmtDate(e.createdAt);
  document.getElementById('jx-content').textContent = e.content || '';

  const moodEl = document.getElementById('jx-mood');
  if (e.moodEmoji) {
    moodEl.textContent = e.moodEmoji;
    moodEl.style.display = 'inline-flex';
  } else {
    moodEl.textContent = '';
    moodEl.style.display = 'none';
  }

  // word count
  const wcEl = document.getElementById('jx-wc');
  if (wcEl) {
    const n = (e.content || '').trim().split(/\s+/).filter(Boolean).length;
    wcEl.textContent = n + (n === 1 ? ' word' : ' words');
  }

  // photos
  const photosEl = document.getElementById('jx-photos');
  if (e.photoUrls?.length) {
    photosEl.style.display = 'grid';
    photosEl.innerHTML = e.photoUrls.map(u =>
      `<img src="${esc(u)}" loading="lazy" onclick="viewPhoto('${esc(u)}')" alt="Journal photo">`
    ).join('');
  } else {
    photosEl.style.display = 'none';
    photosEl.innerHTML = '';
  }

  // tags
  const tagsEl = document.getElementById('jx-tags');
  if (e.tags?.length) {
    tagsEl.style.display = 'flex';
    tagsEl.innerHTML = e.tags.map(t =>
      `<span>${esc(t)}</span>`
    ).join('');
  } else {
    tagsEl.style.display = 'none';
    tagsEl.innerHTML = '';
  }

  // buttons
  const editBtn = document.getElementById('jx-edit-btn');
  if (editBtn) editBtn.onclick = () => { closeJournalExpand(); openJournalModal(id); };

  const delBtn = document.getElementById('jx-del-btn');
  if (delBtn) delBtn.onclick = () => { closeJournalExpand(); delJournal(id); };

  // open overlay
  document.getElementById('jx-overlay').classList.add('jx-open');
  document.body.style.overflow = 'hidden';
  const body = document.getElementById('jx-body');
  if (body) body.scrollTop = 0;
};

window.closeJournalExpand = () => {
  const overlay = document.getElementById('jx-overlay');
  if (!overlay || !overlay.classList.contains('jx-open')) return;
  overlay.style.opacity = '0';
  overlay.style.transition = 'opacity .17s ease';
  setTimeout(() => {
    overlay.classList.remove('jx-open');
    overlay.style.opacity = '';
    overlay.style.transition = '';
    document.body.style.overflow = '';
    expandedJournalId = null;
  }, 170);
};
// aliases — keep any existing callers working
window.closeExpandedJournal = window.closeJournalExpand;
window.openJournalExpand = window.openExpandedJournal;

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    const overlay = document.getElementById('jx-overlay');
    if (overlay?.classList.contains('jx-open')) window.closeJournalExpand();
  }
});

/* ══ DELEGATED LISTENERS ═════════════════════════════════════ */
function initExpandableCards(container) {
  if (!container) return;
  container.onclick = e => {
    const photoEl = e.target.closest('[data-photo]');
    if (photoEl) { e.stopPropagation(); viewPhoto(photoEl.dataset.photo); return; }

    const shareBtn = e.target.closest('[data-share]');
    if (shareBtn) { e.stopPropagation(); shareJournal(shareBtn.dataset.share); return; }

    const editBtn = e.target.closest('[data-edit]');
    if (editBtn) { e.stopPropagation(); openJournalModal(editBtn.dataset.edit); return; }

    const delBtn = e.target.closest('[data-del]');
    if (delBtn) { e.stopPropagation(); delJournal(delBtn.dataset.del); return; }

    const likeBtn = e.target.closest('[data-like]');
    if (likeBtn) { e.stopPropagation(); toggleLike(likeBtn.dataset.like); return; }

    const userBtn = e.target.closest('[data-user]');
    if (userBtn) { e.stopPropagation(); openUserProfileModal(userBtn.dataset.user); return; }

    const entryEl = e.target.closest('.journal-entry, .post-card');
    if (entryEl) {
      if (entryEl.classList.contains('journal-entry')) {
        const jid = entryEl.dataset.id || entryEl.dataset.journalId;
        if (jid) { window.openExpandedJournal(jid); return; }
        // fallback: toggle preview expansion
        const preview = entryEl.querySelector('.entry-preview');
        if (preview) preview.classList.toggle('expanded');
      } else if (entryEl.classList.contains('post-card')) {
        const postId = entryEl.dataset.postId;
        // The global openExpandedPost function handles all post types and pages (community vs profile)
        if (postId && typeof window.openExpandedPost === 'function') {
          window.openExpandedPost(postId);
        }
      }
    }
  };
}

/* ══ RENDER JOURNALS ══════════════════════════════════════════ */
function renderJournals(containerId, maxCount, isDash) {
  const container = document.getElementById(containerId);
  if (!container) return;
  const list = maxCount ? journals.slice(0, maxCount) : journals;

  if (!list.length) {
    container.innerHTML = `<div class="empty-state"><div class="empty-icon">📖</div><div class="empty-text">No entries yet.</div><button class="btn btn-primary" onclick="openJournalModal()">Write first entry</button></div>`;
    return;
  }

  container.innerHTML = list.map(e => {
    const photosHtml = e.photoUrls?.length
      ? `<div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px">${e.photoUrls.map(u => `<img src="${u}" style="width:64px;height:64px;border-radius:8px;object-fit:cover;border:1px solid var(--border)" data-photo="${u}">`).join('')}</div>` : '';
    const tagsHtml = e.tags?.length
      ? `<div class="entry-tags">${e.tags.map(t => `<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}</div>`
      : '';

    const actionBtns = isDash
      ? `<button class="journal-entry-actions" data-share="${e.id}" title="Share" style="background:none;border:1px solid rgba(45,212,191,.3);color:var(--teal);cursor:pointer;font-size:.72rem;padding:4px 8px;border-radius:6px">↗</button>`
      : `<div class="journal-entry-actions">
           <button data-share="${e.id}" title="Share">↗</button>
           <button data-edit="${e.id}" title="Edit">✎</button>
           <button class="del-btn" data-del="${e.id}" title="Delete">✕</button>
         </div>`;

    return `<div class="journal-entry" data-id="${e.id}">
      <div class="entry-header">
        <div>
          <div style="font-size:.85rem;font-weight:600;margin-bottom:2px">${esc(e.title || 'Untitled')}</div>
          <div class="entry-date">${fmtDate(e.createdAt)}</div>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
          <span class="mood-emoji">${e.moodEmoji || '😐'}</span>
          ${actionBtns}
        </div>
      </div>
      <div class="entry-preview">${esc(e.content || '')}</div>
      ${photosHtml}${tagsHtml}
      <div class="entry-expand-hint">↗ tap to expand</div>
    </div>`;
  }).join('');

  initExpandableCards(container);
}

window.selectMood = (val, emoji) => {
  moodVal = val; moodEmoji = emoji;
  document.querySelectorAll('.mood-option').forEach(o => o.classList.remove('selected'));
  document.querySelector(`.mood-option[data-mood="${val}"]`).classList.add('selected');
};

window.openJournalModal = (id = null) => {
  if (!journals) return;
  editJournalId = id;
  photoUrls = [];
  document.getElementById('photo-preview').innerHTML = '';
  document.getElementById('journal-photos').value = '';
  document.getElementById('jmodal-title').textContent = id ? '✏️ Edit Entry' : '📓 New Journal Entry';
  if (id) {
    const e = journals.find(j => j.id === id);
    if (e) {
      document.getElementById('journal-title').value = e.title || '';
      document.getElementById('journal-content').value = e.content || '';
      document.getElementById('journal-tags').value = (e.tags || []).join(', ');
      moodVal = e.mood || 3; moodEmoji = e.moodEmoji || '😐';
      if (e.photoUrls?.length) { photoUrls = [...e.photoUrls]; renderPreviews(); }
      document.querySelectorAll('.mood-option').forEach(o => o.classList.remove('selected'));
      document.querySelector(`.mood-option[data-mood="${moodVal}"]`)?.classList.add('selected');
    }
  } else {
    document.getElementById('journal-title').value = '';
    document.getElementById('journal-content').value = '';
    document.getElementById('journal-tags').value = '';
    moodVal = 3; moodEmoji = '😐';
    document.querySelectorAll('.mood-option').forEach(o => o.classList.remove('selected'));
  }
  document.getElementById('journal-modal').classList.add('open');
  setTimeout(() => document.getElementById('journal-content').focus(), 100);
};

document.getElementById('journal-photos').addEventListener('change', async e => {
  const files = Array.from(e.target.files);
  if (!files.length) return;
  if (photoUrls.length + files.length > 6) { toast('Max 6 photos', '⚠️'); return; }
  toast(`Compressing ${files.length} photo(s)…`, '📷');
  for (const f of files) {
    try { photoUrls.push(await compressImage(f)); } catch { toast('Could not read ' + f.name, '❌'); }
  }
  renderPreviews(); e.target.value = ''; toast('Photos ready!', '📷');
});

function compressImage(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onerror = reject;
    reader.onload = ev => {
      const img = new Image();
      img.onerror = reject;
      img.onload = () => {
        const T = 200 * 1024; const c = document.createElement('canvas'); const ctx = c.getContext('2d');
        let m = 800, q = 0.8, r = '';
        for (let i = 0; i < 8; i++) {
          let w = img.width, h = img.height;
          if (w > h) { if (w > m) { h = Math.round(h * m / w); w = m; } }
          else { if (h > m) { w = Math.round(w * m / h); h = m; } }
          c.width = w; c.height = h; ctx.clearRect(0, 0, w, h); ctx.drawImage(img, 0, 0, w, h);
          r = c.toDataURL('image/jpeg', q);
          if (r.length * .75 <= T) break;
          if (q > .3) q -= .15; else { m = Math.round(m * .7); q = .6; }
        }
        resolve(r);
      };
      img.src = ev.target.result;
    };
    reader.readAsDataURL(file);
  });
}

function renderPreviews() {
  document.getElementById('photo-preview').innerHTML = photoUrls.map((u, i) =>
    `<div style="position:relative;width:60px;height:60px;border-radius:8px;overflow:hidden;border:1px solid var(--border)">
      <img src="${u}" style="width:100%;height:100%;object-fit:cover">
      <button style="position:absolute;top:0;right:0;background:rgba(0,0,0,.7);color:white;border:none;cursor:pointer;width:20px;height:20px;font-size:.7rem" onclick="rmPhoto(${i})">×</button>
    </div>`
  ).join('');
}
window.rmPhoto = i => { photoUrls.splice(i, 1); renderPreviews(); };

window.saveJournalEntry = async () => {
  const content = document.getElementById('journal-content').value.trim();
  if (!content) { toast('Write something first!', '✏️'); return; }
  const data = {
    title: document.getElementById('journal-title').value.trim() || 'Untitled',
    content, mood: moodVal, moodEmoji,
    tags: document.getElementById('journal-tags').value.split(',').map(t => t.trim()).filter(Boolean),
    photoUrls
  };
  const btn = document.querySelector('#journal-modal .progress-illusion');
  const bar = btn ? progressIllusion(btn) : null;
  if (bar) bar.start();
  try {
    if (editJournalId) {
      // optimistic update: keep copy and apply change immediately
      const old = journals.find(j => j.id === editJournalId);
      await optimisticUpdate(async () => {
        await updateDoc(doc(db, 'users', currentUser.uid, 'journals', editJournalId), data);
      },
        () => {
          if (old) {
            const i = journals.findIndex(j => j.id === editJournalId);
            if (i !== -1) journals[i] = old;
          }
        });
      const i = journals.findIndex(j => j.id === editJournalId);
      if (i !== -1) journals[i] = { ...journals[i], ...data };
      toast('Entry updated!', '📓');
    } else {
      data.createdAt = Timestamp.now();
      // optimistic insert
      const fake = { id: '_optimistic', ...data, createdAt: new Date() };
      journals.unshift(fake);
      await optimisticUpdate(async () => {
        const r = await addDoc(collection(db, 'users', currentUser.uid, 'journals'), data);
        // replace fake with real id
        const idx = journals.findIndex(j => j.id === '_optimistic');
        if (idx !== -1) journals[idx] = { id: r.id, ...data, createdAt: new Date() };
      },
        () => { // revert
          journals = journals.filter(j => j.id !== '_optimistic');
        });
      toast('Entry saved! ✨', '📓');
    }
    closeModal('journal-modal'); renderAll(); editJournalId = null;
  } catch (e) { toast('Error: ' + e.message, '❌'); }
  finally { if (bar) bar.finish(); }
};

window.delJournal = async id => {
  if (!confirm('Delete this entry?')) return;
  const removed = journals.find(j => j.id === id);
  journals = journals.filter(j => j.id !== id);
  try {
    await optimisticUpdate(async () => {
      await deleteDoc(doc(db, 'users', currentUser.uid, 'journals', id));
    }, null, () => {
      if (removed) journals.unshift(removed);
    });
    renderAll(); toast('Entry deleted', '🗑️');
  } catch (e) {
    toast('Error: ' + e.message, '❌');
  }
};

window.viewPhoto = url => {
  const d = document.createElement('div');
  d.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.93);display:flex;align-items:center;justify-content:center;z-index:9999';
  d.innerHTML = `<img src="${url}" style="max-width:92%;max-height:92%;border-radius:10px"><button style="position:absolute;top:20px;right:24px;background:none;border:none;color:white;font-size:2.2rem;cursor:pointer" onclick="this.parentElement.remove()">×</button>`;
  d.onclick = e => { if (e.target === d) d.remove(); };
  document.body.appendChild(d);
};

/* ══ TASKS ═══════════════════════════════════════════════════ */
window.openTaskModal = (id = null) => {
  if (!tasks) return;
  editTaskId = id;
  document.getElementById('tmodal-title').textContent = id ? '✏️ Edit Task' : '✅ Add Task';
  if (id) {
    const t = tasks.find(t => t.id === id);
    if (t) {
      document.getElementById('task-text').value = t.text;
      document.getElementById('task-note').value = t.note || '';
      document.getElementById('task-priority').value = t.priority || 'med';
    }
  } else {
    document.getElementById('task-text').value = '';
    document.getElementById('task-note').value = '';
    document.getElementById('task-priority').value = 'med';
  }
  document.getElementById('task-modal').classList.add('open');
  setTimeout(() => document.getElementById('task-text').focus(), 100);
};

window.saveTask = async () => {
  const text = document.getElementById('task-text').value.trim();
  if (!text) { toast('Enter a task!', '✏️'); return; }
  const data = { text, priority: document.getElementById('task-priority').value, note: document.getElementById('task-note').value.trim(), done: false };
  const btn = document.querySelector('#task-modal .progress-illusion');
  const bar = btn ? progressIllusion(btn) : null;
  if (bar) bar.start();
  try {
    if (editTaskId) {
      const old = tasks.find(t => t.id === editTaskId);
      await optimisticUpdate(async () => {
        await updateDoc(doc(db, 'users', currentUser.uid, 'tasks', editTaskId), data);
      },
        () => {
          if (old) {
            const i = tasks.findIndex(t => t.id === editTaskId);
            if (i !== -1) tasks[i] = old;
          }
        });
      const i = tasks.findIndex(t => t.id === editTaskId);
      if (i !== -1) tasks[i] = { ...tasks[i], ...data };
      toast('Task updated!', '✅');
    } else {
      data.createdAt = Timestamp.now();
      const fakeId = '_optimistic';
      tasks.unshift({ id: fakeId, ...data, createdAt: new Date() });
      await optimisticUpdate(async () => {
        const r = await addDoc(collection(db, 'users', currentUser.uid, 'tasks'), data);
        const idx = tasks.findIndex(t => t.id === fakeId);
        if (idx !== -1) tasks[idx] = { id: r.id, ...data, createdAt: new Date() };
      },
        () => { tasks = tasks.filter(t => t.id !== fakeId); });
      toast('Task added!', '✅');
    }
    closeModal('task-modal'); renderAll(); editTaskId = null;
  } catch (e) { toast('Error: ' + e.message, '❌'); }
  finally { if (bar) bar.finish(); }
};

window.toggleTask = async id => {
  const t = tasks.find(t => t.id === id); if (!t) return;
  const oldDone = t.done;
  t.done = !t.done;
  try {
    await optimisticUpdate(async () => {
      await updateDoc(doc(db, 'users', currentUser.uid, 'tasks', id), { done: t.done });
    }, null, () => { t.done = oldDone; });
    if (t.done) toast('Task done! 🎉', '✅');
    renderAll();
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.delTask = async id => {
  if (!confirm('Delete this task?')) return;
  tasks = tasks.filter(t => t.id !== id);
  await deleteDoc(doc(db, 'users', currentUser.uid, 'tasks', id));
  renderAll(); toast('Task removed', '🗑️');
};

function renderTasksIn(container, list) {
  if (!list.length) {
    container.innerHTML = `<div style="padding:14px;color:var(--muted);font-size:.8rem;text-align:center;font-style:italic">All clear here ✓</div>`;
    return;
  }
  container.innerHTML = list.map(t => `
    <div class="task-item ${t.done ? 'done' : ''}" draggable="true" ondragstart="dragStart(event,'${t.id}')" ondragover="dragOver(event)" ondrop="dropTask(event,'${t.priority}')">
      <div class="task-check ${t.done ? 'checked' : ''}" onclick="toggleTask('${t.id}')"></div>
      <div class="priority-dot p-${t.priority}"></div>
      <div class="task-text">${esc(t.text)}${t.note ? `<div style="font-size:.72rem;color:var(--muted);margin-top:2px">${esc(t.note)}</div>` : ''}</div>
      <button class="task-edit" onclick="openTaskModal('${t.id}')">✎</button>
      <button class="task-del" onclick="delTask('${t.id}')">✕</button>
    </div>`).join('');
}

window.dragStart = (e, id) => { draggedId = id; e.dataTransfer.effectAllowed = 'move'; };
window.dragOver = e => e.preventDefault();
window.dropTask = (e, priority) => {
  e.preventDefault();
  const t = tasks.find(t => t.id === draggedId);
  if (t) { t.priority = priority; updateDoc(doc(db, 'users', currentUser.uid, 'tasks', draggedId), { priority }); renderAll(); }
  draggedId = null;
};

/* ══ GOALS ═══════════════════════════════════════════════════ */
window.openGoalModal = (id = null) => {
  editGoalId = id;
  document.getElementById('gmodal-title').textContent = id ? '✏️ Edit Goal' : '🎯 New Goal';
  if (id) {
    const g = goals.find(g => g.id === id);
    if (g) {
      document.getElementById('goal-name').value = g.name;
      document.getElementById('goal-target').value = g.target || '';
      document.getElementById('goal-category').value = g.category || 'personal';
    }
  } else {
    document.getElementById('goal-name').value = '';
    document.getElementById('goal-target').value = '';
    document.getElementById('goal-category').value = 'personal';
  }
  document.getElementById('goal-modal').classList.add('open');
  setTimeout(() => document.getElementById('goal-name').focus(), 100);
};

window.saveGoal = async () => {
  const name = document.getElementById('goal-name').value.trim();
  if (!name) { toast('Name your goal!', '🎯'); return; }
  const data = { name, category: document.getElementById('goal-category').value, target: document.getElementById('goal-target').value.trim() };
  const btn = document.querySelector('#goal-modal .progress-illusion');
  const bar = btn ? progressIllusion(btn) : null;
  if (bar) bar.start();
  try {
    if (editGoalId) {
      const old = goals.find(g => g.id === editGoalId);
      await optimisticUpdate(async () => {
        await updateDoc(doc(db, 'users', currentUser.uid, 'goals', editGoalId), data);
      },
        () => {
          if (old) {
            const i = goals.findIndex(g => g.id === editGoalId);
            if (i !== -1) goals[i] = old;
          }
        });
      const i = goals.findIndex(g => g.id === editGoalId);
      if (i !== -1) goals[i] = { ...goals[i], ...data };
      toast('Goal updated!', '🎯');
    } else {
      data.progress = 0; data.createdAt = Timestamp.now();
      const fakeId = '_optimistic';
      goals.unshift({ id: fakeId, ...data, createdAt: new Date() });
      await optimisticUpdate(async () => {
        const r = await addDoc(collection(db, 'users', currentUser.uid, 'goals'), data);
        const idx = goals.findIndex(g => g.id === fakeId);
        if (idx !== -1) goals[idx] = { id: r.id, ...data, createdAt: new Date() };
      },
        () => { goals = goals.filter(g => g.id !== fakeId); });
      toast('Goal created! 🚀', '🎯');
    }
    closeModal('goal-modal'); renderAll(); editGoalId = null;
  } catch (e) { toast('Error: ' + e.message, '❌'); }
  finally { if (bar) bar.finish(); }
};

window.updGoal = async (id, delta) => {
  const g = goals.find(g => g.id === id); if (!g) return;
  const oldProg = g.progress;
  g.progress = Math.max(0, Math.min(100, (g.progress || 0) + delta));
  try {
    await optimisticUpdate(async () => {
      await updateDoc(doc(db, 'users', currentUser.uid, 'goals', id), { progress: g.progress });
    }, null, () => { g.progress = oldProg; });
    if (g.progress === 100) toast('🎉 Goal completed!', '🏆');
    renderAll();
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.delGoal = async id => {
  if (!confirm('Delete this goal?')) return;
  const removed = goals.find(g => g.id === id);
  goals = goals.filter(g => g.id !== id);
  try {
    await optimisticUpdate(async () => {
      await deleteDoc(doc(db, 'users', currentUser.uid, 'goals', id));
    }, null, () => {
      if (removed) goals.unshift(removed);
    });
    renderAll(); toast('Goal removed', '🗑️');
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

function renderGoalsIn(container, list, mini = false) {
  if (!list.length) {
    container.innerHTML = `<div class="empty-state"><div class="empty-icon">🚀</div><div class="empty-text">No goals yet.</div><button class="btn btn-primary" onclick="openGoalModal()">Set first goal</button></div>`;
    return;
  }
  container.innerHTML = list.map((g, i) => `
    <div class="goal-item">
      <div class="goal-header">
        <div class="goal-name">${CAT_ICONS[g.category] || '⭐'} ${esc(g.name)}</div>
        <div style="display:flex;align-items:center;gap:8px">
          <div class="goal-pct">${g.progress || 0}%</div>
          ${!mini ? `<button style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:.75rem" onclick="openGoalModal('${g.id}')">✎</button>
                     <button style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:.75rem" onclick="delGoal('${g.id}')">✕</button>` : ''}
        </div>
      </div>
      ${g.target ? `<div style="font-size:.72rem;color:var(--muted);margin-bottom:8px;font-family:'JetBrains Mono',monospace">Target: ${esc(g.target)}</div>` : ''}
      <div class="goal-progress-wrap"><div class="goal-progress-bar" style="width:${g.progress || 0}%;background:${COLORS[i % COLORS.length]}"></div></div>
      ${!mini ? `<div class="goal-actions">
        <button class="btn-sm" onclick="updGoal('${g.id}',-10)">−10%</button>
        <button class="btn-sm" onclick="updGoal('${g.id}',10)">+10%</button>
        <button class="btn-sm" onclick="updGoal('${g.id}',25)">+25%</button>
        <button class="btn-sm" style="margin-left:auto" onclick="updGoal('${g.id}',${100 - (g.progress || 0)})">Complete ✓</button>
      </div>` : ''}
    </div>`).join('');
}

/* ══ INSIGHTS ════════════════════════════════════════════════ */
function calcStreak() {
  if (!journals.length) return 0;
  const uniq = [...new Set(journals.map(j => { const d = new Date(j.createdAt); return `${d.getFullYear()}-${d.getMonth()}-${d.getDate()}`; }))].sort().reverse();
  let streak = 0, cur = new Date();
  for (const s of uniq) {
    const [y, m, d] = s.split('-').map(Number);
    const entry = new Date(y, m, d);
    const diff = (new Date(cur.getFullYear(), cur.getMonth(), cur.getDate()) - entry) / 86400000;
    if (diff <= 1) { streak++; cur = entry; } else break;
  }
  return streak;
}

function renderInsights() {
  const streak = calcStreak();
  document.getElementById('insight-streak').textContent = streak;
  const cutoff = Date.now() - 7 * 86400000;
  const daily = {};
  journals.filter(j => new Date(j.createdAt) > cutoff).forEach(j => {
    const k = new Date(j.createdAt).toLocaleDateString();
    if (!daily[k]) daily[k] = [];
    daily[k].push(j.mood || 3);
  });
  const td = document.getElementById('mood-trend-chart');
  const entries = Object.entries(daily);
  td.innerHTML = entries.length ? entries.map(([day, moods]) => {
    const avg = (moods.reduce((a, b) => a + b, 0) / moods.length).toFixed(1);
    return `<div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
      <div style="width:70px;font-size:.68rem;color:var(--muted)">${day}</div>
      <div style="flex:1;background:var(--surface);border-radius:4px;height:20px;overflow:hidden">
        <div style="height:100%;background:linear-gradient(90deg,var(--rose),var(--amber),var(--accent),var(--green));width:${(avg / 5) * 100}%"></div>
      </div>
      <div style="width:28px;font-size:.8rem;font-weight:700;color:var(--accent)">${avg}</div>
    </div>`;
  }).join('') : `<div style="color:var(--muted);font-size:.8rem;padding:8px">No mood data yet.</div>`;

  const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  const mc = { 5: 'var(--green)', 4: 'var(--teal)', 3: 'var(--accent)', 2: 'var(--amber)', 1: 'var(--rose)' };
  document.getElementById('mood-chart').innerHTML = days.map((d, i) => {
    const ent = journals.filter(j => new Date(j.createdAt).getDay() === (i + 1) % 7);
    const avg = ent.length ? Math.round(ent.reduce((s, e) => s + e.mood, 0) / ent.length) : 0;
    return `<div class="mood-row">
      <div class="mood-day-label">${d}</div>
      <div class="mood-bar-wrap"><div class="mood-bar" style="width:${avg * 20}%;background:${mc[avg] || 'var(--muted)'}"></div></div>
      <div class="mood-val">${avg || '—'}</div>
    </div>`;
  }).join('');

  const done = tasks.filter(t => t.done).length, total = tasks.length;
  const avgM = journals.length ? (journals.reduce((s, j) => s + j.mood, 0) / journals.length).toFixed(1) : '—';
  const top = [...goals].sort((a, b) => b.progress - a.progress)[0];
  document.getElementById('activity-summary').innerHTML = `<div style="display:flex;flex-direction:column;gap:14px">
    ${[['📓', 'Journal Entries', 'Total: ' + journals.length, journals.length, 'var(--accent)'],
    ['✅', 'Task Completion', `${done} of ${total} done`, total ? Math.round((done / total) * 100) + '%' : '0%', 'var(--green)'],
    ['😊', 'Average Mood', 'Based on journals', avgM + '/5', 'var(--lavender)']
    ].map(([icon, label, sub, val, color]) =>
      `<div class="mood-row" style="align-items:center">
        <span style="font-size:1.2rem;width:28px">${icon}</span>
        <div style="flex:1"><div style="font-size:.78rem;font-weight:600;margin-bottom:2px">${label}</div><div style="font-size:.68rem;color:var(--muted)">${sub}</div></div>
        <div style="font-size:1.1rem;font-weight:800;color:${color}">${val}</div>
      </div>`).join('')}
    ${top ? `<div class="mood-row" style="align-items:center">
      <span style="font-size:1.2rem;width:28px">🏆</span>
      <div style="flex:1"><div style="font-size:.78rem;font-weight:600;margin-bottom:2px">Top Goal</div><div style="font-size:.68rem;color:var(--muted)">${esc(top.name)}</div></div>
      <div style="font-size:1.1rem;font-weight:800;color:var(--amber)">${top.progress}%</div>
    </div>` : ''}
  </div>`;
}

/* ══ FILTER JOURNALS ═════════════════════════════════════════ */
window.filterJournals = () => {
  const q = document.getElementById('journal-search').value.toLowerCase().trim();
  if (!q) { renderJournals('journal-list'); return; }
  const orig = journals;
  journals = journals.filter(e =>
    e.title?.toLowerCase().includes(q) ||
    e.content?.toLowerCase().includes(q) ||
    (e.tags || []).some(t => t.toLowerCase().includes(q))
  );
  renderJournals('journal-list');
  journals = orig;
};

/* ══ RENDER ALL ══════════════════════════════════════════════ */
function renderAll() {
  const streak = calcStreak();
  document.getElementById('stat-entries').textContent = journals.length;
  document.getElementById('stat-tasks').textContent = tasks.filter(t => t.done && new Date(t.createdAt) > new Date(Date.now() - 7 * 86400000)).length;
  document.getElementById('stat-goals').textContent = goals.filter(g => (g.progress || 0) < 100).length;
  document.getElementById('stat-streak').textContent = streak;
  document.getElementById('insight-streak').textContent = streak;
  if (journals.length) {
    const counts = {}; journals.forEach(j => { counts[j.mood] = (counts[j.mood] || 0) + 1; });
    const fav = Object.entries(counts).sort((a, b) => b[1] - a[1])[0][0];
    document.getElementById('stat-mood').textContent = M_EMOJI[fav] || '😐';
  }

  renderJournals('dash-journal-list', 3, true);
  renderJournals('journal-list');

  const pending = tasks.filter(t => !t.done).slice(0, 5);
  const dt = document.getElementById('dash-task-list');
  if (!pending.length) dt.innerHTML = `<div class="empty-state"><div class="empty-icon">🎉</div><div class="empty-text">All tasks done!</div><button class="btn btn-primary" onclick="openTaskModal()">Add task</button></div>`;
  else renderTasksIn(dt, pending);

  renderTasksIn(document.getElementById('tasks-high'), tasks.filter(t => t.priority === 'high'));
  renderTasksIn(document.getElementById('tasks-med'), tasks.filter(t => t.priority === 'med'));
  renderTasksIn(document.getElementById('tasks-low'), tasks.filter(t => t.priority === 'low' || t.done));
  renderGoalsIn(document.getElementById('goals-list'), goals);
  renderGoalsIn(document.getElementById('dash-goals-list'), goals.slice(0, 3), true);

  if (currentUser && document.getElementById('page-profile')?.classList.contains('active')) renderProfilePage();
  else applyProfileToUI();
}

/* ══════════════════════════════════════════════════════════════
   COMMUNITY FEED
══════════════════════════════════════════════════════════════ */
function subscribeFeed() {
  if (feedUnsubscribe) return;
  // clear previous posts so skeletons can appear
  window.feedPosts = undefined;
  renderFeed();

  const q = query(collection(db, 'community_posts'), orderBy('createdAt', 'desc'), limit(60));
  feedUnsubscribe = onSnapshot(q, snap => {
    feedPosts = snap.docs.map(d => ({ id: d.id, ...d.data(), createdAt: d.data().createdAt?.toDate() }));
    window.feedPosts = feedPosts;
    renderFeed();
    updateCommStats();
    const activePage = document.querySelector('.page.active')?.id;
    if (activePage !== 'page-community' && snap.docChanges().some(c => c.type === 'added')) {
      document.getElementById('new-posts-dot').style.display = 'inline-block';
    }
  }, () => {
    document.getElementById('feed-list').innerHTML = `<div class="loading-posts">Could not load feed. Check Firestore rules.</div>`;
  });
}

function updateCommStats() {
  document.getElementById('comm-stat-posts').textContent = feedPosts.length;
  document.getElementById('comm-stat-members').textContent = new Set(feedPosts.map(p => p.authorId)).size;
  document.getElementById('comm-stat-likes').textContent = feedPosts.reduce((s, p) => s + (p.likes?.length || 0), 0);
}

window.setComposerType = type => {
  composerType = type;
  document.querySelectorAll('.composer-type-btn').forEach(b => b.classList.toggle('active', b.dataset.type === type));
};

window.postThought = async () => {
  const text = document.getElementById('composer-text').value.trim();
  if (!text) { toast('Write something first!', '✍️'); return; }
  const cu = window.currentUser;
  if (!cu) return;
  const p = window.userProfile || {};
  // FIXED: Prioritize freshest profile data to prevent stale name/pic in new posts
  const authorName = p.displayName || cu.displayName || 'Anonymous';
  const authorAvatar = p.avatarUrl || cu.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
  try {
    await addDoc(collection(db, 'community_posts'), {
      type: 'thought', body: text, title: '',
      authorId: cu.uid, authorName, authorAvatar,
      authorUsername: p.username || (authorName.toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user'),
      likes: [], commentCount: 0, createdAt: serverTimestamp()
    });
    document.getElementById('composer-text').value = '';
    toast('Posted to community! 🌐', '✨');
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.openShareModal = (type = 'journal') => {
  if (!journals || !tasks || !goals) return;
  const body = document.getElementById('share-modal-body');
  if (!body) return;
  if (type === 'journal') {
    document.getElementById('share-modal-title').textContent = '📓 Share a Journal Entry';
    const items = journals.slice(0, 20);
    body.innerHTML = items.length
      ? `<p style="font-size:.8rem;color:var(--muted);margin-bottom:14px;font-family:'Newsreader',serif;font-style:italic">Choose an entry to share publicly.</p>
         <div class="share-modal-items">${items.map(e => `
           <div class="share-item" onclick="shareJournal('${e.id}')">
             <span class="share-item-icon">${e.moodEmoji || '📓'}</span>
             <div class="share-item-info"><div class="share-item-title">${esc(e.title || 'Untitled')}</div><div class="share-item-meta">${fmtDate(e.createdAt)}</div></div>
             <span style="color:var(--accent);font-size:.75rem;font-weight:600">Share ↗</span>
           </div>`).join('')}</div>`
      : `<div class="empty-state"><div class="empty-icon">📓</div><div class="empty-text">No journal entries yet.</div></div>`;
  }
  if (type === 'task') {
    document.getElementById('share-modal-title').textContent = '✅ Share a Task';
    const items = tasks.slice(0, 20);
    const PI = { high: '🔴', med: '🟡', low: '🟢' };
    body.innerHTML = items.length
      ? `<div class="share-modal-items">${items.map(t => `
           <div class="share-item" onclick="shareTask('${t.id}')">
             <span class="share-item-icon">${PI[t.priority] || '✅'}</span>
             <div class="share-item-info"><div class="share-item-title">${esc(t.text)}</div><div class="share-item-meta">${t.priority} · ${t.done ? 'Done ✓' : 'Pending'}</div></div>
             <span style="color:var(--green);font-size:.75rem;font-weight:600">Share ↗</span>
           </div>`).join('')}</div>`
      : `<div class="empty-state"><div class="empty-icon">✅</div><div class="empty-text">No tasks yet.</div></div>`;
  }
  if (type === 'goal') {
    document.getElementById('share-modal-title').textContent = '🎯 Share a Goal';
    const items = goals.slice(0, 20);
    body.innerHTML = items.length
      ? `<div class="share-modal-items">${items.map(g => `
           <div class="share-item" onclick="shareGoal('${g.id}')">
             <span class="share-item-icon">${CAT_ICONS[g.category] || '🎯'}</span>
             <div class="share-item-info"><div class="share-item-title">${esc(g.name)}</div><div class="share-item-meta">${g.progress || 0}% · ${esc(g.target || '')}</div></div>
             <span style="color:var(--lavender);font-size:.75rem;font-weight:600">Share ↗</span>
           </div>`).join('')}</div>`
      : `<div class="empty-state"><div class="empty-icon">🎯</div><div class="empty-text">No goals yet.</div></div>`;
  }
  document.getElementById('share-modal').classList.add('open');
};

window.shareJournal = async id => {
  if (!currentUser) { toast('Please wait, user not ready.', '⏳'); return; }
  const e = journals.find(j => j.id === id); if (!e) return;
  const cu = window.currentUser;
  const p = window.userProfile || {};
  const authorName = p.displayName || cu.displayName || 'Anonymous';
  const authorAvatar = p.avatarUrl || cu.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
  closeModal('share-modal');
  try {
    await addDoc(collection(db, 'community_posts'), {
      type: 'journal', title: e.title || 'Untitled', body: e.content || '', mood: e.mood || 3,
      moodEmoji: e.moodEmoji || '😐', tags: e.tags || [], photoUrls: (e.photoUrls || []).slice(0, 3),
      authorId: cu.uid, authorName, authorAvatar,
      authorUsername: p.username || (authorName.toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user'),
      likes: [], commentCount: 0, createdAt: serverTimestamp()
    });
    toast('Journal shared! 🌐', '📓'); navigateTo('community');
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.shareTask = async id => {
  const t = tasks.find(t => t.id === id); if (!t) return;
  const cu = window.currentUser;
  const p = window.userProfile || {};
  const authorName = p.displayName || cu.displayName || 'Anonymous';
  const authorAvatar = p.avatarUrl || cu.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
  closeModal('share-modal');
  const PI = { high: '🔴', med: '🟡', low: '🟢' };
  try {
    await addDoc(collection(db, 'community_posts'), {
      type: 'task', title: t.text, body: t.note || '', priority: t.priority, priorityIcon: PI[t.priority] || '✅', done: t.done || false,
      authorId: cu.uid, authorName, authorAvatar,
      authorUsername: p.username || (authorName.toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user'),
      likes: [], commentCount: 0, createdAt: serverTimestamp()
    });
    toast('Task shared! 🌐', '✅'); navigateTo('community');
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.shareGoal = async id => {
  const g = goals.find(g => g.id === id); if (!g) return;
  const cu = window.currentUser;
  const p = window.userProfile || {};
  const authorName = p.displayName || cu.displayName || 'Anonymous';
  const authorAvatar = p.avatarUrl || cu.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
  closeModal('share-modal');
  try {
    await addDoc(collection(db, 'community_posts'), {
      type: 'goal', title: g.name, body: g.target || '', category: g.category, categoryIcon: CAT_ICONS[g.category] || '⭐', progress: g.progress || 0,
      authorId: cu.uid, authorName, authorAvatar,
      authorUsername: p.username || (authorName.toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user'),
      likes: [], commentCount: 0, createdAt: serverTimestamp()
    });
    toast('Goal shared! 🌐', '🎯'); navigateTo('community');
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

function renderFeed() {
  const container = document.getElementById('feed-list');
  let posts = [...feedPosts];
  if (currentFeedFilter !== 'all') {
    if (currentFeedFilter === 'mine') posts = posts.filter(p => p.authorId === currentUser.uid);
    else posts = posts.filter(p => p.type === currentFeedFilter);
  }
  if (!posts.length) {
    container.innerHTML = `<div class="feed-empty"><div class="empty-icon">🌱</div><div class="empty-title">Nothing here yet</div><div class="empty-sub">Be the first to share something!</div></div>`;
    return;
  }
  container.innerHTML = posts.map(p => renderPostCard(p)).join('');
  initExpandableCards(container);
}

function renderPostCard(p) {
  const isOwn = p.authorId === currentUser?.uid;
  const liked = (p.likes || []).includes(currentUser?.uid);
  const timeAgo = relativeTime(p.createdAt);
  const badgeClass = TYPE_BADGE_CLASS[p.type] || 'badge-journal';
  const repostBadge = p.isRepost
    ? `<div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--teal);margin-bottom:10px;display:flex;align-items:center;gap:6px">🔁 reposted from <img src="${esc(p.originalAuthorAvatar || '')}" style="width:16px;height:16px;border-radius:50%;object-fit:cover"><span>${esc(p.originalAuthorName || '')}</span></div>` : '';

  let typeHtml = '';
  if (p.type === 'goal') {
    typeHtml = `<div class="post-goal-bar"><div class="post-goal-fill" style="width:${p.progress || 0}%"></div></div>
      <div class="post-goal-meta"><span>${p.categoryIcon || '🎯'}</span><span>${p.progress || 0}% complete</span></div>
      ${p.body ? `<div class="post-body" style="margin-top:10px">${esc(p.body)}</div>` : ''}`;
  } else if (p.type === 'task') {
    typeHtml = `<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
        <span>${p.priorityIcon || '✅'}</span>
        <span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--muted);text-transform:uppercase">${p.priority || ''} priority</span>
        ${p.done ? `<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--green)">· Done ✓</span>` : ''}
      </div>
      ${p.body ? `<div class="post-body">${esc(p.body)}</div>` : ''}`;
  } else {
    const isLong = (p.body || '').length > 300;
    typeHtml = `<div class="post-body" id="post-body-${p.id}" style="display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden">${esc(p.body || '')}</div>
      ${isLong ? `<span class="post-read-more" onclick="event.stopPropagation();toggleReadMore('${p.id}')" style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--accent);cursor:pointer;margin-top:4px;display:inline-block">Read more ↓</span>` : ''}
      ${p.moodEmoji ? `<div style="margin-top:8px;font-size:.8rem;color:var(--muted);font-family:'JetBrains Mono',monospace">feeling ${p.moodEmoji}</div>` : ''}
      ${p.photoUrls?.length ? `<div class="post-photos">${p.photoUrls.map(u => `<img src="${u}" class="post-photo" onclick="event.stopPropagation();viewPhoto('${u}')">`).join('')}</div>` : ''}
      ${p.tags?.length ? `<div class="post-tags">${p.tags.map(t => `<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}</div>` : ''}`;
  }

  return `<div class="post-card" id="post-card-${p.id}" data-post-id="${p.id}">
    <div class="post-header">
      <img src="${esc(p.authorAvatar || '')}" class="post-avatar" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
      <div class="post-meta">
        <button class="post-author-btn" onclick="openUserProfileModal('${p.authorId}')">${esc(p.authorName || 'Anonymous')}</button> <span class="post-type-badge ${badgeClass}">${TYPE_BADGES[p.type] || p.type}</span>${isOwn ? `<span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:var(--muted);padding:2px 6px;border-radius:4px;background:var(--surface2)">you</span>` : ''}
        <div class="post-time">${timeAgo}</div>
      </div>
      ${isOwn ? `<button class="post-delete-btn" onclick="deletePost('${p.id}')">🗑️</button>` : ''}
    </div>
    ${p.title ? `<div class="post-title">${esc(p.title)}</div>` : ''}
    ${repostBadge}${typeHtml}
    <div class="post-actions">
      <button class="post-action-btn ${liked ? 'liked' : ''}" onclick="toggleLike('${p.id}')"><span class="heart-icon">${liked ? '❤️' : '🤍'}</span><span class="post-action-count">${(p.likes || []).length || ''}</span></button>
      <button class="post-action-btn" onclick="toggleComments('${p.id}')">💬 <span class="post-action-count" id="comment-count-${p.id}">${p.commentCount || 0}</span></button>
      <button class="post-action-btn" onclick="repost('${p.id}')">🔁 <span class="post-action-count">${p.repostCount || ''}</span></button>
    </div>
    <div class="comments-section" id="comments-${p.id}" style="display:none">
      <div id="comments-list-${p.id}"></div><!-- skeleton will be added by loadComments -->
      <div class="comment-input-row">
        <img src="${esc(currentUser?.photoURL || 'https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff')}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid var(--border);flex-shrink:0">
        <input class="comment-input" id="comment-input-${p.id}" placeholder="Write a comment…" onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();submitComment('${p.id}')}">
        <button class="comment-submit" onclick="submitComment('${p.id}')">↵</button>
      </div>
    </div>
  </div>`;
}

window.toggleReadMore = id => {
  const el = document.getElementById('post-body-' + id);
  const btn = el?.nextElementSibling;
  if (!el || !btn) return;
  const isExpanded = el.style.webkitLineClamp === 'unset';
  if (isExpanded) {
    el.style.webkitLineClamp = '4';
    el.style.overflow = 'hidden';
    btn.textContent = 'Read more ↓';
  } else {
    el.style.webkitLineClamp = 'unset';
    el.style.overflow = 'visible';
    btn.textContent = 'Show less ↑';
  }
};

window.toggleLike = async postId => {
  const post = feedPosts.find(p => p.id === postId); if (!post) return;
  const uid = currentUser.uid, liked = (post.likes || []).includes(uid);
  try { await updateDoc(doc(db, 'community_posts', postId), { likes: liked ? arrayRemove(uid) : arrayUnion(uid) }); }
  catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.deletePost = async postId => {
  if (!confirm('Delete this post?')) return;
  try { await deleteDoc(doc(db, 'community_posts', postId)); toast('Post deleted', '🗑️'); }
  catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.toggleComments = async postId => {
  const section = document.getElementById('comments-' + postId); if (!section) return;
  const isOpen = section.style.display !== 'none';
  section.style.display = isOpen ? 'none' : 'block';
  if (!isOpen) await loadComments(postId);
};

async function loadComments(postId) {
  const listEl = document.getElementById('comments-list-' + postId); if (!listEl) return;
  try {
    const snap = await getDocs(query(collection(db, 'community_posts', postId, 'comments'), orderBy('createdAt', 'asc')));
    const comments = snap.docs.map(d => ({ id: d.id, ...d.data(), createdAt: d.data().createdAt?.toDate() }));
    renderComments(postId, comments);
  } catch { listEl.innerHTML = `<div style="font-size:.72rem;color:var(--muted);padding:8px">Could not load comments.</div>`; }
}

function renderComments(postId, comments) {
  const listEl = document.getElementById('comments-list-' + postId); if (!listEl) return;
  if (!comments.length) { listEl.innerHTML = `<div style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);padding:8px 0">No comments yet.</div>`; return; }
  listEl.innerHTML = comments.map(c => `
    <div class="comment-item">
      <img src="${esc(c.authorAvatar || '')}" class="comment-avatar" onerror="this.src='https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff'">
      <div class="comment-bubble">
        <div class="comment-author"><span>${esc(c.authorName || 'Anonymous')}</span>${c.authorId === currentUser?.uid ? `<button class="comment-del" onclick="deleteComment('${postId}','${c.id}')">✕</button>` : ''}</div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--muted);margin-bottom:4px">${relativeTime(c.createdAt)}</div>
        <div class="comment-text">${esc(c.text)}</div>
      </div>
    </div>`).join('');
}

window.submitComment = async postId => {
  const input = document.getElementById('comment-input-' + postId);
  const text = input?.value.trim(); if (!text) return;
  input.value = '';
  try {
    await addDoc(collection(db, 'community_posts', postId, 'comments'), {
      text, authorId: currentUser.uid, authorName: currentUser.displayName || 'Anonymous',
      authorAvatar: currentUser.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.displayName || 'U')}&background=4f8ef7&color=fff`,
      createdAt: serverTimestamp()
    });
    await updateDoc(doc(db, 'community_posts', postId), { commentCount: increment(1) });
    await loadComments(postId);
    const countEl = document.getElementById('comment-count-' + postId);
    if (countEl) countEl.textContent = parseInt(countEl.textContent || 0) + 1;
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.deleteComment = async (postId, commentId) => {
  try {
    await deleteDoc(doc(db, 'community_posts', postId, 'comments', commentId));
    await updateDoc(doc(db, 'community_posts', postId), { commentCount: increment(-1) });
    await loadComments(postId);
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.repost = async postId => {
  const post = feedPosts.find(p => p.id === postId); if (!post) return;
  if (post.authorId === currentUser.uid) { toast('Cannot repost your own post', '⚠️'); return; }
  const cu = window.currentUser;
  const p = window.userProfile || {};
  const authorName = p.displayName || cu.displayName || 'Anonymous';
  const authorAvatar = p.avatarUrl || cu.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=4f8ef7&color=fff`;
  try {
    await addDoc(collection(db, 'community_posts'), {
      ...post, id: undefined, isRepost: true,
      originalAuthorName: post.authorName, originalAuthorAvatar: post.authorAvatar,
      authorId: cu.uid, authorName, authorAvatar,
      authorUsername: p.username || (authorName.toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user'),
      likes: [], commentCount: 0, repostCount: 0, createdAt: serverTimestamp()
    });
    await updateDoc(doc(db, 'community_posts', postId), { repostCount: increment(1) });
    toast('Reposted! 🔁', '✨');
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.filterFeed = (filter, btn) => {
  currentFeedFilter = filter;
  document.querySelectorAll('.feed-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  renderFeed();
};

function relativeTime(date) {
  if (!date) return '';
  const diff = Date.now() - new Date(date).getTime();
  const mins = Math.floor(diff / 60000);
  if (mins < 1) return 'just now';
  if (mins < 60) return `${mins}m ago`;
  const hrs = Math.floor(mins / 60);
  if (hrs < 24) return `${hrs}h ago`;
  const days = Math.floor(hrs / 24);
  if (days < 7) return `${days}d ago`;
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

/* ══════════════════════════════════════════════════════════════
   USER PROFILE MODAL
   ── IDs match your blade's user-profile-modal partial:
      #user-profile-modal (.user-profile-modal-backdrop)
      #upm-cover / #upm-avatar / #upm-name / #upm-username
      #upm-bio / #upm-info / #upm-posts / #upm-likes
      #upm-joined / #upm-posts-list
══════════════════════════════════════════════════════════════ */
window.openUserProfileModal = async (userId) => {
  if (!userId) return;
  const modal = document.getElementById('user-profile-modal');
  if (!modal) return;

  // open immediately, populate while loading
  modal.classList.add('open');
  document.body.style.overflow = 'hidden';

  const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };

  // reset state
  // use skeletons rather than plain text
  set('upm-name', '');
  set('upm-username', '');
  set('upm-bio', '');
  set('upm-posts', '—');
  set('upm-likes', '—');
  set('upm-joined', '—');
  const upmNameEl = document.getElementById('upm-name');
  if (upmNameEl) { upmNameEl.innerHTML = '<span class="skeleton-text" style="width:120px;height:1.2em;display:inline-block"></span>'; }
  const infoEl = document.getElementById('upm-info');
  if (infoEl) infoEl.innerHTML = '';
  const postsListEl = document.getElementById('upm-posts-list');
  if (postsListEl) {
    showSkeleton(postsListEl, 3, '<div class="skeleton-text" style="height:20px;margin:4px 0"></div>');
  }
  const coverEl = document.getElementById('upm-cover');
  if (coverEl) coverEl.style.background = 'linear-gradient(135deg,#0d1b2a,#1b3a5c)';
  const avatarEl = document.getElementById('upm-avatar');
  if (avatarEl) avatarEl.src = '';

  try {
    // load profile sub-doc first, fall back to users doc
    const [profileSnap, userSnap] = await Promise.all([
      getDoc(doc(db, 'users', userId, 'profile', 'data')).catch(() => null),
      getDoc(doc(db, 'users', userId)).catch(() => null),
    ]);

    const profile = profileSnap?.exists() ? profileSnap.data() : {};
    const userDoc = userSnap?.exists() ? userSnap.data() : {};

    // privacy gate
    if (userDoc.isPublic === false) {
      set('upm-name', userDoc.displayName || 'Private User');
      set('upm-bio', 'This profile is private.');
      if (postsListEl) postsListEl.innerHTML = '<div style="text-align:center;padding:20px;color:var(--muted);font-size:.8rem">🔒 Private profile</div>';
      return;
    }

    const displayName = profile.displayName || userDoc.displayName || 'Anonymous';
    const username = profile.username || userDoc.username || '';
    const bio = profile.bio || userDoc.bio || '';
    const location = profile.location || '';
    const website = profile.website || '';
    const avatarUrl = profile.avatarUrl || userDoc.photoURL ||
      `https://ui-avatars.com/api/?name=${encodeURIComponent(displayName)}&background=4f8ef7&color=fff`;
    const coverGrad = profile.coverGradient || 'linear-gradient(135deg,#0d1b2a,#1b3a5c)';
    const joinedAt = profile.joinedAt || userDoc.joinedAt;

    set('upm-name', displayName);
    set('upm-username', username ? '@' + username : '');
    set('upm-bio', bio);

    if (avatarEl) avatarEl.src = avatarUrl;
    if (coverEl) coverEl.style.background = coverGrad;

    if (joinedAt) {
      const d = joinedAt?.toDate ? joinedAt.toDate() : new Date(joinedAt);
      set('upm-joined', d.toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
    }

    // info row (location / website)
    if (infoEl) {
      const parts = [];
      if (location) parts.push(`📍 ${esc(location)}`);
      if (website) parts.push(`🔗 <a href="${esc(website)}" target="_blank" style="color:var(--accent);text-decoration:none">${esc(website.replace(/^https?:\/\//, ''))}</a>`);
      infoEl.innerHTML = parts.join('<span style="margin:0 8px;opacity:.3">·</span>');
    }

    // posts
    const postsSnap = await getDocs(
      query(collection(db, 'community_posts'),
        where('authorId', '==', userId),
        orderBy('createdAt', 'desc'),
        limit(15))
    ).catch(() => null);

    const posts = postsSnap?.docs.map(d => ({ id: d.id, ...d.data() })) || [];
    const totalLike = posts.reduce((s, p) => s + (p.likes?.length || 0), 0);

    set('upm-posts', posts.length);
    set('upm-likes', totalLike);

    if (postsListEl) {
      postsListEl.innerHTML = posts.length
        ? posts.map(p => {
          const title = p.title || (p.body || '').slice(0, 60) + ((p.body || '').length > 60 ? '…' : '');
          return `<div style="padding:10px 0;border-bottom:1px solid var(--border)">
              <div style="font-size:.8rem;font-weight:600;color:var(--text);margin-bottom:2px">${esc(title)}</div>
              <div style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)">${relativeTime(p.createdAt?.toDate?.() || p.createdAt)} · ❤️ ${(p.likes || []).length}</div>
            </div>`;
        }).join('')
        : '<div style="font-family:\'JetBrains Mono\',monospace;font-size:.65rem;color:var(--muted);padding:8px">No posts yet.</div>';
    }

  } catch (err) {
    console.error('[UserProfileModal]', err);
    set('upm-name', 'Error loading profile');
    if (postsListEl) postsListEl.innerHTML = '<div style="font-size:.75rem;color:var(--rose);padding:8px">Could not load profile.</div>';
  }
};

window.closeUserProfileModal = () => {
  const modal = document.getElementById('user-profile-modal');
  if (modal) modal.classList.remove('open');
  document.body.style.overflow = '';
};

// close on backdrop click
document.addEventListener('click', e => {
  const backdrop = document.getElementById('user-profile-modal');
  if (backdrop?.classList.contains('open') && e.target === backdrop) {
    window.closeUserProfileModal();
  }
});

/* ══════════════════════════════════════════════════════════════
   PROFILE SYSTEM
══════════════════════════════════════════════════════════════ */
async function loadUserProfile() {
  if (!currentUser) return;
  try {
    const snap = await getDoc(doc(db, 'users', currentUser.uid, 'profile', 'data'));
    if (snap.exists()) { userProfile = snap.data(); }
    else {
      userProfile = {
        displayName: currentUser.displayName || '',
        username: (currentUser.displayName || 'user').toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user',
        bio: '', location: '', website: '',
        avatarUrl: currentUser.photoURL || '',
        coverGradient: COVER_PRESETS[0],
        joinedAt: new Date().toISOString(),
      };
      await setUserProfile(userProfile);
    }
    applyProfileToUI();
  } catch (e) { console.warn('Profile load:', e.message); }
}

async function setUserProfile(data) {
  if (!currentUser) return;
  await setDoc(doc(db, 'users', currentUser.uid, 'profile', 'data'), data, { merge: true });
}

function applyProfileToUI() {
  const p = userProfile;
  const av = p.avatarUrl || currentUser?.photoURL ||
    `https://ui-avatars.com/api/?name=${encodeURIComponent(p.displayName || 'U')}&background=4f8ef7&color=fff`;
  document.getElementById('user-name').textContent = p.displayName || currentUser?.displayName || '—';
  document.getElementById('user-avatar').src = av;
  document.getElementById('composer-avatar').src = av;

  const el = id => document.getElementById(id);
  if (el('profile-avatar-large')) el('profile-avatar-large').src = av;
  if (el('profile-display-name')) el('profile-display-name').textContent = p.displayName || '—';
  if (el('profile-username-display')) el('profile-username-display').textContent = '@' + (p.username || '—');
  if (el('profile-bio-display')) el('profile-bio-display').textContent = p.bio || 'No bio yet.';
  if (el('profile-cover-display')) el('profile-cover-display').style.background = p.coverGradient || COVER_PRESETS[0];

  const badges = [];
  if (journals.length >= 1) badges.push({ label: '📓 Writer', color: 'rgba(79,142,247,.2)', border: 'rgba(79,142,247,.4)' });
  if (tasks.filter(t => t.done).length) badges.push({ label: '✅ Doer', color: 'rgba(52,211,153,.15)', border: 'rgba(52,211,153,.4)' });
  if (goals.length >= 1) badges.push({ label: '🎯 Goal-setter', color: 'rgba(167,139,250,.15)', border: 'rgba(167,139,250,.4)' });
  if (calcStreak() >= 3) badges.push({ label: '🔥 On a streak', color: 'rgba(251,191,36,.15)', border: 'rgba(251,191,36,.4)' });
  if (el('profile-badges')) el('profile-badges').innerHTML = badges.map(b => `<span class="profile-badge" style="background:${b.color};border-color:${b.border};color:var(--text)">${b.label}</span>`).join('');

  if (el('pstat-journals')) el('pstat-journals').textContent = journals.length;
  if (el('pstat-tasks')) el('pstat-tasks').textContent = tasks.length;
  if (el('pstat-goals')) el('pstat-goals').textContent = goals.length;
  if (el('pstat-posts')) el('pstat-posts').textContent = feedPosts.filter(p => p.authorId === currentUser?.uid).length;

  const infoEl = el('profile-info-row');
  if (infoEl) {
    const parts = [];
    if (p.location) parts.push(`📍 ${esc(p.location)}`);
    if (p.website) parts.push(`🔗 <a href="${esc(p.website)}" target="_blank" style="color:var(--accent);text-decoration:none">${esc(p.website.replace(/^https?:\/\//, ''))}</a>`);
    if (p.joinedAt) parts.push(`🗓 Joined ${new Date(p.joinedAt).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`);
    infoEl.innerHTML = parts.join('<span style="margin:0 8px;opacity:.3">·</span>');
  }
}

function renderProfilePage() {
  applyProfileToUI();
  const postsEl = document.getElementById('profile-my-posts');
  if (!postsEl) return;
  const isPublic = userProfile.isPublic !== false;
  if (isPublic) {
    const myPosts = feedPosts.filter(p => p.authorId === currentUser?.uid);
    postsEl.innerHTML = myPosts.length
      ? myPosts.map(p => renderPostCard(p)).join('')
      : `<div class="feed-empty" style="padding:32px"><div class="empty-icon">🌐</div><div class="empty-title">No posts yet</div><div class="empty-sub">Share something with the community!</div><button class="btn btn-primary" style="margin-top:16px" onclick="navigateTo('community')">Go to Community</button></div>`;
  } else {
    postsEl.innerHTML = `<div class="feed-empty" style="padding:32px"><div class="empty-icon">🔒</div><div class="empty-title">This Profile is Private</div><div class="empty-sub">This user's activity is not shared publicly.</div></div>`;
  }
}

window.openEditProfileModal = () => {
  const p = userProfile;
  document.getElementById('edit-fullname').value = p.displayName || currentUser?.displayName || '';
  document.getElementById('edit-username').value = p.username || '';
  document.getElementById('edit-bio').value = p.bio || '';
  document.getElementById('edit-location').value = p.location || '';
  document.getElementById('edit-website').value = p.website || '';
  document.getElementById('edit-profile-modal').classList.add('open');
  setTimeout(() => document.getElementById('edit-fullname').focus(), 100);
};

window.saveProfile = async () => {
  const fullName = document.getElementById('edit-fullname').value.trim();
  const rawUser = document.getElementById('edit-username').value.trim().toLowerCase().replace(/[^a-z0-9_]/g, '');
  if (!fullName) { toast('Name cannot be empty', '⚠️'); return; }
  if (!rawUser) { toast('Username cannot be empty', '⚠️'); return; }
  userProfile = {
    ...userProfile, displayName: fullName, username: rawUser,
    bio: document.getElementById('edit-bio').value.trim(),
    location: document.getElementById('edit-location').value.trim(),
    website: document.getElementById('edit-website').value.trim()
  };
  try { await setUserProfile(userProfile); applyProfileToUI(); closeModal('edit-profile-modal'); toast('Profile updated! ✨', '👤'); }
  catch (e) { toast('Error: ' + e.message, '❌'); }
};

window.openAvatarModal = () => {
  selectedAvatarUrl = userProfile.avatarUrl || currentUser?.photoURL || '';
  const grid = document.getElementById('avatar-preset-grid');
  grid.innerHTML = AVATAR_PRESETS.map((url, i) =>
    `<img src="${url}" class="avatar-option ${selectedAvatarUrl === url ? 'selected' : ''}" onclick="selectAvatarPreset('${url}',this)" alt="Avatar ${i + 1}">`
  ).join('');
  const fi = document.getElementById('avatar-upload');
  fi.value = '';
  fi.onchange = async e => {
    const file = e.target.files[0]; if (!file) return;
    toast('Compressing…', '📷');
    try { selectedAvatarUrl = await compressImageTo(file, 80 * 1024); toast('Avatar ready!', '✅'); }
    catch { toast('Could not read image', '❌'); }
  };
  document.getElementById('avatar-modal').classList.add('open');
};

window.selectAvatarPreset = (url, el) => {
  selectedAvatarUrl = url;
  document.querySelectorAll('.avatar-option').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
};

window.saveAvatar = async () => {
  if (!selectedAvatarUrl) { toast('Pick an avatar first', '⚠️'); return; }
  userProfile.avatarUrl = selectedAvatarUrl;
  try { await setUserProfile(userProfile); applyProfileToUI(); closeModal('avatar-modal'); toast('Avatar updated!', '🖼'); }
  catch (e) { toast('Error: ' + e.message, '❌'); }
};

/* ══ COVER MODAL ═════════════════════════════════════════════ */
window.openCoverModal = () => {
  selectedCoverData = { value: userProfile.coverGradient || COVER_PRESETS[0] };
  const grid = document.getElementById('cover-preset-grid');
  grid.innerHTML = COVER_PRESETS.map((grad, i) =>
    `<div class="cover-preset ${userProfile.coverGradient === grad ? 'selected' : ''}"
      style="background:${grad}" onclick="selectCoverPreset('${grad}',this)"></div>`
  ).join('');
  document.getElementById('cover-live-preview').style.background = selectedCoverData.value;
  document.getElementById('hex-text-input').value = '';
  document.getElementById('hex-color-wheel').value = '#4f8ef7';
  const wheel = document.getElementById('hex-color-wheel');
  const textIn = document.getElementById('hex-text-input');
  wheel.oninput = () => { textIn.value = wheel.value; previewHexGradient(wheel.value); };
  textIn.oninput = () => {
    const hex = textIn.value.trim();
    if (/^#[0-9a-fA-F]{6}$/.test(hex)) { wheel.value = hex; previewHexGradient(hex); }
  };
  document.getElementById('cover-modal').classList.add('open');
};

function hexToRgb(hex) {
  const r = parseInt(hex.slice(1, 3), 16);
  const g = parseInt(hex.slice(3, 5), 16);
  const b = parseInt(hex.slice(5, 7), 16);
  return { r, g, b };
}
function darken(hex, factor) {
  const { r, g, b } = hexToRgb(hex);
  const d = v => Math.round(Math.max(0, v * factor)).toString(16).padStart(2, '0');
  return `#${d(r)}${d(g)}${d(b)}`;
}
function hexToGradient(hex) {
  const mid = darken(hex, 0.55);
  const dark = darken(hex, 0.18);
  const ultra = darken(hex, 0.1);
  return `linear-gradient(135deg,${dark},${mid},${ultra})`;
}
function previewHexGradient(hex) {
  const grad = hexToGradient(hex);
  document.getElementById('cover-live-preview').style.background = grad;
  document.querySelectorAll('.cover-preset').forEach(p => p.classList.remove('selected'));
  selectedCoverData = { value: grad };
}
window.applyHexCover = () => {
  const textIn = document.getElementById('hex-text-input');
  const wheel = document.getElementById('hex-color-wheel');
  let hex = textIn.value.trim();
  if (/^#[0-9a-fA-F]{3}$/.test(hex)) hex = '#' + hex[1] + hex[1] + hex[2] + hex[2] + hex[3] + hex[3];
  if (!/^#[0-9a-fA-F]{6}$/.test(hex)) hex = wheel.value;
  previewHexGradient(hex);
  toast('Preview updated!', '🎨');
};
window.selectCoverPreset = (grad, el) => {
  selectedCoverData = { value: grad };
  document.querySelectorAll('.cover-preset').forEach(p => p.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('cover-live-preview').style.background = grad;
  document.getElementById('hex-text-input').value = '';
};
window.saveCover = async () => {
  if (!selectedCoverData) return;
  userProfile.coverGradient = selectedCoverData.value;
  try {
    await setUserProfile(userProfile);
    const cover = document.getElementById('profile-cover-display');
    if (cover) cover.style.background = selectedCoverData.value;
    closeModal('cover-modal');
    toast('Cover updated!', '🖼');
  } catch (e) { toast('Error: ' + e.message, '❌'); }
};

/* ══ IMAGE COMPRESSION ════════════════════════════════════════ */
function compressImageTo(file, targetBytes) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onerror = reject;
    reader.onload = ev => {
      const img = new Image();
      img.onerror = reject;
      img.onload = () => {
        const canvas = document.createElement('canvas'); const ctx = canvas.getContext('2d');
        let maxDim = 400, quality = 0.85, result = '';
        for (let attempt = 0; attempt < 8; attempt++) {
          let w = img.width, h = img.height;
          if (w > h) { if (w > maxDim) { h = Math.round(h * maxDim / w); w = maxDim; } }
          else { if (h > maxDim) { w = Math.round(w * maxDim / h); h = maxDim; } }
          canvas.width = w; canvas.height = h;
          ctx.clearRect(0, 0, w, h); ctx.drawImage(img, 0, 0, w, h);
          result = canvas.toDataURL('image/jpeg', quality);
          if (result.length * 0.75 <= targetBytes) break;
          if (quality > 0.3) quality -= 0.12; else { maxDim = Math.round(maxDim * 0.75); quality = 0.7; }
        }
        resolve(result);
      };
      img.src = ev.target.result;
    };
    reader.readAsDataURL(file);
  });
}

/* ══ UTILS ═══════════════════════════════════════════════════ */
window.openEntryTypeModal = () => document.getElementById('entry-type-modal').classList.add('open');
window.closeModal = id => document.getElementById(id).classList.remove('open');
document.querySelectorAll('.modal-backdrop').forEach(m => m.addEventListener('click', e => {
  if (e.target === m) m.classList.remove('open');
}));

function fmtDate(d) {
  if (!d) return '';
  return new Date(d).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
function esc(s) {
  return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
window.toast = (msg, icon = '✨') => {
  const t = document.createElement('div');
  t.className = 'toast';
  t.innerHTML = `<span>${icon}</span><span>${msg}</span>`;
  document.getElementById('toast-container').appendChild(t);
  setTimeout(() => { t.classList.add('out'); setTimeout(() => t.remove(), 300); }, 3000);
};

/* ══ GLOBAL EXPANDED POST HANDLER ════════════════════════════ */
window.openExpandedPost = function (postId) {
  if (!postId || !window.feedPosts) return;
  const p = window.feedPosts.find(x => x.id === postId);
  if (!p) return;
  window._expandedPostId = postId;

  const cu = window.currentUser;
  const isOwn = p.authorId === cu?.uid;
  const liked = (p.likes || []).includes(cu?.uid);
  const badgeClass = TYPE_BADGE_CLASS[p.type] || 'badge-journal';

  // ── choose overlay by active page ────────────────────────
  const activePage = document.querySelector('.page.active')?.id;
  const useProfile = activePage === 'page-profile';
  const overlayId = useProfile ? 'post-expand-overlay' : 'post-expand-overlay-comm';
  const prefix = useProfile ? 'pexp' : 'exp';
  const overlay = document.getElementById(overlayId);
  if (!overlay) return;

  // ── header ────────────────────────────────────────────────
  const avatarEl = document.getElementById(prefix + '-avatar');
  const timeEl = document.getElementById(prefix + '-time');
  const authorEl = document.getElementById(prefix + '-author-row');

  if (avatarEl) avatarEl.src = p.authorAvatar || 'https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff';
  if (timeEl) timeEl.textContent = relativeTime(p.createdAt);
  if (authorEl) {
    const handle = p.authorUsername ||
      (p.authorName || 'anonymous').toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user';
    authorEl.innerHTML =
      `<button class="post-author-btn"
          onclick="event.stopPropagation();openUserProfileModal('${esc(p.authorId)}')"
          style="background:none;border:none;cursor:pointer;font-family:'Syne',sans-serif;
                 display:inline-flex;align-items:center;gap:5px;padding:0">
         <span style="font-size:.82rem;font-weight:700;color:rgba(232,234,240,.95)">${esc(p.authorName || 'Anonymous')}</span>
         <span style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:rgba(174,184,210,.5)">@${esc(handle)}</span>
       </button>
       <span class="post-type-badge ${badgeClass}">${TYPE_BADGES[p.type] || p.type}</span>
       ${isOwn ? `<span style="font-family:'JetBrains Mono',monospace;font-size:.55rem;color:rgba(174,184,210,.4);
                               padding:2px 6px;border-radius:4px;background:rgba(255,255,255,.04)">you</span>` : ''}`;
  }

  // ── body ──────────────────────────────────────────────────
  let bodyHtml = '';

  if (p.title) {
    bodyHtml += `<div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;
                              letter-spacing:-.02em;margin-bottom:14px;line-height:1.3;
                              color:rgba(232,234,240,.97)">${esc(p.title)}</div>`;
  }

  if (p.type === 'goal') {
    bodyHtml += `
      <div style="background:rgba(255,255,255,.06);border-radius:99px;height:7px;overflow:hidden;margin-bottom:6px">
        <div style="height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent,#4f8ef7),var(--lavender,#a78bfa));width:${p.progress || 0}%"></div>
      </div>
      <div style="display:flex;justify-content:space-between;font-family:'JetBrains Mono',monospace;
                  font-size:.65rem;color:rgba(174,184,210,.5);margin-bottom:14px">
        <span>${p.categoryIcon || '🎯'} ${esc(p.title || '')}</span>
        <span>${p.progress || 0}% complete</span>
      </div>
      ${p.body ? `<div style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.8;
                               color:rgba(232,234,240,.75);font-weight:300">${esc(p.body)}</div>` : ''}`;

  } else if (p.type === 'task') {
    bodyHtml += `
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
        <span>${p.priorityIcon || '✅'}</span>
        <span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;
                     color:rgba(174,184,210,.5);text-transform:uppercase">${p.priority || ''} priority</span>
        ${p.done ? `<span style="font-size:.72rem;font-family:'JetBrains Mono',monospace;color:var(--green,#34d399)">· Done ✓</span>` : ''}
      </div>
      ${p.body ? `<div style="font-family:'Newsreader',serif;font-size:.95rem;line-height:1.8;
                               color:rgba(232,234,240,.75);font-weight:300">${esc(p.body)}</div>` : ''}`;

  } else {
    if (p.moodEmoji) {
      bodyHtml += `<div style="margin-bottom:12px;font-size:.8rem;font-family:'JetBrains Mono',monospace;
                                color:rgba(174,184,210,.45)">feeling ${p.moodEmoji}</div>`;
    }
    bodyHtml += `<div style="font-family:'Newsreader',serif;font-size:1rem;line-height:1.9;
                              color:rgba(232,234,240,.8);font-weight:300;
                              white-space:pre-wrap;word-break:break-word">${esc(p.body || '')}</div>`;
    if (p.photoUrls?.length) {
      bodyHtml += `<div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:18px">
        ${p.photoUrls.map(u =>
        `<img src="${esc(u)}" style="width:120px;height:120px;border-radius:10px;object-fit:cover;
                                       border:1px solid rgba(255,255,255,.07);cursor:pointer;
                                       transition:transform .2s"
               onmouseover="this.style.transform='scale(1.04)'"
               onmouseout="this.style.transform=''"
               onclick="event.stopPropagation();viewPhoto('${esc(u)}')">`
      ).join('')}
      </div>`;
    }
    if (p.tags?.length) {
      bodyHtml += `<div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:14px">
        ${p.tags.map(t =>
        `<span style="font-family:'JetBrains Mono',monospace;font-size:.57rem;font-weight:600;
                         letter-spacing:.1em;text-transform:uppercase;padding:4px 10px;border-radius:6px;
                         background:rgba(79,142,247,.08);border:1px solid rgba(79,142,247,.2);
                         color:rgba(79,142,247,.9)">${esc(t)}</span>`
      ).join('')}
      </div>`;
    }
  }

  // ── comments section ──────────────────────────────────────
  const myAvatar = cu?.photoURL || `https://ui-avatars.com/api/?name=U&background=4f8ef7&color=fff`;
  bodyHtml += `
    <div style="margin-top:22px;padding-top:20px;border-top:1px solid rgba(255,255,255,.06)">
      <div id="${prefix}-comments-list" style="margin-bottom:14px">
        <div style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:rgba(174,184,210,.35)">
          Loading comments…
        </div>
      </div>
      <div class="comment-input-row">
        <img src="${esc(myAvatar)}"
             style="width:28px;height:28px;border-radius:50%;object-fit:cover;
                    border:1px solid rgba(255,255,255,.08);flex-shrink:0">
        <input class="comment-input" id="${prefix}-comment-input"
               placeholder="Write a comment…"
               onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();window._submitCommentGlobal('${postId}')}">
        <button class="comment-submit" onclick="window._submitCommentGlobal('${postId}')">↵</button>
      </div>
    </div>`;

  const bodyEl = document.getElementById(prefix + '-body');
  if (bodyEl) {
    bodyEl.innerHTML = bodyHtml;
    bodyEl.scrollTop = 0;
  }

  // ── footer actions ────────────────────────────────────────
  const footerEl = document.getElementById(prefix + '-footer');
  if (footerEl) {
    footerEl.innerHTML = `
      <button class="post-action-btn ${liked ? 'liked' : ''}" id="${prefix}-like-btn"
              onclick="event.stopPropagation();window._toggleLikeGlobal('${postId}')">
        <span class="heart-icon">${liked ? '❤️' : '🤍'}</span>
        <span id="${prefix}-like-count" class="post-action-count">${(p.likes || []).length || ''}</span>
      </button>
      <button class="post-action-btn"
              onclick="event.stopPropagation();repost('${postId}')">
        🔁 <span class="post-action-count">${p.repostCount || ''}</span>
      </button>
      ${isOwn ? `
        <button class="post-action-btn" style="margin-left:auto;color:var(--rose,#f87171)"
                onclick="event.stopPropagation();deletePost('${postId}');closeExpandedPost()">
          🗑️ Delete
        </button>` : ''}`;
  }

  // ── open ──────────────────────────────────────────────────
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
  window._loadCommentsGlobal(postId);
};

window.closeExpandedPost = function () {
  const overlay = document.getElementById('post-expand-overlay') || document.getElementById('post-expand-overlay-comm');
  if (overlay) overlay.classList.remove('open');
  document.body.style.overflow = '';
  window._expandedPostId = null;
};

window._submitCommentGlobal = async function (postId) {
  const prefix = document.getElementById('post-expand-overlay') ? 'pexp' : 'exp';
  const input = document.getElementById(prefix + '-comment-input');
  const text = input.value.trim(); if (!text) return;
  const user = window.auth.currentUser;
  if (!user) { window.toast && window.toast('Not logged in', '⚠️'); return; }
  try {
    await addDoc(collection(db, 'community_posts', postId, 'comments'), {
      authorId: user.uid, authorName: user.displayName || 'Anonymous',
      authorAvatar: user.photoURL || '', text, createdAt: serverTimestamp(), likes: []
    });
    input.value = '';
    window._loadCommentsGlobal(postId);
  } catch (e) { console.error(e); window.toast && window.toast('Error posting comment', '🔥'); }
};

window._loadCommentsGlobal = function (postId) {
  if (!db) return;
  const prefix = document.getElementById('post-expand-overlay') ? 'pexp' : 'exp';
  const q = query(collection(db, 'community_posts', postId, 'comments'), orderBy('createdAt', 'asc'));
  onSnapshot(q, snap => {
    const comments = snap.docs.map(d => ({ id: d.id, ...d.data() }));
    const list = document.getElementById(prefix + '-comments-list');
    if (!list) return;
    list.innerHTML = comments.length
      ? comments.map(c => `
        <div style="margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border)">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
            <div style="display:flex;align-items:center;gap:8px">
              <img src="${esc(c.authorAvatar)}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;border:1px solid var(--border)">
              <span style="font-weight:600;font-size:.8rem">${esc(c.authorName)}</span>
            </div>
            <span style="font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--muted)">${relativeTime(c.createdAt?.toDate?.() || c.createdAt)}</span>
          </div>
          <div style="font-size:.85rem;color:rgba(232,234,240,.8);line-height:1.6;margin-left:32px">${esc(c.text)}</div>
        </div>`).join('')
      : `<div style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--muted);text-align:center;padding:8px">No comments yet. Start the conversation!</div>`;
  });
};

window._toggleLikeGlobal = async function (postId) {
  const user = window.auth.currentUser; if (!user) return;
  const p = window.feedPosts?.find(x => x.id === postId); if (!p) return;
  const isLiked = (p.likes || []).includes(user.uid);
  try {
    await updateDoc(doc(db, 'community_posts', postId), {
      likes: isLiked ? arrayRemove(user.uid) : arrayUnion(user.uid)
    });
  } catch (e) { console.error(e); }
};

/* ══ LEARNING RESOURCES ══════════════════════════════════════ */
let learningResourcesInitialized = false;
async function initializeLearningResources() {
  if (learningResourcesInitialized) return;
  learningResourcesInitialized = true;
  try {
    const snap = await getDocs(collection(db, 'learning_resources'));
    if (snap.docs.length > 0) return;
    const sampleResources = [
      { title: 'Python for Data Science', type: 'course', platform: 'Coursera', difficulty: 'Intermediate', rating: 4.8, link: 'https://coursera.org/learn/python-for-data-science', description: 'Complete Python course for data science applications', tags: ['python', 'data science', 'programming'] },
      { title: 'Learn Machine Learning', type: 'course', platform: 'Coursera', difficulty: 'Advanced', rating: 4.9, link: 'https://coursera.org/specializations/machine-learning', description: 'Deep dive into ML algorithms and applications', tags: ['machine learning', 'ai', 'data science'] },
      { title: 'Web Development Bootcamp', type: 'course', platform: 'Udemy', difficulty: 'Beginner', rating: 4.7, link: 'https://udemy.com/web-development', description: 'Full stack web development from scratch', tags: ['web development', 'javascript', 'html', 'css'] },
      { title: 'React.js Tutorial', type: 'tutorial', platform: 'YouTube', difficulty: 'Intermediate', rating: 4.6, link: 'https://youtube.com/react-tutorial', description: 'Learn modern React development', tags: ['react', 'javascript', 'web development'] },
      { title: 'The Complete SQL Bootcamp', type: 'course', platform: 'Udemy', difficulty: 'Beginner', rating: 4.8, link: 'https://udemy.com/sql-bootcamp', description: 'Master SQL and database design', tags: ['sql', 'database', 'programming'] },
      { title: 'AI Engineering Guide', type: 'article', platform: 'Medium', difficulty: 'Intermediate', rating: 4.5, link: 'https://medium.com/ai-engineering', description: 'Best practices for AI engineering', tags: ['ai', 'machine learning', 'engineering'] },
      { title: 'Cloud Computing with AWS', type: 'course', platform: 'Linux Academy', difficulty: 'Intermediate', rating: 4.7, link: 'https://linuxacademy.com/aws', description: 'AWS cloud services and deployment', tags: ['aws', 'cloud', 'devops'] },
      { title: 'JavaScript ES6+ Guide', type: 'article', platform: 'Dev.to', difficulty: 'Intermediate', rating: 4.6, link: 'https://dev.to/javascript', description: 'Modern JavaScript best practices', tags: ['javascript', 'programming', 'web development'] },
      { title: 'UI/UX Design Principles', type: 'course', platform: 'Skillshare', difficulty: 'Beginner', rating: 4.7, link: 'https://skillshare.com/ui-ux', description: 'Fundamentals of UI/UX design', tags: ['design', 'ui', 'ux'] },
      { title: 'Git & GitHub Essentials', type: 'tutorial', platform: 'GitHub Learning', difficulty: 'Beginner', rating: 4.8, link: 'https://github.com/skills', description: 'Version control and collaboration', tags: ['git', 'github', 'programming'] },
    ];
    for (const r of sampleResources) await addDoc(collection(db, 'learning_resources'), r);
  } catch (e) { console.warn('Error initializing learning resources:', e.message); }
}