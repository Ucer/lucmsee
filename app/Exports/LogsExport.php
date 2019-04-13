<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;    // 导出集合
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;     // 自动注册事件监听器
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;    // 在工作表流程结束时会引发事件

class LogsExport implements FromCollection, WithTitle, WithHeadings, WithEvents, WithStrictNullComparison, ShouldAutoSize
{
    protected $baseCollection;
    protected $title;

    public function __construct($collectionData, $title)
    {
        $this->baseCollection = $collectionData;
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }

    // set the headings
    public function headings(): array
    {
        return [
            ['日志记录'],
            ['操作人ID', '表名', '日志类型', '操作人IP', '描述', '记录时间']
        ];
    }


    public function registerEvents(): array
    {
        // 更多示例参考 ： https://phpspreadsheet.readthedocs.io
        return [
            AfterSheet::class => function (AfterSheet $event) { // php
                $sheetGetDelegate = $event->sheet->getDelegate();
                $sheet = $event->sheet;


                $sheet->getDelegate()->setMergeCells(['A1:G1']); // 合并
                $sheetGetDelegate->getStyle('A1:A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); //
                $sheetGetDelegate->getStyle('A1')->getFont()->setBold(true)->setSize('30'); // 字体大小
                $sheetGetDelegate->getStyle('A4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED); // 字体颜色
                $sheetGetDelegate->getColumnDimension('B')->setWidth(300); // 单元格宽度
//                $sheetGetDelegate->getDefaultColumnDimension()->setAutoSize(true);

                $sheetGetDelegate->getRowDimension(1)->setRowHeight(50); // 将第一行行高设置为50

                // 设置 A1:D4 范围内文本自动换行
                $sheetGetDelegate->getStyle('A1:D4')
                    ->getAlignment()->setWrapText(true);



                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical_center' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [ // 边框
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                        'diagonal' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];
                $sheetGetDelegate->getStyle('A1:F8')->applyFromArray($styleArray);
            },
        ];
    }

    /**
     * 需要导出的数据统一在这个方法里面处理 这个方法里面也可以直接用 Model取数据
     * 我这里的数据是 Controller 传过来的，至于怎么传的看下面给出的 Controller 里面的代码就知道了
     * 里面数据处理太长了，多余的我都用 ... 表示，大家明白就行
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = [];
        foreach ($this->baseCollection as $item) {
            $data[] = [
                'A' => $item->user_id,
                'B' => $item->table_name,
                'C' => $item->type,
                'D' => $item->ip,
                'E' => $item->description,
                'F' => $item->created_at,
            ];
        }
        return collect($data);
    }


}
