<?php

namespace App\Services\TextExtraction;

use DOMDocument;
use DOMXPath;
use RuntimeException;
use ZipArchive;

class WordExtractor
{
    /**
     * @throws \Throwable
     */
    public function getText(string $path): string
    {
        $zip = new ZipArchive;

        throw_if($zip->open($path) !== true, RuntimeException::class, "Unable to open docx file {$path}");

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        throw_if(! $xml, RuntimeException::class, "word/document.xml missing in {$path}");

        $dom = new DOMDocument;

        throw_if(! $dom->loadXML($xml, LIBXML_NONET), RuntimeException::class, "Invalid document.xml in {$path}");

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $paragraphs = [];
        foreach ($xpath->query('//w:body//w:p') as $paragraph) {
            $runs = $xpath->query('.//w:t | .//w:tab | .//w:br', $paragraph);
            $paragraphs[] = implode('', array_map(fn ($node) => $node->localName === 't' ? $node->textContent : ' ', iterator_to_array($runs)));
        }

        return implode("\n", $paragraphs);
    }
}
