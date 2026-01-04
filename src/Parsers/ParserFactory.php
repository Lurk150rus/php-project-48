<?php

declare(strict_types=1);

namespace Differ\Differ\Parsers;

use Differ\Differ\Parsers\JsonParser;
use Differ\Differ\Parsers\ParserInterface;
use Differ\Differ\Parsers\YamlParser;

final class ParserFactory
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
