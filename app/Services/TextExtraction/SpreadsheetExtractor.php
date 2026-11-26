<?php

namespace App\Services\TextExtraction;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SpreadsheetExtractor
{
    public function getText(string $path): string
    {
        $reader = new Xlsx;

        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);

        $spreadsheet = $reader->load($path);
        $worksheets = $spreadsheet->getAllSheets();

        $sheets = [];
        foreach ($worksheets as $worksheet) {
            $sheets[] = $worksheet->toArray();
        }

        $data = collect($sheets)
            ->flatten()
            ->filter(fn ($value) => ! is_null($value) && $value !== '')
            ->toArray();

        $spreadsheet->disconnectWorksheets();

        return implode("\n", $data);
    }
}
