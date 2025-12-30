<?php

declare(strict_types=1);

namespace Hexlet\Code;

use Hexlet\Code\Parsers\JsonParser;
use Hexlet\Code\Parsers\ParserInterface;

final class FileParserFactory
{
    public static function createParser(string $path): ParserInterface
    {
        return new JsonParser($path);
    }
}
