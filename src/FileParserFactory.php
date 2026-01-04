<?php

declare(strict_types=1);

namespace Hexlet\Code;

use Hexlet\Code\Parsers\JsonParser;
use Hexlet\Code\Parsers\ParserInterface;
use Hexlet\Code\Parsers\YamlParser;

final class FileParserFactory
{
    public static function createParser(string $path): ParserInterface
    {
        $pathinfo = pathinfo($path, PATHINFO_EXTENSION);

        return match ($pathinfo) {
            'json' => new JsonParser($path),
            'yml', 'yaml' => new YamlParser($path),
            default => throw new \RuntimeException('Unsupported file type')
        };
    }
}
