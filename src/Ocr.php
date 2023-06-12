<?php

namespace LaravelEnso\Ocr;

use LaravelEnso\Ocr\Drivers\OcrMyPdf;

class Ocr
{
    public function __construct(private readonly string $file)
    {
    }

    public function text(): string
    {
        return (new OcrMyPdf($this->file))->handle();
    }
}
