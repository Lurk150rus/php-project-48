<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

final class FileFormatterFactory
{
    public static function createFormatter(string $format = ''): FormatterInterface
    {
        return match ($format) {
            'plain' => new PlainFormatter(),
            'json' => new JsonFormatter(),
            default => new StylishFormatter()
        };
    }
}
