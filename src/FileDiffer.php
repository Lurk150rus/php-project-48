<?php

namespace Hexlet\Code;

final class FileDiffer
{
    private $first;
    private $second;
    public function __construct($first, $second)
    {
        $this->first = $first;
        $this->second = $second;

        $this->prepareData();
        $this->getResultDiff();
    }

    private function prepareData(): void
    {
        ksort($this->first);
        ksort($this->second);
    }

    public function getResultDiff(): array
    {
        $result = [];
        $index = 0;

        foreach ($this->first as $key => $value) {
            $index++;

            if (isset($this->second[$key]) && $this->second[$key] === $value) {
                $result[$key] = $value;
                continue;
            }

            if (isset($this->second[$key])) {
                $result[' + ' . $key] = $value;
                $result[' - ' . $key] = $this->second[$key];
                continue;
            }

            $result[' - ' . $key] = $value;
        }

        $tail = array_diff_key($this->second, $this->first);

        $mapTailKeys = array_map(function ($key) {
            return ' + ' . $key;
        }, array_keys($tail));

        $mapTail = array_combine($mapTailKeys, $tail);

        return [...$result, ...$mapTail];
    }
}
