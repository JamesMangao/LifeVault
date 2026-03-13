{{-- resources/views/profile.blade.php --}}
<style>
/* ── PROFILE PAGE STYLES ─────────────────────────────────────── */
.profile-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:24px}
.profile-cover{height:180px;border-radius:16px 16px 0 0;position:relative;overflow:hidden;cursor:pointer;display:block;min-height:180px}
.profile-cover::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 30% 50%,rgba(255,255,255,.18) 0%,transparent 60%),radial-gradient(circle at 80% 20%,rgba(255,255,255,.1) 0%,transparent 50%)}
.profile-cover-edit{position:absolute;top:12px;right:12px;padding:6px 10px;font-size:.7rem;font-weight:600;background:rgba(0,0,0,.4);color:white;border-radius:8px;border:1px solid rgba(255,255,255,.2);cursor:pointer;transition:background .2s}
.profile-cover-edit:hover{background:rgba(79,142,247,.4)}
.profile-identity{padding:0 28px 24px;position:relative}
.profile-avatar-wrap{position:relative;display:inline-block !important;margin-top:-44px;margin-bottom:12px;line-height:0}
#profile-avatar-large{width:88px !important;height:88px !important;min-width:88px !important;min-height:88px !important;max-width:88px !important;max-height:88px !important;border-radius:50% !important;object-fit:cover !important;display:block !important;flex-shrink:0 !important;border:3px solid var(--surface) !important;background:var(--surface2)}
.profile-avatar-edit-btn{position:absolute;bottom:2px;right:2px;width:26px;height:26px;border-radius:50%;background:var(--accent);border:2px solid var(--surface);color:white;font-size:.7rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s}
.profile-display-name{font-size:1.5rem;font-weight:800;letter-spacing:-.03em;line-height:1.1}
.profile-username{font-family:'JetBrains Mono',monospace;font-size:.78rem;color:var(--accent);margin-top:4px}
.profile-bio{font-family:'Newsreader',serif;font-size:.9rem;color:rgba(232,234,240,.7);font-weight:300;line-height:1.6;margin-top:10px;max-width:520px}
.profile-badges{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.profile-badge{font-family:'JetBrains Mono',monospace;font-size:.6rem;padding:3px 10px;border-radius:99px;text-transform:uppercase;letter-spacing:.08em;border:1px solid}
.profile-stats-row{display:grid !important;grid-template-columns:repeat(4,1fr) !important;border-top:1px solid var(--border);margin-top:20px}
.profile-stat{display:block !important;padding:16px;text-align:center;border-right:1px solid var(--border)}
.profile-stat:last-child{border-right:none}
.profile-stat-val{display:block !important;font-size:1.4rem;font-weight:800;letter-spacing:-.03em;line-height:1;margin-bottom:4px}
.profile-stat-label{display:block !important;font-family:'JetBrains Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)}
.profile-section-title{display:flex;align-items:center;gap:12px;font-size:.8rem;font-weight:700;margin-bottom:12px;color:var(--text)}
.profile-section-title::after{content:'';flex:1;height:1px;background:var(--border)}
.loading-posts,.no-posts-found{padding:24px;text-align:center;font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--muted)}
.avatar-picker-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin:12px 0}
.avatar-option{width:100%;aspect-ratio:1;border-radius:50%;border:2px solid var(--border);cursor:pointer;object-fit:cover;transition:border-color .2s,transform .2s}
.avatar-option:hover,.avatar-option.selected{border-color:var(--accent);transform:scale(1.08)}
.cover-preset-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:16px}
.cover-preset{height:48px;border-radius:8px;border:2px solid var(--border);cursor:pointer;transition:border-color .2s,transform .15s;position:relative;overflow:hidden}
.cover-preset::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 30% 50%,rgba(255,255,255,.2) 0%,transparent 60%)}
.cover-preset:hover,.cover-preset.selected{border-color:var(--accent);transform:scale(1.04)}
.cover-preset.selected::after{content:'✓';position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-weight:800;color:white;font-size:.9rem;background:rgba(79,142,247,.35)}
.hex-input-section{border-top:1px solid var(--border);padding-top:16px;margin-top:4px}
.hex-input-row{display:flex;gap:10px;align-items:center}
.hex-input{flex:1;width:100%;padding:8px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;font-family:'JetBrains Mono',monospace;font-size:.8rem;color:var(--text)}
@media(max-width:600px){.cover-preset-grid{grid-template-columns:repeat(3,1fr)}}
</style>

