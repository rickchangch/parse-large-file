<?php

if (!isset($argv[1])) {
    echo "lost taget file";
    exit;
}

$start = time();

$destPath = "../data/split_files";
!is_dir($destPath) && mkdir($destPath, 0777, true);

$types = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f'];
$fpws = [];
foreach($types as $idx => $c) {
    $fpws["U{$c}"] = fopen("{$destPath}/U{$c}.csv", "w");
}

$line = 0;
$fp = fopen($argv[1], "r");
while ($rowJsonData = fgets($fp)) {
    $rowData = json_decode($rowJsonData, true);
    $type = substr($rowData['user_id'], 0, 2);
    fwrite($fpws[$type], $rowJsonData);

    $line++;
    if ($line % 1000000 == 0) {
        $time = time() - $start;
        echo "progress: {$line} rows, time: {$time} sec\n";
    }
}
fclose($fp);

foreach($fpws as $type => $fpw) {
    fclose($fpw);
}

echo time() - $start . ' sec';
