<style>
/* ── Confirm overlay ─────────────────────────────────────────── */
#confirm-modal-overlay {
    position: fixed; inset: 0; z-index: 10999;
    background: rgba(8,10,18,.82);
    backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    display: none; align-items: center; justify-content: center;
    padding: 20px; box-sizing: border-box;
}
#confirm-modal-box {
    background: linear-gradient(145deg, rgba(15,18,35,.98), rgba(20,25,48,.96));
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 24px;
    width: 100%; max-width: 440px;
    padding: 32px;
    box-shadow: 0 40px 100px rgba(0,0,0,.6), inset 0 1px 0 rgba(255,255,255,.05);
    transform: scale(.95) translateY(16px);
    opacity: 0;
    transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .2s ease;
}
#confirm-modal-box.cm-open {
    transform: scale(1) translateY(0);
    opacity: 1;
}
.cm-emoji  { font-size: 1.6rem; margin-bottom: 10px; }
.cm-title  { font-size: 1.1rem; font-weight: 800; color: #f0f2ff; margin-bottom: 8px; letter-spacing: -0.02em; }
.cm-body   {
    font-family: 'Newsreader', serif;
    font-size: .9rem; color: rgba(232,234,240,.7);
    line-height: 1.65; margin-bottom: 24px;
    font-weight: 300;
}
.cm-body strong { color: #f0f2ff; font-weight: 600; }
.cm-footer { display: flex; gap: 10px; justify-content: flex-end; }
.cm-cancel-btn {
    background: transparent;
    border: 1px solid var(--border, rgba(255,255,255,.1));
    color: var(--muted); padding: 8px 16px; border-radius: 8px;
    font-family: 'JetBrains Mono', monospace; font-size: .72rem;
    font-weight: 600; cursor: pointer; transition: all .15s;
}
.cm-cancel-btn:hover { background: rgba(255,255,255,.05); color: var(--text); }
.cm-confirm-btn {
    border: none; padding: 8px 20px; border-radius: 8px;
    font-family: 'JetBrains Mono', monospace; font-size: .72rem;
    font-weight: 700; cursor: pointer;
    display: flex; align-items: center; gap: 6px;
    transition: all .18s;
}
.cm-confirm-btn.cm-teal {
    background: var(--teal, #2dd4bf); color: #0b0f1a;
}
.cm-confirm-btn.cm-teal:hover { filter: brightness(1.1); box-shadow: 0 4px 14px rgba(45,212,191,.3); }
.cm-confirm-btn.cm-danger {
    background: rgba(239,68,68,.9); color: white;
}
.cm-confirm-btn.cm-danger:hover { background: rgba(239,68,68,1); box-shadow: 0 4px 14px rgba(239,68,68,.35); }
.cm-confirm-btn:disabled { opacity: .55; cursor: not-allowed; filter: none; box-shadow: none; }

@media (max-width: 480px) {
    #confirm-modal-overlay { align-items: flex-end; padding: 0; }
    #confirm-modal-box { border-radius: 18px 18px 0 0; max-width: 100%; }
}
</style>

<div id="confirm-modal-overlay">
    <div id="confirm-modal-box">
        <div class="cm-emoji" id="cm-emoji">⚠️</div>
        <div class="cm-title" id="cm-title"></div>
        <div class="cm-body"  id="cm-body"></div>
        <div class="cm-footer">
            <button class="cm-cancel-btn"  id="cm-cancel-btn">Cancel</button>
            <button class="cm-confirm-btn" id="cm-confirm-btn"></button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var overlay    = document.getElementById('confirm-modal-overlay');
    var box        = document.getElementById('confirm-modal-box');
    var emojiEl    = document.getElementById('cm-emoji');
    var titleEl    = document.getElementById('cm-title');
    var bodyEl     = document.getElementById('cm-body');
    var cancelBtn  = document.getElementById('cm-cancel-btn');
    var confirmBtn = document.getElementById('cm-confirm-btn');

    var _callback  = null;
    var _escHandler = null;

    function open(opts) {
        opts = opts || {};
        emojiEl.textContent   = opts.emoji   || '⚠️';
        titleEl.textContent   = opts.title   || 'Are you sure?';
        bodyEl.innerHTML      = opts.body    || '';
        confirmBtn.textContent = opts.confirm || 'Confirm';
        confirmBtn.disabled   = false;

        confirmBtn.className  = 'cm-confirm-btn ' + (opts.danger ? 'cm-danger' : 'cm-teal');
        _callback = opts.onConfirm || null;

        overlay.style.display = 'flex';
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { box.classList.add('cm-open'); });
        });

        _escHandler = function (e) { if (e.key === 'Escape') close(); };
        document.addEventListener('keydown', _escHandler);
    }

    function close() {
        box.classList.remove('cm-open');
        setTimeout(function () { overlay.style.display = 'none'; }, 240);
        if (_escHandler) { document.removeEventListener('keydown', _escHandler); _escHandler = null; }
    }

    overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
    cancelBtn.addEventListener('click', close);

    confirmBtn.addEventListener('click', function () {
        if (typeof _callback === 'function') {
            confirmBtn.disabled = true;
            _callback(close);   // pass close() so async callbacks can close when done
        } else {
            close();
        }
    });

    /* Public API */
    window.confirmAction = open;
    window._confirmModalClose = close;
})();
</script>