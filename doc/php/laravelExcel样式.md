
laravelExcel3.1 是基于 phpspreadsheet

```php
<?php

use PhpOffice\PhpSpreadsheet\Style\Alignment;    // 在工作表流程结束时会引发事件

    public function registerEvents(): array
    {
        // 更多示例参考 ： https://phpspreadsheet.readthedocs.io
        return [
            AfterSheet::class => function (AfterSheet $event) { // php
                $sheetGetDelegate = $event->sheet->getDelegate();
                $sheet = $event->sheet;
                // 合并单元格
                $sheetGetDelegate->setMergeCells(['A1:O1', 'A2:C2', 'D2:O2']);
                // 冻结窗格
                $sheetGetDelegate()->freezePane('A4');
                // 设置单元格水平居中
                $sheetGetDelegate->getStyle('A1:A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // 设置行高
                $sheet->getRowDimension(1)->setRowHeight(50); // 单行行高
                $sheet->getDefaultRowDimension()->setRowHeight(30); // 默认行高
                // 字体
                $sheetGetDelegate->getStyle('A1')->getFont()->setBold(true);
                $sheetGetDelegate->getStyle('A3:F3')->getFont()->setBold(true)->setName('Arial')->setSize('20');
                $sheetGetDelegate->getStyle('A4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                // 列宽
                $sheetGetDelegate->getDefaultColumnDimension()->setAutoSize(true);
                $sheetGetDelegate->getColumnDimension('B')->setAutoSize(true);
                $sheetGetDelegate->getColumnDimension('B')->setWidth(300);
                
                // 设置 A1:D4 范围内文本自动换行
                $sheetGetDelegate->getStyle('A1:D4')
                    ->getAlignment()->setWrapText(true);
                
                // 位置与边框
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical_center' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'outline' => [ // 外边框
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ],
                ];
                $sheetGetDelegate->getStyle('A3:F10')->applyFromArray($styleArray);
            },
        ];
    }


```
