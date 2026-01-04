<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters;

use Exception;
use Throwable;

final class PlainFormatter implements FormatterInterface
{

    private function buildFormattedDiff($diff)
    {
        $result = [];
        foreach ($diff as $key => $value) {

            if ($value['type'] == 'nested') {
                $value_combined = array_combine(
                    array_map(
                        function ($item) use ($key) {
                            return "$key.$item";
                        },
                        array_keys($value['value_old'])
                    ),
                    array_values($value['value_old'])
                );

                $result[] = $this->buildFormattedDiff($value_combined);
                continue;
            }

            try {
                $formatted_data = $this->formatData($value['type'], $key, $value['value_old'], $value['value_new'] ?? null);
            } catch (Throwable $e) {
                throw new Exception("Ошибка ключа $key " . PHP_EOL . $e->getMessage());
            }

            foreach ($formatted_data as $formatted_value) {
                $result[] = $formatted_value;
            }
        }

        return $result;
    }

    private function flatArray($array): array
    {
        $result = [];

        foreach ($array as $value) {

            if (is_array($value)) {
                $result = array_merge($result, $this->flatArray($value));
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    private function formatData($type, $first_key, $first_value, $second_value = null): array
    {

        switch ($type) {
            case 'unchanged':
                return [];

            case 'changed':
                $first_value = is_array($first_value) ? '[complex value]' : $first_value;
                $second_value = is_array($second_value) ? '[complex value]' : $second_value;

                return [
                    sprintf("Property '%s' was updated. From '%s' to '%s'", $first_key, $first_value, $second_value)
                ];

            case 'added':
                return [
                    sprintf("Property '$first_key' was added with value: %s", is_array($first_value) ? '[complex value]' : $first_value)
                ];

            case 'removed':
                return [
                    "Property '$first_key' was removed"
                ];
        }

        throw new Exception('Undefined type');
    }

    public function format(array $diff)
    {
        $diff = $this->flatArray($this->buildFormattedDiff($diff));
        return $diff;
    }
}