{{-- ═══════════ PROFILE PAGE ═══════════ --}}
<div id="page-profile" class="page">
  <div class="page-header">
    <div>
      <div class="page-title">Profile</div>
      <div class="page-subtitle">Your identity in LifeVault</div>
    </div>
    <button class="btn btn-primary" onclick="openEditProfileModal()">✎ Edit Profile</button>
  </div>

  <div class="profile-card">
    <div class="profile-cover" id="profile-cover-display">
      <button class="profile-cover-edit" onclick="openCoverModal()">🖼 Change Cover</button>
    </div>
    <div class="profile-identity">
      <div class="profile-avatar-wrap">
        <img id="profile-avatar-large" class="profile-avatar-large" src="" alt="Profile photo">
        <button class="profile-avatar-edit-btn" onclick="openAvatarModal()">✎</button>
      </div>
      <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div>
          <div class="profile-display-name" id="profile-display-name">—</div>
          <div class="profile-username" id="profile-username-display">@—</div>
        </div>
      </div>
      <div class="profile-bio" id="profile-bio-display">No bio yet.</div>

      <div class="profile-badges" id="profile-badges"></div>
      <div class="profile-stats-row">
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-journals" style="color:var(--accent)">0</span>
          <span class="profile-stat-label">Journals</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-tasks" style="color:var(--green)">0</span>
          <span class="profile-stat-label">Tasks</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-goals" style="color:var(--lavender)">0</span>
          <span class="profile-stat-label">Goals</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-val" id="pstat-posts" style="color:var(--teal)">0</span>
          <span class="profile-stat-label">Posts</span>
        </div>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:24px">
    <div class="profile-section-title">🌐 My Community Posts</div>
    <div id="profile-my-posts">
      <div class="loading-posts">Loading your posts…</div>
    </div>
  </div>
</div>

{{-- ═══════════ EDIT PROFILE MODAL ═══════════ --}}
<div class="modal-backdrop" id="edit-profile-modal">
  <div class="modal" style="max-width:560px">
    <div class="modal-header">
      <div class="modal-title">Edit Your Profile</div>
      <button class="modal-close" onclick="closeModal('edit-profile-modal')">&times;</button>
    </div>
    <div class="modal-body">

      <div class="form-group">
        <label class="form-label" for="edit-fullname">Full Name</label>
        <input type="text" id="edit-fullname" class="form-input" placeholder="Your display name" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="edit-username">Username</label>
        <input type="text" id="edit-username" class="form-input" placeholder="yourhandle"
               pattern="^[a-z0-9_]{3,20}$" required>
        <small style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);margin-top:5px;display:block">
          3–20 characters · lowercase letters, numbers, underscores only
        </small>
      </div>

      <div class="form-group">
        <label class="form-label" for="edit-bio">Bio</label>
        <textarea id="edit-bio" class="form-textarea" rows="3" maxlength="160"
                  placeholder="Tell the community a little about yourself…"></textarea>
        <small style="font-family:'JetBrains Mono',monospace;font-size:.62rem;color:var(--muted);margin-top:5px;display:block">
          Max 160 characters
        </small>
      </div>

      <div class="form-group">
        <label class="form-label" for="edit-location">Location</label>
        <input type="text" id="edit-location" class="form-input" placeholder="City, Country">
      </div>

      <div class="form-group" style="margin-bottom:0">
        <label class="form-label" for="edit-website">Website</label>
        <input type="url" id="edit-website" class="form-input" placeholder="https://yoursite.com">
      </div>

    </div>
    <div class="modal-footer" style="display:flex;justify-content:flex-end;gap:10px;padding-top:20px;margin-top:20px;border-top:1px solid var(--border)">
      <button class="btn" onclick="closeModal('edit-profile-modal')">Cancel</button>
      <button class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
    </div>
  </div>
</div>

