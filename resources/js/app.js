import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {

    const userId = document.querySelector('meta[name="user-id"]')?.content;

    if (!userId) {
        console.warn('Guest user, skipping Echo.');
        return;
    }

    if (typeof window.Echo === 'undefined') {
        console.error('Echo not initialized.');
        return;
    }

    const BootstrapToast = window.bootstrap?.Toast ?? null;
    if (!BootstrapToast) {
        console.error('Bootstrap Toast not found.');
        return;
    }

    // private('channel') .notification() + leading dot + broadcastAs name
    window.Echo.private('admin')
        .notification((notification) => {
            console.log(notification);

            if (notification.type === 'order_created') {
                incrementBadge();
                prependNotification(notification);
                showToast(notification, BootstrapToast);
            }
        });

    // ── Badge ─────────────────────────────────────────────────
    function incrementBadge() {
        let badge = document.getElementById('notification-badge');

        if (!badge) {
            const toggleLink = document.querySelector('a[data-bs-toggle="dropdown"] .ri-notification-line')?.closest('a');
            if (!toggleLink) return;

            badge = document.createElement('span');
            badge.id = 'notification-badge';
            badge.className = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
            badge.textContent = '0';
            toggleLink.appendChild(badge);
        }

        const current = parseInt(badge.textContent.trim()) || 0;
        badge.textContent = current + 1;
        badge.classList.remove('d-none', 'hidden');
    }

    // ── Prepend notification to dropdown ──────────────────────
    function prependNotification(notification) {
        const list = document.getElementById('notification-list');
        if (!list) return;

        const emptyState = list.querySelector('[data-empty-state]');
        if (emptyState) emptyState.remove();

        ensureMarkAllBtn();

        const notifId = notification.id ?? ('rt-' + Date.now());

        const li = document.createElement('li');
        li.className = 'list-group-item p-2 bg-light border-start border-3 border-primary';
        li.id = 'notif-item-' + notifId;

        li.innerHTML = `
            <div class="d-block text-dark text-decoration-none mark-one-read"
                 style="cursor: pointer;"
                 data-id="${escapeHtml(String(notifId))}"
                 data-url="${escapeHtml(notification.url ?? '#')}">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-1">${escapeHtml(notification.title ?? 'Notification')}</h6>
                    <span class="badge bg-primary notif-new-badge">new</span>
                </div>
                <p class="mb-0 small text-muted">${escapeHtml(notification.message ?? '')}</p>
                <small class="text-muted">Just now</small>
            </div>
        `;

        list.prepend(li);
    }

    // ── Mark-one-read (delegated) ─────────────────────────────
    document.getElementById('notification-list')
        ?.addEventListener('click', function (e) {
            const target = e.target.closest('.mark-one-read');
            if (!target) return;

            const id   = target.dataset.id;
            const url  = target.dataset.url;
            const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            // Skip fake real-time IDs that aren't in the DB
            if (id.startsWith('rt-')) {
                if (url && url !== '#') window.location.href = url;
                return;
            }

            fetch(`/admin/notifications/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                },
            }).then(res => {
                if (!res.ok) return;
                const li = document.getElementById('notif-item-' + id);
                li?.classList.remove('bg-light', 'border-start', 'border-3', 'border-primary');
                li?.querySelector('.notif-new-badge')?.remove();
                if (url && url !== '#') window.location.href = url;
            });
        });

    // ── Ensure "Mark all" button exists ───────────────────────
    function ensureMarkAllBtn() {
        if (document.getElementById('markAllRead')) return;

        const headerDiv = document.querySelector('.dropdown-menu-header .dropdown-header');
        if (!headerDiv) return;

        const btn = document.createElement('button');
        btn.id = 'markAllRead';
        btn.className = 'btn btn-sm btn-outline-primary';
        btn.textContent = 'Mark all';
        btn.dataset.url = '/admin/notifications/mark-all-read';
        headerDiv.appendChild(btn);

        attachMarkAllHandler(btn);
    }

    // ── Toast ─────────────────────────────────────────────────
    function showToast(notification, BootstrapToast) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = 9999;
            document.body.appendChild(container);
        }

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-bg-${notification.color ?? 'primary'} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bx bx-${notification.icon ?? 'bell'} fs-5"></i>
                    <div>
                        <strong>${escapeHtml(notification.title)}</strong><br>
                        <small>${escapeHtml(notification.message)}</small>
                    </div>
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Close">
                </button>
            </div>
        `;

        container.appendChild(toastEl);
        const toast = new BootstrapToast(toastEl, { delay: 6000 });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    // ── XSS guard ─────────────────────────────────────────────
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    window._notifEscapeHtml = escapeHtml;
});

// ── Mark-all handler ──────────────────────────────────────────
function attachMarkAllHandler(btn) {
    if (!btn) return;
    btn.addEventListener('click', function () {
        this.setAttribute('disabled', 'disabled');
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        fetch(btn.dataset.url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Content-Type': 'application/json',
            },
        })
        .then(res => {
            if (!res.ok) throw new Error();

            document.querySelectorAll('.list-group-item').forEach(li => {
                li.classList.remove('bg-light', 'border-start', 'border-3', 'border-primary');
            });
            document.querySelectorAll('.notif-new-badge').forEach(b => b.remove());

            const badge = document.getElementById('notification-badge');
            if (badge) badge.remove();

            this.remove();
        })
        .catch(() => {
            this.removeAttribute('disabled');
            alert('Could not update notifications.');
        });
    });
}