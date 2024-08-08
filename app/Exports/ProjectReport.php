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

use App\Models\Project;
class ProjectReport implements FromCollection, WithHeadings, WithMapping, 
WithCustomStartCell, WithStyles, WithEvents, WithColumnWidths
{
    
    protected $search;
    protected $branch_id;
    protected $sales_id;
    protected $status;
    protected $index = 0;


    public function __construct($search, $branch_id, $sales_id, $status)
    {
        $this->search = $search;
        $this->branch_id = $branch_id;
        $this->sales_id = $sales_id;
        $this->status = $status;
    }
 
    public function registerEvents(): array {

        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->mergeCells('B2:M2');
                $sheet->setCellValue('B2', "Report Project List");

                $styleArray = [
                    'font' => [
                        'size' => '20px',
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $cellRange = 'B2:M2';


                $sheet->setCellValue('B2', "Report Project List");

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Project::with(['branch', 'sales'])->get();
        // when()
    }

    public function headings(): array
    {
        $label = [
            '#',
            'Nama Proyek',
            'Alamat Proyek',
            'Nilai Proyek',
            'Nama Konsumen',
            'No HP Konsumen',
            'Email Konsumen',
            'Nama CP',
            'Posisi CP',
            'No HP CP',
        ];


        if(empty($this->branch_id)){
            $label[] = 'Cabang';
        }

        if(empty($this->sales_id)){
            $label[] = 'Sales';
        }

        if(empty($this->status)){
            $label[] = 'Status';
        }

        return $label;
    }

    public function map($data): array
    {
        $result = [
            ++$this->index,
            $data->name,
            $data->address,
            $data->amount,
            $data->customer_name,
            $data->customer_phone,
            $data->customer_email,
            $data->cp_name,
            $data->cp_position,
            $data->cp_phone,
            $data->branch->name,
            $data->sales->name,
            $data->status
            // Date::dateTimeToExcel($invoice->created_at),
        ];

        return $result;
    }

    
    public function columnWidths(): array
    {
        $column = [
            'A' => 3.5,
            'B' => 30,   
            'C' => 36,
            'D' => 20, 
            'E' => 19, 
            'F' => 19, 
            'G' => 20,
            'H' => 19, 
            'I' => 19,
        ];

        if(empty($this->branch_id)){
            $column['K'] = 20;
        }

        if(empty($this->sales_id)){
            $column['L'] = 20;
        }

        if(empty($this->status)){
            $column['M'] = 20;
        }
        return $column;
    }


    public function startCell(): string {
        return 'A7';
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
        $sheet->getStyle('A7:M7')->applyFromArray($styleArray);
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
    }
}
