@if(session('success'))
<div class="alert-modern alert-success-modern mb-4" id="alert-success">
    <div class="alert-icon-wrap">
        <i class="ri ri-checkbox-circle-fill"></i>
    </div>
    <div class="alert-body">
        <strong>Success!</strong>
        <span>{{ session('success') }}</span>
    </div>
    <button type="button" class="alert-close" onclick="dismissAlert('alert-success')">
        <i class="ri ri-close-line"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert-modern alert-error-modern mb-4" id="alert-error">
    <div class="alert-icon-wrap">
        <i class="ri ri-error-warning-fill"></i>
    </div>
    <div class="alert-body">
        <strong>Error!</strong>
        <span>{{ session('error') }}</span>
    </div>
    <button type="button" class="alert-close" onclick="dismissAlert('alert-error')">
        <i class="ri ri-close-line"></i>
    </button>
</div>
@endif

@if(session('success') || session('error'))
<style>
.alert-modern {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 12px;
    border: none;
    position: relative;
    animation: slideInAlert .35s cubic-bezier(.16,1,.3,1) both;
    transition: opacity .25s ease, transform .25s ease;
}
.alert-modern.hiding {
    opacity: 0;
    transform: translateY(-8px);
}
@keyframes slideInAlert {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Success ── */
.alert-success-modern {
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    box-shadow: 0 2px 12px rgba(5,150,105,.12), inset 0 0 0 1px rgba(5,150,105,.15);
}
.alert-success-modern .alert-icon-wrap {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, #059669, #10b981);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 3px 8px rgba(5,150,105,.3);
}
.alert-success-modern .alert-icon-wrap i { color: #fff; font-size: 1.1rem; }
.alert-success-modern .alert-body strong { color: #065f46; }
.alert-success-modern .alert-close:hover { background: rgba(5,150,105,.12); color: #059669; }

/* ── Error ── */
.alert-error-modern {
    background: linear-gradient(135deg, #fff1f2 0%, #fff5f5 100%);
    box-shadow: 0 2px 12px rgba(239,68,68,.12), inset 0 0 0 1px rgba(239,68,68,.15);
}
.alert-error-modern .alert-icon-wrap {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 3px 8px rgba(239,68,68,.3);
}
.alert-error-modern .alert-icon-wrap i { color: #fff; font-size: 1.1rem; }
.alert-error-modern .alert-body strong { color: #991b1b; }
.alert-error-modern .alert-close:hover { background: rgba(239,68,68,.12); color: #dc2626; }

/* ── Shared ── */
.alert-body {
    flex: 1; display: flex; flex-direction: column; gap: 1px;
}
.alert-body strong { font-size: 0.875rem; font-weight: 700; display: block; }
.alert-body span   { font-size: 0.82rem; color: #6b7280; }

.alert-close {
    width: 28px; height: 28px; border-radius: 7px;
    border: none; background: transparent;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #9ca3af; font-size: 1rem;
    transition: all .15s; flex-shrink: 0;
}
</style>

<script>
function dismissAlert(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hiding');
    setTimeout(() => {
        el.style.display = 'none';
    }, 260);
}

// Auto-dismiss after 5 seconds
setTimeout(() => {
    ['alert-success', 'alert-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hiding');
            setTimeout(() => el.style.display = 'none', 260);
        }
    });
}, 5000);
</script>
@endif