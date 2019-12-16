<?php

namespace LaravelEnso\Ocr\Exceptions;

use Exception;

class Command extends Exception
{
    public static function notFound($result)
    {
        return new static('Executable Not Found'.PHP_EOL
            .implode(PHP_EOL, $result));
    }

    public static function executionFailed($result)
    {
        return new static('Cannot scan the document'.PHP_EOL
            .implode(PHP_EOL, $result));
    }
}
