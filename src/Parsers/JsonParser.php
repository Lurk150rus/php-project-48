<?php

declare(strict_types=1);

namespace Hexlet\Code\Parsers;

final class JsonParser implements ParserInterface
{
    /**
     * Путь к файлу
     * @var string
     */
    private string $path;
    /**
     * Строчное представление, содержимого файла
     * @var string
     */
    private string $file_content;
    /**
     * Данные, в формате массива
     * @var array
     */
    private array $parsed_data;

    /**
     * @inheritDoc
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->getContent();
        $this->parse();
    }
    /**
     * Summary of getContent
     * @throws \Exception
     * @return void
     */
    private function getContent(): void
    {
        if (!file_exists($this->path)) {
            throw new \Exception("File not found");
        }
        $this->file_content = (string) file_get_contents($this->path);
    }

    /**
     * Summary of parse
     * @return void
     */
    private function parse(): void
    {
        $this->parsed_data = json_decode($this->file_content, true) ?? [];
    }

    /**
     * Summary of getParsedData
     * @return array
     */
    public function getParsedData(): array
    {
        return $this->parsed_data;
    }
}
