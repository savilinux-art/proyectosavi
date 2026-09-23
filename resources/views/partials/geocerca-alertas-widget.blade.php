{{-- ✅ Ahora: va directo al SessionManager de Laravel --}}
@if(session('user_rol') === 'Administrador')
<div class="card shadow-sm mb-3" id="cardGeocercaAlertas">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <h5 class="mb-0">
            <i class="bi bi-pin-map-fill text-primary"></i>
            Alertas de Geocercas
            <span class="badge bg-danger ms-2" id="badgeGeocercaAlertas" style="display:none;">0</span>
        </h5>
        <div>
            <button class="btn btn-sm btn-outline-secondary" id="btnMarcarTodas" title="Marcar todas como leídas">
                <i class="bi bi-check2-all"></i>
            </button>
            <button class="btn btn-sm btn-outline-primary" id="btnRefrescarAlertas" title="Refrescar">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush" id="listaGeocercaAlertas" style="max-height: 340px; overflow-y:auto;">
            <li class="list-group-item text-muted text-center small py-3">Cargando…</li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const urlRecientes  = "{{ route('geocercas.alertas.recientes') }}";
    const urlLeerTodas  = "{{ route('geocercas.alertas.leerTodas') }}";
    const urlLeerUna    = "{{ url('geocercas/alertas') }}"; // + /{id}/leer
    const csrfToken     = "{{ csrf_token() }}";

    const $lista  = document.getElementById('listaGeocercaAlertas');
    const $badge  = document.getElementById('badgeGeocercaAlertas');

    function renderAlertas(data) {
        const alertas = data.alertas || [];
        $badge.textContent = data.noLeidas;

        if (data.noLeidas > 0) {
            $badge.style.display = 'inline-block';
        } else {
            $badge.style.display = 'none';
        }

        if (alertas.length === 0) {
            $lista.innerHTML = '<li class="list-group-item text-muted text-center small py-3">Sin alertas recientes</li>';
            return;
        }

        $lista.innerHTML = alertas.map(a => `
            <li class="list-group-item d-flex align-items-start gap-2 ${a.leido ? '' : 'bg-light'}" data-id="${a.id}">
                <span style="width:10px;height:10px;border-radius:50%;background:${a.color};display:inline-block;margin-top:6px;"></span>
                <div class="flex-grow-1">
                    <div class="small">
                        ${a.tipo === 'entrada' ? '🟢 Entró a' : '🔴 Salió de'}
                        <strong>${a.geocerca}</strong>
                    </div>
                    <div class="small text-muted">
                        👤 ${a.usuario} · ${a.hace}
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <a href="https://www.google.com/maps?q=${a.lat},${a.lng}" target="_blank"
                       class="btn btn-sm btn-outline-secondary py-0 px-1" title="Ver en mapa">
                        <i class="bi bi-geo-alt"></i>
                    </a>
                    ${a.leido ? '' : `
                        <button class="btn btn-sm btn-outline-success py-0 px-1 btn-leer" data-id="${a.id}" title="Marcar como leída">
                            <i class="bi bi-check2"></i>
                        </button>
                    `}
                </div>
            </li>
        `).join('');
    }

    async function cargarAlertas() {
        try {
            const r = await fetch(urlRecientes + '?limit=15', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await r.json();
            renderAlertas(data);
        } catch (e) {
            console.error('Error cargando alertas geocerca', e);
        }
    }

    // Marcar una
    $lista.addEventListener('click', async (e) => {
        const btn = e.target.closest('.btn-leer');
        if (!btn) return;

        await fetch(`${urlLeerUna}/${btn.dataset.id}/leer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        cargarAlertas();
    });

    // Marcar todas
    document.getElementById('btnMarcarTodas').addEventListener('click', async () => {
        await fetch(urlLeerTodas, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        cargarAlertas();
    });

    document.getElementById('btnRefrescarAlertas').addEventListener('click', cargarAlertas);

    // Carga inicial + polling cada 30s
    cargarAlertas();
    setInterval(cargarAlertas, 30000);

    // Escucha en tiempo real (Reverb) si lo tienes
    if (window.Echo) {
        window.Echo.private('geocercas')
            .listen('.GeocercaAlertaEvent', () => cargarAlertas());
    }
})();
</script>
@endpush
@endif