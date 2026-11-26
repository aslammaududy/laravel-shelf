<?php

namespace App\Services\TextExtraction;

use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class TextExtractor
{
    public function fromPdf(string $path): string
    {
        return Pdf::getText(Storage::path($path));
    }

    public function fromWord(string $path): string
    {
        return (new WordExtractor)->getText(Storage::path($path));
    }

    public function fromSpreadsheet(string $path): string
    {
        return (new SpreadsheetExtractor)->getText(Storage::path($path));
    }
}
