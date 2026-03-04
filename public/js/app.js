import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup, onAuthStateChanged, signOut as fbSignOut }
  from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import { getFirestore, collection, addDoc, getDocs, getDoc, doc, setDoc, updateDoc, deleteDoc,
         query, orderBy, limit, where, arrayUnion, arrayRemove, increment, serverTimestamp, onSnapshot, Timestamp, writeBatch }
  from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

let auth, db;
const fbApp = initializeApp(window.firebaseConfig);
auth = getAuth(fbApp);
db = getFirestore(fbApp);

window.auth = auth;
window.db = db;
window._fbFS = { doc, setDoc, getDoc, updateDoc, deleteDoc, addDoc, getDocs, collection, query, where, orderBy, serverTimestamp, onSnapshot, writeBatch, arrayUnion, arrayRemove, increment, limit, Timestamp };

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

const MOTIVATIONS = ["Every small step counts 🚀","Progress, not perfection 🎯","You're stronger than you think 💪","Today is full of possibilities ✨","Be kind to yourself 💖","Your future self will thank you 🙏","You've got this! 🔥","Growth happens outside comfort zones 🌱"];
const CAT_ICONS = {health:'💪',learn:'📚',finance:'💰',career:'💼',personal:'🌱',other:'⭐'};
const COLORS    = ['var(--accent)','var(--lavender)','var(--teal)','var(--green)','var(--amber)','var(--rose)'];
const M_EMOJI   = {1:'😢',2:'😔',3:'😐',4:'🙂',5:'😄'};
const TYPE_BADGES = {thought:'💭 Thought',journal:'📓 Journal',task:'✅ Task',goal:'🎯 Goal'};
const TYPE_BADGE_CLASS = {thought:'badge-journal',journal:'badge-journal',task:'badge-task',goal:'badge-goal'};

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
    onAuthStateChanged(auth, user => {
      if (user) {
        currentUser = user;
        window.currentUser = user;
        showApp(user);
        loadAll();
      } else showAuth();
    });
    setTimeout(() => {
      const authEl = document.getElementById('auth-screen');
      const appEl  = document.getElementById('app');
      if (authEl.style.display === 'none' && appEl.style.display === 'none') showAuth();
    }, 6000);
  }, 1200);
});

function showAuth() {
  document.getElementById('auth-screen').style.display = 'flex';
  document.getElementById('app').style.display = 'none';
}
function showApp(user) {
  document.getElementById('auth-screen').style.display = 'none';
  document.getElementById('app').style.display = 'block';
  document.getElementById('user-name').textContent = user.displayName || 'User';
  document.getElementById('user-email').textContent = user.email;
  const av = user.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.displayName||'U')}&background=4f8ef7&color=fff`;
  document.getElementById('user-avatar').src = av;
  document.getElementById('composer-avatar').src = av;
  updateGreeting();
  ensureUserProfileExists(user);
  loadUserProfile();
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
        photoURL: user.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.displayName||'U')}&background=4f8ef7&color=fff`,
        username: (user.displayName || 'user').toLowerCase().replace(/[^a-z0-9_]/g, '').slice(0, 20) || 'user',
        bio: 'Just joined LifeVault!',
        isPublic: true,
        joinedAt: serverTimestamp()
      });
    } else {
      console.log(`[Debug] User profile for ${user.uid} already exists.`);
    }
  } catch (error) {
    console.error("Error ensuring user profile exists:", error);
  }
}

/* ══ AUTH ════════════════════════════════════════════════════ */
document.getElementById('google-login-btn').onclick = async () => {
  try { await signInWithPopup(auth, new GoogleAuthProvider()); }
  catch(e) { toast(e.message || 'Login failed', '❌'); }
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
  try {
    const [j,t,g] = await Promise.all([
      getDocs(query(collection(db,'users',uid,'journals'), orderBy('createdAt','desc'))),
      getDocs(query(collection(db,'users',uid,'tasks'),   orderBy('createdAt','desc'))),
      getDocs(query(collection(db,'users',uid,'goals'),   orderBy('createdAt','desc')))
    ]);
    journals = j.docs.map(d => ({id:d.id,...d.data(), createdAt:d.data().createdAt?.toDate()}));
    tasks    = t.docs.map(d => ({id:d.id,...d.data(), createdAt:d.data().createdAt?.toDate()}));
    goals    = g.docs.map(d => ({id:d.id,...d.data(), createdAt:d.data().createdAt?.toDate()}));
    window.journals = journals;
    window.tasks = tasks;
    window.goals = goals;
    subscribeFeed();
    renderAll();
  } catch(e) { toast('Load error: '+e.message,'❌'); }
}