{{-- ═══════════ AVATAR MODAL ═══════════ --}}
<div class="modal-backdrop" id="avatar-modal">
  <div class="modal" style="max-width:480px">
    <div class="modal-header">
      <div class="modal-title">Choose Your Avatar</div>
      <button class="modal-close" onclick="closeModal('avatar-modal')">&times;</button>
    </div>
    <div class="modal-body">
      <p style="font-size:.8rem;color:var(--muted);margin-bottom:12px;">Select a preset or upload your own image.</p>
      <div class="avatar-picker-grid" id="avatar-picker-grid">
        {{-- Presets will be loaded here by JS --}}
      </div>
      <div class="form-group">
        <label for="avatar-upload" class="btn" style="width:100%;text-align:center;display:block;">📤 Upload Image</label>
        <input type="file" id="avatar-upload" accept="image/jpeg,image/png,image/webp" style="display:none;">
        <small>Recommended: Square image, max 2MB.</small>
      </div>
      <div id="avatar-upload-spinner" style="display:none;text-align:center;margin-top:10px;">Uploading and processing...</div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('avatar-modal')">Cancel</button>
      <button class="btn btn-primary" onclick="saveAvatar()">Set as Avatar</button>
    </div>
  </div>
</div>

{{-- ═══════════ COVER MODAL ═══════════ --}}
<div class="modal-backdrop" id="cover-modal">
  <div class="modal" style="max-width:480px">
    <div class="modal-header">
      <div class="modal-title">Set Cover Image</div>
      <button class="modal-close" onclick="closeModal('cover-modal')">&times;</button>
    </div>
    <div class="modal-body">
      <p style="font-size:.8rem;color:var(--muted);margin-bottom:12px;">Choose a preset gradient or set custom colors.</p>
      <div class="cover-preset-grid" id="cover-preset-grid"></div>
      <div class="hex-input-section">
        <label style="font-size:.8rem;font-weight:600;margin-bottom:8px;display:block;">Custom Gradient</label>
        <div class="hex-input-row">
          <input type="text" id="cover-hex-1" class="hex-input" placeholder="#RRGGBB">
          <input type="text" id="cover-hex-2" class="hex-input" placeholder="#RRGGBB">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('cover-modal')">Cancel</button>
      <button class="btn btn-primary" onclick="saveCover()">Set Cover</button>
    </div>
  </div>
</div>

