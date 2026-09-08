<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salida de Inventario</title>
    <style>
        /* ============================================================
           ESTILOS GENERALES
           ============================================================ */
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 15px;
            color: #333;
        }

        /* ========== CONTENEDOR DE CADA COPIA ========== */
        .copia {
            page-break-after: always;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 5px;
            min-height: 700px;
            position: relative;
        }

        .copia:last-child {
            page-break-after: auto;
        }

        /* ========== ENCABEZADO CON LOGO ========== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-left {
            flex: 0 0 80px;
            text-align: left;
        }

        .header-left img {
            max-height: 120px;
            max-width: 160px;
        }

        .header-right {
            flex: 1;
            text-align: right;
        }

        .header-right h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Línea con FOLIO, COPIA y FECHA en una sola línea */
        .header-right .meta-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
            font-size: 11px;
        }

        .header-right .meta-line .folio-text {
            font-weight: bold;
            color: #0066cc;
        }

        .header-right .meta-line .copia-text {
            font-weight: bold;
            color: #0066cc;
        }

        .header-right .meta-line .fecha-text {
            color: #999;
            font-size: 10px;
        }

        /* ========== TIPO DE COPIA (etiqueta en esquina) ========== */
        .tipo-copia {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 3px;
            border: 1px solid #0066cc;
            color: #0066cc;
            background: #e6f0ff;
        }

        .copia-1 .tipo-copia { background: #e6f0ff; border-color: #0066cc; color: #0066cc; }
        .copia-2 .tipo-copia { background: #e6ffe6; border-color: #28a745; color: #28a745; }
        .copia-3 .tipo-copia { background: #fff3cd; border-color: #ffc107; color: #856404; }

        /* ========== INFORMACIÓN DE LA SALIDA ========== */
        .info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .info-item {
            font-size: 10px;
            padding: 2px 5px;
        }

        .info-item strong {
            color: #555;
        }

        /* ========== TABLA DE PRODUCTOS ========== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }

        table th {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }

        table td {
            border: 1px solid #ccc;
            padding: 6px 4px;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* ========== PIE DE FIRMA ========== */
        .firma {
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 25px;
            text-align: center;
        }

        .firma .linea {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #333;
            margin: 0 15px;
            padding-top: 5px;
            font-size: 9px;
            color: #555;
        }

        /* ========== SELLO DE CONTROL ========== */
        .sello {
            position: absolute;
            bottom: 40px;
            right: 30px;
            font-size: 8px;
            color: #999;
            text-align: right;
            border: 1px dashed #ccc;
            padding: 5px 10px;
            border-radius: 3px;
        }

        /* ========== NOTA AL PIE ========== */
        .nota {
            font-size: 8px;
            color: #999;
            margin-top: 15px;
            text-align: center;
        }

        @media print {
            body { padding: 0; }
            .copia { border: none; border-radius: 0; padding: 20px; min-height: auto; }
        }
    </style>
</head>
<body>

{{-- ============================================================
     COPIA 1: ORIGINAL - CLIENTE
     ============================================================ --}}
<div class="copia copia-1">
    <div class="tipo-copia">ORIGINAL CLIENTE</div>
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo Savi Control Home">
        </div>
        <div class="header-right">
            <h1>FORMATO DE SALIDA DE INVENTARIO</h1>
            <div class="meta-line">
                <span class="folio-text">FOLIO: {{ str_pad($salida->id, 6, '0', STR_PAD_LEFT) }}</span>
                <span class="copia-text">(COPIA 1 DE 3)</span>
                <span class="fecha-text">FECHA: {{ now()->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>
    </div>

    <div class="info">
        <div class="info-item"><strong>Entregado por:</strong> {{ $salida->entregadoPor->nombre ?? 'N/A' }}</div>
        <div class="info-item"><strong>Entregado a:</strong> {{ $salida->entregadoA->nombre ?? 'N/A' }}</div>
        <div class="info-item"><strong>Proyecto:</strong> {{ $salida->nombre_proyecto }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:70%;">Artículo</th>
                <th style="width:15%;" class="text-center">Cantidad</th>
                <th style="width:10%;" class="text-center">Unidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $index => $item)
<tr>
    <td class="text-center">{{ $index + 1 }}</td>
    <td>
       <small>{{ $item->inventario->descripcion ?? 'N/A'}}</small>
    </td>
    <td class="text-center">{{ number_format($item->cantidad, 0) }}</td>
    <td class="text-center">PZA</td>
</tr>
@endforeach
        </tbo
        </tbody>
    </table>

    <div style="padding: 5px 0; font-size: 9px; color: #999; border-top: 1px solid #eee;">
        <strong>CONTROL:</strong> REIMPRESION #{{ $salida->id }}
    </div>

    <div class="firma">
        <span class="linea">Firma de quien recibe</span>
        <p class="nota">* Este documento es un comprobante de entrega de materiales.</p>
    </div>

    <div class="sello">
        Documento generado por: {{ $salida->entregadoPor->nombre ?? 'Sistema' }}<br>
        {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

{{-- ============================================================
     COPIA 2: ALMACÉN
     ============================================================ --}}
<div class="copia copia-2">
    <div class="tipo-copia">COPIA ALMACÉN</div>
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo Savi Control Home">
        </div>
        <div class="header-right">
            <h1>FORMATO DE SALIDA DE INVENTARIO</h1>
            <div class="meta-line">
                <span class="folio-text">FOLIO: {{ str_pad($salida->id, 6, '0', STR_PAD_LEFT) }}</span>
                <span class="copia-text">(COPIA 2 DE 3)</span>
                <span class="fecha-text">FECHA: {{ now()->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>
    </div>

    <div class="info">
        <div class="info-item"><strong>Entregado por:</strong> {{ $salida->entregadoPor->nombre ?? 'N/A' }}</div>
        <div class="info-item"><strong>Entregado a:</strong> {{ $salida->entregadoA->nombre ?? 'N/A' }}</div>
        <div class="info-item"><strong>Proyecto:</strong> {{ $salida->nombre_proyecto }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:70%;">Artículo</th>
                <th style="width:15%;" class="text-center">Cantidad</th>
                <th style="width:10%;" class="text-center">Unidad</th>
            </tr>
        </thead>
        <tbody>
          @foreach($productos as $index => $item)
<tr>
    <td class="text-center">{{ $index + 1 }}</td>
    <td>
       <small>{{ $item->inventario->descripcion ?? 'N/A'}}</small>
    </td>
    <td class="text-center">{{ number_format($item->cantidad, 0) }}</td>
    <td class="text-center">PZA</td>
</tr>

    </td>
    <td class="text-center">{{ number_format($item->cantidad, 0) }}</td>
    <td class="text-center">PZA</td>
</tr>
@endforeach
        </tbo
        </tbody>
    </table>

    <div style="padding: 5px 0; font-size: 9px; color: #999; border-top: 1px solid #eee;">
        <strong>CONTROL:</strong> REIMPRESION #{{ $salida->id }}
    </div>

    <div class="firma">
        <span class="linea">Firma de quien recibe</span>
        <p class="nota">* Esta copia queda en el almacén como respaldo.</p>
    </div>

    <div class="sello">
        Documento generado por: {{ $salida->entregadoPor->nombre ?? 'Sistema' }}<br>
        {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

{{-- ============================================================
     COPIA 3: INSTALADOR
     ============================================================ --}}
<div class="copia copia-3">
    <div class="tipo-copia">COPIA INSTALADOR</div>
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo Savi Control Home">
        </div>
        <div class="header-right">
            <h1>FORMATO DE SALIDA DE INVENTARIO</h1>
            <div class="meta-line">
                <span class="folio-text">FOLIO: {{ str_pad($salida->id, 6, '0', STR_PAD_LEFT) }}</span>
                <span class="copia-text">(COPIA 3 DE 3)</span>
                <span class="fecha-text">FECHA: {{ now()->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>
    </div>

    <div class="info">
        <div class="info-item"><strong>Entregado por:</strong> {{ $salida->entregadoPor->nombre ?? 'N/A' }}</div>
        <div class="info-item"><strong>Recibe:</strong> {{ $salida->entregadoA->nombre ?? 'N/A' }}</div>
        <div class="info-item"><strong>Proyecto:</strong> {{ $salida->nombre_proyecto }}</div>
        <div class="info-item"><strong>Generado por:</strong> {{ $salida->entregadoPor->nombre ?? 'Sistema' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:70%;">Artículo</th>
                <th style="width:15%;" class="text-center">Cantidad</th>
                <th style="width:10%;" class="text-center">Unidad</th>
            </tr>
        </thead>
        <tbody>
           @foreach($productos as $index => $item)
<tr>
    <td class="text-center">{{ $index + 1 }}</td>
    <td>
       <small>{{ $item->inventario->descripcion ?? 'N/A'}}</small>
    </td>
    <td class="text-center">{{ number_format($item->cantidad, 0) }}</td>
    <td class="text-center">PZA</td>
</tr>

    </td>
    <td class="text-center">{{ number_format($item->cantidad, 0) }}</td>
    <td class="text-center">PZA</td>
</tr>
@endforeach
        </tbody>
    </table>

    <div style="padding: 5px 0; font-size: 9px; color: #999; border-top: 1px solid #eee;">
        <strong>CONTROL:</strong> REIMPRESION #{{ $salida->id }}
    </div>

    <div class="firma">
        <span class="linea">Firma de quien recibe</span>
        <p class="nota">* Esta copia es para control interno del instalador.</p>
    </div>

    <div class="sello">
        Documento generado por: {{ $salida->entregadoPor->nombre ?? 'Sistema' }}<br>
        {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

</body>
</html>