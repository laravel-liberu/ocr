<?php

namespace LaravelEnso\Ocr\Drivers;

use LaravelEnso\Ocr\Exceptions\CommandException;

class OcrMyPdf
{
    public function run($pdfPath)
    {
        return $this->checkExecutable()
            ->execute($pdfPath);
    }

    private function checkExecutable()
    {
        if (file_exists('ocrmypdf')) {
            return $this;
        }

        $command = $this->isWindows()
            ? 'where.exe ocrmypdf > NUL 2>&1'
            : 'type ocrmypdf > /dev/null 2>&1';

        exec($command, $result, $exitCode);

        if ($exitCode !== 0) {
            throw CommandException::notFound($result);
        }

        return $this;
    }

    private function execute($file)
    {
        $txtFile = $this->tempFile();
        $pdfFile = $this->tempFile();

        $command = "ocrmypdf $file {$pdfFile} --sidecar {$txtFile} --force-ocr 2>&1";

        $string = exec($command, $result, $exitCode);

        if ($exitCode !== 0) {
            throw CommandException::executionFailed($result);
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
