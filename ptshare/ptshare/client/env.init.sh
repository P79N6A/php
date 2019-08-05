#!/bin/sh
## App Env Init Script


if test $# -lt 1
then
    echo Usage: env.init.sh who
    echo    eg: env.init.sh bjsc
    exit
fi

REGION=$1

DIRS="logs"
EXECUTES=""
SUBSYS="nginx server"

ROOT=`pwd`

echo create application environment for $REGION

# link app config file
cd $ROOT/config

for SUBSYS in $SUBSYS
do
    if test -e host.php
    then 
        rm host.php
    fi
    if (test -s host_conf.php.$REGION)
    then
        ln -s host_conf.php.$REGION host.php
        echo ln -s host_conf.php.$REGION host.php .............. OK
    else
        echo ln -s host_conf.php.$REGION host.php .............. Fail
    fi 
done
