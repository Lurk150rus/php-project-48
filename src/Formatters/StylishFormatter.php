<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters;

use Exception;
use Throwable;

final class StylishFormatter implements FormatterInterface
{
    private function buildFormattedDiff($diff)
    {
        $result = [];
        foreach ($diff as $key => $value) {
            if ($value['type'] == 'nested') {
                $result[$key] = $this->buildFormattedDiff($value['value_old']);
                continue;
            }

            try {
                $formatteData = $this->formatData($value['type'], $key, $value['value_old'], $value['value_new'] ?? null);
            } catch (Throwable $e) {
                throw new Exception("Ошибка ключа $key " . PHP_EOL . $e->getMessage());
            }

            foreach ($formatteData as [$formattedKey, $formattedValue]) {
                $result[$formattedKey] = $formattedValue;
            }
        }
        return $result;
    }

    private function formatData($type, $firstKey, $firstValue, $second_value = null): array
    {
        switch ($type) {
            case 'unchanged':
                return [[$firstKey, $firstValue]];
            case 'changed':
                return [[' - ' . $firstKey, $firstValue], [' + ' . $firstKey, $second_value]];
            case 'added':
                return [[' + ' .  $firstKey, $firstValue]];
            case 'removed':
                return [[" - $firstKey", $firstValue]];
        }

        throw new Exception('Undefined type');
    }

    public function format(array $diff)
    {
        return $this->buildFormattedDiff($diff);
    }
}
