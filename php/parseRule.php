<?php

if (!isset($argv[1])) {
    echo "lost file path";
    exit;
}

// start time
$start = round(microtime(true) * 1000);

$dataSource = $argv[1];
$transformMapFile = "rules/transformMap.php";
$targetTagsFile = "rules/targetTags.php";

$loadData = [];
$targetTagsList = [];

$readResource = fopen($dataSource, "r");
// skip csv header
fgetcsv($readResource);
while (($rowData = fgetcsv($readResource)) !== false) {
    $loadData[$rowData[1]] = $rowData;
}
fclose($readResource);

// build transform map
$writeResource = fopen($transformMapFile, "w");
fwrite($writeResource, "<?php" . "\n" . "return [" . "\n");
foreach($loadData as $name => $row) {

    if (empty($row[3]) || empty($row[4])) {
        continue;
    }

    if (!isset($loadData[$row[4]])) {
        echo "lost tag: {$row[4]}" . PHP_EOL;
        continue;
    }

    $targetTag = $loadData[$row[4]];

    fwrite($writeResource, "'{$row[0]}' => ['id' => '{$targetTag[0]}', 'name' => '$targetTag[1]']," . "\n");

    $targetTagsList[] = [$targetTag[0], $targetTag[1]];
}
fwrite($writeResource, "];");
fclose($writeResource);

// build target tag map
$writeResource = fopen($targetTagsFile, "w");
fwrite($writeResource, "<?php" . "\n" . "return [" . "\n");
foreach($targetTagsList as $idx => $row) {
    fwrite($writeResource, "'{$row[0]}' => '{$row[1]}'," . "\n");
}
fwrite($writeResource, "];");
fclose($writeResource);

// end time
echo round(microtime(true) * 1000) - $start . 'ms';
