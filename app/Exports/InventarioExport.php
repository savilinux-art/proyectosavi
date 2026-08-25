<?php
namespace App\Exports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventarioExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Inventario::with(['categoriaRelacion', 'modificadoPor'])->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Modelo', 'Descripción', 'Marca', 'Categoría',
            'Existencia', 'Almacén', 'APEA', 'Comentarios',
            'Fecha Modificación', 'Modificado por'
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->modelo,
            $item->descripcion,
            $item->marca,
            $item->categoriaRelacion->nombre_categoria ?? 'N/A',
            $item->existencia,
            $item->almacen,
            $item->apea,
            $item->comentarios ?? '',
            $item->fecha_modificacion,
            $item->modificadoPor->nombre ?? 'N/A'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}