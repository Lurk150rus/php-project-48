<?php

declare(strict_types=1);

namespace Hexlet\Code;

final class FileParser
{
    private $path;
    private $file_content;
    private $parsed_data;
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->getContent();
        $this->parse();
    }

    private function getContent(): void
    {
        if (!file_exists($this->path)) {
            throw new \Exception("File not found");
        }
        $this->file_content = file_get_contents($this->path);
    }

    private function parse(): void
    {
        $this->parsed_data = json_decode($this->file_content, true) ?? [];
    }

    public function print(): void
    {
        print_r($this->parsed_data);
    }

    public function getParsedData(): array
    {
        return $this->parsed_data;
    }
}
