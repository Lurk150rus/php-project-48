<?php

namespace Hexlet\Code;

final class FileDiffer
{
    private $first, $second;
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
        
        foreach($this->first as $key => $value) {
            $index++;
        
            if(isset($this->second[$key]) && $this->second[$key] === $value) {
                $result[$key] = $value;
                continue;
            }

            if(isset($this->second[$key])) {
                $result[' + ' . $key] = $value;
                $result[' - ' . $key] = $this->second[$key];
                continue;
            }

            $result[' - ' . $key] = $value;
        }

        $tail = array_diff_key($this->second, $this->first);

        $map_tail_keys = array_map(function($key) { return ' + ' . $key; }, array_keys($tail));

        $map_tail = array_combine($map_tail_keys, $tail);

        return [...$result, ...$map_tail];
    }
}
