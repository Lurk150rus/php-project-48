<?php

declare(strict_types=1);

namespace Hexlet\Code;

use Hexlet\Code\Parsers\ParserFactory;
use Hexlet\Code\Parsers\ParserInterface;

/**
 * Парсит файл, возвращает массив
 * Выбрасывает исключение, если файл не найден.
 */
final class FileParser
{
    private ParserInterface $parser;

    public function __construct(string $path)
    {
        $this->parser = ParserFactory::createParser($path);
    }

    /**
     * Summary of getParsedData
     * @return array
     */
    public function getParsedData(): array
    {
        return $this->parser->getParsedData();
    }
}
