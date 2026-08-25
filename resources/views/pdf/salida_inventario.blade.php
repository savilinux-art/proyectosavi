<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salida de Inventario</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; }
        .header p { color: #666; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px 10px; border: 1px solid #ddd; }
        .info-table td.label { font-weight: bold; background: #f5f5f5; width: 30%; }
        .productos-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .productos-table th { background: #4CAF50; color: white; padding: 8px; text-align: left; }
        .productos-table td { padding: 8px; border-bottom: 1px solid #ddd; }
        .productos-table tr:nth-child(even) { background: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 11px; border-top: 1px solid #ddd; padding-top: 10px; }
        .copia { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 60px; color: rgba(200,200,200,0.3); pointer-events: none; z-index: 1000; }
        .total { font-weight: bold; text-align: right; padding: 10px; border-top: 2px solid #333; }
        .firma { margin-top: 30px; display: flex; justify-content: space-between; }
        .firma div { text-align: center; width: 30%; }
        .firma hr { margin-top: 30px; border: none; border-top: 1px solid #333; }
    </style>
</head>
<body>
    @if($tipoCopia != 'General')
    <div class="copia">{{ $tipoCopia }}</div>
    @endif

    <div class="header">
        <h1>SALIDA DE INVENTARIO</h1>
        <p>ProyectoSAVI v0.1.1 - {{ $tipoCopia }} | {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr><td class="label">Proyecto</td><td>{{ $salida->nombre_proyecto }}</td></tr>
        <tr><td class="label">Entregado por</td><td>{{ $salida->entregadoPor->nombre ?? 'N/A' }} ({{ $salida->entregado_por }})</td></tr>
        <tr><td class="label">Entregado a</td><td>{{ $salida->entregadoA->nombre ?? 'N/A' }} ({{ $salida->entregado_a }})</td></tr>
        <tr><td class="label">Fecha y hora</td><td>{{ \Carbon\Carbon::parse($salida->fecha_hora_salida)->format('d/m/Y H:i:s') }}</td></tr>
        @if($salida->observaciones)<tr><td class="label">Observaciones</td><td>{{ $salida->observaciones }}</td></tr>@endif
    </table>

    <h3>Productos entregados</h3>
    <table class="productos-table">
        <thead><tr><th>#</th><th>Modelo</th><th>Descripción</th><th style="text-align:right;">Cantidad</th></tr></thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($productos as $index => $item)
            <tr><td>{{ $index+1 }}</td><td>{{ $item['modelo'] ?? 'N/A' }}</td><td>{{ $item['descripcion'] ?? 'N/A' }}</td><td style="text-align:right;">{{ $item['cantidad'] }}</td></tr>
            @php $total += $item['cantidad']; @endphp
            @endforeach
            <tr class="total"><td colspan="3" style="text-align:right;">Total:</td><td style="text-align:right;">{{ $total }}</td></tr>
        </tbody>
    </table>

    <div class="firma">
        <div><hr><p>Entregó</p></div>
        <div><hr><p>Recibió</p></div>
        <div><hr><p>Vo.Bo. Administración</p></div>
    </div>

    <div class="footer">
        <p>Documento generado por ProyectoSAVI v0.1.1 - {{ now()->format('d/m/Y H:i') }}</p>
        <p>Este documento es un comprobante de salida de inventario</p>
    </div>
</body>
</html>