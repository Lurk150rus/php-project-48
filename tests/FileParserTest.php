<?php

declare(strict_types=1);

namespace Differ\Differ\Tests;

use Differ\Differ\FileParser;
use PHPUnit\Framework\TestCase;

final class FileParserTest extends TestCase
{
    /**
     * Summary of firstArray
     * @var array
     */
    private array $firstArray;
    /**
     * Summary of secondArray
     * @var array
     */
    private array $secondArray;

    /**
     * Summary of dirpath
     * @var string
     */
    private string $dirpath;

    /**
     * Summary of setUp
     * @return void
     */
    public function setUp(): void
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

        $this->dirpath = __DIR__ . '/fixtures/';
    }

    /**
     * Summary of testCorrect
     * @return void
     */
    public function testCorrect(): void
    {
        $filePath1 = $this->dirpath . 'file1.json';
        $filePath2 = $this->dirpath . 'file2.json';
        $filePathEmpty = $this->dirpath . 'empty.json';

        $first = new FileParser($filePath1);
        $second = new FileParser((string) realpath($filePath2));
        $empty = new FileParser($filePathEmpty);

        $this->assertEquals($this->firstArray, $first->getParsedData());
        $this->assertEquals($this->secondArray, $second->getParsedData());

        $this->assertEmpty($empty->getParsedData());
    }

    /**
     * Summary of testUndefined
     * @return void
     */
    public function testUndefined(): void
    {
        $this->expectException(\Exception::class);
        $fileUndefined = 'undefined.json';

        new FileParser($fileUndefined);
    }
}
