#!/bin/bash

#config
. ./game_config.sh

while :
do
    num=`ps aux | grep $GAME | grep -v grep|grep -c $GAME`
    if test "$num" -le 0 ; then
#         echo "starting..."
        nohup php $GAME >> $ERRLOG 2>&1 &
    fi
    sleep 5
done
