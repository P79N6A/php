#!/bin/bash
if test $# -lt 1
then
    echo Usage: bootstrap.sh who
    echo    eg: bootstrap.sh test 
    exit
fi

REGION=$1

platform=`uname -s`
if [ "$platform" = "Linux" ]
then
    SCRIPT=`readlink -f $0`
    # Absolute path this script is in, thus /home/user/bin
    base_dir=`dirname $SCRIPT`
    base_name=`basename $SCRIPT`
elif [ "$platform" = "FreeBSD" ]
then
    SCRIPT=`realpath $0`
    # Absolute path this script is in, thus /home/user/bin
    base_dir=`dirname $SCRIPT`
    base_name=`basename $SCRIPT`
else
    echo "ERROR: not support ${platform}"
    exit -1
fi

gendirs="/home/dream/codebase/process_client/logs /home/dream/codebase/process_client/logs/task /home/dream/codebase/process_client/logs/run /home/dream/codebase/process_client/logs/job"

for dir in $gendirs; do
    if (test ! -d $dir)
    then
        mkdir -p -m0777 $dir
    fi

    if (test ! -d $dir)
    then
        echo ERROR: can find $dir
        exit
    fi
done

#check dependency 
depfile=$base_dir/DEPENDENCY
if (test -s $depfile)
    then
   
    deps=`cat $depfile` 
    for dep in $deps; do
        if (test ! -d $dep) && (test ! -s $dep)
        then
            echo ERROR: depend on $dep, pls install it first
        fi
    done
fi

if test -e process_conf.php
then 
    rm process_conf.php
fi
if (test -s config/process_conf.php.$REGION)
then
    ln -s config/process_conf.php.$REGION process_conf.php
    echo ln -s config/process_conf.php.$REGION process_conf.php .............. OK
else
    echo ln -s config/process_conf.php.$REGION process_conf.php .............. Fail 
fi

