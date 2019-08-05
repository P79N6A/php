#!/bin/bash

#config
. ./run_horse_config.sh

#guard
GUARD=./run_horse_guard.sh

start() {

		num=`ps aux |grep -c $GAME`
        if [ "$num" -gt 1 ]
        then
            echo "$GAME is running ,please stop or restart"
        else
            nohup php $GAME >> $ERRLOG 2>&1 &
            sh $GUARD &

            if test "$?" -eq 0; then
                echo "run horse start success ......";
            else
                echo "run horse start fail ......";
            fi


        fi
}

stop() {
        ps aux|grep $GUARD|grep -v grep|grep $GUARD|awk '{print $2}'|xargs kill -9
        sleep 1
        ps aux|grep $GAME |grep -v grep | grep $GAME|awk '{print $2}'|xargs kill -s 15

        if test "$?" -eq 0; then
             echo "run horse stop success ......";
        else
             echo "run horse stop fail ......";
        fi
}

restart() {
	stop;
    echo "sleeping.........";
    sleep 3;
    start;

    if test "$?" -eq 0; then
         echo "run horse restart success ......";
    else
         echo "run horse restart fail ......";
    fi
}

status() {
	 ps aux|grep $GAME | grep -v grep
	 ps aux |grep $GUARD|grep -v grep
 }

clean() {
    a_week_ago=`date -d "-1 week" '+%Y%m%d'`
    a_week_ago=`date -d "$a_week_ago" +%s`

    cln_log=`ls "$LOGDIR"`
    for f in $cln_log
    do
        fd=${f##*log.}
        echo $fd
        fd_tmp=`date -d "$fd" +%s`
        if test "$fd_tmp" -le "$a_week_ago"; then
            rm -rf "$LOGDIR/$f"
        fi
    done
}

case "$1" in
    'start')
		start
        ;;
    'stop')
		stop
        ;;
    'restart')
    	restart
    	;;
    'status')
    	status
    	;;
    'clean')
    	clean
    	;;
    *)
    echo "usage: $0 {start|stop|restart|status|clean}"
    exit 1
        ;;
esac

