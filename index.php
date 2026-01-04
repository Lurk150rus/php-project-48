<?php

require_once __DIR__ . '/vendor/autoload.php';

use function Differ\Differ\genDiff;

set_time_limit(1);

$recursive = true;

$f1 = sprintf('tests/fixtures/file3%s.json', $recursive ? '_recursive' : '');
$f2 = sprintf('tests/fixtures/file4%s.json', $recursive ? '_recursive' : '');


$diff_data = genDiff($f1, $f2, 'json');

// $json = json_encode($diff_data, JSON_PRETTY_PRINT);

file_put_contents('tests/fixtures/result.json', $diff_data);


// echo ('<pre>');
// print_r($diff_data);
// var_dump($json);
// echo ('</pre>');