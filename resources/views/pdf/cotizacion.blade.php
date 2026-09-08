<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cotización</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; padding: 30px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 24px; margin: 0; text-transform: uppercase; letter-spacing: 2px; }
        .header .sub { font-size: 12px; color: #666; }
        .info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-box { border: 1px solid #ddd; padding: 10px; border-radius: 5px; width: 48%; }
        .info-box strong { display: block; font-size: 11px; color: #666; margin-bottom: 5px; }
        .info-box .dato { font-size: 13px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table th { background: #f0f0f0; border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
        table td { border: 1px solid #ccc; padding: 8px; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totales { margin-top: 15px; text-align: right; }
        .totales table { width: 40%; float: right; border: none; }
        .totales table td { border: none; padding: 4px 8px; }
        .totales table .label { font-weight: bold; }
        .totales table .total { font-size: 14px; font-weight: bold; border-top: 2px solid #333; padding-top: 8px; }
        .condiciones { margin-top: 30px; padding: 15px; background: #f9f9f9; border-radius: 5px; font-size: 9px; border-left: 4px solid #0066cc; }
        .condiciones p { margin: 0 0 5px 0; }
        .firma { margin-top: 40px; text-align: center; }
        .firma .linea { display: inline-block; width: 200px; border-top: 1px solid #333; margin: 0 15px; padding-top: 5px; font-size: 9px; color: #555; }
        .total-letras { font-size: 11px; font-weight: bold; margin: 10px 0; }
        @media print { .condiciones { background: none; } }
    </style>
</head>
<body>

<div class="header">
    <h1>Cotización</h1>
    <div class="sub">Folio: {{ $cotizacion->folio }}</div>
</div>

<div class="info">
    <div class="info-box">
        <strong>Cliente</strong>
        <div class="dato">{{ $cotizacion->cliente->razon_social ?? 'N/A' }}</div>
        <div style="font-size:10px;color:#666;">{{ $cotizacion->cliente->rfc ?? '' }}</div>
        <div style="font-size:10px;color:#666;">{{ $cotizacion->cliente->correo_electronico ?? '' }}</div>
    </div>
    <div class="info-box" style="text-align:right;">
        <strong>Proyecto</strong>
        <div class="dato">{{ $cotizacion->proyecto }}</div>
        <div style="font-size:10px;color:#666;">Fecha: {{ $cotizacion->fecha_emision->format('d/m/Y') }}</div>
        <div style="font-size:10px;color:#666;">Válida hasta: {{ $cotizacion->fecha_validez ? $cotizacion->fecha_validez->format('d/m/Y') : 'N/A' }}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th style="width:5%;">#</th>
            <th style="width:55%;">Descripción</th>
            <th style="width:10%;" class="text-center">Cant.</th>
            <th style="width:15%;" class="text-right">Precio Unit.</th>
            <th style="width:15%;" class="text-right">Importe</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cotizacion->detalles as $index => $item)
        <tr>
            <td class="text-center">{{ $index+1 }}</td>
            <td>{{ $item->descripcion }}</td>
            <td class="text-center">{{ $item->cantidad }}</td>
            <td class="text-right">{{ $cotizacion->moneda }} {{ number_format($item->precio_unitario, 2) }}</td>
            <td class="text-right">{{ $cotizacion->moneda }} {{ number_format($item->importe, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totales">
    <table>
        <tr><td class="label">Subtotal:</td><td>{{ $cotizacion->moneda }} {{ number_format($cotizacion->subtotal, 2) }}</td></tr>
        <tr><td class="label">IVA (16%):</td><td>{{ $cotizacion->moneda }} {{ number_format($cotizacion->iva, 2) }}</td></tr>
        <tr><td class="label total">TOTAL:</td><td class="total">{{ $cotizacion->moneda }} {{ number_format($cotizacion->total, 2) }}</td></tr>
    </table>
</div>

<div style="clear:both;"></div>

<div class="total-letras">
    Total en letras: {{ \App\Helpers\NumeroALetras::convertir($cotizacion->total, $cotizacion->moneda) }}
</div>

@if($cotizacion->condiciones)
<div class="condiciones">
    {!! nl2br(e($cotizacion->condiciones)) !!}
</div>
@endif

<div class="firma">
    <span class="linea">Firma y sello</span>
    <p style="font-size:9px;color:#999;margin-top:15px;">
        Agradecemos su atención y quedamos a sus órdenes para cualquier duda.
    </p>
</div>

</body>
</html>