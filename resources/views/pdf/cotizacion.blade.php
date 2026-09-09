<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $cotizacion->folio }}</title>
    <style>
        /* ============================================================
           CONFIGURACIÓN DE PÁGINA
           ============================================================ */
        @page {
            size: letter;
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            background: #ddd; /* Solo para previsualizar */
        }

        /* ============================================================
           HOJA PRINCIPAL
           ============================================================ */
        .pagina-pdf {
            width: 8in;
            min-height: 11in;
            padding: 0.3in;
            margin: 0 auto;
            box-sizing: border-box;
            background: white;
            page-break-after: always;

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

        .header .cliente-nombre {
            font-size: 12px;
            font-weight: bold;
            margin-top: 6px;
            text-align: left;
        }

        .header .cliente-email {
            font-size: 10px;
            color: #555;
            text-align: left;
        }

        .header .folio-line {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #ddd;
            font-size: 10px;
            text-align: right;
            display: flex;
            justify-content: space-between;
        }

        .header .folio-line .folio-text {
            font-weight: bold;
            color: #0066cc;
        }

        /* ========== CONTENIDO ========== */
        .contenido {
            flex: 1; /* ← Ocupa todo el espacio disponible */
            display: flex;
            flex-direction: column;
        }

        .proyecto-info {
            display: flex;
            text-align: left;
            font-size: 10px;
            
        }

        /* ============================================================
           TABLA DE PRODUCTOS
           ============================================================ */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 4px 4px;
            font-size: 9px;
            vertical-align: middle;
            word-wrap: break-word;
            word-break: break-word;
        }

        .col-numero { width: 3%; }
        .col-descripcion { width: 70%; }
        .col-cantidad { width: 6%; }
        .col-precio { width: 10%; }
        .col-importe { width: 10%; }

        table th {
            background: #f0f0f0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .text-right { text-align: right; }
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
            word-break: normal;
        }

        .totales table .label { font-weight: bold; }
        .totales table .total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 8px;
        }

        .clearfix { clear: both; }

        /* ========== TOTAL EN LETRAS ========== */
        .total-letras {
            font-size: 11px;
            font-weight: bold;
            margin: 15px 0 10px 0;
            padding: 8px 12px;
            background: #f9f9f9;
            border-left: 4px solid #0066cc;
            border-radius: 3px;
        }

        /* ========== CONDICIONES ========== */
        .condiciones {
            margin-top: 15px;
            padding: 12px;
            background: #f9f9f9;
            border-radius: 5px;
            font-size: 9px;
            border-left: 4px solid #0066cc;
        }

        /* ========== FOOTER (Fijo al final) ========== */
        .footer {
            margin-top: auto; /* ← Empuja el footer hacia abajo */
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

        .clearfix {
            clear: both;
        }

        @media print {
            body { background: white; }
            .pagina-pdf { margin: 0; }
        }
    </style>
</head>
<body>

<div class="pagina-pdf">

    <!-- ============================================================
         HEADER
         ============================================================ -->
    <div class="header">
        <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
        <div class="cliente-nombre">{{ $cotizacion->cliente->razon_social ?? 'N/A' }}</div>
        <div class="cliente-email">{{ $cotizacion->cliente->correo_electronico ?? 'N/A' }}</div>
        <div class="proyecto-info">
            <span><strong>Proyecto:</strong> {{ $cotizacion->proyecto->nombre_proyecto ?? 'N/A' }}</span>
            <span><strong>Moneda:</strong> {{ $cotizacion->moneda }}</span>
        </div>
          
        <div class="folio-line">
            <span class="folio-text">FOLIO: {{ $cotizacion->folio }}</span>
            <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- ============================================================
         CONTENIDO
         ============================================================ -->
    <div class="contenido">

        

        <!-- ===== TABLA DE PRODUCTOS ===== -->
        <table>
            <thead>
                <tr>
                    <th class="col-numero text-center">#</th>
                    <th class="col-descripcion">Descripción</th>
                    <th class="col-cantidad text-center">Cant.</th>
                    <th class="col-precio text-right">Precio Unit.</th>
                    <th class="col-importe text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cotizacion->detalles as $index => $item)
                <tr>
                    <td class="col-numero text-center">{{ $index + 1 }}</td>
                    <td class="col-descripcion">{{ $item->descripcion }}</td>
                    <td class="col-cantidad text-center">{{ $item->cantidad }}</td>
                    <td class="col-precio text-right">{{ $cotizacion->moneda }} {{ number_format($item->precio_unitario, 2) }}</td>
                    <td class="col-importe text-right">{{ $cotizacion->moneda }} {{ number_format($item->importe, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Sin productos</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- ===== TOTALES ===== -->
        <div class="totales">
            <table>
                <tr>
                    <td class="label">Subtotal:</td>
                    <td>{{ $cotizacion->moneda }} {{ number_format($cotizacion->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">IVA (16%):</td>
                    <td>{{ $cotizacion->moneda }} {{ number_format($cotizacion->iva, 2) }}</td>
                </tr>
                <tr>
                    <td class="label total">TOTAL:</td>
                    <td class="total">{{ $cotizacion->moneda }} {{ number_format($cotizacion->total, 2) }}</td>
                </tr>
            </table>
        </div>
        <div class="clearfix"></div>

        <!-- ===== TOTAL EN LETRAS ===== -->
        @php
            try {
                $totalLetras = \App\Helpers\NumeroALetras::convertir($cotizacion->total, $cotizacion->moneda);
            } catch (\Exception $e) {
                $totalLetras = 'No disponible';
            }
        @endphp
        <div class="total-letras">Total en letras: {{ $totalLetras }}</div>

        <!-- ===== CONDICIONES ===== -->
        @if($cotizacion->condiciones)
        <div class="condiciones">
            {!! nl2br(e($cotizacion->condiciones)) !!}
        </div>
        @endif

    </div>

    <!-- ============================================================
         FOOTER (SIEMPRE AL FINAL DE LA PÁGINA)
         ============================================================ -->
    <div class="footer">
        <div>
            <span class="firma-linea">Firma y sello</span>
        </div>
        <p class="nota">Agradecemos su atención y quedamos a sus órdenes.</p>
        <div class="sello">
            Generado por: {{ $cotizacion->creador->nombre ?? 'Sistema' }}<br>
            {{ now()->format('d/m/Y H:i') }}
        </div>
        <div style="clear:both;"></div>
    </div>

</div>

</body>
</html>