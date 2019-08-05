#!/bin/bash

start() {
	max=1
	for i in {1, $max}
	do
		date=`date '+%Y-%m-%d %H:%M:%S'`
        num=`ps aux |grep -c \[r]ead-once`
        echo "$date read-once: $num \n"

        if [ "$num" -lt "$max" ]
        then
            nohup /usr/local/php/bin/php /data/website/toupai/walle-dev/toupai/yii user-schedule/read-once >> logs/read-once.log 2>&1 &
        fi
	done

	max=1
	for i in {1, $max}
	do
		date=`date '+%Y-%m-%d %H:%M:%S'`
        num=`ps aux |grep -c \[r]ead-repeat`
        echo "$date read-repeat: $num \n"

        if [ "$num" -lt "$max" ]
        then
            nohup /usr/local/php/bin/php /data/website/toupai/walle-dev/toupai/yii user-schedule/read-repeat >> logs/read-repeat.log 2>&1 &
        fi
	done
}

stop() {
    ps aux|grep [r]ead-once|awk '{print $2}'|xargs kill -9
    ps aux|grep [r]ead-repeat|awk '{print $2}'|xargs kill -9
}

restart() {
	stop;
    echo "sleeping.........";
    sleep 3;
    start;
}

status() {
	 ps -aux|grep read_once
	 ps -aux|grep read_repeat
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
    *)
    echo "usage: $0 {start|stop|restart|status}"
    exit 1
        ;;
esac