#!/bin/bash

####################################################################################################
#   配置项
DEPLOY_DIR=`pwd`

PROJECT_NAME=`basename $DEPLOY_DIR`
REGION_NAME=$1

shift 1

DEPLOY_TOOLS_DIR=`dirname $DEPLOY_DIR`/deploy
. $DEPLOY_TOOLS_DIR/config/conf.common.sh
. $DEPLOY_TOOLS_DIR/utils.sh

####################################################################################################
# 使用帮助
if [ $# -eq 0 ] 
then
    cecho "用法: $0 REGION .|beta"
    cecho "从svn获取代码， 部署到cluster中"
    exit 0;
fi

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
. $DEPLOY_TOOLS_DIR/config/conf.${PROJECT_NAME}.sh $REGION_NAME

if [ $1 = '.' ]
then
    pre_online_cluster=$pre_online_cluster
else
    pre_online_cluster=$beta_cluster
fi

if [ -z "$online_cluster" ]
then
    cecho "$1 is not a deploy cluster"
    exit 0;
fi

init
####################################################################################################
# 从svn获取当前的最新代码
CURRENT_REVISION=$(get_svn_head_revision $SVN_URL)
CURRENT_TIME=$(now)
LOCAL_SOURCE_DIR=$LOCAL_TMP_DIR/$PROJECT_NAME-$CURRENT_REVISION-$CURRENT_TIME
export_package_svn $SVN_URL $LOCAL_SOURCE_DIR $CURRENT_REVISION

# 待上线的代码打包

#   源文件打包
cecho "\n=== 上线文件打包 === \n" $c_notify
src_tgz="$LOCAL_TMP_DIR/patch.${PROJECT_NAME}-${CURRENT_REVISION}-${CURRENT_TIME}.tgz"
decho $LOCAL_SOURCE_DIR
decho $files
tar cvfz $src_tgz -C $LOCAL_SOURCE_DIR . > /dev/null 2>&1
decho "打包文件:   $src_tgz"
if [ ! -s "$src_tgz" ]; then
	cecho "错误：文件打包失败" $c_error
	exit 1
fi

#   开始上线代码
hosts=$pre_online_cluster

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
	
	#$SSH $host "$EXPORT_LANGUAGE;test -e $REAL_REMOTE_DEPLOY_DIR"
	#if [ 0 -eq $? ]; then
		#cecho "\t错误：服务器上存在目录: $REAL_REMOTE_DEPLOY_DIR" $c_error
		#deploy_confirm "    继续部署？"
		#if [ 1 != $? ]; then
		#	exit 1;
		#fi
	#fi

    # 上传需要更新的代码
	$SSH $host "$EXPORT_LANGUAGE;mkdir -p $REAL_REMOTE_DEPLOY_DIR;cd $REAL_REMOTE_DEPLOY_DIR;cd ..;rm -f $REMOTE_DEPLOY_DIR;$LINK $REAL_REMOTE_DEPLOY_DIR $REMOTE_DEPLOY_DIR"
	upload_src $host $src_tgz
	##########################################################################################################
	# 在这里添加更新代码后需要执行的程序。 单元测试，建立link等
	for script in $SUDO_AUTORUN_PACKAGE
	do
		sudo_ssh_run $host "$REMOTE_DEPLOY_DIR/$script"
		check_succ $? "运行脚本 $REMOTE_DEPLOY_DIR/$script 失败， 是否继续？"
	done
	for script in $AUTORUN_PACKAGE
	do
		ssh_run $host "$REMOTE_DEPLOY_DIR/$script"
		check_succ $? "运行脚本 $REMOTE_DEPLOY_DIR/$script 失败， 是否继续？"
	done
    if [ "$AUTORUN_PACKAGE_CMD" != '' ]
    then
        ssh_run $host "$AUTORUN_PACKAGE_CMD"
        check_succ $? "运行脚本失败： $AUTORUN_PACKAGE_CMD, 是否继续?"
    fi
	##########################################################################################################
done