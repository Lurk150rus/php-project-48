<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters;

use Exception;
use Throwable;

final class JsonFormatter implements FormatterInterface
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
                $formatted_data = $this->formatData($value['type'], $key, $value['value_old'], $value['value_new'] ?? null);
            } catch (Throwable $e) {
                throw new Exception("Ошибка ключа $key " . PHP_EOL . $e->getMessage());
            }

            foreach ($formatted_data as [$formatted_key, $formatted_value]) {
                $result[$formatted_key] = $formatted_value;
            }
        }
        return $result;
    }

    private function formatData($type, $first_key, $first_value, $second_value = null): array
    {
        switch ($type) {
            case 'unchanged':
                return [[$first_key, $first_value]];
            case 'changed':
                return [[' - ' . $first_key, $first_value], [' + ' . $first_key, $second_value]];
            case 'added':
                return [[' + ' .  $first_key, $first_value]];
            case 'removed':
                return [[" - $first_key", $first_value]];
        }

        throw new Exception('Undefined type');
    }

    public function format(array $diff)
    {
        return $this->buildFormattedDiff($diff);
    }
}
