<?php

class DreamConfig
{
    const DEFAULT_HOST = "api.dreamlive.com";
    const UPLOAD_HOST = "upload.dreamlive.com";
    const JS_TICKET_HOST = "api.dreamlive.com";

    public static $api_conf = [
        'privacyRefundEcnomic' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/recovery/recoveryByUidToUid',
            'method' => 'post',
            'params' => [
                'sender',
                'receiver',
                'amount',
                'remark',
                'adminid',
                'key',
            ],
        ],
        'getLiveInfo' => [/*{{{*/
        'host' => self::DEFAULT_HOST,
        'url' => '/live/getLiveInfo',
        'method' => 'post',
        'params' => [
        'liveid',
        'platform'
        ],
        ],
        'updateQuickWord' => [/*{{{*/
        'host' => self::DEFAULT_HOST,
        'url' => '/chat/updateQuickWord',
        'method' => 'post',
        'params' => [
        'id',
        'adminid',
        'status',
        ],
        ],
        'adminAddSilence' => [/*{{{*/
        'host' => self::DEFAULT_HOST,
        'url' => '/operation/addSilence',
        'method' => 'post',
        'params' => [
        'liveid',
        'userid'
        ],
        ],
        'adminDelSilence' => [/*{{{*/
        'host' => self::DEFAULT_HOST,
        'url' => '/operation/delSilence',
        'method' => 'post',
        'params' => [
        'liveid',
        'userid'
        ],
        ],
        'privacyRefund' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/privacy/refund',
            'method' => 'post',
            'params' => [
                'id',
                'reason',
            ],
        ],
        'getFrontCacheQuestionList' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/getQuestion',
            'method' => 'post',
            'params' => [
                'roundid',
            ],
        ],
        'getFrontCacheQuestionLogList' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/getQuestionStats',
            'method' => 'post',
            'params' => [
                'roundid',
                'questionid',
            ],
        ],
        'getFrontCacheAnswerLogList' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/getAnswerStats',
            'method' => 'post',
            'params' => [
                'roundid',
                'questionid',
                'answer',
            ],
        ],
        'getPkRankList' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/match/getPkInfo',
            'method' => 'post',
            'params' => [
                'matchid',
                'uid',
            ],
        ],
        'addLinkRights' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/link/setLinkRights',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
            ],
        ],
        'delLinkRights' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/link/remLinkRights',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'delPrivateRoom' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/privacy/remove',
            'method' => 'post',
            'params' => [
                'privacyid',
            ],
        ],
        'imprison' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/match/imprison',
            'method' => 'post',
            'params' => [
                'uid',
                'second',
                'source',
                'matchid',
                'adminid',
            ],
        ],
        'prisonrelease' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/match/release',
            'method' => 'post',
            'params' => [
                'prisonid',
                'note',
            ],
        ],
        'adminnoticepush' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/live/noticepush',
            'method' => 'post',
            'params' => [
                'text',
                'image',
                'sender',
                'type',
            ],
        ],
        'setLiveInfo' => [/*{{{修改直播信息*/
            'host' => self::DEFAULT_HOST,
            'url' => '/live/setLiveinfo',
            'method' => 'post',
            'params' => [
                'liveid',
                'cover',
                'title',
            ],
        ],
        //=====================live start===================//
        'liveprepare' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/istudio/prepare',
            'method' => 'post',
            'params' => [
                'userid',
            ],
        ],
        'livestop' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/istudio/stop',
            'method' => 'post',
            'params' => [
                'liveid',
                'userid',
            ],
        ],
        'livefans' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/istudio/getAudience',
            'method' => 'post',
            'params' => [
                'liveid',
                'userid',
            ],
        ],
        'livestart' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/istudio/start',
            'method' => 'post',
            'params' => [
                'liveid',
                'sn',
                'partner',
                'title',
                'userid',
                'pullurl',
                'point',
            ],
        ],
        //=====================live end===================//
        'uploadFile' => [/*{{{*/
            'host' => self::UPLOAD_HOST,
            'url' => '/upload/uploadFile',
            'method' => 'post',
            'params' => [
                'filename',
            ],
        ],
        'uploadImage' => [/*{{{*/
            'host' => self::UPLOAD_HOST,
            'url' => '/upload/uploadImage',
            'method' => 'POST',
            'params' => [
                "data",
                "kind",
            ],
        ],
        'createTask' => [/*{{{*/
            'host' => self::UPLOAD_HOST,
            'url' => '/upload/createTask',
            'method' => 'POST',
            'params' => [
                "filename",
                "filesize",
            ],
        ],
        'uploadPart' => [/*{{{*/
            'host' => self::UPLOAD_HOST,
            'url' => '/upload/uploadPart',
            'method' => 'POST',
            'params' => [
                "filename",
                "uploadid",
                "partnumber",
                "md5",
            ],
        ],
        'uploadDelete' => [/*{{{*/
            'host' => self::UPLOAD_HOST,
            'url' => '/upload/delFile',
            'method' => 'POST',
            'params' => [
                "url",
            ],
        ],
        'completeTask' => [/*{{{*/
            'host' => self::UPLOAD_HOST,
            'url' => '/upload/completeTask',
            'method' => 'POST',
            'params' => [
                "filename",
                "uploadid",
                "md5",
            ],
        ],
        //============================用户==================================
        'getUserInfo' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getUserInfo',
            'method' => 'POST',
            'params' => [
                "userid",
                "uid",
            ],
        ],
        'mergeOldUser' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/mergeOldUser',
            'method' => 'POST',
            'params' => [
                "mergeid",
            ],
        ],
        'getMergeUidFromOldUid' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getMergeUidFromOldUid',
            'method' => 'POST',
            'params' => [
                "uid",
            ],
        ],
        'delUserMergeOld' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/delUserMergeOld',
            'method' => 'POST',
            'params' => [
                "mergeid",
            ],
        ],
        //============================计数器==================================
        'increaseCounter' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/counter/increase',
            'method' => 'post',
            'params' => [
                'type',
                'relateid',
                'number',
            ],
        ],
        'getCounter' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/counter/get',
            'method' => 'post',
            'params' => [
                'type',
                'relateid',
            ],
        ],
        'mixedCounter' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/counter/mixed',
            'method' => 'post',
            'params' => [
                'types',
                'relateids',
            ],
        ],
        'mixedShare' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/share/mixed',
            'method' => 'post',
            'params' => [
                'types',
                'relateids',
            ],
        ],
        //============================资源操作==================================
        'breakLive' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/live/break',
            'method' => 'post',
            'params' => [
                'liveid',
                'clean',
                'operator',
            ],
        ],
        'cleanLive' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/live/clean',
            'method' => 'post',
            'params' => [
                'liveid',
                'reason',
            ],
        ],
        'mixed' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/counter/mixed',
            'method' => 'post',
            'params' => [
                'types',
                'relateids',
            ],
        ],
        'deleteImage' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/image/adminDelete',
            'method' => 'post',
            'params' => [
                'imageid',
            ],
        ],
        'deleteVideo' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/video/adminDelete',
            'method' => 'post',
            'params' => [
                'videoid',
            ],
        ],
        'deleteReply' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/reply/delete',
            'method' => 'post',
            'params' => [
                'pid',
                'rid',
            ],
        ],
        'addVideo' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/video/adminAdd',
            'method' => 'post',
            'params' => [
                'userid',
                'mp4',
                'cover',
            ],
        ],
        //============================用户操作==================================
        'setUserInfo' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/setUserInfo',
            'method' => 'post',
            'params' => [
                'uid',
                'nickname',
                'signature',
                'avatar',
            ],
        ],
        'addForbidden' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/forbidden/forbidden',
            'method' => 'post',
            'params' => [
                'relateid',
                'expire',
                'reason',
            ],
        ],
        'unForbidden' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/forbidden/unForbidden',
            'method' => 'post',
            'params' => [
                'relateid',
            ],
        ],
        'addForbiddenMsg' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/forbidden/forbiddenMsg',
            'method' => 'post',
            'params' => [
                'relateid',
            ],
        ],
        'unForbiddenMsg' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/forbidden/unForbiddenMsg',
            'method' => 'post',
            'params' => [
                'relateid',
            ],
        ],
        'addDefriend' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/defriend/',
            'method' => 'post',
            'params' => [
                'uid',
                'expire',
            ],
        ],
        'unDefriend' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/unDefriend',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'isBigLiveHide' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/isBigLive',
            'method' => 'post',
            'params' => [
                'plat',
            ],
        ],
        'bigLiveHide' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/bigLiveHide',
            'method' => 'post',
            'params' => [
                'uids',
                'plat',
            ],
        ],
        'bigLiveShow' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/bigLiveShow',
            'method' => 'post',
            'params' => [
                'plat',
            ],
        ],
        'isBigLivePartHide' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/isBigPartLive',
            'method' => 'post',
            'params' => [
                'plat',
            ],
        ],
        'bigLivePartHide' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/bigLivePartHide',
            'method' => 'post',
            'params' => [
                'plat',
            ],
        ],
        'bigLivePartShow' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/bigLivePartShow',
            'method' => 'post',
            'params' => [
                'plat',
            ],
        ],
        'regionBigLiveHide' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/regionBigLiveHide',
            'method' => 'post',
            'params' => [
            ],
        ],
        'regionBigLiveShow' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/regionBigLiveShow',
            'method' => 'post',
            'params' => [
            ],
        ],
        'isRegionBigLiveHide' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/isRegionBigLiveHide',
            'method' => 'post',
            'params' => [
            ],
        ],
        'addHotWhite' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/addHotWhite',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'delHotWhite' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/defriend/delHotWhite',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        //============================Bucket操作==================================
        'getBucket' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/getBuckets',
            'method' => 'post',
            'params' => [
                'bucket_names',
                'region',
            ],
        ],
        'fetchBucket' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/fetch',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
                'offset',
                'num',
            ],
        ],
        'existsBucket' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/exists',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'type',
                'relateid',
            ],
        ],
        'openBucket' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/bucketOpen',
            'method' => 'post',
            'params' => [
                'region',
                'bucket_name',
            ],
        ],
        'closeBucket' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/bucketClose',
            'method' => 'post',
            'params' => [
                'region',
                'bucket_name',
            ],
        ],
        'setBucket' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/setBucket',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
                'capacity',
                'official',
                'extends',
            ],
        ],
        'delBucket' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/delBucket',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
            ],
        ],
        'setForward' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/setForward',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'forward_name',
                'region',
            ],
        ],
        'truncate' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/truncate',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
                'offset',
            ],
        ],
        'setBucketElement' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/set',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
                'relateid',
                'type',
                'score',
                'extends',
            ],
        ],
        'importBucketElement' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/import',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
                'data',
            ],
        ],
        'fetchBucketElement' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/fetch',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
                'offset',
                'num',
            ],
        ],
        'deleteBucketElement' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/delete',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'region',
                'relateid',
                'type',
            ],
        ],
        'cleanBucketElement' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Bucket/clean',
            'method' => 'post',
            'params' => [
                'relateid',
                'type',
            ],
        ],
        //============================Message操作==================================
        'sendBroadcastMessage' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Message/sendBroadcast',
            'method' => 'post',
            'params' => [
                'type',
                'relateid',
                'title',
                'content',
            ],
        ],
        'sendPrivateMessage' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Message/send',
            'method' => 'post',
            'params' => [
                'receiver',
                'content',
                'userid',
            ],
        ],
        //============================云控操作==================================
        'getConfigs' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Config/gets',
            'method' => 'post',
            'params' => [
                'region',
                'platform',
                'version',
                'names',
            ],
        ],
        'setConfig' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Config/set',
            'method' => 'post',
            'params' => [
                'platform',
                'id',
                'region',
                'name',
                'value',
                'expire',
                'min_version',
                'max_version',
            ],
        ],
        'deleteConfig' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Config/delete',
            'method' => 'post',
            'params' => [
                'id',
            ],
        ],
        //============================分享操作==================================

        'addShareConfig' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/share/add',
            'params' => [
                'uid',
                'role',
                'type',
                'target',
                'begin',
                'finish',
                'starttime',
                'endtime',
                'title',
                'content',
                'titled',
            ],
        ],
        'updateShareConfig' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/share/update',
            'params' => [
                'id',
                'uid',
                'role',
                'type',
                'target',
                'begin',
                'finish',
                'starttime',
                'endtime',
                'title',
                'content',
                'titled',
            ],
        ],
        'deleteShareConfig' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/share/delete',
            'params' => [
                'id',
            ],
        ],
        'fetchShareConfig' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/share/fetch',
            'method' => 'post',
            'params' => [
                'offset',
                'num',
                'role',
                'type',
                'target',
                'uid',
                'titled',
            ],
        ],
        'getShareConfig' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/share/index',
            'params' => [
                'type',
                'author',
                'relateid',
                'target',
                'title',
                'userid',
            ],
        ],
        'shareCallbackConfig' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/share/callback',
            'params' => [
                'type',
                'uid',
                'relateid',
                'target',
                'shareid',

            ],
        ],
        //============================分享操作==================================
        'addGift' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/gift/addGift',
            'method' => 'post',
            'params' => [
                'name',
                'image',
                'label',
                'type',
                'price',
                'ticket',
                'consume',
                'score',
                'status',
                'uri',
                'zip_md5',
                'extends',
                "tag",
                "region",
            ],
        ],
        'setGift' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/gift/setGift',
            'method' => 'post',
            'params' => [
                'giftid',
                'name',
                'image',
                'label',
                'type',
                'price',
                'ticket',
                'consume',
                'score',
                'status',
                'uri',
                'zip_md5',
                'extends',
                "tag",
                "region",
            ],
        ],
        //============================产品==================================
        'addProduct' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/product/addProduct',
            'method' => 'post',
            'params' => [
                'name',
                'image',
                'cateid',
                'tag',
                'price',
                'expire',
                'currency',
                'online',
                'mark',
                'weight',
                'extends',
                'unit',
                'remark',
            ],
        ],
        'modifyProduct' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/product/modifyProduct',
            'method' => 'post',
            'params' => [
                'productid',
                'name',
                'image',
                'cateid',
                'tag',
                'price',
                'expire',
                'currency',
                'online',
                'mark',
                'weight',
                'extends',
                'unit',
                'deleted',
                'remark',
            ],
        ],
        'sendRideByActive' => [
            'secret' => 'eac63e66d8c4a6f0303f00bc76d0217c',
            'host' => self::DEFAULT_HOST,
            'url' => '/product/sendRideByActivity',
            'method' => 'post',
            'params' => [
                'uid',
                'rideid',
                'expire',
                'notice',
                'extends',
                'type',
                'num',
            ],
        ],
        //============================提现审核==================================
        'acceptWithdraw' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Withdraw/accept',
            'method' => 'post',
            'params' => [
                'orderid',
            ],
        ],
        'refuseWithdraw' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Withdraw/reject',
            'method' => 'post',
            'params' => [
                'orderid',
                'reason',
            ],
        ],

        'operationAcceptWithdraw' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Withdraw/operationAccept',
            'method' => 'post',
            'params' => [
                'orderid',
                'reason',
            ],
        ],

        'familyAcceptWithdraw' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Withdraw/familyAccept',
            'method' => 'post',
            'params' => [
                'orderid',
                'reason',
            ],
        ],
        //============================家族==================================
        'login' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/login',
            'method' => 'post',
            'params' => [
                'username',
                'password',
            ],
        ],
        'active' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/active',
            'method' => 'post',
            'params' => [
                'rid',
                'source',
                'access_token',
                'isweb',
                'unionid',
                'channel',
            ],
        ],
        'getMyUserInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getMyUserInfo',
            'method' => 'post',
            'params' => [
            ],
        ],
        'familyStatus' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/status',
            'method' => 'post',
            'params' => [
            ],
        ],
        'familyMyFamily' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/myFamily',
            'method' => 'post',
            'params' => [
            ],
        ],
        'familySearch' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/search',
            'method' => 'post',
            'params' => [
                'familyid',
                'owner',
            ],
        ],
        'familyApplyEmploye' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/applyEmploye',
            'method' => 'post',
            'params' => [
                'familyid',
                'authorid',
                'type',
                'value',
                'old_value',
                'reason',
            ],
        ],
        'familyAcceptEmploye' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/acceptEmploye',
            'method' => 'post',
            'params' => [
                'applyid',
            ],
        ],
        'familyRejectEmploye' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/rejectEmploye',
            'method' => 'post',
            'params' => [
                'applyid',
            ],
        ],
        'familyApplyList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/applyList',
            'method' => 'post',
            'params' => [
                'offset',
                'num',
            ],
        ],
        'familyEmployeList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/employeList',
            'method' => 'post',
            'params' => [
                'offset',
                'num',
                'search',
            ],
        ],
        'familyEmployeInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/employeInfo',
            'method' => 'post',
            'params' => [
                'authorid',
            ],
        ],
        'familyApplySearch' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/applySearch',
            'method' => 'post',
            'params' => [
                'authorid',
                'type',
            ],
        ],
        'familyInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/familyInfo',
            'method' => 'post',
            'params' => [
                'familyid',
            ],
        ],
        'familyCreateDraft' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/createFamilyDraft',
            'method' => 'post',
            'params' => [
                'name',
                'logo',
                'owner',
                'corporate',
                'family_percent',
                'author_percent',
                'author_maxpercent',
                'maximum',
                'organization',
                'image_organizationlicence1',
                'corporation',
                'corporationphone',
                'corporationidcard',
                'image_corporationimage1',
                'image_corporationimage2',
                'broker',
                'brokerphone',
                'brokeridcard',
                'image_brokerimage1',
                'image_brokerimage2',
                'image_brokerimage3',
                'declaration',
                'announcement',
                'adminid',
                'kprate',
                'bfprate',
            ],
        ],
        'familyReviewDraft' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/reviewFamilyDraft',
            'method' => 'post',
            'params' => [
                'familyid',
                'review',
                'review_reason',
            ],
        ],
        'familyModify' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/modifyFamily',
            'method' => 'post',
            'params' => [
                'familyid',
                'name',
                'logo',
                'author_percent',
                'maximum',
                'organization',
                'image_organizationlicence1',
                'corporation',
                'corporationphone',
                'corporationidcard',
                'image_corporationimage1',
                'image_corporationimage2',
                'broker',
                'brokerphone',
                'brokeridcard',
                'image_brokerimage1',
                'image_brokerimage2',
                'image_brokerimage3',
                'declaration',
                'announcement',
                'collaborate',
                'adminid',
            ],
        ],
        'familyDaily' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/moidfyDaily',
            'method' => 'post',
            'params' => [
                'id',
                'newfans',
                'share',
                'livelength',
            ],
        ],
        'familyDailySync' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/syncDaily',
            'method' => 'post',
            'params' => [
                'id',
            ],
        ],
        'familyForceAccept' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/forceAddEmploye',
            'method' => 'post',
            'params' => [
                'authorid',
                'familyid',
                'real_name',
                'mobile',
                'idcard',
                'address',
                'qq',
                'wechat',
                'image',
            ],
        ],
        'familyFamilyPercent' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/familyPercent',
            'method' => 'post',
            'params' => [
                'id',
                'family_percent',
            ],
        ],
        'familyAuthorMaxpercent' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/authorMaxpercent',
            'method' => 'post',
            'params' => [
                'id',
                'author_maxpercent',
            ],
        ],
        'familyKprate' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/kprate',
            'method' => 'post',
            'params' => [
                'id',
                'kprate',
            ],
        ],
        'familyBfprate' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/bfprate',
            'method' => 'post',
            'params' => [
                'id',
                'bfprate',
            ],
        ],
        'familyAddStatisticsTask' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/addStatisticsTask',
            'method' => 'post',
            'params' => [
                'authorid',
                'begin_time',
                'end_time',
            ],
        ],
        'familyRelease' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/release',
            'method' => 'post',
            'params' => [
                'authorid',
                'familyid',
                'reason',
            ],
        ],
        'familyCorporate' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/corporate',
            'method' => 'post',
            'params' => [
                'familyid',
                'corporate',
            ],
        ],
        'familyAuthorGlobalPercent' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/family/authorPercent',
            'method' => 'post',
            'params' => [
                'authorid',
                'global_percent',
            ],
        ],
        'getWithdrawPriceForH5' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getWithdrawPriceForH5',
            'method' => 'post',
            'params' => [
                'userid',
                'sign',
            ],
        ],
        'getWithdrawPrice' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getWithdrawPrice',
            'method' => 'post',
            'params' => [

            ],
        ],
        'add' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/paybind/add',
            'method' => 'post',
            'params' => [
                'userid',
                'account',
                'realname',
                'source',
            ],
        ],
        'update' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/paybind/update',
            'method' => 'post',
            'params' => [
                'userid',
                'account',
                'realname',
                'source',
                'mobile',
                'code',
                'relateid',
            ],
        ],
        'getWithdrawListForH5' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getWithdrawListForH5',
            'method' => 'post',
            'params' => [
                'userid',
                'sign',
                'offset',
            ],
        ],
        'getWithdrawList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getWithdrawList',
            'method' => 'post',
            'params' => [
            ],
        ],
        'applyForH5' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/applyForH5',
            'method' => 'post',
            'params' => [
                'userid',
                'amount',
                'source',
                'sign',
            ],
        ],
        'apply' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/apply',
            'method' => 'post',
            'params' => [
                'amount',
                'source',
            ],
        ],
        'getUserAvatar' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getUserAvatar',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'getSign' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getSign',
            'method' => 'post',
            'params' => [
                'userid',
                'mobile',
                'code',
            ],
        ],
        'getCode' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getCode',
            'method' => 'post',
            'params' => [
                'mobile',
                'type',
            ],
        ],
        'getWithdrawPriceFromTicketForH5' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getWithdrawPriceFromTicketForH5',
            'method' => 'post',
            'params' => [
                'userid',
                'sign',
                'ticket',
            ],
        ],
        'getTicketByFamilyid' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getTicketByFamilyid',
            'method' => 'post',
            'params' => [
                'familyid',
            ],
        ],
        'getPaybindList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/paybind/getPaybindList',
            'method' => 'post',
            'params' => [
                'userid',
                'uid',
            ],
        ],
        'checkCode' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/paybind/checkCode',
            'method' => 'post',
            'params' => [
                'userid',
                'mobile',
                'code',
            ],
        ],
        'fetch' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/bucket/fetch',
            'method' => 'post',
            'params' => [
                'bucket_name',
                'offset',
                'num',
            ],
        ],
        'getLiveInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/live/getLiveInfo',
            'method' => 'post',
            'params' => [
                'liveid',
                'version',
            ],
        ],
        'expranking' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/expranking',
            'method' => 'post',
            'params' => [
            ],
        ],
        'addApply' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/addApply',
            'method' => 'post',
            'params' => [
                'uid',
                'realname',
                'credentials',
                'mobile',
                'idcard',
                'imgs',
                'code',
            ],
        ],
        'exist' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/paybind/exist',
            'method' => 'post',
            'params' => [
                'userid',
            ],
        ],

        //============================认证审核==================================
        'verifiedModifyRefuse' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/refuseModify',
            'method' => 'post',
            'params' => [
                'uid',
                'reason',
            ],
        ],
        'verifiedModifyPassed' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/passedModify',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
                'realname',
                'credentials',
                'mobile',
                'idcard',
                'imgs',
            ],
        ],
        'verifyingApplyPassed' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/passed',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
                'realname',
                'credentials',
                'mobile',
                'idcard',
                'imgs',
            ],
        ],
        'verifyingApplyRefuse' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/refuse',
            'method' => 'post',
            'params' => [
                'uid',
                'reason',
            ],
        ],
        'verifiedCancel' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/cancel',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'verifiedArtist' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/ArtistVerified/verifiedArtist',
            'method' => 'post',
            'params' => [
                'passed',
                'uid',
                'reason',
                'wb_rid',
                'contact',
                'artist_img',
                'supplementary',
                'refuse_reason',
            ],
        ],
        'cancelArtist' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/ArtistVerified/cancelArtist',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'verifiedStudent' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Student/verifiedStudent',
            'method' => 'post',
            'params' => [
                'passed',
                'uid',
                'realname',
                'school',
                'year',
                'imgs',
                'reason',
                'status',
            ],
        ],
        'cancelStudent' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Student/cancelStudent',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'adminAddVerified' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/adminAdd',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
                'realname',
                'credentials',
                'mobile',
                'idcard',
                'imgs',
            ],
        ],
        'adminAddVerified' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/adminAdd',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
                'realname',
                'credentials',
                'mobile',
                'idcard',
                'imgs',
            ],
        ],
        'modifyVerified' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/modifyApply',
            'method' => 'post',
            'params' => [
                'uid',
                'realname',
                'credentials',
                'mobile',
                'idcard',
                'imgs',
                'code',
            ],
        ],
        'adminModifyVerified' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/adminModify',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
                'realname',
                'credentials',
                'mobile',
                'idcard',
            ],
        ],
        'prepareDeposit' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/deposit/prepare',
            'method' => 'post',
            'params' => [
                'userid',
                'source',
                'amount',
                'currency',
                'openid',
                'type',
            ],
        ],
        'prepareweb' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/deposit/prepare',
            'method' => 'post',
            'params' => [
                'userid',
                'source',
                'amount',
                'type',
            ],
        ],
        'verifyweb' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/deposit/verify',
            'method' => 'post',
            'params' => [
                'orderid',
            ],
        ],
        'getVerifiedInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/getVerifiedInfo',
            'method' => 'post',
            'params' => [
            ],
        ],
        //============================Rank==================================
        'getLiveUserNum' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Rank/getLiveUserNum',
            'method' => 'post',
            'params' => [
                'liveids',
            ],
        ],
        //============================经济系统==================================
        'internaleconomic' => [ //运营币加钻
            'host' => self::DEFAULT_HOST,
            'url' => '/internaleconomic/accept',
            'method' => 'post',
            'params' => [
                'adminid',
                'uid',
                'currency',
                'amount',
            ],
        ],
        'activeinternaleconomic' => [ //活动币加钻
            'host' => self::DEFAULT_HOST,
            'url' => '/ActiveInternaleconomic/accept',
            'method' => 'post',
            'params' => [
                'adminid',
                'activeid',
                'uid',
                'currency',
                'amount',
            ],
        ],
        'activeRecovery' => [ //活动回收
            'host' => self::DEFAULT_HOST,
            'url' => '/Recovery/active',
            'method' => 'post',
            'params' => [
                'key',
                'adminid',
                'activeid',
            ],
        ],
        'activeRecoveryUid' => [ //活动回收
            'host' => self::DEFAULT_HOST,
            'url' => '/Recovery/activebyuid',
            'method' => 'post',
            'params' => [
                'key',
                'adminid',
                'uid',
            ],
        ],
        'frozen' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Account/frozen',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'unfrozen' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Account/unfrozen',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'frozenTicket' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Account/frozenTicket',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'unfrozenTicket' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Account/unfrozenTicket',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'applyCashForH5' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/applyCashForH5',
            'method' => 'post',
            'params' => [
                'userid',
                'amount',
                'source',
                'sign',
            ],
        ],
        'getJsToken' => [
            'host' => self::JS_TICKET_HOST,
            'url' => '/deposit/getJsToken',
            'method' => 'post',
            'params' => [
            ],
        ],
        'getAccountInfoForH5' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/getAccountInfoForH5',
            'method' => 'post',
            'params' => [
                'userid',
                'sign',
            ],
        ],
        'followAdd' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/follow/add',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'getToken' => [
            'host' => self::DEFAULT_HOST,
            'url' => ' /message/getToken',
            'method' => 'post',
            'params' => [
                'userid',
            ],
        ],
        'setReplayUrl' => [
            'host' => self::DEFAULT_HOST,
            'url' => ' /live/setReplayUrl',
            'method' => 'post',
            'params' => [
                'sn',
                'url',
                'live_partner',
                'startTime',
                'endTime',
            ],
        ],
        'isFollowed' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/follow/isFollowed',
            'method' => 'post',
            'params' => [
                'fids',
            ],
        ],
        'getFeeds' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feed/getFeeds',
            'method' => 'post',
            'params' => [
                'region',
                'bucket_name',
                'offset',
                'num',
                'offset_type',
            ],
        ],
        'activeTransfer' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Account/activeTransfer',
            'method' => 'post',
            'params' => [
                'key',
                'num',
                'active_account',
            ],
        ],
        'operaterPlus' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Deposit/operaterPlus',
            'method' => 'post',
            'params' => [
                'uid',
                'key',
                'adminid',
                'source',
                'amount',
                'deposit_num',
                'tradeid',
            ],
        ],
        'operaterwuPlus' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/WuInternaleconomic/diamond',
            'method' => 'POST',
            'params' => [
                "adminid",
                "uid",
                "amount",
                "key",
                'tradeid',
            ],
        ],
        'testerPlus' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Tester/transfer',
            'method' => 'post',
            'params' => [
                'uid',
                'key',
                'adminid',
                'deposit_num',
                'remark',
            ],
        ],
        'recovery' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Recovery/recovery',
            'method' => 'post',
            'params' => [
                'uid',
                'key',
                'adminid',
                'amount',
                'remark',
            ],
        ],
        'recoveryDiamond' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Recovery/recoveryDiamond',
            'method' => 'post',
            'params' => [
                'uid',
                'key',
                'adminid',
                'amount',
                'remark',
            ],
        ],
        'punishment' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Recovery/punishment',
            'method' => 'post',
            'params' => [
                'uid',
                'key',
                'adminid',
                'amount',
                'remark',
            ],
        ],
        'getVideoInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/video/getVideoInfo',
            'method' => 'post',
            'params' => [
                'videoid',
            ],
        ],
        'getImageInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/image/getImageInfo',
            'method' => 'post',
            'params' => [
                'imageid',
            ],
        ],
        'video_praise' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/video/praise',
            'method' => 'post',
            'params' => [
                'videoid',
            ],
        ],
        'image_praise' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/image/praise',
            'method' => 'post',
            'params' => [
                'imageid',
            ],
        ],
        'replay_praise' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/live/praise',
            'method' => 'post',
            'params' => [
                'isfirst',
                'liveid',
                'num',
            ],
        ],
        'feedback' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feedback/add',
            'method' => 'post',
            'params' => [
                'content',
            ],
        ],
        'adminUpdateTask' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/task/adminUpdateTask',
            'method' => 'post',
            'params' => [
                'taskid', 'name', 'active', 'totallimit', 'daylimit', 'extend', 'type', 'status',
            ],
        ],
        'getActivingLive' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feed/getActivingLive',
            'method' => 'post',
            'params' => [
                'uids',
            ],
        ],

        'getUserFeeds' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feed/getUserFeeds',
            'method' => 'post',
            'params' => [
                'uid',
                'offset',
                'num',
                'userid',
            ],
        ],

        'modifyWithdrawalStatus' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/modifyWithdrawalStatus',
            'method' => 'post',
            'params' => [
                'uid',
                'status',
                'note',
            ],
        ],

        'addFilterKeyword' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/keyword/addFilterKeyword',
            'method' => 'post',
            'params' => [
                'keyword',
                'type',
                'opt_uid',
            ],
        ],

        'forbiddenKeyword' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Message/setForbiddenWord',
            'method' => 'post',
            'params' => [
                'keyword',
            ],
        ],
        'forbiddenIp' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Message/setForbiddenIp',
            'method' => 'post',
            'params' => [
                'ip',
            ],
        ],
        'delForbiddenIp' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Message/remForbiddenIp',
            'method' => 'post',
            'params' => [
                'ip',
            ],
        ],
        'delForbiddenDevice' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Message/remForbiddenDevice',
            'method' => 'post',
            'params' => [
                'deviceid',
            ],
        ],
        'forbiddenDevice' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Message/setForbiddenDevice',
            'method' => 'post',
            'params' => [
                'deviceid',
            ],
        ],
        'setKeyword' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/filterkw/setKeyword',
            'method' => 'post',
            'params' => [
                'keyword',
            ],
        ],

        'delKeyword' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/filterkw/delKeyword',
            'method' => 'post',
            'params' => [
                'keyword',
            ],
        ],
        'setForbiddenKeyword' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/filterkw/setForbiddenKeyword',
            'method' => 'post',
            'params' => [
                'keyword',
            ],
        ],
        'delForbiddenKeyword' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/filterkw/delForbiddenKeyword',
            'method' => 'post',
            'params' => [
                'keyword',
            ],
        ],
        'getKeyword' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/filterkw/getKeyword',
            'method' => 'post',
            'params' => [
                'keyword',
            ],
        ],
        'getRankingInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/horseracing/getRankingInfo',
            'method' => 'get',
            'params' => [
                'userid',
            ],
        ],
        //------------------------充值星光---------------------------------------------//
        'starTransfer' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/starTransfer',
            'method' => 'post',
            'params' => [
                'userid',
                'key',
                'num',
            ],
        ],
        //----------------------------星钻转星光------------------------------------------------//
        'diamondTransferStar' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/diamondTransferStar',
            'method' => 'post',
            'params' => [
                'userid',
                'key',
                'num',
            ],
        ],
        'gameAdd' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/add',
            'method' => 'post',
            'params' => [
                'name',
                'icon',
                'type',
                'isshow',
                'extends',
                'h5_url',
                'weight',
                'fullscreen',
            ],
        ],
        'gameUpdateBaseInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/updateBaseInfo',
            'method' => 'post',
            'params' => [
                'gameid',
                'name',
                'icon',
                'type',
                'isshow',
                'h5_url',
                'weight',
                'fullscreen',
            ],
        ],
        'gameUpdateExtends' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/updateExtends',
            'method' => 'post',
            'params' => [
                'gameid',
                'extends',
            ],
        ],
        'gameAddRebot' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/addGameRebot',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
                'currency',
            ],
        ],
        'gameDeleteRebot' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/deleteGameRebot',
            'method' => 'post',
            'params' => [
                'uid',
                'type',
                'currency',
            ],
        ],
        'gameAddReward' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/addReward',
            'method' => 'post',
            'params' => [
                'giftid',
                'lower',
                'upper',
                'orderid',
            ],
        ],
        'gameDeleteReward' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/deleteReward',
            'method' => 'post',
            'params' => [
                'id',
            ],
        ],
        'gameStatAdd' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/GameStat/addData',
            'method' => 'post',
            'params' => [
                'total_num',
                'stake_num',
                'round_num',
                'stake_amount',
                'stake_income',
                'banker_num',
                'banker_income',
                'robots_income',
                'total_split',
                'total_income',
                'day',
                'robots_deposit',
            ],
        ],
        'roomWatched' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/live/watched',
            'method' => 'post',
            'params' => [
                'liveid',
            ],
        ],
        'setLiveCover' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/live/adminSetCover',
            'method' => 'post',
            'params' => [
                'liveid',
                'cover',
            ],
        ],
        'setVideoCover' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/video/adminSetCover',
            'method' => 'post',
            'params' => [
                'videoid',
                'cover',
            ],
        ],
        'privacyRoomBuy' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/privacy/buy',
            'method' => 'post',
            'params' => [
                'privacyid',
            ],
        ],
        'privacyRoomPreview' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/privacy/preview',
            'method' => 'post',
            'params' => [
                'privacyid',
            ],
        ],
        'accountCoin' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/diamondTransferBaiyingGold',
            'method' => 'post',
            'params' => [
                'uid',
                'amount',
                'key',
            ],
        ],
        'winMessage' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/message/guessing',
            'method' => 'post',
            'params' => [
                'uid',
                'title',
                'content',
                'date',
            ],
        ],
        'topicAdd' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/topic/add',
            'method' => 'post',
            'params' => [
                'name',
                'region',
                'relateid',
                'type',
            ],
        ],
        'topicDel' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/topic/del',
            'method' => 'post',
            'params' => [
                'name',
                'region',
                'relateid',
                'type',
            ],
        ],
        'topicClean' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/topic/clean',
            'method' => 'post',
            'params' => [
                'name',
                'region',
            ],
        ],
        'putGift' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/bag/putGift',
            'method' => 'post',
            'params' => [
                'uid',
                'giftid',
                'num',
                'notice',
            ],
        ],
        'putRide' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/bag/putRide',
            'method' => 'post',
            'params' => [
                'uid',
                'cateid',
                'productid',
                'expire',
                'num',
                'notice',
            ],
        ],
        'topicTop' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/bucket/top',
            'method' => 'post',
            'params' => [
                'name',
                'region',
                'relateid',
                'type',
            ],
        ],
        'topicUnTop' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/bucket/unTop',
            'method' => 'post',
            'params' => [
                'name',
                'region',
                'relateid',
                'type',
            ],
        ],
        'getBannerInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/banner/getBannerInfo',
            'method' => 'post',
            'params' => [
                'banner_id',
            ],
        ],
        'getWithdrawalVerifiedStatus' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/verified/getWithdrawalVerifiedStatus',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'getWeekStarRank' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/rank/getWeekStarRank',
            'method' => 'post',
            'params' => [
                'anchorid',
            ],
        ],
        'getWebCategory' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/web/index',
            'method' => 'post',
            'params' => [],
        ],
        'getWebList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/web/getList',
            'method' => 'post',
            'params' => [
                'fid',
                'offset',
                'limit',
            ],
        ],
        'getWebContent' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/web/content',
            'method' => 'post',
            'params' => [
                'contentid',
            ],
        ],
        'adminSetVipConfig' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/adminSetVipConfig',
            'method' => 'post',
            'params' => [
                'vip', 'incr_amount', 'decr_amount', 'logo', 'exclusive', 'ride', 'ride_expire', 'horn_num', 'prop_num', 'font_color', 'silence', 'kick', 'silence_num', 'kick_num',
            ],
        ],
        'getOtherToken' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/otherLogin/login',
            'method' => 'post',
            'params' => [
                'token',
            ],
        ],
        'getOtherUserInfo' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/otherLogin/getUserInfo',
            'method' => 'POST',
            'params' => [
                "token",
            ],
        ],
        'getOtherIsLogin' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/otherLogin/isLogin',
            'method' => 'POST',
            'params' => [
                "token",
            ],
        ],
        'getMultiUserInfo' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getMultiUserInfo',
            'method' => 'POST',
            'params' => [
                "uids",
            ],
        ],
        'getVipInfo' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getVipInfo',
            'method' => 'POST',
            'params' => [],
        ],
        //============================活动管理==================================
        'getPromotionRank' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/activity/getPromotionRank',
            'method' => 'POST',
            'params' => [
                "supportModuleId",
                "zone",
                "date",
                "type",
            ],
        ],
        'editPrizeList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lotto/editPrizeList',
            'method' => 'POST',
            'params' => [
                "prize_list",
            ],
        ],
        'modPrizeLog' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lotto/modPrizeLog',
            'method' => 'POST',
            'params' => [
                "id",
                'prizeid',
            ],
        ],
        'addOperationDreamid' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Operation/add',
            'method' => 'POST',
            'params' => [
                "uids",
            ],
        ],
        'delOperationDreamid' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Operation/delete',
            'method' => 'POST',
            'params' => [
                "uid",
            ],
        ],
        'diamond' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/AwardInternaleconomic/diamond',
            'method' => 'POST',
            'params' => [
                "adminid",
                "activeid",
                "active_name",
                "uid",
                "amount",
                "key",
            ],
        ],
        'ticket' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/AwardInternaleconomic/ticket',
            'method' => 'POST',
            'params' => [
                "adminid",
                "activeid",
                "active_name",
                "uid",
                "amount",
                "key",
            ],
        ],

        'lotto/getPrize' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lotto/getPrize',
            'method' => 'GET',
            'params' => [
                "liveid",
                "type",
            ],
        ],
        'game/getGameInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/getGameInfo',
            'method' => 'GET',
            'params' => [
                "gameid",
            ],
        ],
        'game/getLottoState' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/game/getLottoState',
            'method' => 'GET',
            'params' => [
                "gameid",
            ],
        ],
        'lotto/getLottoLog' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lotto/getLottoLog',
            'method' => 'GET',
            'params' => [
                "gameid",
                "page",
            ],
        ],
        'live/trace' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/live/trace',
            'method' => 'GET',
            'params' => [
                'liveid',//必填
                //'userid',
                'bps',
                'fps',
                'plr',
                'direct',
                'relayip',
                'userip',
                'lng',
                'lat',
                'location',
                'platform',
                'province',
                'city',
                'district',
                'version',
                'brand',
                'model',
                'network',
                'deviceid',
                'localbps',
                'webpull',//必填
            ],
        ],
        'getKingList' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/king/getlist',
            'method' => 'POST',
            'params' => [
            ],
        ],
        'getListVisitor' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/king/getListVisitor',
            'method' => 'POST',
            'params' => [
            ],
        ],
        'getKingMedal' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/king/getMedalList',
            'method' => 'POST',
            'params' => [
            ],
        ],
        'getTicketByFamilyid' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getTicketByFamilyid',
            'method' => 'POST',
            'params' => [
                "familyid",
            ],
        ],
        'familyWithdrawAdd' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/familyWithdrawAdd',
            'method' => 'POST',
            'params' => [
                "familyid",
                "admin",
            ],
        ],
        'familyCorporateChange' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/familyCorporateChange',
            'method' => 'POST',
            'params' => [
                "familyid",
            ],
        ],
        'familyNoCorporateChange' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/familyNoCorporateChange',
            'method' => 'POST',
            'params' => [
                "familyid",
            ],
        ],
        'addToFamily' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/addToFamily',
            'method' => 'POST',
            'params' => [
                "uid",
            ],
        ],
        'getAuthorPercent' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/getAuthorPercent',
            'method' => 'POST',
            'params' => [
                "uid",
            ],
        ],
        'streamSpeed' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/live/streamSpeed',
            'method' => 'POST',
            'params' => [
                "partners",
                "region",
                "uid",
                "ip",
                "liveid",
            ],
        ],
        'adminImportAnchors' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/user/adminImportAnchors',
            'method' => 'post',
            'params' => [
                'data',
            ],
        ],
        'userSetUserLiveAuth' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/setUserLiveAuth',
            'method' => 'post',
            'params' => [
                'uid',
                'flag',
            ],
        ],
        'userGetUserLiveAuth' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getUserLiveAuth',
            'method' => 'post',
            'params' => [
                'uid',
            ],
        ],
        'imprison' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/match/imprison',
            'method' => 'post',
            'params' => [
                'uid',
                'second',
                'source',
                'matchid',
                'adminid',
            ],
        ],
        'release' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/match/release',
            'method' => 'post',
            'params' => [
                'prisonid',
            ],
        ],
        'actChristmas' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/actChristmas',
            'method' => 'post',
            'params' => [
                'uid',
                'stage',
            ],
        ],
        'cancelReport' => [//后台 举报删除关注关系
            'host' => self::DEFAULT_HOST,
            'url' => '/follow/cancelReport',
            'method' => 'post',
            'params' => [
                'follower', 'followed',
            ],
        ],
        'operationWithdrawCorporate' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/corporateEdit',
            'method' => 'post',
            'params' => [
                'orderid',
                'familyid',
                'author_percent',
                'pay_percent',
                'three_pay_percent',
                'is_receipt',
                'is_receipt_real',
                'settlement',
                'rate',
            ],
        ],
        'operationWithdrawCorporateDetail' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/withdraw/corporateEditDetail',
            'method' => 'post',
            'params' => [
                'id',
                'real_tickets',
            ],
        ],
        'refuseCorporate' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Withdraw/corporateReject',
            'method' => 'post',
            'params' => [
                'orderid',
                'reason',
            ],
        ],
        'acceptCorporate' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Withdraw/corporateAccept',
            'method' => 'post',
            'params' => [
                'orderid',
            ],
        ],
        //星星兑换星钻
        'starDiamondList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/starDiamondList',
            'method' => 'post',
            'params' => [

            ],
        ],
        'starTransferDiamond' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/starTransferDiamond',
            'method' => 'post',
            'params' => [
                'num',
                'key',
            ],
        ],
        'iconDiamondList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/iconDiamondList',
            'method' => 'post',
            'params' => [

            ],
        ],
        'exchange' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/account/exchange',
            'method' => 'post',
            'params' => [
                'amount',
            ],
        ],
        'applyInviteCode' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/activityMummit/applyInviteCode',
            'method' => 'post',
            'params' => [

            ],
        ],
        'useInviteCode' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/activityMummit/useInviteCode',
            'method' => 'post',
            'params' => [
                'code',
            ],
        ],
        'getRank' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/activityMummit/getRank',
            'method' => 'post',
            'params' => [

            ],
        ],
        'getMatchInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/activityMummit/getMatchInfo',
            'method' => 'post',
            'params' => [

            ],
        ],
        'getHelpList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feedback/getHelpList',
            'method' => 'post',
            'params' => [],
        ],
        'getHelpInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feedback/getHelpInfo',
            'method' => 'post',
            'params' => [
                'id',
            ],
        ],
        'adminSetfeedbackHelps' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feedback/adminSetfeedbackHelps',
            'method' => 'post',
            'params' => [
                'id', 'title', 'content', 'rank',
            ],
        ],
        'adminAddfeedbackHelps' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feedback/adminAddfeedbackHelps',
            'method' => 'post',
            'params' => [
                'title', 'content', 'rank',
            ],
        ],
        'adminDelfeedbackHelps' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/feedback/adminDelfeedbackHelps',
            'method' => 'post',
            'params' => [
                'id',
            ],
        ],
        'getPrivateGiftWhiteList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/gift/getPrivateGiftWhiteList',
            'method' => 'post',
            'params' => [
            ],
        ],
        'setPrivateGiftWhiteList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/gift/setPrivateGiftWhiteList',
            'method' => 'post',
            'params' => [
                'data',
            ],
        ],
        'answerSetRound' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/setRound',
            'method' => 'post',
            'params' => [
                'roundid',
                'amount',
                'startime',
                'title',
            ],
        ],
        'answerPublish' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/publish',
            'method' => 'post',
            'params' => [
                'roundid',
                'questionid',
                'answer',
                'order',
                'options',
                'title',
                'duration',
            ],
        ],
        'answerWinners' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/getWinners',
            'method' => 'post',
            'params' => [
                'roundid',
            ],
        ],
        'answerAward' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/cashSend',
            'method' => 'post',
            'params' => [
                'roundid',
                'amount',
                'questionid',
            ],
        ],
        'answerChangeSN' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/adminChange/changeSN',
            'method' => 'post',
            'params' => [
                'liveid',
                'sn',
                'partners',
            ],
        ],
        'clearCache' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/answer/clearCache',
            'method' => 'post',
            'params' => [
                'roundid',
            ],
        ],
        'getUserCodeInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/activityMummit/getUserCodeInfo',
            'method' => 'post',
            'params' => [
                'userid',
            ],
        ],
        //pk活动
        'getConductPK' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/match/getConductPK',
            'method' => 'post',
            'params' => [

            ],
        ],
        'getAnchorTop3' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/match/getAnchorTop3',
            'method' => 'post',
            'params' => [

            ],
        ],
        'getUserTop3' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/match/getUserTop3',
            'method' => 'post',
            'params' => [

            ],
        ],
        'freeRevivalCard' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/ActivityMummit/freeRevivalCard',
            'method' => 'post',
            'params' => [

            ],
        ],
        'liveTimeRank' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/rank/liveTimeRank',
            'method' => 'post',
            'params' => [
                'anchorid',
            ],
        ],
        'addLiveTimeWarning' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/rank/addLiveTimeWarning',
            'method' => 'post',
            'params' => [
                'info',
            ],
        ],
        'resetQqUserPwd' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/resetQqUserPwd',
            'method' => 'post',
            'params' => [
                'uid', 'pwd',
            ],
        ],

        'actOldUserRecall' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/recallAct',
            'method' => 'post',
            'params' => [

            ],
        ],
        'getLoginUserInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/getLoginUserInfo',
            'method' => 'post',
            'params' => [

            ],
        ],
        'addUserRelations' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/addUserRelation',
            'method' => 'post',
            'params' => [
                'phones',
                'sharid',
                'userid',
                'type',

            ],
        ],
        'miniAppSession' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/miniAppSession',
            'method' => 'post',
            'params' => [
                'code'
            ],
        ],
        'actYuanxiao' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/yuanxiaoAct',
            'method' => 'post',
            'params' => [

            ],
        ],
        'adminSetVipLevel' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/adminSetVipLevel',
            'method' => 'post',
            'params' => [
                'uid','newlevel'
            ],
        ],
        'rbRegister' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/rbRegister',
            'method' => 'post',
            'params' => [
                'anchorid','group'
            ],
        ],
        'rbRank' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/rbRank',
            'method' => 'post',
            'params' => [
                'anchorid','uid'
            ],
        ],
        'qingMing' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/qingMing',
            'method' => 'post',
            'params' => [
                'uid'
            ],
        ],
        'addUserSroceList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/act/addUserSroceList',
            'method' => 'post',
            'params' => [
                'info'
            ],
        ],
        'bagReload' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/bag/reloadBag',
            'method' => 'post',
            'params' => [
                'uid'
            ],
        ],
        //星钻兑换跑马游戏币
        'prepare' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/gameExchange/prepare',
            'method' => 'post',
            'params' => [
                'amount',
                'game',
                'direct',
                'trade_no',
                'sign'
            ],
        ],
        'complete' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/gameExchange/complete',
            'method' => 'post',
            'params' => [
                'orderid',
                'sign'
            ],
        ],
        'verify' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/gameExchange/verify',
            'method' => 'post',
            'params' => [
                'orderid'
            ],
        ],
        'gameExchangeList' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/gameExchange/list',
            'method' => 'post',
            'params' => [
                'game'
            ],
        ],
        'getGameUserInfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getGameUserInfo',
            'method' => 'post',
            'params' => [

            ],
        ],
        'getMiniAppAcode' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/MiniApp/acode',
            'method' => 'post',
            'params' => [
                "xuan"
            ],
        ],
    ];

    public static $mini_conf = [

    ];

    public static function conf($name)
    {
        $conf=array_merge(static::$api_conf, static::$mini_conf);
        return isset($conf[$name])?$conf[$name]:null;
    }
}