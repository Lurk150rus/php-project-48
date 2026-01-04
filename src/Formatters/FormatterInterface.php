<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters;

interface FormatterInterface
{
    public function format(array $diff);
}
