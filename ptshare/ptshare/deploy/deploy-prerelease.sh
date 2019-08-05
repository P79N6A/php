#!/bin/bash

####################################################################################################
#   配置项 从主分支发布到预发布环境

#   include lib
DEPLOY_DIR=`pwd`

if [ $# -lt 1 ] || [ "-h" = "$1" ] || [ "--help" = "$1" ]
then
	echo "用法: $0 FILE1 [ FILE2 ... ]";
	echo "FILE* : 需要上传的文件/目录；注意：每一个文件必须是相对于项目根目录的相对路径"
	exit 0;
fi

PROJECT_NAME=`basename $DEPLOY_DIR`

DEPLOY_TOOLS_DIR=`dirname $DEPLOY_DIR`/deploy

. $DEPLOY_TOOLS_DIR/config/conf.common.sh
. $DEPLOY_TOOLS_DIR/utils.sh

if test ! -e $DEPLOY_TOOLS_DIR/config/conf.deploy.sh
then
    cecho "$DEPLOY_TOOLS_DIR/config/conf.deploy.sh 文件不存在" $c_error
    exit 0;
fi
. $DEPLOY_TOOLS_DIR/config/conf.deploy.sh

if test ! -e $DEPLOY_TOOLS_DIR/config/conf.${PROJECT_NAME}.sh
then
    cecho "$DEPLOY_TOOLS_DIR/config/conf.${PROJECT_NAME}.sh 文件不存在" $c_error
    exit 0;
fi
. $DEPLOY_TOOLS_DIR/config/conf.${PROJECT_NAME}.sh

init

####################################################################################################
# 从svn获取当前的最新代码
CURRENT_REVISION=$(get_svn_head_revision $SVN_URL)
cecho "=== 当前最新版本号:$CURRENT_REVISION === \n" $c_notify
CURRENT_TIME=$(now)
LOCAL_SOURCE_DIR=$LOCAL_TMP_DIR/$PROJECT_NAME-$CURRENT_REVISION-$CURRENT_TIME
# 获取当前要上线的代码文件列表
files=$(get_file_list $DEPLOY_DIR $BLACKLIST $@)

file_num=`echo $files | wc -w`

if [[ $file_num -lt 10 ]]
then 
    export_svn_files $SVN_URL $LOCAL_SOURCE_DIR $CURRENT_REVISION "$files"
else
    export_svn $SVN_URL $LOCAL_SOURCE_DIR $CURRENT_REVISION
fi

if [ 0 -ne `expr "$files" : ' *'` ]; then
	cecho "\n没有找到要上传的文件，请调整输入参数" $c_error
	exit 1;
fi

# 确认文件列表
cecho "\n=== 上传文件列表 === \n" $c_notify
no=0;
for file in $files
do
    /usr/local/bin/php -n -l $file
	no=`echo "$no + 1" | bc`
	cecho "$no\t$file";
done
echo ""
deploy_confirm "确认文件列表？"
if [ 1 != $? ]; then
	exit 1;
fi


# 待上线的代码打包

#   源文件打包
cecho "\n=== 上线文件打包 === \n" $c_notify
src_tgz="$LOCAL_TMP_DIR/patch.${PROJECT_NAME}-${CURRENT_REVISION}-${CURRENT_TIME}.tgz"
decho $LOCAL_SOURCE_DIR
decho $files

tar cvfz $src_tgz -C $LOCAL_SOURCE_DIR $files > /dev/null 2>&1
decho "tar cvfz $src_tgz -C $LOCAL_SOURCE_DIR $files > /dev/null 2>&1"
decho "打包文件:   $src_tgz"
if [ ! -s "$src_tgz" ]; then
	cecho "错误：文件打包失败" $c_error
	exit 1
fi

hosts=$pre_online_cluster

#记录当前的更新日志
mkdir -p $LOCAL_DEPLOY_HISTORY_DIR
backup_src_tgz="$ONLINE_BACKUP_DIR/$CURRENT_TIME-$PROJECT_NAME-bak.tgz"
echo $backup_src_tgz $USER >> $DEPLOY_HISTORY_FILE


for host in ${hosts}
do
  if [ $(get_remote_os $host) == "Linux" ]
  then
    LINK="ln -T -s"
  else
    LINK="ln -s"
  fi

  if [[ $(get_remote_shell $host) == *csh ]]
  then
    EXPORT_LANGUAGE="setenv LANGUAGE $LANGUAGE"
  else
    EXPORT_LANGUAGE="export LANGUAGE=$LANGUAGE"
  fi
	cecho "\n=== ${host} ===\n" $c_notify
	#   获取此主机的对应文件
	local_online=$LOCAL_TMP_DIR/$host
	get_online_src $host $REMOTE_DEPLOY_DIR $local_online "$files"

	#   记录基准主机
	if [ "" = "$bench_host_src_dir" ]; then
		bench_host="$host"
		bench_host_src_dir="$local_online"
	fi

	# 对比文件的 SVN 版本与线上版本
	cecho "\t--- 逐个文件比较差异 ---\n" $c_notify
	check_files_diff "$files" $LOCAL_SOURCE_DIR $bench_host $bench_host_src_dir $host  $local_online

	# 备份线上代码
	backup_online_src $host $backup_src_tgz "$files"

	# 上传需要更新的代码
	upload_src $host $src_tgz

	##########################################################################################################
	# 在这里添加更新代码后需要执行的程序。 单元测试，建立link等
	#ssh_run $host "echo test ok!"
	#ssh_run $host "bash /home/sync360/a.sh"
	# 在这里添加更新代码后需要执行的程序。 单元测试，建立link等
	for script in $AUTORUN_RELEASE
	do
	    cecho "    执行此命令恢复原始版本： $SSH $host tar xvfz $backup_src_tgz -C $REMOTE_DEPLOY_DIR ";
		ssh_run $host "$REMOTE_DEPLOY_DIR/$script"
		check_succ $? "运行脚本失败： $REMOTE_DEPLOY_DIR/$script, 是否继续?"
	done

    if [ "$AUTORUN_RELEASE_CMD" != '' ]
    then
	    cecho "    执行此命令恢复原始版本： $SSH $host tar xvfz $backup_src_tgz -C $REMOTE_DEPLOY_DIR ";
        ssh_run $host "$AUTORUN_RELEASE_CMD"
		check_succ $? "运行脚本失败： $AUTORUN_RELEASE_CMD, 是否继续?"
    fi
	##########################################################################################################


	verify="    --- 上线完毕，执行此命令恢复原始版本： $SSH $host tar xvfz $backup_src_tgz -C $REMOTE_DEPLOY_DIR ";

	if [ "$host" == "$bench_host" ]
	then
		echo ""
		deploy_confirm "$verify，请验证效果"
		if [ 1 != $? ]; then
			exit 1;
		fi
	else
		cecho "\n$verify \n" $c_notify
	fi
done

clean