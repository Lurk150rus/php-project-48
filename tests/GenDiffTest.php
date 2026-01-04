<?php

declare(strict_types=1);

namespace Differ\Differ\Tests;

use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

final class GenDiffTest extends TestCase
{
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
        $this->dirpath = __DIR__ . '/fixtures/';
    }

    /**
     * Summary of testCorrectJson
     * @return void
     */
    public function testCorrectJson(): void
    {
        $correctDiff = array(
            ' - follow' => false,
            'host' => 'hexlet.io',
            ' - proxy' => '123.234.53.22',
            ' - timeout' => 50,
            ' + timeout' => 20,
            ' + verbose' => true,
        );

        $filePath1 = $this->dirpath . 'file1.json';
        $filePath2 = $this->dirpath . 'file2.json';

        $diff_data = genDiff($filePath1, $filePath2);
        $this->assertEquals($diff_data, $correctDiff);
    }

    /**
     * Summary of testCorrectJson
     * @return void
     */
    public function testCorrectYaml(): void
    {
        $correctDiff = array(
            ' - follow' => false,
            'host' => 'hexlet.io',
            ' - proxy' => '123.234.53.22',
            ' - timeout' => 50,
            ' + timeout' => 20,
            ' + verbose' => true,
        );

        $filePath1 = $this->dirpath . 'file1.yml';
        $filePath2 = $this->dirpath . 'file2.yaml';

        $diff_data = genDiff($filePath1, $filePath2);

        $this->assertEquals($diff_data, $correctDiff);
    }

    /**
     * Summary of testIncorrectFiles
     * @param array $files
     * @return void
     */
    #[DataProvider('incorrectFilesDataProvider')]
    public function testIncorrectFiles($files): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid number of files');
        genDiff($files[0], $files[1]);
    }

    public static function incorrectFilesDataProvider(): array
    {
        return [
            'empty file' => [['', '']],
            'only spaces' => [['   ', '   ']],
        ];
    }
}
