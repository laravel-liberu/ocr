<?php

namespace LaravelEnso\Ocr;

use LaravelEnso\Ocr\Drivers\OcrMyPdf;

class Ocr
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function text(): string
    {
        return (new OcrMyPdf($this->file))->handle();
    }
}
