<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
    <a class="nav-link dropdown-toggle hide-arrow position-relative" href="#" data-bs-toggle="dropdown">
        <i class="icon-base ri ri-notification-line"></i>

        {{-- FIX #1: id changed from 'notif-badge' → 'notification-badge' to match app.js --}}
        @if($unreadCount > 0)
            <span id="notification-badge" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end py-0" style="width: 350px;">

        {{-- Header --}}
        <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
                <h6 class="mb-0 me-auto">Notifications</h6>

                @if($unreadCount > 0)
                    {{-- FIX #5: store the route URL in data-url so JS can read it
                                 when re-creating the button after real-time notifications arrive --}}
                    <button id="markAllRead"
                            class="btn btn-sm btn-outline-primary"
                            data-url="{{ route('admin.notifications.readAll') }}">
                        Mark all
                    </button>
                @endif
            </div>
        </li>

        {{-- List --}}
        <li class="dropdown-notifications-list scrollable-container" style="max-height: 300px; overflow-y: auto;">

            {{-- FIX #2: added id="notification-list" so app.js prependNotification() can find this element --}}
            <ul id="notification-list" class="list-group list-group-flush">

                @forelse($notifications as $notification)
                    <li class="list-group-item p-2 {{ $notification->read_at ? '' : 'bg-light border-start border-3 border-primary' }}"
                        id="notif-item-{{ $notification->id }}">

                        {{-- FIX #3: kept as <div class="mark-one-read"> with data-id / data-url
                                     so the delegation listener in the inline <script> below handles it correctly.
                                     Real-time items added by app.js use the same structure. --}}
                        <div class="d-block text-dark text-decoration-none mark-one-read"
                             style="cursor: pointer;"
                             data-id="{{ $notification->id }}"
                             data-url="{{ $notification->data['url'] ?? '#' }}">

                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h6>

                                @if(!$notification->read_at)
                                    <span class="badge bg-primary notif-new-badge">new</span>
                                @endif
                            </div>

                            <p class="mb-0 small text-muted">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            <small class="text-muted">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>

                        </div>

                    </li>
                @empty
                    {{-- data-empty-state lets app.js remove this placeholder when a real notification arrives --}}
                    <li class="text-center py-4 text-muted" data-empty-state>
                        🔕 No notifications yet
                    </li>
                @endforelse

            </ul>
        </li>

        {{-- Footer --}}
        <li class="dropdown-menu-footer border-top">
            <a href="{{ route('admin.notifications.index') }}"
               class="dropdown-item text-center py-2 fw-semibold">
                View all notifications
            </a>
        </li>

    </ul>
</li>

@push('scripts')
<script>
(function () {
    const CSRF     = "{{ csrf_token() }}";
    const badge    = document.getElementById('notification-badge');
    const markAllBtn = document.getElementById('markAllRead');

    // ── Mark ALL read ────────────────────────────────────────────────────────
    // FIX #5: route URL now comes from data-url attribute, not hardcoded in JS,
    //         so the re-created button (from app.js) also works correctly.
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function () {
            this.setAttribute('disabled', 'disabled');

            fetch(this.dataset.url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF }
            })
            .then(res => {
                if (!res.ok) throw new Error();

                document.querySelectorAll('.list-group-item').forEach(li => {
                    li.classList.remove('bg-light', 'border-start', 'border-3', 'border-primary');
                });
                document.querySelectorAll('.notif-new-badge').forEach(b => b.remove());

                const b = document.getElementById('notification-badge');
                if (b) b.remove();

                this.remove();
            })
            .catch(() => {
                this.removeAttribute('disabled');
                alert('Could not update notifications.');
            });
        });
    }

    // ── Mark ONE read — event delegation on the list ─────────────────────────
    // FIX #3: delegation is now on '#notification-list' (the <ul>) instead of
    //         '.list-group', which is more precise and still catches both
    //         server-rendered items AND items injected in real-time by app.js.
    const list = document.getElementById('notification-list');
    if (list) {
        list.addEventListener('click', function (e) {
            const item = e.target.closest('.mark-one-read');
            if (!item) return;

            const id  = item.dataset.id;
            const url = item.dataset.url;
            const li  = id ? document.getElementById('notif-item-' + id) : null;

            // If already read, just navigate
            if (li && !li.classList.contains('bg-light')) {
                if (url && url !== '#') window.location.href = url;
                return;
            }

            // Only POST mark-as-read when we have a real DB id (not a rt-* fallback)
            if (!id || id.startsWith('rt-')) {
                if (url && url !== '#') window.location.href = url;
                return;
            }

            fetch("{{ route('admin.notifications.readOne') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
            .then(res => {
                if (!res.ok) throw new Error();

                if (li) {
                    li.classList.remove('bg-light', 'border-start', 'border-3', 'border-primary');
                    li.querySelector('.notif-new-badge')?.remove();
                }

                // Decrement badge
                const b = document.getElementById('notification-badge');
                if (b) {
                    const count = parseInt(b.textContent, 10) - 1;
                    count > 0 ? (b.textContent = count) : b.remove();
                }
            })
            .catch(console.error)
            .finally(() => {
                if (url && url !== '#') window.location.href = url;
            });
        });
    }
})();
</script>
@endpush