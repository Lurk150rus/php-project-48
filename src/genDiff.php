<?php

namespace Hexlet\Code;

function genDiff($firstFilePath, $secondFilePath)
{
    $files = array_filter([$firstFilePath, $secondFilePath]);
    if (count($files) !== 2) {
        throw new \Exception("Invalid number of files");
    }

    $firstFileData = (new FileParser($firstFilePath))->getParsedData();
    $secondFileData = (new FileParser($secondFilePath))->getParsedData();

    return (new FileDiffer($firstFileData, $secondFileData))->getResultDiff();
}