function updateGreeting() {
  const h = new Date().getHours();
  document.getElementById('greeting-time').textContent = h<12?'morning':h<17?'afternoon':'evening';
  document.getElementById('today-date').textContent = new Date().toLocaleDateString('en-US',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  document.getElementById('daily-motivation').textContent = MOTIVATIONS[Math.floor(Math.random()*MOTIVATIONS.length)];
}

/* ══ NAVIGATION ══════════════════════════════════════════════ */
window.navigateTo = (page, event) => {
  if (event) event.stopPropagation();
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  const pageEl = document.getElementById('page-'+page);
  if (!pageEl) return;
  pageEl.classList.add('active');
  const navEl = document.querySelector(`.nav-item[data-page="${page}"]`);
  if (navEl) navEl.classList.add('active');
  localStorage.setItem('lifeVaultLastPage', page);
  closeSidebar();
  if (page === 'insights')   renderInsights();
  if (page === 'community')  { subscribeFeed(); document.getElementById('new-posts-dot').style.display='none'; }
  if (page === 'profile')    renderProfilePage();
  if (page === 'settings')   window.renderSettingsPage && window.renderSettingsPage();
  if (page === 'shadow-self' || page === 'life-story') initializeLearningResources();
};

function restoreLastPage() {
  const lastPage = localStorage.getItem('lifeVaultLastPage') || 'dashboard';
  navigateTo(lastPage);
}

/* ══ JOURNAL EXPAND ══════════════════════════════════════════ */
window.openExpandedJournal = id => {
  const e = journals.find(j => j.id === id);
  if (!e) return;
  expandedJournalId = id;
  document.getElementById('exp-title').textContent   = e.title || 'Untitled';
  document.getElementById('exp-mood').textContent    = e.moodEmoji || '😐';
  document.getElementById('exp-date').textContent    = fmtDate(e.createdAt);
  document.getElementById('exp-content').textContent = e.content || '';

  const photosEl = document.getElementById('exp-photos');
  if (e.photoUrls?.length) {
    photosEl.style.display = 'flex';
    photosEl.innerHTML = e.photoUrls.map(u =>
      `<img src="${u}" class="expanded-photo" onclick="viewPhoto('${u}')">`
    ).join('');
  } else { photosEl.style.display = 'none'; photosEl.innerHTML = ''; }

  const tagsEl = document.getElementById('exp-tags');
  if (e.tags?.length) {
    tagsEl.style.display = 'flex';
    tagsEl.innerHTML = e.tags.map(t =>
      `<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`
    ).join('');
  } else { tagsEl.style.display = 'none'; tagsEl.innerHTML = ''; }

  document.getElementById('journal-expand-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.closeExpandedJournal = () => {
  document.getElementById('journal-expand-overlay').classList.remove('open');
  document.body.style.overflow = '';
  expandedJournalId = null;
};

window.editFromExpanded = () => {
  const id = expandedJournalId;
  closeExpandedJournal();
  openJournalModal(id);
};

window.shareJournalFromExpanded = () => {
  if (expandedJournalId) { closeExpandedJournal(); setTimeout(() => shareJournal(expandedJournalId), 100); }
};

document.getElementById('journal-expand-overlay').addEventListener('click', e => {
  if (e.target === document.getElementById('journal-expand-overlay')) closeExpandedJournal();
});

/* ══ FIX: ESC only closes journal overlay when community overlays are NOT open ══ */
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return;
  // Let community.blade.php's own ESC handler deal with its overlays
  if (document.getElementById('upm-overlay')?.classList.contains('open')) return;
  if (document.getElementById('post-expand-overlay-comm')?.classList.contains('open')) return;
  if (document.getElementById('share-modal')?.classList.contains('open')) return;
  if (expandedJournalId) closeExpandedJournal();
});

/* ══ JOURNAL DELEGATED LISTENERS ════════════════════════════ */
function initJournalListeners(containerId) {
  const container = document.getElementById(containerId);
  if (!container) return;
  const fresh = container.cloneNode(true);
  container.parentNode.replaceChild(fresh, container);
  document.getElementById(containerId).addEventListener('click', handleJournalClick);
}

function handleJournalClick(e) {
  const entry = e.target.closest('.journal-entry');
  if (!entry) return;
  const journalId = entry.dataset.id;
  const action = e.target.closest('[data-action]')?.dataset.action;
  if (action) {
    e.stopPropagation();
    switch (action) {
      case 'share':  shareJournal(journalId);     break;
      case 'edit':   openJournalModal(journalId); break;
      case 'delete': delJournal(journalId);       break;
      case 'expand-preview':
        entry.querySelector('.entry-preview')?.classList.toggle('expanded');
        break;
    }
  } else {
    openExpandedJournal(journalId);
  }
}

