<?php

namespace App\Exports;

use App\Models\Material;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaterialsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection(): Enumerable
    {
        return Material::with('user:id,name')
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Material',
            'Quantidade',
            'Unidade',
            'Custo Unitário (€)',
            'Valor Total em Stock (€)',
            'Estado do Stock',
            'Registado por',
        ];
    }

    public function map($material): array
    {
        $totalValue = (float) $material->quantity * (float) $material->unit_cost;

        return [
            $material->id,
            $material->name,
            (float) $material->quantity,
            $material->unit,
            number_format((float) $material->unit_cost, 2, ',', '.'),
            number_format($totalValue, 2, ',', '.'),
            $material->isLowStock() ? 'Stock Baixo' : 'OK',
            $material->user?->name ?? 'Sistema',
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '881337']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FCE7F3'],
                ],
            ],
        ];
    }
}
