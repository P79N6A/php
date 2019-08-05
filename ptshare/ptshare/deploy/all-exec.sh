#!/bin/bash

################################################################################
#
#   配置项
DEPLOY_DIR=`pwd`

PROJECT_NAME=`basename $DEPLOY_DIR`

DEPLOY_TOOLS_DIR=`dirname $DEPLOY_DIR`/deploy
. $DEPLOY_TOOLS_DIR/config/conf.common.sh
. $DEPLOY_TOOLS_DIR/utils.sh

################################################################################
# 使用帮助
if [ $# -lt 1 ] || [ "-h" = "$1" ] || [ "--help" = "$1" ]
then
	cecho "用法: $0 cmd"
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
. $DEPLOY_TOOLS_DIR/config/conf.${PROJECT_NAME}.sh

hosts=$online_cluster
# 确认服务器列表
cecho "\n=== 服务器列表 === \n" $c_notify
no=0;
for host in $hosts
do
	no=`echo "$no + 1" | bc`
	cecho "$no\t$host";
done
echo ""
deploy_confirm "确认服务器列表？"
if [ 1 != $? ]; then
	exit 1;
fi

for host in $hosts
do
	cecho "\n=== $host  === \n" $c_notify
	$SSH $host $@
done
