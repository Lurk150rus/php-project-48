<?php

namespace Hexlet\Code;

function genDiff(string $firstFilePath, string $secondFilePath): array
{
    $files = array_filter([trim($firstFilePath), trim($secondFilePath)]);

    if (count($files) !== 2) {
        throw new \Exception("Invalid number of files");
    }

    [$firstFilePath, $secondFilePath] = $files;
    $pathinfo = pathinfo($firstFilePath, PATHINFO_EXTENSION);

    var_dump($pathinfo);

    $firstFileData = (new FileParser($firstFilePath))->getParsedData();
    $secondFileData = (new FileParser($secondFilePath))->getParsedData();

    return (new FileDiffer($firstFileData, $secondFileData))->getResultDiff();
}
