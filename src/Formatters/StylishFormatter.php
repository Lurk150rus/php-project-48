<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

use Exception;
use Symfony\Component\Yaml\Yaml;
use Throwable;

final class StylishFormatter implements FormatterInterface
{
    private function buildFormattedDiff(array $diff, int $depth = 1): string
    {
        $result = '';

        $indentBase = str_repeat(' ', $depth * 4);

        foreach ($diff as $key => $value) {
            if ($value['type'] == 'nested') {
                $result .= PHP_EOL . $indentBase;
                $result .= "$key: {";
                $result .= $this->buildFormattedDiff($value['value_old'], $depth + 1);
                $result .= PHP_EOL . str_repeat(' ', $depth * 4) . "}";
                continue;
            }

            try {
                $formatteData = $this->formatData(
                    $value['type'],
                    $key,
                    $value['value_old'],
                    $value['value_new'] ?? null
                );
            } catch (Throwable $e) {
                throw new Exception("Ошибка ключа $key " . PHP_EOL . $e->getMessage());
            }

            foreach ($formatteData as [$formattedKey, $formattedValue]) {
                $result .= PHP_EOL . $indentBase;
                if (is_array($formattedValue)) {
                    $result .= "$key: {";
                    $data = $this->formatArray([$formattedKey, $formattedValue], $depth += 1);
                    $result .= $data;
                    $result .= PHP_EOL . str_repeat(' ', $depth * 4) . "}";
                    continue;
                }
                $result .= "$formattedKey: $formattedValue";
            }
        }
        return $result;
    }
    private function formatArray(mixed $value, int $depth = 1): string
    {
        $result = '';
        $indentBase = str_repeat(' ', $depth * 4);
        foreach ($value as $item) {
            if (is_array($item)) {
                $result .= PHP_EOL . $indentBase;
                $result .= "{";
                $result .= $this->formatArray(
                    $item,
                    $depth + 1
                );
                $result .= PHP_EOL . str_repeat(' ', $depth * 4) . "}";
            } else {
                $result .= PHP_EOL . $indentBase;
                $result .= "$item";
            }
        }

        return $result;
    }
    private function formatData(string $type, string $firstKey, mixed $firstValue, mixed $secondValue = null): array
    {
        if (is_bool($firstValue)) {
            $firstValue = $firstValue ? 'true' : 'false';
        }

        if (is_bool($secondValue)) {
            $secondValue = $secondValue ? 'true' : 'false';
        }

        switch ($type) {
            case 'unchanged':
                return [['   ' . $firstKey, $firstValue]];
            case 'changed':
                return [[' - ' . $firstKey, $firstValue], [' + ' . $firstKey, $secondValue]];
            case 'added':
                return [[' + ' .  $firstKey, $firstValue]];
            case 'removed':
                return [[" - $firstKey", $firstValue]];
        }

        throw new Exception('Undefined type');
    }

    public function format(array $diff): mixed
    {
        $formattedData = '{';
        $formattedData .= $this->buildFormattedDiff($diff);
        $formattedData .= PHP_EOL . '}';

        return $formattedData;
    }
}
