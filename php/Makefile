parseRule:
	php parseRule.php rules/tag_relationship.csv

split:
	php splitLargeFile.php ../data/pxmart.tags.csv

getResult:
	cat ../data/split_files_after_process/U* > ../data/_final.csv

analyze:
	php count.php ../data/_final.csv > tagCount.txt

.PHONY: parseRule split getResult analyze
