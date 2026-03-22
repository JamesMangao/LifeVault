<div id="toast-container"></div>

<style>
/* ── Toast Notifications ─────────────────────────────────────── */
#toast-container {
    position: fixed;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 11000;
    display: flex;
    flex-direction: column;
    gap: 12px;
    pointer-events: none;
    width: 100%;
    max-width: 400px;
    padding: 0 20px;
    box-sizing: border-box;
}

.lv-toast {
    background: linear-gradient(145deg, rgba(20, 25, 48, 0.95), rgba(15, 18, 35, 0.98));
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.05);
    transform: translateY(20px) scale(0.9);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    pointer-events: all;
    cursor: default;
}

.lv-toast.show {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.lv-toast-icon {
    font-size: 1.2rem;
    flex-shrink: 0;
}

.lv-toast-message {
    font-size: 0.88rem;
    font-weight: 500;
    color: #f0f2ff;
    line-height: 1.4;
    letter-spacing: -0.01em;
}

.lv-toast-close {
    margin-left: auto;
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    font-size: 0.9rem;
    padding: 4px;
    display: flex;
    margin-right: -8px;
    transition: color 0.2s;
}

.lv-toast-close:hover {
    color: #fca5a5;
}

@media (max-width: 480px) {
    #toast-container {
        bottom: 24px;
    }
}
</style>

<script>
window.toast = function(message, icon = '✨', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'lv-toast';
    toast.innerHTML = `
        <span class="lv-toast-icon">${icon}</span>
        <span class="lv-toast-message">${message}</span>
        <button class="lv-toast-close" onclick="this.parentElement.remove()">✕</button>
    `;

    container.appendChild(toast);

    // Trigger animation
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    // Auto-remove
    if (duration > 0) {
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }
};
</script>