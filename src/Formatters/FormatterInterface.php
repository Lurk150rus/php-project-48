<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

interface FormatterInterface
{
    public function format(array $diff): mixed;
}
