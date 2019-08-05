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
    if test -e $SUBSYS\_conf.php
    then 
        rm $SUBSYS\_conf.php
    fi
    if (test -s $SUBSYS/$SUBSYS\_conf.php.$REGION)
    then
        ln -s $SUBSYS/$SUBSYS\_conf.php.$REGION $SUBSYS\_conf.php
        echo ln -s $SUBSYS/$SUBSYS\_conf.php.$REGION $SUBSYS\_conf.php .............. OK
    else
        echo ln -s $SUBSYS/$SUBSYS\_conf.php.$REGION $SUBSYS\_conf.php .............. Fail
    fi 
done

cd $ROOT
for dir in $DIRS
do
    if (test ! -d $dir)
    then
        mkdir -p $dir
        chmod 777 $dir
        echo mkdir $dir ................ OK
    fi
done

for execute in $EXECUTES
do
    sh $execute > /dev/null
    if test $? -eq 0
    then
        echo sh $execute ................ OK
    fi
done
