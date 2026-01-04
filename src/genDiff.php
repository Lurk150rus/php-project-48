<?php

namespace Hexlet\Code;

function genDiff(string $firstFilePath, string $secondFilePath, string $format = ''): array
{
    $files = array_filter([trim($firstFilePath), trim($secondFilePath)]);

    if (count($files) !== 2) {
        throw new \Exception("Invalid number of files");
    }

    [$firstFilePath, $secondFilePath] = $files;

    $firstFileData = (new FileParser($firstFilePath))->getParsedData();
    $secondFileData = (new FileParser($secondFilePath))->getParsedData();

    return (new FileDiffer($firstFileData, $secondFileData, $format))->getResultDiff();
}
