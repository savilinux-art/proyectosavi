@php
    $esAdmin = session('user_rol') === 'Administrador';
    $r = $recordatorio ?? null;
    $old = fn($k, $def = null) => old($k, $r->{$k} ?? $def);
    $canalesActuales = (array) old('canal', $r->canal ?? ['telegram']);
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Corrige los siguientes errores:</strong>
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Título <span class="text-danger">*</span></label>
        <input type="text" name="titulo" class="form-control" required
               value="{{ $old('titulo') }}" maxlength="200">
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipo <span class="text-danger">*</span></label>
        <select name="tipo" id="tipoSelect" class="form-select" required>
            @foreach(['general' => 'General', 'certificado' => 'Certificado', 'sistema' => 'Sistema'] as $k => $v)
                <option value="{{ $k }}" @selected($old('tipo', 'general') === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="2" maxlength="2000">{{ $old('descripcion') }}</textarea>
    </div>

    @if($esAdmin)
    <div class="col-md-4">
        <label class="form-label">Destinatario <span class="text-danger">*</span></label>
        <select name="usuario_id" class="form-select" required>
            <option value="">— Selecciona —</option>
            @foreach($usuarios as $u)
                <option value="{{ $u->id }}" @selected((string) $old('usuario_id') === (string) $u->id)>
                    {{ $u->nombre }} ({{ $u->usuario }})
                </option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="col-md-4">
        <label class="form-label">Fecha y hora <span class="text-danger">*</span></label>
        <input type="datetime-local" name="fecha_hora_programada" class="form-control" required
               value="{{ $r?->fecha_hora_programada?->format('Y-m-d\TH:i') ?? old('fecha_hora_programada') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Recurrencia</label>
        <select name="recurrencia" class="form-select">
            @foreach(['una_vez' => 'Una vez', 'diario' => 'Diario', 'semanal' => 'Semanal', 'mensual' => 'Mensual', 'personalizado' => 'Personalizado'] as $k => $v)
                <option value="{{ $k }}" @selected($old('recurrencia', 'una_vez') === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Canales <span class="text-danger">*</span></label>
        <div class="d-flex gap-4">
            @foreach(['telegram' => 'Telegram', 'email' => 'Email', 'web' => 'Web', 'whatsapp' => 'WhatsApp (fase 2)'] as $k => $v)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="canal[]"
                           id="canal_{{ $k }}" value="{{ $k }}" @checked(in_array($k, $canalesActuales))>
                    <label class="form-check-label" for="canal_{{ $k }}">{{ $v }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Bloque certificado --}}
<div id="bloqueCertificado" class="mt-4 p-3 border rounded bg-light">
    <h6 class="mb-3"><i class="bi bi-patch-check"></i> Datos del certificado</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="cert_nombre" class="form-control" value="{{ $old('cert_nombre') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tipo</label>
            <select name="cert_tipo" class="form-select">
                <option value="">—</option>
                @foreach(['ssl' => 'SSL', 'csd' => 'CSD', 'dominio' => 'Dominio', 'otro' => 'Otro'] as $k => $v)
                    <option value="{{ $k }}" @selected($old('cert_tipo') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Emisor</label>
            <input type="text" name="cert_emisor" class="form-control" value="{{ $old('cert_emisor') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Serie</label>
            <input type="text" name="cert_serie" class="form-control" value="{{ $old('cert_serie') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Fecha emisión</label>
            <input type="date" name="cert_fecha_emision" class="form-control"
                   value="{{ $r?->cert_fecha_emision?->format('Y-m-d') ?? old('cert_fecha_emision') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Fecha vencimiento <span class="text-danger">*</span></label>
            <input type="date" name="cert_fecha_vencimiento" class="form-control"
                   value="{{ $r?->cert_fecha_vencimiento?->format('Y-m-d') ?? old('cert_fecha_vencimiento') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Link de renovación</label>
            <input type="url" name="cert_link_renovacion" class="form-control" value="{{ $old('cert_link_renovacion') }}">
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end gap-2">
    <a href="{{ route('recordatorios.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
</div>

@push('scripts')
<script>
(function () {
    const tipo   = document.getElementById('tipoSelect');
    const bloque = document.getElementById('bloqueCertificado');
    const toggle = () => { bloque.style.display = (tipo.value === 'certificado') ? '' : 'none'; };
    tipo.addEventListener('change', toggle);
    toggle();
})();
</script>
@endpush