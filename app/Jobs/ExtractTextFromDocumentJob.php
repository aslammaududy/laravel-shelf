<?php

namespace App\Jobs;

use App\Facades\TextExtractor;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtractTextFromDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Document $document) {}

    public function handle(): void
    {
        $contentText = match ($this->document->mime_type) {
            'application/pdf' => TextExtractor::fromPdf($this->document->storage_key),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => TextExtractor::fromWord($this->document->storage_key),
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => TextExtractor::fromSpreadsheet($this->document->storage_key),
            default => null
        };

        $this->document->content_text = $contentText;
        $this->document->save();
    }
}
