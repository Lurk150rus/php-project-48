<?php

declare(strict_types=1);

namespace Differ\Differ\Parsers;

interface ParserInterface
{
    public function __construct(string $path);

    public function getParsedData(): array;
}
