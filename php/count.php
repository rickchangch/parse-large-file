<?php

if (!isset($argv[1])) {
    echo "lost category";
    exit;
}

$start = time();

$dataSource = $argv[1];
$readResource = fopen($dataSource, "r");

$result = [];
$line = 0;
while (($rawData = fgets($readResource)) != false) {
    $data = json_decode($rawData, true);

    // skip empty row
    if (!isset($data)) { continue; }

    // count tags
    if (!isset($result[$data['tag_id']])) {
        $result[$data['tag_id']] = [
            'tag_name' => $data['tag_name'],
            'count' => 1,
        ];
    } else {
        $result[$data['tag_id']]['count']++;
    }

    $line++;
    if ($line % 1000000 == 0) {
        $time = time() - $start;
        echo "progress: {$line} rows, time: {$time} sec\n";
    }
}
fclose($readResource);

// print result
$totalRow = 0;
foreach($result as $tag_id => $row) {
    $totalRow += $row['count'];
    echo "{$tag_id},{$row['tag_name']},{$row['count']}" . PHP_EOL;
}

echo "rows total count: {$totalRow}" . PHP_EOL;

echo time() - $start . 'ms';
