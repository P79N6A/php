#!/bin/sh

if test $# -lt 1
then
    echo Usage: install.sh project
    echo    eg: install.sh passport_dreamlive 
    exit
fi

ROOT=`pwd`

PROJECT=$1
REGION=$2

if test -e /usr/local/nginx/sbin/nginx
then
    if test -e nginx\_conf.php
        if test -e /usr/local/nginx/conf/vhost/$PROJECT.conf
        then
            rm -f /usr/local/nginx/conf/vhost/$PRODUCT.conf
        fi
    then
        ln -sf $ROOT/config/nginx\_conf.php /usr/local/nginx/conf/vhost/$PROJECT.conf
        echo ln -sf $ROOT/config/nginx\_conf.php /usr/local/nginx/conf/vhost/$PROJECT.conf
        echo link -s nginx\_conf.php to nginx/conf/vhost  .............OK
    else
        echo link -s nginx\_conf.php to nginx/conf/vhost  .............Fail
    fi

    /usr/local/nginx/sbin/nginx -s reload
fi
