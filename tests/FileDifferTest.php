<?php

declare(strict_types=1);

namespace Hexlet\Code\Tests;

use Hexlet\Code\FileDiffer;
use PHPUnit\Framework\TestCase;

final class FileDifferTest extends TestCase
{
    private array $diff, $firstArray, $secondArray;
    public function setUp(): void
    {
        $this->diff = array(
            ' - follow' => false,
            'host' => 'hexlet.io',
            ' - proxy' => '123.234.53.22',
            ' - timeout' => 50,
            ' + timeout' => 20,
            ' + verbose' => true,
        );

        $this->firstArray = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];

        $this->secondArray = [
            "timeout" => 20,
            "verbose" => true,
            "host" => "hexlet.io"
        ];

    }
    public function testCorrectDiff(): void
    {
        $fileDiffer = new FileDiffer($this->firstArray, $this->secondArray);

        $this->assertEquals($this->diff, $fileDiffer->getResultDiff());
    }
}
