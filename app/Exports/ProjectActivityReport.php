<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\Project;
use App\Models\ProjectActivity;
use Carbon\Carbon;
class ProjectActivityReport implements FromCollection, WithHeadings, WithMapping, 
WithCustomStartCell, WithStyles, WithEvents, WithColumnWidths
{
    
    protected $project_id;
    protected $index = 0;


    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }
 
    public function registerEvents(): array {

        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->mergeCells('B2:H2');
                $sheet->setCellValue('B2', "Report Project Activity");

                $headerStyle = [
                    'font' => [
                        'size' => '18',
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $project = Project::with(['branch', 'sales'])
                ->where('projects.id', $this->project_id)
                ->first();
                
                $sheet->setCellValue('B7', "Nama");
                $sheet->setCellValue('B8', "Alamat");
                $sheet->setCellValue('B9', "Nilai Proyek");
                $sheet->setCellValue('B10', "Nama Konsumen");
                $sheet->setCellValue('B11', "No HP Konsumen");
                $sheet->setCellValue('B12', "Email Konsumen");
                $sheet->setCellValue('B13', "Nama CP");
                $sheet->setCellValue('B14', "No HP CP");
                $sheet->setCellValue('B15', "Posisi CP");
                $sheet->setCellValue('B16', "Cabang");
                $sheet->setCellValue('B17', "Sales");

                
                $sheet->setCellValue('C7', ":");
                $sheet->setCellValue('C8', ":");
                $sheet->setCellValue('C9', ":");
                $sheet->setCellValue('C10', ":");
                $sheet->setCellValue('C11', ":");
                $sheet->setCellValue('C12', ":");
                $sheet->setCellValue('C13', ":");
                $sheet->setCellValue('C14', ":");
                $sheet->setCellValue('C15', ":");
                $sheet->setCellValue('C16', ":");
                $sheet->setCellValue('C17', ":");
                
                $sheet->setCellValue('D7', $project->name);
                $sheet->setCellValue('D8', $project->address);
                $sheet->setCellValue('D9', $project->amount);
                $sheet->setCellValue('D10', $project->customer_name);
                $sheet->setCellValue('D11', $project->customer_phone);
                $sheet->setCellValue('D12', $project->customer_email);
                $sheet->setCellValue('D13', $project->cp_name);
                $sheet->setCellValue('D14', $project->cp_phone);
                $sheet->setCellValue('D15', $project->cp_position);
                $sheet->setCellValue('D16', $project->branch->name);
                $sheet->setCellValue('D17', $project->sales->name);


                $event->sheet->getDelegate()->getStyle('B2:H2')->applyFromArray($headerStyle);
            },
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProjectActivity::where('project_id', '=', $this->project_id)->get();
        // when()
    }

    public function headings(): array
    {
        $label = [
            '#',
            'Tanggal',
            'Keterangan',
        ];

        return $label;
    }

    public function map($data): array
    {
        $result = [
            ++$this->index,
            Carbon::parse($data->date)->format('d-m-Y'),
            $data->description
        ];

        return $result;
    }

    
    public function columnWidths(): array
    {
        $column = [
            'A' => 8.43,
            'B' => 17,   
            'C' => 3,
            'D' => 40, 
            'E' => 10, 
            'F' => 3.5, 
            'G' => 14,
            'H' => 45,
        ];

        return $column;
    }


    public function startCell(): string {
        return 'F7';
    }
    
    public function styles(Worksheet $sheet) {
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'b6c2d4']
            ],
        ];
        $sheet->getStyle('F7:H7')->applyFromArray($styleArray);
        $sheet->getStyle('B')->getAlignment()->setHorizontal('right');
        $sheet->getStyle('F')->getAlignment()->setHorizontal('center');
    }
}
