<?php


namespace app\services;


use app\models\Bookmark;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class BookmarkExcel
{
    private const STYLE_BORDERS = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ]
        ]
    ];

    public function download()
    {
        $this->setHeaders();

        /** @var Bookmark[] $bookmarks */
        $bookmarks = Bookmark::find()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet
            ->setCellValue('A1', 'Закладки')
            ->mergeCellsByColumnAndRow(1, 1, 6, 1);

        $sheet->setCellValue('A2', '№');
        $sheet->setCellValue('B2', 'url');
        $sheet->setCellValue('C2', 'заголовок');
        $sheet->setCellValue('D2', 'meta desciprion');
        $sheet->setCellValue('E2', 'meta keywords');
        $sheet->setCellValue('F2', 'дата добавления');

        $sheet->getColumnDimensionByColumn(2)->setWidth(40);
        $sheet->getColumnDimensionByColumn(3)->setWidth(40);
        $sheet->getColumnDimensionByColumn(4)->setWidth(60);
        $sheet->getColumnDimensionByColumn(5)->setWidth(40);
        $sheet->getColumnDimensionByColumn(6)->setWidth(20);

        $i = 3;
        foreach ($bookmarks as $bookmark) {
            $sheet->setCellValue('A' . $i, $i);
            $sheet->setCellValue('B' . $i, $bookmark->url);
            $sheet->setCellValue('C' . $i, $bookmark->title);
            $sheet->setCellValue('D' . $i, $bookmark->meta_description);
            $sheet->setCellValue('E' . $i, $bookmark->meta_keywords);
            $sheet->setCellValue('F' . $i, $bookmark->created_at);
            $i++;
        }

        $sheet->getStyleByColumnAndRow(1, 1, 6, $i - 1)->applyFromArray(self::STYLE_BORDERS);

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    private function setHeaders()
    {
        $fileName = 'bookmarks.xlsx';

        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header("Cache-Control: no-cache, must-revalidate" );
        header("Pragma: no-cache" );
        header("Content-type: application/vnd.ms-excel" );
        header("Content-Disposition: attachment; filename={$fileName}" );
    }
}
