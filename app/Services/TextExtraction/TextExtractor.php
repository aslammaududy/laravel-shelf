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
}
