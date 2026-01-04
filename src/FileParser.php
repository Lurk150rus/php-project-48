<?php

declare(strict_types=1);

namespace Differ\Differ;

use Differ\Differ\Parsers\ParserFactory;
use Differ\Differ\Parsers\ParserInterface;

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
