#!/bin/bash

step=10 #间隔的秒数，不能大于60
:'
#second=$(date "+%S")
#s=$(($second/$step))
#b=$(($s+1))
#t_b=$(($b*10));
#x=$(($t_b-$second));
#echo $x
#sleep $x
#echo $t_b
'
for (( i = t_b; i < 60; i=(i+step) )); do
    d=$(date "+%x %X")
    echo $d
    echo $i
    $(php '/home/dream/codebase/service/src/application/process/crontab_activity_christmas.php' 1)
    #sleep $step
done

exit 0
