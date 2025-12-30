<?php

namespace Hexlet\Code;

function genDiff($first_file_path, $second_file_path)
{
    $files = array_filter([$first_file_path, $second_file_path]);
    if (count($files) !== 2) {
        throw new \Exception("Invalid number of files");
    }

    $first_file_data = (new FileParser($first_file_path))->getParsedData();
    $second_file_data = (new FileParser($second_file_path))->getParsedData();

    return (new FileDiffer($first_file_data, $second_file_data))->getResultDiff();
}
