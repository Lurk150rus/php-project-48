<?php

declare(strict_types=1);

namespace Differ\Differ\Parsers;

abstract class AbstractParser implements ParserInterface
{
    protected string $path;
    protected string $fileContent;
    protected array $parsedData = [];

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->loadContent();
        $this->parse();
    }

    protected function loadContent(): void
    {
        if (!file_exists($this->path)) {
            throw new \RuntimeException('File not found');
        }

        $this->fileContent = (string) file_get_contents($this->path);
    }

    abstract protected function parse(): void;

    public function getParsedData(): array
    {
        return $this->parsedData;
    }
}
