<?php

namespace Differ\Differ;

use Differ\Differ\Formatters\FileFormatterFactory;
use Differ\Differ\Formatters\FormatterInterface;

final class FileDiffer
{
    private array $first;
    private array $second;
    private mixed $resultDiff;

    private FormatterInterface $formatter;

    public function __construct(array $first, array $second, string $format = '')
    {
        $this->first = $first;
        $this->second = $second;
        $this->formatter = FileFormatterFactory::createFormatter($format);
    }

    private function getUniqueKeys(array $first, array $second): array
    {
        $uniqueKeys = array_unique([...array_keys($first), ...array_keys($second)]);
        sort($uniqueKeys);
        return $uniqueKeys;
    }


    private function makeResultDiff(array $first, array $second): array
    {
        // 1. Формируем ключи.
        $uniqueKeys = $this->getUniqueKeys($first, $second);

        $result = [];

        foreach ($uniqueKeys as $key) {
            // 2. Проверяем на наличие ключа в обоих массивах.
            if (array_key_exists($key, $first) && array_key_exists($key, $second)) {
                if ($first[$key] === $second[$key]) {
                    $result[$key] = ['type' => 'unchanged', 'value_old' => $first[$key]];
                    continue;
                } else {
                    // 2.1: рекурсивная проверка на массив.
                    if (is_array($first[$key]) && is_array($second[$key])) {
                        $result[$key] = [
                            'type' => 'nested',
                            'value_old' => $this->makeResultDiff($first[$key], $second[$key])
                        ];
                        continue;
                    }

                    $result[$key] = ['type' => 'changed', 'value_old' => $first[$key], 'value_new' => $second[$key]];
                    continue;
                }
            }

            // 3. Проверяем на наличие ключа только в одном из массивов.
            if (array_key_exists($key, $first)) {
                $result[$key] = ['type' => 'removed', 'value_old' => $first[$key]];
                continue;
            }

            // 3. Проверяем на наличие ключа только в одном из массивов.
            if (array_key_exists($key, $second)) {
                $result[$key] = ['type' => 'added', 'value_old' => $second[$key]];
                continue;
            }
        }
        return $result;
    }

    public function getResultDiff(): mixed
    {
        if (! isset($this->resultDiff)) {
            $diff = $this->makeResultDiff($this->first, $this->second);
            $this->resultDiff = $this->formatter->format($diff);
        }

        return $this->resultDiff;
    }
}
