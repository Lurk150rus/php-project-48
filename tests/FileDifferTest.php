<?php

declare(strict_types=1);

namespace Hexlet\Code\Tests;

use Hexlet\Code\FileDiffer;
use PHPUnit\Framework\TestCase;

final class FileDifferTest extends TestCase
{
    private array $diff, $firstArray, $secondArray, $recursiveDiff;

    const FIXTURES_DIRECTORY_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;
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
        $this->recursiveDiff = json_decode((string) file_get_contents(self::FIXTURES_DIRECTORY_PATH . 'file3_file4_recursive_result.json'), true);
    }
    public function testCorrectDiff(): void
    {

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

        $fileDiffer = new FileDiffer($this->firstArray, $this->secondArray);

        $this->assertEquals($this->diff, $fileDiffer->getResultDiff());
    }

    public function testRecursiveDiff(): void
    {
        $this->firstArray = json_decode((string) file_get_contents(self::FIXTURES_DIRECTORY_PATH . 'file3_recursive.json'), true);

        $this->secondArray = json_decode((string) file_get_contents(self::FIXTURES_DIRECTORY_PATH . 'file4_recursive.json'), true);

        $fileDiffer = new FileDiffer($this->firstArray, $this->secondArray);

        $this->assertEquals($this->recursiveDiff, $fileDiffer->getResultDiff());
    }
}
