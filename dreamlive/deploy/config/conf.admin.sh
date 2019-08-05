#!/bin/bash

# 项目名
PROJECT_NAME="admin"
REGION_NAME=$1
VERSION="1.0.1"
SVN_URL="svn://192.168.1.168/dreamadmin"

# 公共线上集群
this_file=`pwd`"/"$0
CURRENT_DIR=`dirname $this_file`
CONFIG_DIR="$CURRENT_DIR/config"

# 补充集群


online_cluster="$ADMIN_HOST"

# beta_cluster
beta_cluster="$TEST_HOST"

# 项目部署的目录， link 到  $REAL_REMOTE_DEPLOY_DIR 上
REMOTE_DEPLOY_DIR="/home/dream/codebase/$PROJECT_NAME"
# 部署的真实目录
REAL_REMOTE_DEPLOY_DIR="/home/dream/codebase/$PROJECT_NAME-$VERSION"


# 部署使用的账号 默认问dream
SSH_USER="dream"

# 设置为1的时候， 会输出debug信息
UTILS_DEBUG=1

# 安装后自动执行初始化脚本

# 运行deploy-package.sh 后自动通过全路径直接运行，脚本需要有可执行权限
#AUTORUN_PACKAGE=""
# 同上 root权限
#SUDO_AUTORUN_PACKAGE=""
AUTORUN_PACKAGE_CMD="cd $REMOTE_DEPLOY_DIR;sh env.init.sh $REGION_NAME;ls -l;ls -l config"

# 运行deploy-release.sh 后自动通过全路径直接运行，脚本需要有可执行权限
AUTORUN_RELEASE_CMD=""

# 用于diff命令  打包时过滤logs目录
DEPLOY_BASENAME=`basename $REMOTE_DEPLOY_DIR`
TAR_EXCLUDE="--exclude $DEPLOY_BASENAME/logs"


########## 不要修改 ########################

SSH="sudo -u $SSH_USER ssh"
SCP="sudo -u $SSH_USER scp"

LOCAL_TMP_DIR="/tmp/deploy_tools/$USER"                                   # 保存本地临时文件的目录
BLACKLIST='(.*\.tmp$)|(.*\.log$)|(.*\.svn.*)'                             # 上传代码时过滤这些文件
ONLINE_TMP_DIR="/tmp"													  # 线上保存临时文件的目录
ONLINE_BACKUP_DIR="/home/$SSH_USER/deploy_history/$PROJECT_NAME"          # 备份代码的目录
LOCAL_DEPLOY_HISTORY_DIR="/home/$USER/deploy_history/$PROJECT_NAME"
DEPLOY_HISTORY_FILE="$LOCAL_DEPLOY_HISTORY_DIR/deploy_history"            # 代码更新历史(本地文件）
DEPLOY_HISTORY_FILE_BAK="$LOCAL_DEPLOY_HISTORY_DIR/deploy_history.bak"
