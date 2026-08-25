<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salida de Inventario - {{ $salida->folio }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            color: #1a1a2e;
        }
        .header .folio {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
        }
        .info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 5px 10px;
        }
        .info .label {
            font-weight: bold;
            width: 30%;
        }
        table.productos {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table.productos th {
            background: #1a1a2e;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table.productos td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        table.productos tr:nth-child(even) {
            background: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .copia {
            text-align: center;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .firmas {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
        }
        .firma {
            text-align: center;
            width: 30%;
        }
        .firma .linea {
            border-top: 1px solid #333;
            margin: 10px 0 5px 0;
        }
        .firma .nombre {
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
            margin-top: 30px;
            padding-top: 30px;
        }
        .copias {
            display: none;
        }
        @media print {
            .page-break {
                page-break-after: always;
            }
            .copias {
                display: block;
            }
        }
    </style>
</head>
<body>
    @if($copia)
        <div class="copia">COPIA: {{ $copia }}</div>
    @endif

    <div class="header">
        <h1>PROYECTO SAVI</h1>
        <p>Salida de Inventario</p>
        <div class="folio">FOLIO: {{ $salida->folio }}</div>
        <p>Fecha: {{ $fecha }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td class="label">Proyecto:</td>
                <td>{{ $salida->proyecto->nombre_proyecto ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Entregado por:</td>
                <td>{{ $salida->entregadoPor->nombre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Entregado a:</td>
                <td>{{ $salida->entregadoA->nombre ?? 'N/A' }}</td>
            </tr>
            @if($salida->observaciones)
            <tr>
                <td class="label">Observaciones:</td>
                <td>{{ $salida->observaciones }}</td>
            </tr>
            @endif
        </table>
    </div>

    <h3>Productos Entregados</h3>
    <table class="productos">
        <thead>
            <tr>
                <th style="width: 20%;">Modelo</th>
                <th style="width: 50%;">Descripción</th>
                <th style="width: 15%; text-align: center;">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salida->productos as $producto)
            <tr>
                <td>{{ $producto['modelo'] }}</td>
                <td>{{ $producto['descripcion'] }}</td>
                <td style="text-align: center;">{{ $producto['cantidad'] }}</td>
            </tr>
            @endforeach
            <tr style="font-weight: bold; background: #e9ecef;">
                <td colspan="2" style="text-align: right;">TOTAL:</td>
                <td style="text-align: center;">{{ collect($salida->productos)->sum('cantidad') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="firmas">
        <div class="firma">
            <p>________________________</p>
            <p class="nombre">{{ $salida->entregadoPor->nombre ?? 'Entregó' }}</p>
            <p>Entregó</p>
        </div>
        <div class="firma">
            <p>________________________</p>
            <p class="nombre">{{ $salida->entregadoA->nombre ?? 'Recibió' }}</p>
            <p>Recibió</p>
        </div>
        <div class="firma">
            <p>________________________</p>
            <p class="nombre">Autorizado</p>
            <p>Autorizó</p>
        </div>
    </div>

    <div class="footer">
        <p>Documento generado por Sistema ProyectoSAVI v0.1.1</p>
        <p>Este documento es un comprobante de salida de inventario</p>
    </div>

    @if(!$copia)
    <div class="copias">
        <div class="page-break"></div>
        <h2 style="text-align: center; color: #667eea;">--- COPIA PARA ADMINISTRACIÓN ---</h2>
        <div style="height: 20px;"></div>
        
        <div class="header">
            <h1>PROYECTO SAVI</h1>
            <p>Salida de Inventario</p>
            <div class="folio">FOLIO: {{ $salida->folio }}</div>
            <p>Fecha: {{ $fecha }}</p>
        </div>

        <div class="info">
            <table>
                <tr><td class="label">Proyecto:</td><td>{{ $salida->proyecto->nombre_proyecto ?? 'N/A' }}</td></tr>
                <tr><td class="label">Entregado por:</td><td>{{ $salida->entregadoPor->nombre ?? 'N/A' }}</td></tr>
                <tr><td class="label">Entregado a:</td><td>{{ $salida->entregadoA->nombre ?? 'N/A' }}</td></tr>
            </table>
        </div>

        <table class="productos">
            <thead>
                <tr>
                    <th style="width: 20%;">Modelo</th>
                    <th style="width: 50%;">Descripción</th>
                    <th style="width: 15%; text-align: center;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salida->productos as $producto)
                <tr>
                    <td>{{ $producto['modelo'] }}</td>
                    <td>{{ $producto['descripcion'] }}</td>
                    <td style="text-align: center;">{{ $producto['cantidad'] }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background: #e9ecef;">
                    <td colspan="2" style="text-align: right;">TOTAL:</td>
                    <td style="text-align: center;">{{ collect($salida->productos)->sum('cantidad') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>--- COPIA PARA ADMINISTRACIÓN ---</p>
        </div>
    </div>
    @endif
</body>
</html>