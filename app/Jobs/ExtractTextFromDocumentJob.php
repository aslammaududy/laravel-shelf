<?php

namespace App\Jobs;

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
            'application/pdf' => \App\Facades\TextExtractor::fromPdf($this->document->storage_key),
        };

        $this->document->content_text = $contentText;
        $this->document->save();
    }
}
