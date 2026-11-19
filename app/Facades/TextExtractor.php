<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\TextExtraction\TextExtractor
 */
class TextExtractor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\TextExtraction\TextExtractor::class;
    }
}
