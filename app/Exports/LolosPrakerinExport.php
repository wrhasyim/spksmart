<?php

namespace App\Exports;

use App\Models\Placement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LolosPrakerinExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $academicYearId;

    public function __construct($academicYearId)
    {
        $this->academicYearId = $academicYearId;
    }

    public function view(): View
    {
        $placements = Placement::where('academic_year_id', $this->academicYearId)
            ->where('status_pencocokan', 'final')
            ->with(['student.major', 'company', 'companySlot'])
            ->get()
            ->sortBy('company.name');

        return view('admin.placements.export_excel', [
            'placements' => $placements
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A1:F1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E7FF']
                ]
            ]
        ];
    }
}