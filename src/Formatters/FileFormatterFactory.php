<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters;

final class FileFormatterFactory
{
    public static function createFormatter(string $formatter_type = ''): FormatterInterface
    {
        return match ($formatter_type) {
            'plain' => new PlainFormatter(),
            'json' => new JsonFormatter(),
            default => new StylishFormatter()
        };
    }
}
