<?php

declare(strict_types=1);

namespace Differ\Differ\Parsers;

final class JsonParser extends AbstractParser
{
    /**
     * Summary of parse
     * @return void
     */
    protected function parse(): void
    {
        $this->parsedData = json_decode($this->fileContent, true) ?? [];
    }
}
