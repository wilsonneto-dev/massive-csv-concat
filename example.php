<?php

include_once('CsvUtils.class.php');

header("Content-Type: text/plain");

$time_init = microtime(true);

// tnhe csv concatenation
$result = Workana\Utils\CsvUtils::_concat(
    [
        "csv\\input1.txt",
        "csv\\input2.txt",
        "csv\\input3.txt",
        "csv\\full1.csv",
        "csv\\full1.csv"
    ],
    "csv/output.txt"
);

$time_elapsed_secs = microtime(true) - $time_init;


echo json_encode($result, JSON_PRETTY_PRINT);
echo "\nExecution secs: $time_elapsed_secs";

?>