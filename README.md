# parse-large-file

This is a project to record the process that how to parse and operate the huge data in large file with different programming language.

## Folder structure
```
├─── scripts
│ └─── run.sh     # execute the make command or other composite commands by specified programming language and task.
├─── go           # go solution (TBC)
├─── php          # php solution
└─── README.md
```

## Usage
- php
    ```shell
    # parse deduplication rule from the csv file.
    bash scripts/run.sh -l php -t parseRule

    # split the file according to some specific rules like id prefix, or just use built-in linux command `split -n l/n` to fit needs.
    bash scripts/run.sh -l php -t split

    # activate 4 processes to operate sevaral separated files concurrently.
    bash scripts/run.sh -l php -t deduplicate -c 4

    # concat all separated files to one result.
    bash scripts/run.sh -l php -t getResult

    # count result by a specific column. or use `wc -l {file_path}` to inspect the result rows.
    bash scripts/run.sh -l php -t analyze
    ```
- go
    ```shell
    ```
