<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Venta de Mostrador VM-{{ str_pad($ventasMostrador->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { size: letter; margin: 0; }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .pagina-pdf {
            width: 8in;
            min-height: 11in;
            padding: 0.3in;
            margin: 0 auto;
            box-sizing: border-box;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* ========== HEADER ========== */
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header .logo {
            max-height: 70px;
            max-width: 160px;
            margin: 0 auto;
            display: block;
        }

        .header .titulo {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a1a;
            margin-top: 6px;
        }

        .header .folio-line {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #ddd;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
        }

        .header .folio-line .folio-text {
            font-weight: bold;
            color: #0066cc;
        }

        /* ========== INFO PROYECTO ========== */
        .info-box {
            display: flex;
            justify-content: space-between;
            background: #f9f9f9;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 10px;
            border-left: 3px solid #0066cc;
        }

        .info-box span { display: inline-block; }
        .info-box strong { color: #555; }

        /* ========== TABLA ========== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            table-layout: fixed;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 4px 6px;
            font-size: 9px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        table th {
            background: #f0f0f0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .col-numero      { width: 4%; }
        .col-modelo      { width: 15%; }
        .col-descripcion { width: 40%; }
        .col-cantidad    { width: 7%; }
        .col-precio      { width: 11%; }
        .col-descuento   { width: 11%; }
        .col-subtotal    { width: 12%; }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        /* ========== TOTALES ========== */
        .totales {
            margin-top: 15px;
            text-align: right;
        }

        .totales table {
            width: 40%;
            float: right;
            border: none;
        }

        .totales table td {
            border: none;
            padding: 4px 8px;
            font-size: 10px;
            word-wrap: normal;
        }

        .totales table .total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 8px;
        }

        .clearfix { clear: both; }

        /* ========== OBSERVACIONES ========== */
        .observaciones {
            margin-top: 20px;
            padding: 12px;
            background: #f9f9f9;
            border-radius: 5px;
            font-size: 9px;
            border-left: 4px solid #0066cc;
        }

        /* ========== FOOTER ========== */
        .footer {
            margin-top: auto;
            padding-top: 60px;
            text-align: center;
        }

        .footer .firma-linea {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #333;
            margin: 0 15px;
            padding-top: 5px;
            font-size: 9px;
            color: #555;
        }

        .footer .nota {
            font-size: 8px;
            color: #999;
            margin-top: 10px;
        }

        .footer .sello {
            margin-top: 10px;
            font-size: 8px;
            color: #999;
            text-align: right;
            border: 1px dashed #ccc;
            padding: 4px 10px;
            border-radius: 3px;
            display: inline-block;
            float: right;
        }
    </style>
</head>
<body>

<div class="pagina-pdf">

    @php
        $folio = 'VM-' . str_pad($ventasMostrador->id, 4, '0', STR_PAD_LEFT);
    @endphp

    <!-- ========== HEADER ========== -->
    <div class="header">
        <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
        <div class="titulo">Venta de Mostrador</div>
        <div class="folio-line">
            <span class="folio-text">FOLIO: {{ $folio }}</span>
            <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- ========== INFO ========== -->
    <div class="info-box">
        <span><strong>Proyecto:</strong> {{ $ventasMostrador->proyecto->nombre_proyecto ?? 'N/A' }}</span>
        <span><strong>Estado:</strong> {{ ucfirst($ventasMostrador->estado) }}</span>
        <span><strong>Fecha:</strong> {{ $ventasMostrador->created_at?->format('d/m/Y H:i') }}</span>
    </div>

    <!-- ========== TABLA ========== -->
    <table>
        <thead>
            <tr>
                <th class="col-numero text-center">#</th>
                <th class="col-modelo">Modelo</th>
                <th class="col-descripcion">Descripción</th>
                <th class="col-cantidad text-center">Cant.</th>
                <th class="col-precio text-right">Precio</th>
                <th class="col-descuento text-right">Desc.</th>
                <th class="col-subtotal text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventasMostrador->detalles as $i => $d)
            <tr>
                <td class="col-numero text-center">{{ $i + 1 }}</td>
                <td class="col-modelo">{{ $d->inventario->modelo ?? '—' }}</td>
                <td class="col-descripcion">{{ $d->inventario->descripcion ?? '—' }}</td>
                <td class="col-cantidad text-center">{{ $d->cantidad }}</td>
                <td class="col-precio text-right">${{ number_format($d->precio_unitario, 2) }}</td>
                <td class="col-descuento text-right">${{ number_format($d->descuento, 2) }}</td>
                <td class="col-subtotal text-right">${{ number_format($d->subtotal, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Sin productos</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========== TOTAL ========== -->
    <div class="totales">
        <table>
            <tr>
                <td class="total">TOTAL:</td>
                <td class="total">${{ number_format($ventasMostrador->total, 2) }}</td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>

    <!-- ========== OBSERVACIONES ========== -->
    @if($ventasMostrador->observaciones)
    <div class="observaciones">
        <strong>Observaciones:</strong><br>
        {!! nl2br(e($ventasMostrador->observaciones)) !!}
    </div>
    @endif

    <!-- ========== FOOTER ========== -->
    <div class="footer">
        <div>
            <span class="firma-linea">Firma y sello</span>
        </div>
        <p class="nota">Gracias por su compra.</p>
        <div class="sello">
            Generado por: {{ $ventasMostrador->creado_por ?? 'Sistema' }}<br>
            {{ now()->format('d/m/Y H:i') }}
        </div>
        <div style="clear:both;"></div>
    </div>

</div>

</body>
</html>