<?php

namespace LaravelEnso\Ocr\Drivers;

use LaravelEnso\Ocr\Exceptions\Command;

class OcrMyPdf
{
    public function __construct(private string $file)
    {
    }

    public function handle(): string
    {
        return $this->checkExecutable()
            ->execute();
    }

    private function checkExecutable(): self
    {
        if (file_exists('ocrmypdf')) {
            return $this;
        }

        $command = $this->isWindows()
            ? 'where.exe ocrmypdf > NUL 2>&1'
            : 'type ocrmypdf > /dev/null 2>&1';

        exec($command, $result, $exitCode);

        if ($exitCode !== 0) {
            throw Command::notFound($result);
        }

        return $this;
    }

    private function execute(): string
    {
        $txtFile = $this->tempFile();
        $pdfFile = $this->tempFile();

        $command = "ocrmypdf {$this->file} {$pdfFile} --sidecar {$txtFile} --force-ocr 2>&1";

        exec($command, $result, $exitCode);

        if ($exitCode !== 0) {
            throw Command::executionFailed($result);
        }

        $text = file_get_contents($txtFile);
        unlink($txtFile);
        unlink($pdfFile);

        return $text;
    }

    private function tempFile()
    {
        return tempnam(sys_get_temp_dir(), 'ocr');
    }

    private function isWindows(): bool
    {
        return stripos(PHP_OS, 'WIN') === 0;
    }
}
