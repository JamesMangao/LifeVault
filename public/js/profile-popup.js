// profile-popup.js
// handles sidebar profile dropdown

window.toggleProfilePopup = function(e) {
    if (e) e.stopPropagation();
    const pop = document.getElementById('profile-popup');
    if (!pop) return;
    if (pop.classList.contains('open')) {
        closeProfilePopup();
    } else {
        // populate fields from currentUser if available
        if (window.currentUser) {
            const u = window.currentUser;
            const avatar = document.getElementById('pp-avatar');
            const nameEl = document.getElementById('pp-name');
            const emailEl = document.getElementById('pp-email');
            if (avatar) avatar.src = u.photoURL || '';
            if (nameEl) nameEl.textContent = u.displayName || '';
            if (emailEl) emailEl.textContent = u.email || '';
        }
        pop.classList.add('open');
        document.addEventListener('click', profileOutsideListener);
    }
};

window.closeProfilePopup = function() {
    const pop = document.getElementById('profile-popup');
    if (pop) pop.classList.remove('open');
    document.removeEventListener('click', profileOutsideListener);
};

function profileOutsideListener(ev) {
    const pop = document.getElementById('profile-popup');
    if (pop && !pop.contains(ev.target)) {
        closeProfilePopup();
    }
}

// close popup when user navigates manually
window.addEventListener('navigate', () => {
    closeProfilePopup();
});
