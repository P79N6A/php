#!/bin/bash
##############################################
#
#功能：控制台
# 
#使用sync360账号
#
##############################################
##############################################
function showHelp
{
    echo "Usage: $0 <cmd> <job> <product>" 
    echo "cmd: [info | ls | restart | stop]"
}

function showJobs 
{
    jobs=`ps -ef | grep " _wmasync_" | grep " start " | grep " $dojob "|grep " $findproduct "| awk '{printf $(NF-3);printf "\n"}'| sort -u`
    jobfound=0

    for job in $jobs; do
        jobfound=1
        workers=`ps -ef | grep " _wmasync_" | grep " start " | grep " $job " | grep " $findproduct "| awk 'BEGIN {i=0;j=""}{i=i+1;if($3 == 1){j=$0;}}END {printf "job["$(NF-3)"] total "i":\n";printf j"\n"}'`

        IFS=$(echo -en "\n")
        echo $workers
    done

    if [ $jobfound == 0 ]; then
        echo "$product $dojob not found!"
    fi
}

function runCmd
{
    commands=`ps -ef | grep " _wmasync_" | grep " start " | grep " $dojob "|grep " $findproduct "| awk '{for(i=8;i<=NF-5;i++)printf $i" ";printf "\n"}'| sort -u`
    if [ -n "$commands" ]; then
        SAVEIFS=$IFS
        IFS=$(echo -en "\n")

        echo $commands|while read line
        do
            echo "[ run command: $line $cmd $dojob ]"
            OLD_IFS="$IFS"
            IFS=" "
            arr=($line)
            IFS="$OLD_IFS"
            ${arr[*]} $cmd $dojob 
        done

        IFS=$SAVEIFS
    else
        echo "$product $dojob not found!"
    fi
}

cmd=$1
dojob=$2
product=$3

if [ "$cmd" != "ls" ] && [ "$cmd" != "stop" ] && [ "$cmd" != "restart" ] && [ "$cmd" != "info" ]; then
    showHelp
    exit 1
fi

if [ "$product" != "" ]; then
    findproduct=_$product"_"
fi

if [ "$cmd" == "info" ]; then
    showJobs
else
    runCmd
fi
