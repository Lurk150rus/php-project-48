<?php

require_once __DIR__ . '/vendor/autoload.php';

use function Hexlet\Code\genDiff;

set_time_limit(1);

$recursive = true;

$f1 = sprintf('tests/fixtures/file3%s.json', $recursive ? '_recursive' : '');
$f2 = sprintf('tests/fixtures/file4%s.json', $recursive ? '_recursive' : '');


$diff_data = genDiff($f1, $f2);

$json = json_encode($diff_data);

file_put_contents('tests/fixtures/result.json', $json);

echo ('<pre>');
var_dump($json);
echo ('</pre>');