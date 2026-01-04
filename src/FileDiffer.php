<?php

namespace Hexlet\Code;

use Exception;
use Hexlet\Code\Formatters\FileFormatterFactory;
use Throwable;

final class FileDiffer
{
    private $first;
    private $second;
    private $resultDiff;

    private $formatter;

    public function __construct(array $first, array $second, string $format = '')
    {
        $this->first = $first;
        $this->second = $second;
        $this->formatter = FileFormatterFactory::createFormatter($format);
    }

    private function getUniqueKeys($first, $second): array
    {
        $uniqueKeys = array_unique([...array_keys($first), ...array_keys($second)]);
        sort($uniqueKeys);
        return $uniqueKeys;
    }


    private function makeResultDiff($first, $second)
    {
        // 1. Формируем ключи.
        $uniqueKeys = $this->getUniqueKeys($first, $second);

        $result = [];

        foreach ($uniqueKeys as $key) {
            // 2. Проверяем на наличие ключа в обоих массивах.
            if (isset($first[$key]) && isset($second[$key])) {
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
            if (isset($first[$key])) {
                $result[$key] = ['type' => 'removed', 'value_old' => $first[$key]];
                continue;
            }

            // 3. Проверяем на наличие ключа только в одном из массивов.
            if (isset($second[$key])) {
                $result[$key] = ['type' => 'added', 'value_old' => $second[$key]];
                continue;
            }
        }
        return $result;
    }

    public function getResultDiff()
    {
        if (! isset($this->resultDiff)) {
            $diff = $this->makeResultDiff($this->first, $this->second);
            $this->resultDiff = $this->formatter->format($diff);
        }

        return $this->resultDiff;
    }
}
