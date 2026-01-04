<?php

declare(strict_types=1);

namespace Hexlet\Code\Parsers;

use Symfony\Component\Yaml\Yaml;

final class YamlParser extends AbstractParser
{
    /**
     * Summary of parse
     * @return void
     */
    protected function parse(): void
    {
        $this->parsedData = Yaml::parse($this->fileContent) ?? [];
    }
}
