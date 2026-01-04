<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

use Exception;
use Throwable;

final class PlainFormatter implements FormatterInterface
{
    private function buildFormattedDiff(array $diff): array
    {
        $result = [];
        foreach ($diff as $key => $value) {
            if ($value['type'] == 'nested') {
                $valueCombined = array_combine(
                    array_map(
                        function ($item) use ($key) {
                            return "$key.$item";
                        },
                        array_keys($value['value_old'])
                    ),
                    array_values($value['value_old'])
                );

                $result[] = $this->buildFormattedDiff($valueCombined);
                continue;
            }

            try {
                $formattedData = $this->formatData(
                    $value['type'],
                    $key,
                    $value['value_old'],
                    $value['value_new'] ?? null
                );
            } catch (Throwable $e) {
                throw new Exception("Ошибка ключа $key " . PHP_EOL . $e->getMessage());
            }

            foreach ($formattedData as $formattedValue) {
                $result[] = $formattedValue;
            }
        }

        return $result;
    }

    private function flatArray(array $array): array
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

    private function formatData(string $type, string $firstKey, mixed $firstValue, mixed $secondValue = null): array
    {

        switch ($type) {
            case 'unchanged':
                return [];

            case 'changed':
                $firstValue = is_array($firstValue) ? '[complex value]' : $firstValue;
                $secondValue = is_array($secondValue) ? '[complex value]' : $secondValue;

                return [
                    sprintf("Property '%s' was updated. From '%s' to '%s'", $firstKey, $firstValue, $secondValue)
                ];

            case 'added':
                return [
                    sprintf(
                        "Property '$firstKey' was added with value: %s",
                        is_array($firstValue) ? '[complex value]' : $firstValue
                    )
                ];

            case 'removed':
                return [
                    "Property '$firstKey' was removed"
                ];
        }

        throw new Exception('Undefined type');
    }

    public function format(array $diff): mixed
    {
        $diff = $this->flatArray($this->buildFormattedDiff($diff));
        return implode(PHP_EOL, $diff);
    }
}
