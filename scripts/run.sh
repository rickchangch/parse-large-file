#!/bin/bash
set -e -u

SCRIPT_DIR="$( cd "$( dirname "$0" )" && pwd )"
PROG=""
TASK=""
CORES=""

while getopts "l:t:c:" option
do
  case $option in
    l)  PROG="$OPTARG"
        ;;
    t)  TASK="$OPTARG"
        ;;
    c)  CORES=$OPTARG
        ;;
  esac
done

case "$PROG" in
  php)
    cd $SCRIPT_DIR/../php
    ;;
  go)
    cd $SCRIPT_DIR/../go
    ;;
esac

case "$TASK" in
  deduplicate)
    mkdir -p logs
    for (( i = 0; i < $CORES; i++ ))
    do
      nohup php deduplicate.php $i $CORES > logs/process_$i.log &
    done
    ;;
  *)
    make "$TASK"
    ;;
esac
