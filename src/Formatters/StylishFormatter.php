<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

use Exception;

final class StylishFormatter implements FormatterInterface
{
    private function stringify(mixed $value, int $depth): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === '') {
            return '';
        }

        if (is_null($value)) {
            return 'null';
        }

        if (!is_array($value)) {
            return (string) $value;
        }

        $indentInner = str_repeat(' ', ($depth + 1) * 4);
        $indentClose = str_repeat(' ', $depth * 4);

        $lines = [];
        foreach ($value as $k => $v) {
            $lines[] = $indentInner . "{$k}: " . $this->stringify($v, $depth + 1);
        }

        return "{\n" . implode("\n", $lines) . "\n" . $indentClose . "}";
    }

    /**
     * Build formatted diff lines (no surrounding root braces).
     *
     * @param array $diff  Diff in structure used by tests (type, value_old, value_new)
     * @param int $depth   Current depth (1 = root children)
     *
     * @return string
     * @throws Exception
     */
    private function buildFormattedDiff(array $diff, int $depth = 1): string
    {
        $lines = [];

        foreach ($diff as $key => $node) {
            $type = $node['type'] ?? null;

            $markerIndent = str_repeat(' ', max(0, $depth * 4 - 2));
            $plainIndent = str_repeat(' ', $depth * 4);

            switch ($type) {
                case 'nested':
                    $lines[] = $plainIndent . "{$key}: " . "{";
                    $inner = $this->buildFormattedDiff($node['value_old'] ?? [], $depth + 1);
                    if ($inner !== '') {
                        $lines[] = $inner;
                    }
                    $lines[] = $plainIndent . "}";
                    break;

                case 'unchanged':
                    $val = $this->stringify($node['value_old'], $depth);
                    $lines[] = $markerIndent . "  " . "{$key}: " . $val;
                    break;

                case 'removed':
                    $val = $this->stringify($node['value_old'], $depth);
                    $lines[] = $markerIndent . "- " . "{$key}: " . $val;
                    break;

                case 'added':
                    $val = $this->stringify($node['value_new'] ?? $node['value_old'], $depth);
                    $lines[] = $markerIndent . "+ " . "{$key}: " . $val;
                    break;

                case 'changed':
                    $valOld = $this->stringify($node['value_old'], $depth);
                    $valNew = $this->stringify($node['value_new'], $depth);
                    $lines[] = $markerIndent . "- " . "{$key}: " . $valOld;
                    $lines[] = $markerIndent . "+ " . "{$key}: " . $valNew;
                    break;

                default:
                    throw new Exception("Undefined type: {$type}");
            }
        }

        return implode("\n", $lines);
    }

    public function format(array $diff): string
    {
        $body = $this->buildFormattedDiff($diff, 1);
        if ($body === '') {
            return "{\n}";
        }
        return "{\n" . $body . "\n}";
    }
}
