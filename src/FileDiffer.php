<?php

namespace Hexlet\Code;

use Exception;
use Throwable;

final class FileDiffer
{
    private $first;
    private $second;
    private array $resultDiff;

    const TYPES = ['added', 'removed', 'unchanged', 'changed'];

    public function __construct(array $first, array $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    private function getUniqueKeys($first, $second): array
    {
        $uniqueKeys = array_unique([...array_keys($first), ...array_keys($second)]);
        sort($uniqueKeys);
        return $uniqueKeys;
    }

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

    private function makeResultDiff($first, $second)
    {
        // 1. Формируем ключи.
        $unique_keys = $this->getUniqueKeys($first, $second);

        $result = [];

        foreach ($unique_keys as $key) {

            // 2. Проверяем на наличие ключа в обоих массивах.
            if (isset($first[$key]) && isset($second[$key])) {
                if ($first[$key] === $second[$key]) {
                    $result[$key] = ['type' => 'unchanged', 'value_old' => $first[$key]];
                    continue;
                } else {
                    // TODO: рекурсивная проверка на массив.
                    if (is_array($first[$key]) && is_array($second[$key])) {
                        $result[$key] = ['type' => 'nested', 'value_old' => $this->makeResultDiff($first[$key], $second[$key])];
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

    public function getResultDiff(): array
    {
        if (! isset($this->resultDiff)) {
            $diff = $this->makeResultDiff($this->first, $this->second);
            $this->resultDiff = $this->buildFormattedDiff($diff);
        }

        return $this->resultDiff;
    }
}