<script>
(function() {
  /* ── Wait for FirebaseReady, then init ─────────────────────── */
  function initProfilePage() {
    const $ = id => document.getElementById(id);

    const COVER_PRESETS = [
      '135deg, #2E3168 0%, #1B2A47 100%', '135deg, #6B3A73 0%, #342E5C 100%',
      '135deg, #A94358 0%, #58335E 100%', '135deg, #306A6E 0%, #2A4858 100%',
      '135deg, #B06B4C 0%, #614A51 100%', '135deg, #8E44AD 0%, #3498DB 100%',
      '135deg, #16A085 0%, #F4D03F 100%', '135deg, #D35400 0%, #C0392B 100%'
    ];
    const AVATAR_PRESETS = [
      '/img/avatars/01.png', '/img/avatars/02.png', '/img/avatars/03.png',
      '/img/avatars/04.png', '/img/avatars/05.png', '/img/avatars/06.png',
      '/img/avatars/07.png', '/img/avatars/08.png', '/img/avatars/09.png',
      '/img/avatars/10.png'
    ];
    let selectedAvatar = null;
    let selectedCover  = null;

    /* ── applyProfileToUI ── */
    window.applyProfileToUI = function() {
      const p  = window.userProfile || {};
      const cu = getCurrentUser();
      const av = _currentAvatarUrl();

      if ($('user-name'))           $('user-name').textContent           = p.displayName || cu?.displayName || '—';
      if ($('user-avatar'))         $('user-avatar').src                 = av;
      if ($('composer-avatar'))     $('composer-avatar').src             = av;
      if ($('profile-avatar-large'))$('profile-avatar-large').src        = av;
      if ($('profile-display-name'))$('profile-display-name').textContent = p.displayName || cu?.displayName || '—';
      if ($('profile-username-display')) $('profile-username-display').textContent = '@' + (p.username || '—');
      if ($('profile-bio-display')) $('profile-bio-display').textContent = p.bio || 'No bio yet.';
      if ($('profile-cover-display')) {
        $('profile-cover-display').style.background = 'linear-gradient(' + (p.coverGradient || COVER_PRESETS[0]) + ')';
      }
    };

    /* ── renderProfilePage ── */
    window.renderProfilePage = function() {
      const p  = window.userProfile || {};
      const cu = getCurrentUser();
      if (!cu) return;

      // Apply all profile UI fields
      applyProfileToUI();

      // Stats
      if ($('pstat-journals')) $('pstat-journals').textContent = window.journals?.length  || 0;
      if ($('pstat-tasks'))    $('pstat-tasks').textContent    = window.tasks?.length     || 0;
      if ($('pstat-goals'))    $('pstat-goals').textContent    = window.goals?.length     || 0;
      if ($('pstat-posts'))    $('pstat-posts').textContent    = (window.feedPosts || []).filter(post => post.authorId === cu.uid).length;

      // My Posts
      const myPostsContainer = $('profile-my-posts');
      if (myPostsContainer) {
        const myPosts = (window.feedPosts || []).filter(post => post.authorId === cu.uid);
        if (myPosts.length > 0) {
          myPostsContainer.innerHTML = myPosts.map(post => renderPost(post, false)).join('');
        } else {
          myPostsContainer.innerHTML = '<div class="no-posts-found">You haven\'t posted anything in the community yet.</div>';
        }
      }
    };

    /* ── Edit profile ── */
    window.openEditProfileModal = function() {
      const p  = window.userProfile || {};
      const cu = getCurrentUser();
      $('edit-fullname').value = p.displayName || cu?.displayName || '';
      $('edit-username').value = p.username    || '';
      $('edit-bio').value      = p.bio         || '';
      $('edit-location').value = p.location    || '';
      $('edit-website').value  = p.website     || '';
      $('edit-profile-modal').classList.add('open');
    };

    window.saveProfile = async function() {
      const cu = getCurrentUser();
      if (!cu) return;

      const newName     = $('edit-fullname').value.trim();
      const newUsername = $('edit-username').value.trim();

      if (!newName || !newUsername) {
        showToast('Full Name and Username are required.', 'error');
        return;
      }

      const profileData = {
        displayName: newName,
        username:    newUsername,
        bio:         $('edit-bio').value.trim(),
        location:    $('edit-location').value.trim(),
        website:     $('edit-website').value.trim(),
      };

      await _setUserProfile(profileData);
      window.userProfile = { ...window.userProfile, ...profileData };
      
      // FIXED: Refresh global app.js state + trigger re-renders to prevent stale snapshots in new posts
      if (window.currentUser) {
        window.currentUser.displayName = newName;
        if (typeof window.applyProfileToUI === 'function') window.applyProfileToUI();
        // Trigger auth listener refresh for persistence
        window.auth?.currentUser?.reload();
      }
      
      applyProfileToUI();
      showToast('Profile updated successfully!');
      closeModal('edit-profile-modal');
      _syncProfileUpdateToPosts(newName, _currentAvatarUrl());

      // Also update the Firebase Auth profile
      const { updateProfile } = window._fbAU;
      const cu = getCurrentUser();
      if (cu) {
        try {
          await updateProfile(cu, { displayName: newName });
        } catch (e) {
          console.warn('Failed to update auth profile displayName', e.message);
        }
      }
    };

    /* ── Avatar ── */
    window.openAvatarModal = function() {
      selectedAvatar = _currentAvatarUrl();
      const grid     = $('avatar-picker-grid');
      grid.innerHTML = AVATAR_PRESETS.map(url =>
        `<img src="${url}" class="avatar-option ${url === selectedAvatar ? 'selected' : ''}" onclick="selectAvatar(this,'${url}')">`
      ).join('');
      $('avatar-modal').classList.add('open');
    };

    window.selectAvatar = function(el, url) {
      document.querySelectorAll('.avatar-option').forEach(opt => opt.classList.remove('selected'));
      el.classList.add('selected');
      selectedAvatar = url;
    };

    $('avatar-upload').addEventListener('change', async (e) => {
      const file = e.target.files[0];
      if (!file) return;

      $('avatar-upload-spinner').style.display = 'block';
      try {
        const resizedDataUrl = await _resizeImage(file, 400, 0.85, 100 * 1024);
        const storagePath    = `avatars/${getCurrentUser().uid}/${Date.now()}.jpg`;
        const { uploadString, ref, getDownloadURL } = window._fbST;
        const storageRef = ref(window.storage, storagePath);

        await uploadString(storageRef, resizedDataUrl, 'data_url');
        const downloadURL = await getDownloadURL(storageRef);

        const newImg = document.createElement('img');
        newImg.src       = downloadURL;
        newImg.className = 'avatar-option selected';
        newImg.onclick   = () => window.selectAvatar(newImg, downloadURL);
        $('avatar-picker-grid').appendChild(newImg);
        selectedAvatar = downloadURL;

      } catch (error) {
        console.error('Avatar upload error:', error);
        showToast('Failed to upload avatar. ' + error.message, 'error');
      } finally {
        $('avatar-upload-spinner').style.display = 'none';
      }
    });

    window.saveAvatar = async function() {
      if (!selectedAvatar) return;
      await _setUserProfile({ avatarUrl: selectedAvatar });
      window.userProfile.avatarUrl = selectedAvatar;
      
      // FIXED: Refresh global state
      if (window.currentUser && window.auth?.currentUser) {
        window.currentUser.photoURL = selectedAvatar;
        if (typeof window.applyProfileToUI === 'function') window.applyProfileToUI();
        window.auth.currentUser.reload();
      }
      
      applyProfileToUI();
      showToast('Avatar updated!');
      closeModal('avatar-modal');
      _syncProfileUpdateToPosts(window.userProfile.displayName, selectedAvatar);
    };

    /* ── Cover ── */
    window.openCoverModal = function() {
      selectedCover  = window.userProfile?.coverGradient || COVER_PRESETS[0];
      const grid     = $('cover-preset-grid');
      grid.innerHTML = COVER_PRESETS.map(grad =>
        `<div class="cover-preset ${grad === selectedCover ? 'selected' : ''}" style="background:linear-gradient(${grad})" onclick="selectCover(this,'${grad.replace(/'/g,'&apos;')}')"></div>`
      ).join('');
      const colors = selectedCover.match(/#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})/g) || [];
      $('cover-hex-1').value = colors[0] || '';
      $('cover-hex-2').value = colors[1] || '';
      $('cover-modal').classList.add('open');
    };

    window.selectCover = function(el, grad) {
      document.querySelectorAll('.cover-preset').forEach(opt => opt.classList.remove('selected'));
      el.classList.add('selected');
      selectedCover = grad;
      const colors = grad.match(/#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})/g) || [];
      $('cover-hex-1').value = colors[0] || '';
      $('cover-hex-2').value = colors[1] || '';
    };

    window.saveCover = async function() {
      const hex1 = $('cover-hex-1').value.trim();
      const hex2 = $('cover-hex-2').value.trim();
      if (hex1 && hex2 && /^#[0-9a-fA-F]{6}$/.test(hex1) && /^#[0-9a-fA-F]{6}$/.test(hex2)) {
        selectedCover = `135deg, ${hex1} 0%, ${hex2} 100%`;
      }
      await _setUserProfile({ coverGradient: selectedCover });
      window.userProfile.coverGradient = selectedCover;
      applyProfileToUI();
      showToast('Cover updated!');
      closeModal('cover-modal');
    };

    /* ── Modal helpers ── */
    window.closeModal = function(id) { $(id)?.classList.remove('open'); };
    document.querySelectorAll('.modal-backdrop').forEach(m =>
      m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); })
    );

    /* ── Private helpers ── */
    function _currentAvatarUrl() {
      return window.userProfile?.avatarUrl || getCurrentUser()?.photoURL || '/img/avatars/default.png';
    }

    async function updateAuthPhotoURL(url) {
      const { getAuth, updateProfile } = await import('//www.gstatic.com/firebasejs/9.6.10/firebase-auth.js');
      const auth = getAuth();
      if (auth.currentUser) {
        try {
          await updateProfile(auth.currentUser, { photoURL: url });
        } catch (error) {
          console.error("Error updating auth photo URL:", error);
        }
      }
    }

    async function _setUserProfile(data) {
      const cu = getCurrentUser();
      if (!cu) return;
      const { setDoc, doc } = window._fbFS;
      await setDoc(doc(window.db, 'users', cu.uid, 'profile', 'data'), data, { merge: true });
    }

    async function _syncProfileUpdateToPosts(newName, newAvatarUrl) {
      try {
        const { collection, query, where, getDocs, writeBatch, doc } = window._fbFS;
        const cu = getCurrentUser();
        if (!cu) return;

        const q    = query(collection(window.db, 'community_posts'), where('authorId', '==', cu.uid));
        const snap = await getDocs(q);
        if (snap.empty) return;

        const BATCH_SIZE = 450;
        let batch = writeBatch(window.db);
        let count = 0;
        for (const ds of snap.docs) {
          batch.update(doc(window.db, 'community_posts', ds.id), { authorName: newName, authorAvatar: newAvatarUrl });
          count++;
          if (count % BATCH_SIZE === 0) {
            await batch.commit();
            batch = writeBatch(window.db);
          }
        }
        if (count % BATCH_SIZE !== 0) await batch.commit();

        if (Array.isArray(window.feedPosts)) {
          window.feedPosts.forEach(p => {
            if (p.authorId === cu.uid) {
              p.authorName   = newName;
              p.authorAvatar = newAvatarUrl;
            }
          });
          if (typeof window.renderFeed         === 'function') window.renderFeed();
          if (typeof window.renderProfilePage  === 'function') window.renderProfilePage();
        }
      } catch (e) {
        console.warn('[Profile] Sync failed:', e.message);
      }
    }

    function _resizeImage(file, maxDim, quality, targetBytes) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onerror = reject;
        reader.onload  = ev => {
          const img    = new Image();
          img.onerror  = reject;
          img.onload   = () => {
            const canvas = document.createElement('canvas');
            const ctx    = canvas.getContext('2d');
            let result   = '';
            for (let i = 0; i < 8; i++) {
              let w = img.width, h = img.height;
              if (w > h) { if (w > maxDim) { h = Math.round(h * maxDim / w); w = maxDim; } }
              else        { if (h > maxDim) { w = Math.round(w * maxDim / h); h = maxDim; } }
              canvas.width  = w;
              canvas.height = h;
              ctx.clearRect(0, 0, w, h);
              ctx.drawImage(img, 0, 0, w, h);
              result = canvas.toDataURL('image/jpeg', quality);
              if (result.length * 0.75 <= targetBytes) break;
              if (quality > 0.3) quality -= 0.12;
              else { maxDim = Math.round(maxDim * 0.75); quality = 0.7; }
            }
            resolve(result);
          };
          img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
      });
    }

    /* ── Trigger initial render once profile data is available ── */
    function _tryRender() {
      if (window.userProfile && getCurrentUser()) {
        renderProfilePage();
      }
    }

    // Attempt immediately, then retry briefly in case data arrives late
    _tryRender();
    setTimeout(_tryRender, 500);
    setTimeout(_tryRender, 1500);
  }

  /* ── Hook into app navigation so profile re-renders on visit ── */
  function _patchNavigateTo() {
    if (typeof window.navigateTo !== 'function') {
      // navigateTo not ready yet — retry
      setTimeout(_patchNavigateTo, 100);
      return;
    }
    const _prev = window.navigateTo;
    window.navigateTo = function(page, event) {
      _prev(page, event);
      if (page === 'profile') {
        setTimeout(() => {
          if (typeof window.renderProfilePage === 'function') {
            window.renderProfilePage();
          }
        }, 150);
      }
    };
  }

  /* ── Boot: wait for FirebaseReady event ──────────────────── */
  document.addEventListener('FirebaseReady', () => {
    initProfilePage();
    _patchNavigateTo();
  });

  // Fallback: if FirebaseReady already fired before this script ran
  if (window._firebaseReady) {
    initProfilePage();
    _patchNavigateTo();
  }
})();