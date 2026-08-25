<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Imprimir Salida - {{ $salida->folio }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .page { page-break-after: always; padding: 20px; }
        .copia-header { 
            text-align: center; 
            font-size: 18px; 
            font-weight: bold; 
            color: #667eea;
            margin-bottom: 20px;
        }
        @media print {
            .page { page-break-after: always; }
        }
    </style>
</head>
<body>
    <!-- Copia 1: Administración -->
    <div class="page">
        <div class="copia-header">--- COPIA PARA ADMINISTRACIÓN ---</div>
        {!! $copias['Administracion']->output() !!}
    </div>

    <!-- Copia 2: Cliente -->
    <div class="page">
        <div class="copia-header">--- COPIA PARA CLIENTE ---</div>
        {!! $copias['Cliente']->output() !!}
    </div>

    <!-- Copia 3: Instalador -->
    <div class="page">
        <div class="copia-header">--- COPIA PARA INSTALADOR ---</div>
        {!! $copias['Instalador']->output() !!}
    </div>

    <script>
        window.print();
    </script>
</body>
</html>