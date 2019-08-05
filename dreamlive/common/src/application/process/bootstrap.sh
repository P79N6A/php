#!/bin/bash

start() {
		max=5
		
		for i in {1, $max}
		do
				date=`date '+%Y-%m-%d %H:%M:%S'`
		        num=`ps aux |grep -c \[l]ive_receive_audience_worker`
		        echo "$date live_receive_audience_worker: $num \n"
		
		        if [ "$num" -lt "$max" ]
		        then
		                cd /home/dream/codebase/service/src/application/process && nohup php live_receive_audience_worker.php >> logs/live_receive_audience_worker.log 2>&1 &
		        fi
		done
		
		max=2
		
		for i in {1, $max}
		do
				date=`date '+%Y-%m-%d %H:%M:%S'`
		        num=`ps aux |grep -c \[c]lean_relict_live_worker`
		        echo "$date clean_relict_live_worker: $num \n"
		
		        if [ "$num" -lt "$max" ]
		        then
		                cd /home/dream/codebase/service/src/application/process && nohup php clean_relict_live_worker.php >> logs/clean_relict_live_worker.log 2>&1 &
		        fi
		done
		
		max=1
		
		for i in {1, $max}
		do
				date=`date '+%Y-%m-%d %H:%M:%S'`
		        num=`ps aux |grep -c \[c]lean_relict_finish_match_worker`
		        echo "$date clean_relict_finish_match_worker: $num \n"
		
		        if [ "$num" -lt "$max" ]
		        then
		                cd /home/dream/codebase/service/src/application/process && nohup php clean_relict_finish_match_worker.php >> logs/clean_relict_finish_match_worker.log 2>&1 &
		        fi
		done
}

stop() {
        ps aux|grep [l]ive_receive_audience_worker|awk '{print $2}'|xargs kill -9
        ps aux|grep [c]lean_relict_live_worker|awk '{print $2}'|xargs kill -9
}

restart() {
	stop;
    echo "sleeping.........";
    sleep 3;
    start;
}

status() {
	 ps aux|grep live_receive_audience_worker
	 ps aux|grep clean_relict_live_worker
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