/* ══ RENDER JOURNALS ═════════════════════════════════════════ */
function renderJournals(containerId, maxCount, isDash) {
  const container = document.getElementById(containerId);
  if (!container) return;
  const list = maxCount ? journals.slice(0, maxCount) : journals;

  if (!list.length) {
    container.innerHTML = `<div class="empty-state">
      <div class="empty-icon">📖</div>
      <div class="empty-text">No entries yet.</div>
      <button class="btn btn-primary" onclick="openJournalModal()">Write first entry</button>
    </div>`;
    return;
  }

  container.innerHTML = list.map(e => {
    const photosHtml = e.photoUrls?.length
      ? `<div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px">
          ${e.photoUrls.map(u => `<img src="${u}" style="width:64px;height:64px;border-radius:8px;object-fit:cover;border:1px solid var(--border)" onclick="event.stopPropagation();viewPhoto('${u}')">`).join('')}
        </div>` : '';
    const tagsHtml = e.tags?.length
      ? `<div class="entry-tags">${e.tags.map(t => `<span class="tag" style="background:rgba(79,142,247,.12);color:var(--accent)">${esc(t)}</span>`).join('')}</div>`
      : '';
    const actionBtns = isDash
      ? `<button class="journal-entry-actions" data-action="share" title="Share" style="background:none;border:1px solid rgba(45,212,191,.3);color:var(--teal);cursor:pointer;font-size:.72rem;padding:4px 8px;border-radius:6px">↗</button>`
      : `<div class="journal-entry-actions">
           <button data-action="share" title="Share">↗</button>
           <button data-action="edit" title="Edit">✎</button>
           <button class="del-btn" data-action="delete" title="Delete">✕</button>
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

  initJournalListeners(containerId);
}

window.selectMood = (val, emoji) => {
  moodVal = val; moodEmoji = emoji;
  document.querySelectorAll('.mood-option').forEach(o => o.classList.remove('selected'));
  document.querySelector(`.mood-option[data-mood="${val}"]`).classList.add('selected');
};

window.openJournalModal = (id = null) => {
  editJournalId = id;
  photoUrls = [];
  document.getElementById('photo-preview').innerHTML = '';
  document.getElementById('journal-photos').value = '';
  document.getElementById('jmodal-title').textContent = id ? '✏️ Edit Entry' : '📓 New Journal Entry';
  if (id) {
    const e = journals.find(j => j.id === id);
    if (e) {
      document.getElementById('journal-title').value   = e.title || '';
      document.getElementById('journal-content').value = e.content || '';
      document.getElementById('journal-tags').value    = (e.tags || []).join(', ');
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
  if (photoUrls.length + files.length > 6) { toast('Max 6 photos','⚠️'); return; }
  toast(`Compressing ${files.length} photo(s)…`,'📷');
  for (const f of files) {
    try { photoUrls.push(await compressImage(f)); } catch { toast('Could not read '+f.name,'❌'); }
  }
  renderPreviews(); e.target.value = ''; toast('Photos ready!','📷');
});

function compressImage(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onerror = reject;
    reader.onload = ev => {
      const img = new Image();
      img.onerror = reject;
      img.onload = () => {
        const T = 200*1024; const c = document.createElement('canvas'); const ctx = c.getContext('2d');
        let m = 800, q = 0.8, r = '';
        for (let i = 0; i < 8; i++) {
          let w = img.width, h = img.height;
          if (w > h) { if (w > m) { h = Math.round(h*m/w); w = m; } }
          else { if (h > m) { w = Math.round(w*m/h); h = m; } }
          c.width = w; c.height = h; ctx.clearRect(0,0,w,h); ctx.drawImage(img,0,0,w,h);
          r = c.toDataURL('image/jpeg', q);
          if (r.length*.75 <= T) break;
          if (q > .3) q -= .15; else { m = Math.round(m*.7); q = .6; }
        }
        resolve(r);
      };
      img.src = ev.target.result;
    };
    reader.readAsDataURL(file);
  });
}

function renderPreviews() {
  document.getElementById('photo-preview').innerHTML = photoUrls.map((u,i) =>
    `<div style="position:relative;width:60px;height:60px;border-radius:8px;overflow:hidden;border:1px solid var(--border)">
      <img src="${u}" style="width:100%;height:100%;object-fit:cover">
      <button style="position:absolute;top:0;right:0;background:rgba(0,0,0,.7);color:white;border:none;cursor:pointer;width:20px;height:20px;font-size:.7rem" onclick="rmPhoto(${i})">×</button>
    </div>`
  ).join('');
}
window.rmPhoto = i => { photoUrls.splice(i,1); renderPreviews(); };

window.saveJournalEntry = async () => {
  const content = document.getElementById('journal-content').value.trim();
  if (!content) { toast('Write something first!','✏️'); return; }
  const data = {
    title: document.getElementById('journal-title').value.trim() || 'Untitled',
    content, mood: moodVal, moodEmoji,
    tags: document.getElementById('journal-tags').value.split(',').map(t=>t.trim()).filter(Boolean),
    photoUrls
  };
  try {
    if (editJournalId) {
      await updateDoc(doc(db,'users',currentUser.uid,'journals',editJournalId), data);
      const i = journals.findIndex(j => j.id === editJournalId);
      if (i !== -1) journals[i] = {...journals[i],...data};
      toast('Entry updated!','📓');
    } else {
      data.createdAt = Timestamp.now();
      const r = await addDoc(collection(db,'users',currentUser.uid,'journals'), data);
      journals.unshift({id:r.id,...data, createdAt:new Date()});
      toast('Entry saved! ✨','📓');
    }
    closeModal('journal-modal'); renderAll(); editJournalId = null;
  } catch(e) { toast('Error: '+e.message,'❌'); }
};

window.delJournal = async id => {
  if (!confirm('Delete this entry?')) return;
  journals = journals.filter(j => j.id !== id);
  await deleteDoc(doc(db,'users',currentUser.uid,'journals',id));
  renderAll(); toast('Entry deleted','🗑️');
};

window.viewPhoto = url => {
  const d = document.createElement('div');
  d.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.93);display:flex;align-items:center;justify-content:center;z-index:9999';
  d.innerHTML = `<img src="${url}" style="max-width:92%;max-height:92%;border-radius:10px">
    <button style="position:absolute;top:20px;right:24px;background:none;border:none;color:white;font-size:2.2rem;cursor:pointer" onclick="this.parentElement.remove()">×</button>`;
  d.onclick = e => { if (e.target === d) d.remove(); };
  document.body.appendChild(d);
};

/* ══ TASKS ═══════════════════════════════════════════════════ */
window.openTaskModal = (id = null) => {
  editTaskId = id;
  document.getElementById('tmodal-title').textContent = id ? '✏️ Edit Task' : '✅ Add Task';
  if (id) {
    const t = tasks.find(t => t.id === id);
    if (t) {
      document.getElementById('task-text').value     = t.text;
      document.getElementById('task-note').value     = t.note || '';
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
  if (!text) { toast('Enter a task!','✏️'); return; }
  const data = { text, priority: document.getElementById('task-priority').value, note: document.getElementById('task-note').value.trim(), done: false };
  try {
    if (editTaskId) {
      await updateDoc(doc(db,'users',currentUser.uid,'tasks',editTaskId), data);
      const i = tasks.findIndex(t => t.id === editTaskId);
      if (i !== -1) tasks[i] = {...tasks[i],...data};
      toast('Task updated!','✅');
    } else {
      data.createdAt = Timestamp.now();
      const r = await addDoc(collection(db,'users',currentUser.uid,'tasks'), data);
      tasks.unshift({id:r.id,...data, createdAt:new Date()});
      toast('Task added!','✅');
    }
    closeModal('task-modal'); renderAll(); editTaskId = null;
  } catch(e) { toast('Error: '+e.message,'❌'); }
};

window.toggleTask = async id => {
  const t = tasks.find(t => t.id === id); if (!t) return;
  t.done = !t.done;
  await updateDoc(doc(db,'users',currentUser.uid,'tasks',id), {done:t.done});
  if (t.done) toast('Task done! 🎉','✅');
  renderAll();
};

window.delTask = async id => {
  if (!confirm('Delete this task?')) return;
  tasks = tasks.filter(t => t.id !== id);
  await deleteDoc(doc(db,'users',currentUser.uid,'tasks',id));
  renderAll(); toast('Task removed','🗑️');
};

function renderTasksIn(container, list) {
  if (!list.length) {
    container.innerHTML = `<div style="padding:14px;color:var(--muted);font-size:.8rem;text-align:center;font-style:italic">All clear here ✓</div>`;
    return;
  }
  container.innerHTML = list.map(t => `
    <div class="task-item ${t.done?'done':''}" draggable="true" ondragstart="dragStart(event,'${t.id}')" ondragover="dragOver(event)" ondrop="dropTask(event,'${t.priority}')">
      <div class="task-check ${t.done?'checked':''}" onclick="toggleTask('${t.id}')"></div>
      <div class="priority-dot p-${t.priority}"></div>
      <div class="task-text">${esc(t.text)}${t.note ? `<div style="font-size:.72rem;color:var(--muted);margin-top:2px">${esc(t.note)}</div>` : ''}</div>
      <button class="task-edit" onclick="openTaskModal('${t.id}')">✎</button>
      <button class="task-del" onclick="delTask('${t.id}')">✕</button>
    </div>`).join('');
}

window.dragStart = (e, id) => { draggedId = id; e.dataTransfer.effectAllowed = 'move'; };
window.dragOver  = e => e.preventDefault();
window.dropTask  = (e, priority) => {
  e.preventDefault();
  const t = tasks.find(t => t.id === draggedId);
  if (t) { t.priority = priority; updateDoc(doc(db,'users',currentUser.uid,'tasks',draggedId),{priority}); renderAll(); }
  draggedId = null;
};

/* ══ GOALS ═══════════════════════════════════════════════════ */
window.openGoalModal = (id = null) => {
  editGoalId = id;
  document.getElementById('gmodal-title').textContent = id ? '✏️ Edit Goal' : '🎯 New Goal';
  if (id) {
    const g = goals.find(g => g.id === id);
    if (g) {
      document.getElementById('goal-name').value     = g.name;
      document.getElementById('goal-target').value   = g.target || '';
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
  if (!name) { toast('Name your goal!','🎯'); return; }
  const data = { name, category: document.getElementById('goal-category').value, target: document.getElementById('goal-target').value.trim() };
  try {
    if (editGoalId) {
      await updateDoc(doc(db,'users',currentUser.uid,'goals',editGoalId), data);
      const i = goals.findIndex(g => g.id === editGoalId);
      if (i !== -1) goals[i] = {...goals[i],...data};
      toast('Goal updated!','🎯');
    } else {
      data.progress = 0; data.createdAt = Timestamp.now();
      const r = await addDoc(collection(db,'users',currentUser.uid,'goals'), data);
      goals.unshift({id:r.id,...data, createdAt:new Date()});
      toast('Goal created! 🚀','🎯');
    }
    closeModal('goal-modal'); renderAll(); editGoalId = null;
  } catch(e) { toast('Error: '+e.message,'❌'); }
};

window.updGoal = async (id, delta) => {
  const g = goals.find(g => g.id === id); if (!g) return;
  g.progress = Math.max(0, Math.min(100, (g.progress||0) + delta));
  await updateDoc(doc(db,'users',currentUser.uid,'goals',id), {progress:g.progress});
  if (g.progress === 100) toast('🎉 Goal completed!','🏆');
  renderAll();
};

window.delGoal = async id => {
  if (!confirm('Delete this goal?')) return;
  goals = goals.filter(g => g.id !== id);
  await deleteDoc(doc(db,'users',currentUser.uid,'goals',id));
  renderAll(); toast('Goal removed','🗑️');
};

function renderGoalsIn(container, list, mini = false) {
  if (!list.length) {
    container.innerHTML = `<div class="empty-state"><div class="empty-icon">🚀</div><div class="empty-text">No goals yet.</div><button class="btn btn-primary" onclick="openGoalModal()">Set first goal</button></div>`;
    return;
  }
  container.innerHTML = list.map((g,i) => `
    <div class="goal-item">
      <div class="goal-header">
        <div class="goal-name">${CAT_ICONS[g.category]||'⭐'} ${esc(g.name)}</div>
        <div style="display:flex;align-items:center;gap:8px">
          <div class="goal-pct">${g.progress||0}%</div>
          ${!mini ? `<button style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:.75rem" onclick="openGoalModal('${g.id}')">✎</button>
                     <button style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:.75rem" onclick="delGoal('${g.id}')">✕</button>` : ''}
        </div>
      </div>
      ${g.target ? `<div style="font-size:.72rem;color:var(--muted);margin-bottom:8px;font-family:'JetBrains Mono',monospace">Target: ${esc(g.target)}</div>` : ''}
      <div class="goal-progress-wrap"><div class="goal-progress-bar" style="width:${g.progress||0}%;background:${COLORS[i%COLORS.length]}"></div></div>
      ${!mini ? `<div class="goal-actions">
        <button class="btn-sm" onclick="updGoal('${g.id}',-10)">−10%</button>
        <button class="btn-sm" onclick="updGoal('${g.id}',10)">+10%</button>
        <button class="btn-sm" onclick="updGoal('${g.id}',25)">+25%</button>
        <button class="btn-sm" style="margin-left:auto" onclick="updGoal('${g.id}',${100-(g.progress||0)})">Complete ✓</button>
      </div>` : ''}
    </div>`).join('');
}

/* ══ INSIGHTS ════════════════════════════════════════════════ */
function calcStreak() {
  if (!journals.length) return 0;
  const uniq = [...new Set(journals.map(j => { const d=new Date(j.createdAt); return `${d.getFullYear()}-${d.getMonth()}-${d.getDate()}`; }))].sort().reverse();
  let streak = 0, cur = new Date();
  for (const s of uniq) {
    const [y,m,d] = s.split('-').map(Number);
    const entry = new Date(y,m,d);
    const diff = (new Date(cur.getFullYear(),cur.getMonth(),cur.getDate()) - entry) / 86400000;
    if (diff <= 1) { streak++; cur = entry; } else break;
  }
  return streak;
}

function renderInsights() {
  const streak = calcStreak();
  document.getElementById('insight-streak').textContent = streak;
  const cutoff = Date.now() - 7*86400000;
  const daily = {};
  journals.filter(j => new Date(j.createdAt) > cutoff).forEach(j => {
    const k = new Date(j.createdAt).toLocaleDateString();
    if (!daily[k]) daily[k] = [];
    daily[k].push(j.mood||3);
  });
  const td = document.getElementById('mood-trend-chart');
  const entries = Object.entries(daily);
  td.innerHTML = entries.length ? entries.map(([day,moods]) => {
    const avg = (moods.reduce((a,b)=>a+b,0)/moods.length).toFixed(1);
    return `<div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
      <div style="width:70px;font-size:.68rem;color:var(--muted)">${day}</div>
      <div style="flex:1;background:var(--surface);border-radius:4px;height:20px;overflow:hidden">
        <div style="height:100%;background:linear-gradient(90deg,var(--rose),var(--amber),var(--accent),var(--green));width:${(avg/5)*100}%"></div>
      </div>
      <div style="width:28px;font-size:.8rem;font-weight:700;color:var(--accent)">${avg}</div>
    </div>`;
  }).join('') : `<div style="color:var(--muted);font-size:.8rem;padding:8px">No mood data yet.</div>`;

  const days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
  const mc = {5:'var(--green)',4:'var(--teal)',3:'var(--accent)',2:'var(--amber)',1:'var(--rose)'};
  document.getElementById('mood-chart').innerHTML = days.map((d,i) => {
    const ent = journals.filter(j => new Date(j.createdAt).getDay() === (i+1)%7);
    const avg = ent.length ? Math.round(ent.reduce((s,e)=>s+e.mood,0)/ent.length) : 0;
    return `<div class="mood-row">
      <div class="mood-day-label">${d}</div>
      <div class="mood-bar-wrap"><div class="mood-bar" style="width:${avg*20}%;background:${mc[avg]||'var(--muted)'}"></div></div>
      <div class="mood-val">${avg||'—'}</div>
    </div>`;
  }).join('');

  const done = tasks.filter(t=>t.done).length, total = tasks.length;
  const avgM = journals.length ? (journals.reduce((s,j)=>s+j.mood,0)/journals.length).toFixed(1) : '—';
  const top = [...goals].sort((a,b)=>b.progress-a.progress)[0];
  document.getElementById('activity-summary').innerHTML = `<div style="display:flex;flex-direction:column;gap:14px">
    ${[['📓','Journal Entries','Total: '+journals.length,journals.length,'var(--accent)'],
       ['✅','Task Completion',`${done} of ${total} done`,total?Math.round((done/total)*100)+'%':'0%','var(--green)'],
       ['😊','Average Mood','Based on journals',avgM+'/5','var(--lavender)']
      ].map(([icon,label,sub,val,color]) =>
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
    (e.tags||[]).some(t => t.toLowerCase().includes(q))
  );
  renderJournals('journal-list');
  journals = orig;
};

/* ══ EXPORT ══════════════════════════════════════════════════ */
window.exportAsJSON = () => {
  const blob = new Blob([JSON.stringify({journals,tasks,goals,exportedAt:new Date().toISOString(),user:currentUser?.email},null,2)],{type:'application/json'});
  const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
  a.download = `lifevault-backup-${Date.now()}.json`; a.click();
  toast('Backup downloaded!','💾');
};

/* ══ RENDER ALL ══════════════════════════════════════════════ */
function renderAll() {
  const streak = calcStreak();
  document.getElementById('stat-entries').textContent = journals.length;
  document.getElementById('stat-tasks').textContent   = tasks.filter(t => t.done && new Date(t.createdAt) > new Date(Date.now()-7*86400000)).length;
  document.getElementById('stat-goals').textContent   = goals.filter(g => (g.progress||0) < 100).length;
  document.getElementById('stat-streak').textContent  = streak;
  document.getElementById('insight-streak').textContent = streak;
  if (journals.length) {
    const counts = {}; journals.forEach(j => { counts[j.mood] = (counts[j.mood]||0)+1; });
    const fav = Object.entries(counts).sort((a,b)=>b[1]-a[1])[0][0];
    document.getElementById('stat-mood').textContent = M_EMOJI[fav] || '😐';
  }
  renderJournals('dash-journal-list', 3, true);
  renderJournals('journal-list');

  const pending = tasks.filter(t => !t.done).slice(0,5);
  const dt = document.getElementById('dash-task-list');
  if (!pending.length) dt.innerHTML = `<div class="empty-state"><div class="empty-icon">🎉</div><div class="empty-text">All tasks done!</div><button class="btn btn-primary" onclick="openTaskModal()">Add task</button></div>`;
  else renderTasksIn(dt, pending);

  renderTasksIn(document.getElementById('tasks-high'), tasks.filter(t=>t.priority==='high'));
  renderTasksIn(document.getElementById('tasks-med'),  tasks.filter(t=>t.priority==='med'));
  renderTasksIn(document.getElementById('tasks-low'),  tasks.filter(t=>t.priority==='low'||t.done));
  renderGoalsIn(document.getElementById('goals-list'), goals);
  renderGoalsIn(document.getElementById('dash-goals-list'), goals.slice(0,3), true);

  if (currentUser && document.getElementById('page-profile')?.classList.contains('active')) renderProfilePage();
  else applyProfileToUI();
}

/* ══ COMMUNITY FEED ══════════════════════════════════════════ */
function subscribeFeed() {
  if (feedUnsubscribe) return;
  const q = query(collection(db,'community_posts'), orderBy('createdAt','desc'), limit(60));
  feedUnsubscribe = onSnapshot(q, snap => {
    feedPosts = snap.docs.map(d => ({id:d.id,...d.data(), createdAt:d.data().createdAt?.toDate()}));
    window.feedPosts = feedPosts;
    if (typeof window.renderFeed === 'function') window.renderFeed();
    updateCommStats();
    const activePage = document.querySelector('.page.active')?.id;
    if (activePage !== 'page-community' && snap.docChanges().some(c=>c.type==='added')) {
      const dot = document.getElementById('new-posts-dot');
      if (dot) dot.style.display = 'inline-block';
    }
  }, () => {
    const fl = document.getElementById('feed-list');
    if (fl) fl.innerHTML = `<div class="loading-posts">Could not load feed. Check Firestore rules.</div>`;
  });
}

function updateCommStats() {
  const ps = document.getElementById('comm-stat-posts');
  const ms = document.getElementById('comm-stat-members');
  const ls = document.getElementById('comm-stat-likes');
  if (ps) ps.textContent = feedPosts.length;
  if (ms) ms.textContent = new Set(feedPosts.map(p=>p.authorId)).size;
  if (ls) ls.textContent = feedPosts.reduce((s,p)=>s+(p.likes?.length||0),0);
}

window.shareJournal = async id => {
  if (!currentUser) { toast('Please wait, user not ready.','⏳'); return; }
  const e = journals.find(j=>j.id===id); if (!e) return;
  closeModal('share-modal');
  try {
    await addDoc(collection(db,'community_posts'), {
      type:'journal', title:e.title||'Untitled', body:e.content||'', mood:e.mood||3,
      moodEmoji:e.moodEmoji||'😐', tags:e.tags||[], photoUrls:(e.photoUrls||[]).slice(0,3),
      authorId:currentUser.uid, authorName:currentUser.displayName||'Anonymous',
      authorAvatar:currentUser.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.displayName||'U')}&background=4f8ef7&color=fff`,
      likes:[], commentCount:0, createdAt:serverTimestamp()
    });
    toast('Journal shared! 🌐','📓'); navigateTo('community');
  } catch(e) { toast('Error: '+e.message,'❌'); }
};

window.shareTask = async id => {
  const t = tasks.find(t=>t.id===id); if (!t) return;
  closeModal('share-modal');
  const PI = {high:'🔴',med:'🟡',low:'🟢'};
  try {
    await addDoc(collection(db,'community_posts'), {
      type:'task', title:t.text, body:t.note||'', priority:t.priority, priorityIcon:PI[t.priority]||'✅', done:t.done||false,
      authorId:currentUser.uid, authorName:currentUser.displayName||'Anonymous',
      authorAvatar:currentUser.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.displayName||'U')}&background=4f8ef7&color=fff`,
      likes:[], commentCount:0, createdAt:serverTimestamp()
    });
    toast('Task shared! 🌐','✅'); navigateTo('community');
  } catch(e) { toast('Error: '+e.message,'❌'); }
};

window.shareGoal = async id => {
  const g = goals.find(g=>g.id===id); if (!g) return;
  closeModal('share-modal');
  try {
    await addDoc(collection(db,'community_posts'), {
      type:'goal', title:g.name, body:g.target||'', category:g.category, categoryIcon:CAT_ICONS[g.category]||'⭐', progress:g.progress||0,
      authorId:currentUser.uid, authorName:currentUser.displayName||'Anonymous',
      authorAvatar:currentUser.photoURL||`https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.displayName||'U')}&background=4f8ef7&color=fff`,
      likes:[], commentCount:0, createdAt:serverTimestamp()
    });
    toast('Goal shared! 🌐','🎯'); navigateTo('community');
  } catch(e) { toast('Error: '+e.message,'❌'); }
};

function relativeTime(date) {
  if (!date) return '';
  const diff = Date.now() - new Date(date).getTime();
  const mins = Math.floor(diff/60000);
  if (mins < 1) return 'just now';
  if (mins < 60) return `${mins}m ago`;
  const hrs = Math.floor(mins/60);
  if (hrs < 24) return `${hrs}h ago`;
  const days = Math.floor(hrs/24);
  if (days < 7) return `${days}d ago`;
  return new Date(date).toLocaleDateString('en-US',{month:'short',day:'numeric'});
}

/* ══ PROFILE SYSTEM ══════════════════════════════════════════ */
async function loadUserProfile() {
  if (!currentUser) return;
  try {
    const snap = await getDoc(doc(db,'users',currentUser.uid,'profile','data'));
    if (snap.exists()) { userProfile = snap.data(); }
    else {
      userProfile = {
        displayName: currentUser.displayName||'',
        username: (currentUser.displayName||'user').toLowerCase().replace(/[^a-z0-9_]/g,'').slice(0,20)||'user',
        bio:'', location:'', website:'',
        avatarUrl: currentUser.photoURL||'',
        coverGradient: COVER_PRESETS[0],
        joinedAt: new Date().toISOString(),
      };
      await setUserProfile(userProfile);
    }
    window.userProfile = userProfile;
    applyProfileToUI();
  } catch(e) { console.warn('Profile load:', e.message); }
}

async function setUserProfile(data) {
  if (!currentUser) return;
  await setDoc(doc(db,'users',currentUser.uid,'profile','data'), data, {merge:true});
}

function applyProfileToUI() {
  const p = userProfile;
  window.userProfile = userProfile;
  const av = p.avatarUrl || currentUser?.photoURL ||
    `https://ui-avatars.com/api/?name=${encodeURIComponent(p.displayName||'U')}&background=4f8ef7&color=fff`;
  document.getElementById('user-name').textContent = p.displayName || currentUser?.displayName || '—';
  document.getElementById('user-avatar').src = av;
  const compAv = document.getElementById('composer-avatar');
  if (compAv) compAv.src = av;

  const el = id => document.getElementById(id);
  if (el('profile-avatar-large'))      el('profile-avatar-large').src = av;
  if (el('profile-display-name'))      el('profile-display-name').textContent = p.displayName||'—';
  if (el('profile-username-display'))  el('profile-username-display').textContent = '@'+(p.username||'—');
  if (el('profile-bio-display'))       el('profile-bio-display').textContent = p.bio||'No bio yet.';
  if (el('profile-cover-display'))     el('profile-cover-display').style.background = p.coverGradient||COVER_PRESETS[0];

  const badges = [];
  if (journals.length >= 1)           badges.push({label:'📓 Writer',color:'rgba(79,142,247,.2)',border:'rgba(79,142,247,.4)'});
  if (tasks.filter(t=>t.done).length) badges.push({label:'✅ Doer',color:'rgba(52,211,153,.15)',border:'rgba(52,211,153,.4)'});
  if (goals.length >= 1)              badges.push({label:'🎯 Goal-setter',color:'rgba(167,139,250,.15)',border:'rgba(167,139,250,.4)'});
  if (calcStreak() >= 3)              badges.push({label:'🔥 On a streak',color:'rgba(251,191,36,.15)',border:'rgba(251,191,36,.4)'});
  if (el('profile-badges')) el('profile-badges').innerHTML = badges.map(b=>`<span class="profile-badge" style="background:${b.color};border-color:${b.border};color:var(--text)">${b.label}</span>`).join('');

  if (el('pstat-journals')) el('pstat-journals').textContent = journals.length;
  if (el('pstat-tasks'))    el('pstat-tasks').textContent    = tasks.length;
  if (el('pstat-goals'))    el('pstat-goals').textContent    = goals.length;
  if (el('pstat-posts'))    el('pstat-posts').textContent    = feedPosts.filter(p=>p.authorId===currentUser?.uid).length;

  const infoEl = el('profile-info-row');
  if (infoEl) {
    const parts = [];
    if (p.location) parts.push(`📍 ${esc(p.location)}`);
    if (p.website)  parts.push(`🔗 <a href="${esc(p.website)}" target="_blank" style="color:var(--accent);text-decoration:none">${esc(p.website.replace(/^https?:\/\//,''))}</a>`);
    if (p.joinedAt) parts.push(`🗓 Joined ${new Date(p.joinedAt).toLocaleDateString('en-US',{month:'long',year:'numeric'})}`);
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
    if (myPosts.length && typeof window.renderPostCard === 'function') {
      postsEl.innerHTML = myPosts.map(p => window.renderPostCard(p)).join('');
    } else if (!myPosts.length) {
      postsEl.innerHTML = `<div class="feed-empty" style="padding:32px"><div class="empty-icon">🌐</div><div class="empty-title">No posts yet</div><div class="empty-sub">Share something with the community!</div><button class="btn btn-primary" style="margin-top:16px" onclick="navigateTo('community')">Go to Community</button></div>`;
    }
  } else {
    postsEl.innerHTML = `<div class="feed-empty" style="padding:32px"><div class="empty-icon">🔒</div><div class="empty-title">This Profile is Private</div></div>`;
  }
}

window.openEditProfileModal = () => {
  const p = userProfile;
  document.getElementById('edit-fullname').value  = p.displayName||currentUser?.displayName||'';
  document.getElementById('edit-username').value  = p.username||'';
  document.getElementById('edit-bio').value       = p.bio||'';
  document.getElementById('edit-location').value  = p.location||'';
  document.getElementById('edit-website').value   = p.website||'';
  document.getElementById('edit-profile-modal').classList.add('open');
  setTimeout(() => document.getElementById('edit-fullname').focus(), 100);
};

window.saveProfile = async () => {
  const fullName = document.getElementById('edit-fullname').value.trim();
  const rawUser  = document.getElementById('edit-username').value.trim().toLowerCase().replace(/[^a-z0-9_]/g,'');
  if (!fullName) { toast('Name cannot be empty','⚠️'); return; }
  if (!rawUser)  { toast('Username cannot be empty','⚠️'); return; }
  userProfile = {...userProfile, displayName:fullName, username:rawUser,
    bio:document.getElementById('edit-bio').value.trim(),
    location:document.getElementById('edit-location').value.trim(),
    website:document.getElementById('edit-website').value.trim()};
  try { await setUserProfile(userProfile); applyProfileToUI(); closeModal('edit-profile-modal'); toast('Profile updated! ✨','👤'); }
  catch(e) { toast('Error: '+e.message,'❌'); }
};

window.openAvatarModal = () => {
  selectedAvatarUrl = userProfile.avatarUrl||currentUser?.photoURL||'';
  const grid = document.getElementById('avatar-preset-grid');
  grid.innerHTML = AVATAR_PRESETS.map((url,i) =>
    `<img src="${url}" class="avatar-option ${selectedAvatarUrl===url?'selected':''}" onclick="selectAvatarPreset('${url}',this)" alt="Avatar ${i+1}">`
  ).join('');
  const fi = document.getElementById('avatar-upload');
  fi.value = '';
  fi.onchange = async e => {
    const file = e.target.files[0]; if (!file) return;
    toast('Compressing…','📷');
    try { selectedAvatarUrl = await compressImageTo(file, 80*1024); toast('Avatar ready!','✅'); }
    catch { toast('Could not read image','❌'); }
  };
  document.getElementById('avatar-modal').classList.add('open');
};

window.selectAvatarPreset = (url, el) => {
  selectedAvatarUrl = url;
  document.querySelectorAll('.avatar-option').forEach(o=>o.classList.remove('selected'));
  el.classList.add('selected');
};

window.saveAvatar = async () => {
  if (!selectedAvatarUrl) { toast('Pick an avatar first','⚠️'); return; }
  userProfile.avatarUrl = selectedAvatarUrl;
  try { await setUserProfile(userProfile); applyProfileToUI(); closeModal('avatar-modal'); toast('Avatar updated!','🖼'); }
  catch(e) { toast('Error: '+e.message,'❌'); }
};

/* ══ COVER MODAL ═════════════════════════════════════════════ */
window.openCoverModal = () => {
  selectedCoverData = { value: userProfile.coverGradient || COVER_PRESETS[0] };
  const grid = document.getElementById('cover-preset-grid');
  grid.innerHTML = COVER_PRESETS.map((grad) =>
    `<div class="cover-preset ${userProfile.coverGradient===grad?'selected':''}"
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
  return { r:parseInt(hex.slice(1,3),16), g:parseInt(hex.slice(3,5),16), b:parseInt(hex.slice(5,7),16) };
}
function darken(hex, factor) {
  const {r,g,b} = hexToRgb(hex);
  const d = v => Math.round(Math.max(0, v*factor)).toString(16).padStart(2,'0');
  return `#${d(r)}${d(g)}${d(b)}`;
}
function hexToGradient(hex) {
  return `linear-gradient(135deg,${darken(hex,0.18)},${darken(hex,0.55)},${darken(hex,0.1)})`;
}
function previewHexGradient(hex) {
  const grad = hexToGradient(hex);
  document.getElementById('cover-live-preview').style.background = grad;
  document.querySelectorAll('.cover-preset').forEach(p=>p.classList.remove('selected'));
  selectedCoverData = { value: grad };
}
window.applyHexCover = () => {
  const textIn = document.getElementById('hex-text-input');
  const wheel  = document.getElementById('hex-color-wheel');
  let hex = textIn.value.trim();
  if (/^#[0-9a-fA-F]{3}$/.test(hex)) hex = '#'+hex[1]+hex[1]+hex[2]+hex[2]+hex[3]+hex[3];
  if (!/^#[0-9a-fA-F]{6}$/.test(hex)) hex = wheel.value;
  previewHexGradient(hex);
  toast('Preview updated!','🎨');
};
window.selectCoverPreset = (grad, el) => {
  selectedCoverData = { value: grad };
  document.querySelectorAll('.cover-preset').forEach(p=>p.classList.remove('selected'));
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
    toast('Cover updated!','🖼');
  } catch(e) { toast('Error: '+e.message,'❌'); }
};

/* ══ SETTINGS ════════════════════════════════════════════════ */
window.exportUserData = () => { exportAsJSON(); };

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
          if (w > h) { if (w > maxDim) { h = Math.round(h*maxDim/w); w = maxDim; } }
          else { if (h > maxDim) { w = Math.round(w*maxDim/h); h = maxDim; } }
          canvas.width = w; canvas.height = h;
          ctx.clearRect(0,0,w,h); ctx.drawImage(img,0,0,w,h);
          result = canvas.toDataURL('image/jpeg', quality);
          if (result.length*0.75 <= targetBytes) break;
          if (quality > 0.3) quality -= 0.12; else { maxDim = Math.round(maxDim*0.75); quality = 0.7; }
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
  return new Date(d).toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'});
}
function esc(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
window.toast = (msg, icon='✨') => {
  const t = document.createElement('div');
  t.className = 'toast';
  t.innerHTML = `<span>${icon}</span><span>${msg}</span>`;
  document.getElementById('toast-container').appendChild(t);
  setTimeout(() => { t.classList.add('out'); setTimeout(()=>t.remove(), 300); }, 3000);
};

/* ══ LEARNING RESOURCES ══════════════════════════════════════ */
let learningResourcesInitialized = false;
async function initializeLearningResources() {
  if (learningResourcesInitialized) return;
  learningResourcesInitialized = true;
  try {
    const snap = await getDocs(collection(db,'learning_resources'));
    if (snap.docs.length > 0) return;
    const sampleResources = [
      { title:'Python for Data Science', type:'course', platform:'Coursera', difficulty:'Intermediate', rating:4.8, link:'https://coursera.org/learn/python-for-data-science', description:'Complete Python course for data science applications', tags:['python','data science','programming'] },
      { title:'Learn Machine Learning', type:'course', platform:'Coursera', difficulty:'Advanced', rating:4.9, link:'https://coursera.org/specializations/machine-learning', description:'Deep dive into ML algorithms and applications', tags:['machine learning','ai','data science'] },
      { title:'Web Development Bootcamp', type:'course', platform:'Udemy', difficulty:'Beginner', rating:4.7, link:'https://udemy.com/web-development', description:'Full stack web development from scratch', tags:['web development','javascript','html','css'] },
      { title:'React.js Tutorial', type:'tutorial', platform:'YouTube', difficulty:'Intermediate', rating:4.6, link:'https://youtube.com/react-tutorial', description:'Learn modern React development', tags:['react','javascript','web development'] },
      { title:'The Complete SQL Bootcamp', type:'course', platform:'Udemy', difficulty:'Beginner', rating:4.8, link:'https://udemy.com/sql-bootcamp', description:'Master SQL and database design', tags:['sql','database','programming'] },
      { title:'AI Engineering Guide', type:'article', platform:'Medium', difficulty:'Intermediate', rating:4.5, link:'https://medium.com/ai-engineering', description:'Best practices for AI engineering', tags:['ai','machine learning','engineering'] },
      { title:'Cloud Computing with AWS', type:'course', platform:'Linux Academy', difficulty:'Intermediate', rating:4.7, link:'https://linuxacademy.com/aws', description:'AWS cloud services and deployment', tags:['aws','cloud','devops'] },
      { title:'JavaScript ES6+ Guide', type:'article', platform:'Dev.to', difficulty:'Intermediate', rating:4.6, link:'https://dev.to/javascript', description:'Modern JavaScript best practices', tags:['javascript','programming','web development'] },
      { title:'UI/UX Design Principles', type:'course', platform:'Skillshare', difficulty:'Beginner', rating:4.7, link:'https://skillshare.com/ui-ux', description:'Fundamentals of UI/UX design', tags:['design','ui','ux'] },
      { title:'Git & GitHub Essentials', type:'tutorial', platform:'GitHub Learning', difficulty:'Beginner', rating:4.8, link:'https://github.com/skills', description:'Version control and collaboration', tags:['git','github','programming'] }
    ];
    for (const resource of sampleResources) {
      await addDoc(collection(db,'learning_resources'), resource);
    }
  } catch(e) { console.warn('Error initializing learning resources:', e.message); }
}