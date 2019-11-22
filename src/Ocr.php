<?php

namespace LaravelEnso\Ocr;

use LaravelEnso\Ocr\Drivers\OcrMyPdf;

class Ocr
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function text()
    {
        return (new OcrMyPdf())->run($this->file);
    }
}
