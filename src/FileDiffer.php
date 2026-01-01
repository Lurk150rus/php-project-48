<?php

namespace Hexlet\Code;

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

    private function prepareData($first, $second): array
    {
        ksort($first);
        ksort($second);

        return [$first, $second];
    }

    private function formatData($type, $first_key, $first_value, $second_value = null): void
    {
        switch ($type) {
            case 'unchanged':
                $this->resultDiff[$first_key] = $first_value;
                return;
            case 'changed':
                $this->resultDiff[' + ' . $first_key] = $first_value;
                $this->resultDiff[' - ' . $first_key] = $second_value;
                return;
            case 'added':
                $this->resultDiff[' + ' . $first_key] = $first_value;
                return;
            case 'removed':
                $this->resultDiff[' - ' . $first_key] = $first_value;
                return;
        }
    }

    private function makeResultDiff($first, $second)
    {
        // 1. Подготовка входных данных
        [$first, $second] = $this->prepareData($first, $second);

        $this->resultDiff = [];

        // 2. Основной цикл сравнения ключей из первого массива
        foreach ($first as $key => $value) {

            // 3. Обработка одинаковых ключей
            if (isset($second[$key]) && $second[$key] === $value) {
                $this->formatData('unchanged', $key, $value);
                continue;
            }

            // 4. Обработка изменённых значений
            if (isset($second[$key])) {

                // 4.1. Сравнение вложенных массивов
                // if (is_array($value) && is_array($second[$key])) {
                //     $this->makeResultDiff($value, $second[$key]);
                //     continue;
                // }

                $this->formatData('changed', $key, $value, $second[$key]);
                continue;
            }

            $this->formatData('removed', $key, $value); // 5. Удалённые ключи: ключи, которые есть только в первом массиве и отсутствуют во втором массиве
        }

        // 5. Хвост: ключи, которые есть только во втором массиве
        $tail = array_diff_key($second, $first);

        foreach ($tail as $key => $value) {
            $this->formatData('added', $key, $value);
        }
    }

    public function getResultDiff(): array
    {
        if (! isset($this->resultDiff)) {
            $this->makeResultDiff($this->first, $this->second);
        }

        return $this->resultDiff;
    }
}
