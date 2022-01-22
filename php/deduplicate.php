<?php

if (!isset($argv[1]) || !isset($argv[2])) {
    echo "lost arguments";
    exit;
}

ini_set("memory_limit", "256M");

$trasnformMap = require("rules/transformMap.php");
$targetTagsMap = require("rules/targetTags.php");

// get traversal info
$begin = $argv[1];
$range = $argv[2];

// ouput destination
$destPath = "../data/split_files_after_process";
!is_dir($destPath) && mkdir($destPath, 0777, true);

$start = time();

$hex = dechex($begin);
while (($fpr = @fopen("../data/split_files/U{$hex}.csv", "r")) !== false) {

    // create output file
    $outputDest = "{$destPath}/U{$hex}.csv";
    $fpw = fopen($outputDest, "w");

    // process current separated file
    $line = 0;
    $distinctTags = [];
    while (($rowJsonData = fgets($fpr)) !== false) {

        $rowData = json_decode($rowJsonData, true);
        $userID = $rowData['user_id'];
        $tagID = $rowData['tag_id'];
        $createdAt = (int) $rowData['created_at'];

        if (isset($trasnformMap[$tagID]) || isset($targetTagsMap[$tagID])) {

            // transform error tag id
            isset($trasnformMap[$tagID]) && $tagID = $trasnformMap[$tagID]['id'];

            // de-duplicate
            if (!isset($distinctTags["{$userID}_{$tagID}"])) {
                $distinctTags["{$userID}_{$tagID}"] = $createdAt;
            } else {
                $comparedTime = $distinctTags["{$userID}_{$tagID}"];
                if (($createdAt !== 0 && $comparedTime === 0)
                    || ($createdAt !== 0 && $comparedTime !==0 && $createdAt < $comparedTime)) {
                    $distinctTags["{$userID}_{$tagID}"] = $createdAt;
                }
            }
        } else {
            fwrite($fpw, $rowJsonData);
        }

        $line++;
        if ($line % 1000000 == 0) {
            $time = time() - $start;
            echo "file: U{$hex} | progress: {$line} rows | time: {$time} sec\n";
        }
    }

    foreach($distinctTags as $key => $time) {
        list($userID, $tagID) = explode('_', $key);
        fwrite($fpw, json_encode([
            'user_id' => $userID,
            'tag_id' => $tagID,
            'tag_name' => $targetTagsMap[$tagID],
            'created_at' => $time,
        ]) . "\n");
    }

    fclose($fpw);
    fclose($fpr);
    $hex = dechex(hexdec($hex) + $range);
}

echo 'end: ' . time() - $start . ' sec';
