<?php
if (! defined('SERVER_CONF_COMMON')) {
    define('SERVER_CONF_COMMON', true);

    date_default_timezone_set('Asia/Shanghai');

    // ------------------ 错误码 ------------------
    // 成功
    define("OK", 0);

    // 公共错误码(参数、用户、api和app、系统)
    define("ERROR_PARAM_IS_EMPTY", 1001);
    define("ERROR_PARAM_NOT_EXIST", 1002);
    define("ERROR_PARAM_INVALID_SIGN", 1003);
    define("ERROR_PARAM_FLOOD_REQUEST", 1004);
    define("ERROR_PARAM_INVALID_FORMAT", 1005);
    define("ERROR_API_NOT_ALLOWED", 1006);
    define("ERROR_IP_NOT_ALLOWED", 1007);
    define("ERROR_METHOD_NOT_ALLOWED", 1008);
    define("ERROR_PARAM_REQUEST_RESTRICT", 1010);
    define("ERROR_PARAM_NOT_SMALL_ZERO", 1011);
    define("ERROR_PARAM_DATA_NOT_EXIST", 1012);
    define("ERROR_PARAM_PLATFORM_INVALID", 1013);
    define("ERROR_PARAM_SIGN_INVALID", 1014);
    define("ERROR_PARAM_NOT_HAS_POINT", 1015);

    define("ERROR_USER_NOT_LOGIN", 1101);
    define("ERROR_LOGINUSER_NOT_EXIST", 1102);
    define("ERROR_USER_NOT_EXIST", 1103);
    define("ERROR_USER_ERR_TOKEN", 1104);
    define("ERROR_USER_ERR_BLACK", 1105);
    define("ERROR_USER_NAME_EXISTS", 1106);
    define("ERROR_PHONENUM_INVALID", 1107);
    define("ERROR_CODE_OVERTIMES", 1108);
    define("ERROR_CODE_INVALID", 1109);
    define("ERROR_USER_NAME_DIRTY", 1110);
    define("ERROR_FOLLOW_TOO_MUCH", 1111);
    define("ERROR_USER_SIGNATURE_DIRTY", 1600);
    define("ERROR_FOLLOW_FOLLOWED", 1506);
    define("ERROR_USER_REGED", 1112);
    define("BIZ_PASSPORT_ERROR_NOTBINDED", 10113);
    define("ERROR_USER_PASSWORD_WRONG", 1114);
    define("ERROR_USER_OAUTH_FAILD", 1115);
    define("BIZ_PASSPORT_ERROR_BINDED", 10116);
    define("BIZ_PASSPORT_ERROR_BINDED_OTHER", 10117);
    define("BIZ_PASSPORT_ERROR_BIND_LIMIT", 10118);
    define("BIZ_PASSPORT_ERROR_NOT_BIND", 10119);
    define("ERROR_KEYWORD_SHIELD", 10120); //关键字屏蔽
    define("ERROR_CODE_INTERVAL_TIME", 1119);
    define("ERROR_CAPTCHA_INVALID", 1120);
    define("ERROR_BIZ_BLOCKED_TOO_MUCH", 1121);
    define("ERROR_USER_PASSWORD_WRONG_REPEATEDLY", 1122);
    define("ERROR_USER_REPORTED", 1123);
    define("ERROR_USER_NAME_TOOLONG", 1124);
    define("ERROR_USER_NAME_SHORT", 1155);
    define('ERROR_INVITE_EXIST', 1125);
    define('ERROR_INVITE_NOT_EXIST', 1126);
    define('ERROR_INVITE_INVALID', 1127);
    define('ERROR_INVITE_ACTIVE', 1128);
    define('ERROR_INVITE_RECEIVED', 1129);
    define('ERROR_INVITER_TOTAL_DAY_LIMIT', 1130);
    define('ERROR_INVITEE_TOTAL_DAY_LIMIT', 1131);
    define('ERROR_INVITEE_DAY_LIMIT', 1132);
    define('ERROR_INVITER_NO_REWARD', 1133);
    define('ERROR_INVITEE_NO_REWARD', 1134);
    define('ERROR_INVITE_NOT_NEW', 1135);
    define("ERROR_USER_BLOCKED", 1136);
    define("ERROR_VR_BIND", 1137);
    define("ERROR_STUDENT_VERIFIED", 1138);
    define("ERROR_STUDENT_VERIFING", 1139);
    define("ERROR_STUDENT_APPLY_FIRST", 1140);
    define("ERROR_USER_LOGIN_OTHER_PLACES", 1141);
    define("ERROR_LOGIN_ERROR_NUM", 1142);
    define("ERROR_USER_LOGIN_OTHER_DEVICE", 1143);
    define("ERROR_USER_INVALID_MOBILE", 1145);
    define("ERROR_USER_WEAKPASS", 1146);
    define("ERROR_USER_FROZEN", 1147);
    define("ERROR_BIZ_USER_SIGNED", 1148);
    define("ERROR_USER_NOTBINDED", 1149);
    define("ERROR_USER_IS_BLOCKED", 1150);
    define("ERROR_MESSAGE_NO_AUTHORITY", 1151);
    define("ERROR_CHAT_MESSAGE_NUM_OVERRUN", 1152);
    define("ERROR_CHAT_MESSAGE_OVER_FREQUENCY", 1153);
    define("ERROR_CHAT_MESSAGE_QUICK_WORD_MAX_WORD", 1154);
    define("ERROR_CHAT_MESSAGE_QUICK_MAX_NUM", 1155);
    define("ERROR_CHAT_MESSAGE_QUICK_NOT_SELF", 1156);
    //oss
    define("ERROR_OSS_CONNECT_FAILD",  1501);
    define("ERROR_OSS_PUT_FAILD",  1502);
    define("ERROR_OSS_GET_FAILD",  1503);
    define("ERROR_OSS_DELETE_FAILD",  1504);
    define("ERROR_OSS_UNSUPPORTED_TYPE",  1505);

    //任务
    define("ERROR_BIZ_TASK_NOT_EXIST", 2001);
    define("ERROR_BIZ_TASK_UNSTART", 2002);
    define("ERROR_BIZ_TASK_EXPIRE", 2003);
    define("ERROR_BIZ_TASK_CLOSED", 2004);
    define("ERROR_BIZ_TASK_OVER_TIMES", 2005);
    define("ERROR_BIZ_TASK_PAYMENT", 2006);
    define("ERROR_BIZ_TASK_MIN_TIME_LIMIT", 2007);
    define("ERROR_BIZ_TASK_BAD_TICKET", 2008);
    define("ERROR_BIZ_TASK_TICKET_USED", 2009);
    define("ERROR_BIZ_TASK_NOT_IN_ROOM", 2010);

    define("ERROR_BIZ_TASK_IS_SIGN", 2011);
    define("ERROR_BIZ_TASK_SIGN_ERROR", 2012);
    define("ERROR_BIZ_TASK_AWARD_EMPTY", 2013);
    define("ERROR_BIZ_TASK_NOT_COMPLETE", 2014);

    //用户
    define("ERROR_BIZ_PASSPORT_QUEUE_UID_MASTER_ISNULL", 10201);
    define("ERROR_BIZ_PASSPORT_ERROR_SMS_SEND", 10301);
    define("ERROR_BIZ_PASSPORT_SEARCH_NOT_FOUND", 10401);
    define("ERROR_BIZ_PASSPORT_OLD_USER_NOT_FOUND", 10501);
    define("ERROR_BIZ_PASSPORT_OLD_USER_SUCESSED", 10502);
    define("ERROR_BIZ_PASSPORT_FEEDBACK_TOO_OFTEN", 10601);
    define("ERROR_BIZ_PASSPORT_FEEDBACK_HELP_NOT_FOUND", 10604);
    define("ERROR_BIZ_PASSPORT_FEEDBACK_RANK_EXIST", 10605);
    define("ERROR_BIZ_PASSPORT_USER_PASSWORD_OLD_EQ_NEW", 10701);

    //直播
    define("ERROR_BIZ_LIVE_NOT_EXIST", 3000);
    define("ERROR_BIZ_LIVE_NOT_ACTIVE", 3001);
    define("ERROR_BIZ_LIVE_NOT_PAUSED", 3002);
    define("ERROR_BIZ_LIVE_NOT_OWNER", 3003);
    define("ERROR_BIZ_LIVE_SN_NOT_EMPTY", 3004);
    define("ERROR_BIZ_LIVE_PRTNER_NOT_EMPTY", 3005);
    define('ERROR_LIVE_IS_OVER', 3006);
    define("ERROR_BIZ_PRIVACY_LIVE_NOT_EXIST", 3007);
    define("ERROR_BIZ_PRIVACY_LIVE_NOT_YOURSELF", 3008);
    define("ERROR_LIVE_SET_COVER", 3009);
    define("ERROR_BIZ_PRIVACY_CHANGE_FAILE", 3010);
    define("ERROR_BIZ_ERROR_HAS_BUYED_PRIVACY_LIVE", 3011);
    define("ERROR_BIZ_PRIVACY_LIVE_HAS_PREVIEWED", 3012);
    define("ERROR_BIZ_PRIVACY_LIVE_HAS_EXIST", 3013);
    define("ERROR_BIZ_PRIVACY_PERMISSION", 3014);
    define("ERROR_BIZ_PRIVACY_DELAYTIME", 3015);
    define("ERROR_BIZ_LIVE_SN_ERROR", 3016);

    //图片
    define("ERROR_BIZ_IMAGE_NOT_EXIST", 4000);

    //视频
    define("ERROR_BIZ_VIDEO_NOT_EXIST", 5000);
    define("ERROR_VIDEO_SET_COVER", 5001);
    define("ERROR_BIZ_VIDEO_NOT_OWNER", 5002);

    // 公会
    define("ERROR_BIZ_PAYMENT_FAMILY_ISVERIFIED", 60001);
    define("ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST", 60002);
    define("ERROR_BIZ_PAYMENT_FAMILY_ISEMPLOYE", 60007);
    define("ERROR_BIZ_PAYMENT_FAMILY_ISNOTEMPLOYE", 60004);
    define("ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID", 60005);
    define("ERROR_BIZ_PAYMENT_FAMILY_ISCONTRACT", 60006);
    define("ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY", 61001);
    define("ERROR_BIZ_PAYMENT_FAMILY_OWNER", 61002);
    define("ERROR_BIZ_PAYMENT_FAMILY_NOTOWNER", 61004);
    define("ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT", 61003);
    define("ERROR_BIZ_PAYMENT_FAMILY_HASAPPROVE", 61005);
    // 经济系统
    define("ERROR_BIZ_PAYMENT_WITHDRAW_EXIST", 60003);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN", 60008);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_FAMILY_FROZEN", 60009);
    define("ERROR_BIZ_PAYMENT_ACCOUNT_TICKET_LACK", 60010);
    define("ERROR_BIZ_PAYBIND_NOT_EXIST", 60011);
    define("ERROR_BIZ_PAYBIND_MOBILE_CODE_INVAILD", 60012);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_TOKEN", 60013);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_TOTAL_OUT", 60014);
    define("ERROR_BIZ_PAYBIND_ACCOUNT_NOT_VALID", 60015);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_CLOSE", 60016);
    define("ERROR_BIZ_PAYMENT_DEPOSIT_CLOSE", 60017);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_SMALL", 60018);
    define("ERROR_BIZ_PAYMENT_DEPOSIT_FALSE", 60019);
    define("ERROR_BIZ_PAYMENT_ACCOUNT_TRANSFER_FAMILY_FALSE", 60020);
    define("ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK", 60021);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_CASH_AMOUNT_SMALL", 60022);
    define("ERROR_BIZ_PAYMENT_IS_OPERATION", 60023);
    define("ERROR_BIZ_PAYMENT_TICKET_FROZEN", 600024);
    define("ERROR_BIZ_PAYMENT_DEPOSIT_HANDLING", 600025);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_NO_POWER", 600034);
    define("ERROR_BIZ_PAYMENT_WITHDRAW_APPLY", 600044);

    define("ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE", 60101);
    define("ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE", 60102);
    define("ERROR_BIZ_PAYMENT_VIRTUAL_BALANCE_DUE", 60103);
    define("ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE", 60104);
    define("ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE", 60105);
    define("ERROR_BIZ_PAYMENT_STAR_DIAMOND_LIMIT", 60106);

    define("ERROR_BIZ_PAYMENT_TICKET_OUT_ACCOUNTED", 60201);
    define("ERROR_BIZ_PAYMENT_TICKET_ACCOUNTED_FOR", 60202);
    define("ERROR_BIZ_PAYMENT_DIAMOND_OUT_ACCOUNTED", 60203);
    define("ERROR_BIZ_PAYMENT_DIAMOND_ACCOUNTED_FOR", 60204);
    define("ERROR_BIZ_PAYMENT_VIRTUAL_OUT_ACCOUNTED", 60205);
    define("ERROR_BIZ_PAYMENT_VIRTUAL_ACCOUNTED_FOR", 60206);
    define("ERROR_BIZ_PAYMENT_ACCOUNTED_ERROR", 60207);
    define("ERROR_BIZ_PAYMENT_DEPOSIT_SOURCE_ERROR", 60208);
    define("ERROR_BIZ_PAYMENT_STAR_OUT_ACCOUNTED", 60209);

    //经济系统-gift
    define("ERROR_BIZ_GIFT_NOT_FIND", 60301);
    define("ERROR_BIZ_GIFT_NOT_SEND_SELF", 60302);
    define("ERROR_BIZ_PAYMENT_PRODUCT_ADD_FAIL", 60303);
    define("ERROR_BIZ_PAYMENT_PRODUCT_UPDATE_FAIL", 60304);
    define("ERROR_BIZ_GIFT_NOT_GUARD", 60909);
    define("ERROR_BIZ_GIFT_HAS_NOT_PRIVATE_SEND_AUTH", 60910);

    define("ERROR_BIZ_PAYMENT_TRADE_LIMIT", 60901);
    define("ERROR_BIZ_PAYMENT_ACTIVE_LIMIT", 60902);

    //经济系统-product
    define("ERROR_BIZ_PAYMENT_PRODUCT_DEFAULT", 60400);
    define("ERROR_BIZ_PAYMENT_PRODUCT_NOT_FOUND", 60401);
    define("ERROR_BIZ_PAYMENT_PRODUCT_NOT_ONLINE", 60402);
    define("ERROR_BIZ_PAYMENT_TRADE_PROCESS_LOCK", 60403);
    define("ERROR_BIZ_PAYMENT_TRADE_ACCOUNT_SYSTEM", 60404);
    define("ERROR_BIZ_PAYMENT_TRADE_ACCOUNT_USER", 60405);
    define("ERROR_BIZ_PAYMENT_TRADE_NOT_PAY", 60406);
    define("ERROR_BIZ_PAYMENT_OUT_DATE", 60407);


    //游戏系统-game
    define("ERROR_BIZ_GAME_ANCHOR_GRADE_NOT_ENOUGH", 101000);
    define("ERROR_BIZ_GAME_NOT_EXIST", 101001);
    define("ERROR_BIZ_GAME_NOT_DO", 101002);
    define("ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST", 101003);
    define("ERROR_BIZ_GAME_HORSERACING_AMOUNT_MIN", 101005);
    define("ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE", 101006);
    define("ERROR_BIZ_GAME_HORSERACING_TRACKNO_NOT_EXIST", 101007);
    define("ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL", 101008);
    define("ERROR_BIZ_GAME_HORSERACING_TRACKNO_FALSE", 101009);
    define("ERROR_BIZ_GAME_FALSE", 101010);
    define("ERROR_BIZ_GAME_HORSERACING_BLANKER_TIME_OUT", 101011);
    define("ERROR_BIZ_GAME_HORSERACING_TRACKNO_TIME_OUT", 101012);
    define("ERROR_BIZ_GAME_NOT_CLOSE", 101013);
    define("ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST", 101014);
    define("ERROR_BIZ_GAME_ROBOTS_EXIST", 101015);
    define("ERROR_BIZ_GAME_NOT_BANKER", 101016);
    define("ERROR_BIZ_STAR_BALANCE_DUE", 101017);
    define("ERROR_BIZ_LOTTO_GAME_LESS_TIME", 101018);

    // 红包
    define("ERROR_BIZ_PACKET_SEND_ERROR", 60309);
    define("ERROR_BIZ_PACKET_SERCEIVE_NOT_ACTIVE", 60310);
    define("ERROR_BIZ_PACKET_SEND_NOTALLOW", 60311);
    define("ERROR_BIZ_PACKET_SERCEIVE_EMPTY", 60305);
    define("ERROR_BIZ_PACKET_OPEN_ERROR", 60306);
    define("ERROR_BIZ_PACKET_OPEN_FINISH", 60307);
    define("ERROR_BIZ_PACKET_SEND_AMOUNT_NOT_ENOUGH", 60308);

    // 社团
    define("ERROR_BIZ_CLUB_NAME_EXIST", 60501);
    define("ERROR_BIZ_CLUB_SHORTNAME_EXIST", 60502);
    define("ERROR_BIZ_CLUB_MEMBER_EXIST", 60503);
    define("ERROR_BIZ_CLUB_SETUP_VIP", 60504);

    //背包
    define("ERROR_BIZ_BAG_USE_RIDE_FAIL", 63001);
    define("ERROR_BIZ_BAG_USE_RIDE_OUT_DATE", 63002);
    define("ERROR_BIZ_BAG_USE_GIFT_NUM_LESS", 63003);
    define("ERROR_BIZ_BAG_USE_GIFT_FAIL", 63004);

    //计数器
    define("ERROR_BIZ_COUNTER_BUSY_RETRY",  70001);
    define('ERROR_BIZ_COUNTER_IS_NEGATIVE', 70002);

    //评论
    define('ERROR_BIZ_REPLY_NOT_EXISTS',    80000);
    define('ERROR_BIZ_REPLY_NOT_SELF',        80001);
    define('ERROR_BIZ_NOTICE_ONLY_THREE',    80002);
    define('ERROR_BIZ_NOTICE_ONLY_LIVE_ONE',    80003);

    //聊天室
    define('ERROR_BIZ_CHATROOM_USER_HAS_SILENCED', 90000);
    define('ERROR_BIZ_CHATROOM_HAS_BLOCKED', 90001);
    define('ERROR_BIZ_CHATROOM_HAS_FORBIDEEN', 90002);
    define('ERROR_BIZ_CHATROOM_PATROLLER_LIMIT', 90003);
    define('ERROR_BIZ_CHATROOM_NO_AUTHORITY', 90004);
    define('ERROR_BIZ_CHATROOM_PATROLLER_EXSIT', 90005);
    define('ERROR_BIZ_CHATROOM_PATROLLER_CANNOT_SELF', 90006);
    define('ERROR_BIZ_CHATROOM_KICK_CANNOT_SELF', 90007);
    define('ERROR_BIZ_CHATROOM_SILENCE_CANNOT_SELF', 90008);
    define('ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER', 90009);
    define('ERROR_BIZ_CHATROOM_HAS_KICKED', 90010);
    define('ERROR_BIZ_CHATROOM_ONLY_OPEN_ONE', 90011);
    define('ERROR_BIZ_CHATROOM_VIP_GUARD', 90012);
    define('ERROR_BIZ_CHATROOM_VIP_KICK_NUM_LT_ONE', 90013);
    define('ERROR_BIZ_CHATROOM_VIP_SILENCE_NUM_LT_ONE', 90014);
    define('ERROR_BIZ_CHATROOM_NO_PRIVACY_RIGHTS', 90015);

    define("ERROR_SYS_DB_SQL",             1301);
    define("ERROR_SYS_REDIS",              1302);
    define("ERROR_SYS_CONTEXT_KEY_EXISTS", 1303);
    define("ERROR_SYS_CONTEXT_KEY_NOT_EXISTS", 1307);
    define("ERROR_SYS_NEEDPOST",           1304);
    define("ERROR_SYS_UNKNOWN",               1305);
    define("ERROR_SYS_DB_UID",             1306);

    //守护
    define("ERROR_GUARD_NOT_EXIST",        100001);
    define("ERROR_GUARD_TYPE_MONTH",       100002);
    define("ERROR_BUY_GUARD",              100003);
    define("ERROR_GUARD_DIAMONDS",         100004);
    define("ERROR_GUARD_NOT_SELF",         100005);

    //私密直播
    define("ERROR_BUY_PRIVACY_ROOM",                   100006);
    define("ERROR_BUY_PRIVACY_ROOM_NOORDERID",       100007);
    define("ERROR_BUY_PRIVACY_ROOM_ADDLOG",            100008);
    define("ERROR_DELAY_PRIVACY_ROOM",                100009);
    define("ERROR_MODIFY_PRICE_PRIVACY_ROOM",       100010);

    //场控
    define('ERROR_PATROLLER_ADD', 90015);
    define('ERROR_PATROLLER_DEL', 90016);
    //自定义
    define("ERROR_CUSTOM",  93004);

    //回源鉴权
    define("AUTHORITY_WS_TIME_ERROR",  200001);
    define("AUTHORITY_WS_SECRET_ERROR",  200002);
    define("AUTHORITY_WS_LIVE_ERROR",  200003);

    //pk项目
    define("ERROR_MATCH_USER_IS_PRISON", 210000);
    define("ERROR_MATCH_DURATION_NOT_EXIST", 210001);
    define("ERROR_MATCH_INVITEE_IS_PRISON", 210002);
    define("ERROR_MATCH_USER_IS_PK", 210003);
    define("ERROR_MATCH_INVITEE_IS_PK", 210004);
    define("ERROR_MATCH_USER_LIVE_NOT_ACTIVE", 210005);
    define("ERROR_MATCH_INVITEE_LIVE_NOT_ACTIVE", 210006);
    define("ERROR_MATCH_NOT_EXIST", 210013);
    define("ERROR_MATCH_APPLY_FALSE", 210007);
    define("ERROR_MATCH_ACCEPT_FALSE", 210008);
    define("ERROR_MATCH_ACCEPT_EXIST", 210009);
    define("ERROR_MATCH_IS_FLOW", 210010);
    define("ERROR_MATCH_ADD_PRISON_FALSE", 210011);
    define("ERROR_MATCH_DEL_PRISON_FALSE", 210012);
    define("ERROR_MATCH_INVITEE_NOT_MY", 210014);
    define("ERROR_MATCH_NOT_ACCEPT", 210015);
    define("ERROR_MATCH_USER_LIVE", 210016);
    define("ERROR_MATCH_INVITER_IS_PK", 210017);
    define("ERROR_MATCH_INVITER_TICKET_FALSE", 210018);
    define("ERROR_MATCH_INVITEE_TICKET_FALSE", 210019);

    //连麦
    define("ERROR_LINK_REPEAT_APPLY", 220001);
    define("ERROR_LINK_ANCHOR_PERMISSIONS", 220002);
    define("ERROR_LINK_USER_PERMISSIONS", 220003);
    define("ERROR_LINK_APPLY_NOT_IN_LIVEROOM", 220004);

    //冲顶项目
    define("ERROR_SUMMIT_CODE_EXIST", 230001);
    define("ERROR_SUMMIT_CODE_NOT_EXIST", 230002);
    define("ERROR_SUMMIT_USER_IS_EXIST", 230003);
    define("ERROR_SUMMIT_USER_IS_FALSE", 230004);
    define("ERROR_SUMMIT_USER_IS_DIE_OUT", 230005);//已淘汰
    define("ERROR_SUMMIT_USER_IS_UNSTART", 230006);//活动未开启
    define("ERROR_SUMMIT_USER_QUESTION_NOT_SEND", 230007);//问题还未下发
    define("ERROR_SUMMIT_USER_QUESTION_IS_END", 230008);//答题时间结束
    define("ERROR_SUMMIT_USER_QUESTION_NOT_START", 230009);//答题时间未开始
    define("ERROR_SUMMIT_USER_QUESTION_ONLY_ONE_TIME", 230010);//一场比赛一道题只能答一次
    define("ERROR_SUMMIT_USER_IS_ENDED", 230011);//活动已结束
    define("ERROR_SUMMIT_REVIVAL_IS_EXIST", 230012);//已领过复活卡

    //微信小程序
    define("ERROR_SUMMIT_IS_DRAW", 240001);

    //新版赛马
    define("ERROR_SUMMIT_HANDBOOKINGER_TRACKNO_IS_NOT_EXIST", 250001);
    define("ERROR_SUMMIT_HANDBOOKINGER_STAKE_TIME_OUT", 250002);
    define("ERROR_SUMMIT_HANDBOOKINGER_STAKE_AMOUNT_NOT_EXIST", 250003);
    define("ERROR_SUMMIT_HANDBOOKINGER_DIAMOND_BALANCE_DUE", 250004);
}

$ERROR_LIST = array(
    OK => "操作成功",
    // 参数
    ERROR_PARAM_IS_EMPTY => "参数%s不能为空!",
    ERROR_PARAM_NOT_EXIST => "参数%s不存在",
    ERROR_PARAM_INVALID_SIGN => "签名校验失败",
    ERROR_PARAM_FLOOD_REQUEST => "不能重复请求",
    ERROR_PARAM_INVALID_FORMAT => "参数%s格式错误",
    ERROR_API_NOT_ALLOWED => "接口未授权",
    ERROR_IP_NOT_ALLOWED => "IP未授权%s",
    ERROR_METHOD_NOT_ALLOWED => "方法未授权%s",
    ERROR_PARAM_REQUEST_RESTRICT => "异常重复请求",
    ERROR_PARAM_NOT_SMALL_ZERO => "参数%s不能小于0",
    ERROR_PARAM_DATA_NOT_EXIST=>'参数%s数据不存在',
    ERROR_PARAM_PLATFORM_INVALID=>'非法平台访问',
    ERROR_PARAM_SIGN_INVALID=>'签名校验失败',
    ERROR_PARAM_NOT_HAS_POINT => "参数%s不能是小数",

    // 用户
    ERROR_USER_NOT_LOGIN => "登录失败, 请重试",
    ERROR_LOGINUSER_NOT_EXIST => "当前登录用户不存在",
    ERROR_USER_NOT_EXIST => "用户不存在",
    ERROR_USER_ERR_TOKEN => "token无效",
    ERROR_USER_ERR_BLACK => "您的追梦账号：%s(ID：%s)已被封禁，截止到%s解封，如有疑问请联系官方客服qq：2129668693", // 封禁
    ERROR_USER_NAME_EXISTS => "昵称[%s]已存在",
    ERROR_PHONENUM_INVALID => "您输入的手机号码有误, 请重新输入!",
    ERROR_CODE_OVERTIMES => "手机验证码发送次数超限",
    ERROR_CODE_INVALID => "验证码不正确或已过期",
    ERROR_USER_NAME_DIRTY => "昵称不合法或已存在",
    ERROR_USER_SIGNATURE_DIRTY => "签名不合法",
    ERROR_FOLLOW_TOO_MUCH => "关注的人太多了",
    ERROR_FOLLOW_FOLLOWED => "已经关注",
    ERROR_USER_REGED => "已经注册",
    BIZ_PASSPORT_ERROR_NOTBINDED => "尚未注册",
    ERROR_USER_PASSWORD_WRONG => "密码错误",
    ERROR_USER_OAUTH_FAILD => "认证授权失败",
    BIZ_PASSPORT_ERROR_BINDED => "您已绑定过其他%s账号 …(T_T)请解绑后重试。",
    BIZ_PASSPORT_ERROR_BINDED_OTHER => "该%s已绑定其他追梦账号 …(T_T)",
    BIZ_PASSPORT_ERROR_BIND_LIMIT=>"至少绑定一个账号",
    BIZ_PASSPORT_ERROR_NOT_BIND=>"您的手机没有绑定追梦账号",
    ERROR_KEYWORD_SHIELD=>"包含屏蔽词不可发送",
    ERROR_CODE_INTERVAL_TIME=>"手机验证码发送间隔太短",
    ERROR_CAPTCHA_INVALID=>"图片验证码不正确",
    ERROR_BIZ_BLOCKED_TOO_MUCH=>"屏蔽的人太多了",
    ERROR_USER_PASSWORD_WRONG_REPEATEDLY=>"密码错误多次",
    ERROR_USER_REPORTED=>"您已经举报过了",
    ERROR_USER_NAME_TOOLONG=>"昵称不能大于8个汉字或16个字符",
    ERROR_USER_NAME_SHORT=>"昵称不能少于3个汉字或6个字符",

    ERROR_INVITE_EXIST=>'邀请记录已存在',
    ERROR_INVITE_NOT_EXIST=>'邀请记录不存在',
    ERROR_INVITE_INVALID=>'邀请记录无效',
    ERROR_INVITE_ACTIVE=>'邀请记录已激活',
    ERROR_INVITE_RECEIVED=>'邀请记录已领取',
    ERROR_INVITER_TOTAL_DAY_LIMIT=>'邀请记录邀请人红包超过当天总数限制',
    ERROR_INVITEE_TOTAL_DAY_LIMIT=>'邀请记录被邀请人红包超过当天总数限制',
    ERROR_INVITEE_DAY_LIMIT=>'邀请记录邀请人红包超过当天限制',
    ERROR_INVITER_NO_REWARD=>'邀请记录邀请人无红包',
    ERROR_INVITEE_NO_REWARD=>'邀请记录被邀请人无红包',
    ERROR_INVITE_NOT_NEW=>'邀请记录不是新用户',
    ERROR_USER_BLOCKED=>'已经被对方拉黑',
    ERROR_VR_BIND=>'该VR设备已经被绑定',
    ERROR_STUDENT_VERIFIED => "已经论证过",
    ERROR_STUDENT_VERIFING => "正在审核中",
    ERROR_STUDENT_APPLY_FIRST => "请先申请审核",
    ERROR_USER_LOGIN_OTHER_PLACES => "异地登录",
    ERROR_LOGIN_ERROR_NUM=>"账户已被锁定，请通过短信验证码登录",
    ERROR_USER_LOGIN_OTHER_DEVICE=> "新设备登录",
    ERROR_USER_INVALID_MOBILE=> "不是用户绑定的手机",
    ERROR_USER_WEAKPASS=> "用户密码比较弱",
    ERROR_USER_FROZEN=> "账号已冻结",
    ERROR_USER_IS_BLOCKED=>"用户被拉黑",
    ERROR_MESSAGE_NO_AUTHORITY=>"没有私信权限",
    ERROR_CHAT_MESSAGE_NUM_OVERRUN=>"聊天字数超限%s",
    ERROR_CHAT_MESSAGE_OVER_FREQUENCY => "聊天频次超限%s",
    ERROR_CHAT_MESSAGE_QUICK_WORD_MAX_WORD=> "快捷语字数超限%s，最多15个字符",
    ERROR_CHAT_MESSAGE_QUICK_MAX_NUM => "每人只能添加三条",
    ERROR_CHAT_MESSAGE_QUICK_NOT_SELF => "没有权限编辑他人数据",
        
    ERROR_BIZ_PASSPORT_QUEUE_UID_MASTER_ISNULL=> "uid主队列无数据",
    ERROR_BIZ_PASSPORT_ERROR_SMS_SEND => "验证码发送失败",

    ERROR_BIZ_PASSPORT_SEARCH_NOT_FOUND => "无用户信息",
    ERROR_BIZ_PASSPORT_OLD_USER_NOT_FOUND => "未发现您有旧版本账户请重新尝试或联系客服QQ：2129668693",
    ERROR_BIZ_PASSPORT_OLD_USER_SUCESSED=>"旧版本账户已被找回",
    ERROR_BIZ_PASSPORT_FEEDBACK_TOO_OFTEN=>"提交频繁，请稍后提交",
    ERROR_BIZ_PASSPORT_FEEDBACK_HELP_NOT_FOUND=>"数据找不到了，她可能去火星了~",
    ERROR_BIZ_PASSPORT_FEEDBACK_RANK_EXIST=>"序号重复",
    ERROR_BIZ_PASSPORT_USER_PASSWORD_OLD_EQ_NEW =>"两次输入密码相同",

    //任务
    ERROR_BIZ_TASK_NOT_EXIST  => "任务不存在",
    ERROR_BIZ_TASK_UNSTART    => "任务未到开始时间",
    ERROR_BIZ_TASK_EXPIRE     => "任务已过期",
    ERROR_BIZ_TASK_CLOSED     => "任务未开启",
    ERROR_BIZ_TASK_OVER_TIMES => "任务次数超限",
    ERROR_BIZ_TASK_PAYMENT    => "支付系统出错",
    ERROR_BIZ_TASK_MIN_TIME_LIMIT => "未达到最小时间限制(%s)",
    ERROR_BIZ_TASK_BAD_TICKET=> "ticket错误(%s)",
    ERROR_BIZ_TASK_TICKET_USED=> "ticket已使用过(%s)",
    ERROR_BIZ_TASK_NOT_IN_ROOM=> "用户没有在直播间(%s)",

    ERROR_BIZ_TASK_IS_SIGN => "已经签到",
    ERROR_BIZ_TASK_SIGN_ERROR => "签到错误",
    ERROR_BIZ_TASK_AWARD_EMPTY=>"奖励不能为空",
    ERROR_BIZ_TASK_NOT_COMPLETE=>"任务未完成",

    ERROR_INVITE_EXIST => '邀请记录已存在',
    ERROR_INVITE_NOT_EXIST => '邀请记录不存在',
    ERROR_INVITE_INVALID => '邀请记录无效',
    ERROR_INVITE_ACTIVE => '邀请记录已激活',
    ERROR_INVITE_RECEIVED => '邀请记录已领取',
    ERROR_INVITER_TOTAL_DAY_LIMIT => '邀请记录邀请人红包超过当天总数限制',
    ERROR_INVITEE_TOTAL_DAY_LIMIT => '邀请记录被邀请人红包超过当天总数限制',
    ERROR_INVITEE_DAY_LIMIT => '邀请记录邀请人红包超过当天限制',
    ERROR_INVITER_NO_REWARD => '邀请记录邀请人无红包',
    ERROR_INVITEE_NO_REWARD => '邀请记录被邀请人无红包',
    ERROR_INVITE_NOT_NEW => '邀请记录不是新用户',
    ERROR_USER_BLOCKED => '已经被对方拉黑',
    ERROR_VR_BIND => '该VR设备已经被绑定',
    ERROR_STUDENT_VERIFIED => "已经论证过",
    ERROR_STUDENT_VERIFING => "正在审核中",
    ERROR_STUDENT_APPLY_FIRST => "请先申请审核",
    ERROR_USER_LOGIN_OTHER_PLACES => "异地登录",
    ERROR_LOGIN_ERROR_NUM => "账户已被锁定，请通过短信验证码登录",
    ERROR_USER_LOGIN_OTHER_DEVICE => "新设备登录",
    ERROR_USER_INVALID_MOBILE => "不是用户绑定的手机",
    ERROR_USER_WEAKPASS => "用户密码比较弱",
    ERROR_USER_FROZEN => "账号已冻结",

    // 任务
    ERROR_BIZ_TASK_NOT_EXIST => "任务不存在",
    ERROR_BIZ_TASK_UNSTART => "任务未到开始时间",
    ERROR_BIZ_TASK_EXPIRE => "任务已过期",
    ERROR_BIZ_TASK_CLOSED => "任务未开启",
    ERROR_BIZ_TASK_OVER_TIMES => "任务次数超限",
    ERROR_BIZ_TASK_PAYMENT => "支付系统出错",
    ERROR_BIZ_TASK_MIN_TIME_LIMIT => "未达到最小时间限制(%s)",
    ERROR_BIZ_TASK_BAD_TICKET => "ticket错误(%s)",
    ERROR_BIZ_TASK_TICKET_USED => "ticket已使用过(%s)",
    ERROR_BIZ_TASK_NOT_IN_ROOM => "用户没有在直播间(%s)",

    ERROR_BIZ_USER_SIGNED => "已经签过到",
    ERROR_USER_NOTBINDED => "%s 未绑定",
    //图片
    ERROR_BIZ_IMAGE_NOT_EXIST => '图片不存在',
    //视频
    ERROR_BIZ_VIDEO_NOT_EXIST => '视频不存在',
    ERROR_VIDEO_SET_COVER => '封面修改失败',
    ERROR_BIZ_VIDEO_NOT_OWNER => '不能操作别人资源',

    // 公会
    ERROR_BIZ_PAYMENT_FAMILY_ISVERIFIED => '用户已经认证',
    ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST => '公会信息不可用',
    ERROR_BIZ_PAYMENT_FAMILY_ISNOTEMPLOYE => '还不是公会成员',
    ERROR_BIZ_PAYMENT_FAMILY_ISEMPLOYE => '用户已经加入公会',
    ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID => '公会成员信息错误',
    ERROR_BIZ_PAYMENT_FAMILY_ISCONTRACT => '公会成员已经签约过',
    ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY => '已经处于申请状态',
    ERROR_BIZ_PAYMENT_FAMILY_HASAPPROVE => '存在待审核信息',
    ERROR_BIZ_PAYMENT_FAMILY_OWNER => '公会长可操作',
    ERROR_BIZ_PAYMENT_FAMILY_NOTOWNER => '公会长不可操作',
    ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT => '超过最大提现比例',
    // 经济系统
    ERROR_BIZ_PAYMENT_WITHDRAW_EXIST => '提现已经处理过',
    ERROR_BIZ_PAYMENT_WITHDRAW_FROZEN => '帐户已冻结',
    ERROR_BIZ_PAYMENT_TICKET_FROZEN => '票已冻结',
    ERROR_BIZ_PAYMENT_WITHDRAW_FAMILY_FROZEN => '帐户被公会冻结',
    ERROR_BIZ_PAYMENT_ACCOUNT_TICKET_LACK => "星票不足",
    ERROR_BIZ_PAYBIND_NOT_EXIST    => "绑定信息不存在",
    ERROR_BIZ_PAYBIND_ACCOUNT_NOT_VALID => "%s提现账户必须是邮箱或手机号",
    ERROR_BIZ_PAYMENT_WITHDRAW_CLOSE => '提现关闭',
    ERROR_BIZ_PAYMENT_DEPOSIT_CLOSE => '充值关闭',
    ERROR_BIZ_PAYMENT_DEPOSIT_FALSE => '充值失败',
    ERROR_BIZ_PAYMENT_DEPOSIT_HANDLING => '充值处理中',
    ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK=>'余额不足',
    ERROR_BIZ_PAYMENT_ACCOUNT_TRANSFER_FAMILY_FALSE => '公会主播不能票换钻',
    ERROR_BIZ_PAYMENT_PRODUCT_ADD_FAIL => '添加失败',
    ERROR_BIZ_PAYMENT_PRODUCT_UPDATE_FAIL=>"更新失败",
    ERROR_BIZ_PAYMENT_TRADE_LIMIT=>'测试号只能对测试号操作',
    ERROR_BIZ_PAYMENT_ACTIVE_LIMIT=>'活动号只能对活动号操作',
    ERROR_BIZ_GIFT_NOT_GUARD=>'非当前主播守护不能使用该礼物',
    ERROR_BIZ_PAYMENT_IS_OPERATION=>'提现账号为运营号',
    ERROR_BIZ_GIFT_HAS_NOT_PRIVATE_SEND_AUTH=>'没有私信收礼权限',

    ERROR_BIZ_BAG_USE_RIDE_FAIL=>'使用座驾失败',
    ERROR_BIZ_BAG_USE_RIDE_OUT_DATE=>'座驾过期',
    ERROR_BIZ_BAG_USE_GIFT_NUM_LESS=>'背包礼物数不足',
    ERROR_BIZ_BAG_USE_GIFT_FAIL=>'使用背包礼物失败',

    //游戏
    ERROR_BIZ_GAME_ANCHOR_GRADE_NOT_ENOUGH  => '主播等级不够',
    ERROR_BIZ_GAME_NOT_EXIST                  => '该游戏不存在',
    ERROR_BIZ_GAME_NOT_DO                     => '该游戏没开启',
    ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST => '跑马游戏抢庄金额不存在',
    ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST => '跑马游戏押注金额不存在',
    ERROR_BIZ_GAME_HORSERACING_AMOUNT_MIN => '跑马游戏抢庄金额小于以前抢庄人',
    ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE => '跑马游戏抢庄失败',
    ERROR_BIZ_GAME_HORSERACING_TRACKNO_NOT_EXIST => '赛道不存在',
    ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL => '该赛道金额已满',
    ERROR_BIZ_GAME_HORSERACING_TRACKNO_FALSE => '跑马游戏押注失败',
    ERROR_BIZ_GAME_FALSE                     => '开启游戏失败',
    ERROR_BIZ_GAME_HORSERACING_BLANKER_TIME_OUT => '抢庄时间过期',
    ERROR_BIZ_GAME_HORSERACING_TRACKNO_TIME_OUT => '押注时间过期',
    ERROR_BIZ_GAME_NOT_CLOSE                => '游戏关闭失败',
    ERROR_BIZ_GAME_ROBOTS_EXIST => '帐号已经存在',
    ERROR_BIZ_GAME_NOT_BANKER   => '押注用户不能是庄主',
    ERROR_BIZ_STAR_BALANCE_DUE  => '星光不足',
    ERROR_BIZ_LOTTO_GAME_LESS_TIME=>'时间不足三分钟',

    // html5
    ERROR_BIZ_PAYMENT_WITHDRAW_TOKEN => '认证信息错误',
    ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_TOTAL_OUT => '今日提现金额不能大于3000',
    ERROR_BIZ_PAYMENT_WITHDRAW_AMOUNT_SMALL => '提现金额最小50元',
    ERROR_BIZ_PAYMENT_WITHDRAW_CASH_AMOUNT_SMALL => '提现金额最小30元',
    ERROR_BIZ_PAYMENT_WITHDRAW_NO_POWER => '没有权限',
    ERROR_BIZ_PAYMENT_WITHDRAW_APPLY => '当前有主播正在提现审核中，不能转换工会类型',

    ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE=>'星票余额不足',
    ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE=>'钻石余额不足',
    ERROR_BIZ_PAYMENT_VIRTUAL_BALANCE_DUE=>'虚拟币余额不足',
    ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE=>'现金不足',
    ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE=>'星星余额不足%s',
    ERROR_BIZ_PAYMENT_STAR_DIAMOND_LIMIT=>'今日星钻兑换数量已用完(100)，明天再来吧',

    ERROR_BIZ_PAYMENT_TICKET_OUT_ACCOUNTED=>'星票出账错误',
    ERROR_BIZ_PAYMENT_TICKET_ACCOUNTED_FOR=>'星票入账错误',
    ERROR_BIZ_PAYMENT_DIAMOND_OUT_ACCOUNTED=>'钻石出账错误',
    ERROR_BIZ_PAYMENT_STAR_OUT_ACCOUNTED=>'星星出账错误',
    ERROR_BIZ_PAYMENT_DIAMOND_ACCOUNTED_FOR=>'钻石入账错误',
    ERROR_BIZ_PAYMENT_VIRTUAL_OUT_ACCOUNTED=>'虚拟币出账错误',
    ERROR_BIZ_PAYMENT_VIRTUAL_ACCOUNTED_FOR=>'虚拟币入账错误',
    ERROR_BIZ_GIFT_NOT_FIND                =>'礼物找不到',
    ERROR_BIZ_GIFT_NOT_SEND_SELF        =>'礼物不能发自己',
    ERROR_BIZ_PAYMENT_ACCOUNTED_ERROR => "账户变动出错",
    ERROR_BIZ_PAYBIND_MOBILE_CODE_INVAILD => "绑定手机号验证码错误",
    ERROR_BIZ_PAYMENT_DEPOSIT_SOURCE_ERROR => "第三方支付源错误",
    ERROR_BIZ_PAYMENT_PRODUCT_DEFAULT   => "操作失败",
    ERROR_BIZ_PAYMENT_PRODUCT_NOT_FOUND => "商品不存在",
    ERROR_BIZ_PAYMENT_PRODUCT_NOT_ONLINE => "商品已下架",
    ERROR_BIZ_PAYMENT_TRADE_PROCESS_LOCK => "重复交易",
    ERROR_BIZ_PAYMENT_TRADE_ACCOUNT_SYSTEM => "系统账户操作异常",
    ERROR_BIZ_PAYMENT_TRADE_ACCOUNT_USER => "用户账户操作异常",
    ERROR_BIZ_PAYMENT_TRADE_NOT_PAY => "尚未购买",
    ERROR_BIZ_PAYMENT_OUT_DATE => "已过期",
    ERROR_BIZ_PACKET_SEND_ERROR => '红包发送失败',
    ERROR_BIZ_PACKET_SERCEIVE_NOT_ACTIVE => '红包未开启',
    ERROR_BIZ_PACKET_SERCEIVE_EMPTY => '红包已经领完',
    ERROR_BIZ_PACKET_OPEN_ERROR => '红包打开失败',
    ERROR_BIZ_PACKET_OPEN_FINISH => '红包已经领取',
    ERROR_BIZ_PACKET_SEND_AMOUNT_NOT_ENOUGH => '红包金额不足',
    ERROR_BIZ_PACKET_SEND_NOTALLOW => '不允许发送红包',

    // 社团
    ERROR_BIZ_CLUB_NAME_EXIST => '该社团名已经被别人捷足先登了 再想一想吧~',
    ERROR_BIZ_CLUB_SHORTNAME_EXIST => '该社团勋章名已经被别人捷足先登了再想一想吧~',
    ERROR_BIZ_CLUB_MEMBER_EXIST => '已经在社团中',
    ERROR_BIZ_CLUB_SETUP_VIP => '用户等级不满足',

    //直播
    ERROR_BIZ_LIVE_NOT_EXIST  =>"直播不存在",
    ERROR_BIZ_LIVE_NOT_ACTIVE => "不在直播状态",
    ERROR_BIZ_LIVE_NOT_PAUSED => "不在暂停状态",
    ERROR_BIZ_LIVE_NOT_OWNER => "不是自已操作",
    ERROR_BIZ_LIVE_SN_NOT_EMPTY=> "参数%s不能为空",
    ERROR_BIZ_LIVE_PRTNER_NOT_EMPTY=> "参数%s不能为空",
    //ERROR_BIZ_LIVE_NOT_ONLY => "账号正在开播，请下播后再开播",
    ERROR_LIVE_IS_OVER    => "直播已结束",
    ERROR_BIZ_PRIVACY_LIVE_NOT_EXIST => "私密直播不存在",
    ERROR_BIZ_PRIVACY_LIVE_NOT_YOURSELF=>"不是你自己的私密直播，无权修改",
    ERROR_LIVE_SET_COVER=>"封面修改失败",
    ERROR_BIZ_PRIVACY_CHANGE_FAILE => "修改失败",
    ERROR_BIZ_ERROR_HAS_BUYED_PRIVACY_LIVE => "你已经购买过该门票",
    ERROR_BIZ_PRIVACY_LIVE_HAS_PREVIEWED => "你已经预览过",
    ERROR_BIZ_PRIVACY_LIVE_HAS_EXIST => "付费直播时间重复",
    ERROR_BIZ_PRIVACY_PERMISSION => "没有私密直播权限",
    ERROR_BIZ_PRIVACY_DELAYTIME => "私密直播已结束",
    ERROR_BIZ_LIVE_SN_ERROR => "SN错误",

    // 计数器
    ERROR_BIZ_COUNTER_BUSY_RETRY => '计数系统繁忙，请重试',
    ERROR_BIZ_COUNTER_IS_NEGATIVE => '结果为负数',

    // 评论
    ERROR_BIZ_REPLY_NOT_EXISTS => '评论回复不存在',
    ERROR_BIZ_REPLY_NOT_SELF => '不是评论本人',
    ERROR_BIZ_NOTICE_ONLY_THREE => '通知每天只能使用三次',
    ERROR_BIZ_NOTICE_ONLY_LIVE_ONE => '同一直播间只能发一次',

    //聊天室
    ERROR_BIZ_CHATROOM_USER_HAS_SILENCED => "房间用户在之前已被禁言",
    ERROR_BIZ_CHATROOM_HAS_BLOCKED    => '你被对方拉黑',
    ERROR_BIZ_CHATROOM_HAS_FORBIDEEN => '你的账号被封禁',
    ERROR_BIZ_CHATROOM_PATROLLER_LIMIT => '场控最多添加三个',
    ERROR_BIZ_CHATROOM_NO_AUTHORITY => '没有场控权限',
    ERROR_BIZ_CHATROOM_PATROLLER_EXSIT => "场控已经存在",
    ERROR_BIZ_CHATROOM_PATROLLER_CANNOT_SELF => "不能添加自己为场控",
    ERROR_BIZ_CHATROOM_KICK_CANNOT_SELF => "自己不能踢自己",
    ERROR_BIZ_CHATROOM_SILENCE_CANNOT_SELF => "不能禁言主播",
    ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER => "您的权限不足，无法操作",
    ERROR_BIZ_CHATROOM_HAS_KICKED => '已经踢过了',
    ERROR_BIZ_CHATROOM_ONLY_OPEN_ONE => "每个账号只能同时开播一个聊天室",
    ERROR_BIZ_CHATROOM_VIP_GUARD => '没有操作权限/(ㄒoㄒ)/~~',
    ERROR_BIZ_CHATROOM_VIP_KICK_NUM_LT_ONE => '今天的踢人次数用完啦~',
    ERROR_BIZ_CHATROOM_VIP_SILENCE_NUM_LT_ONE => '今天的禁言次数用完啦~',
    ERROR_BIZ_CHATROOM_NO_PRIVACY_RIGHTS => '没有私密权限，请联系官方',

    // 系统
    ERROR_SYS_DB_SQL => "系统有问题，请稍后重试",
    ERROR_SYS_REDIS => "REDIS错误",
    ERROR_SYS_CONTEXT_KEY_EXISTS => "Context变量已定义:%s",
    ERROR_SYS_CONTEXT_KEY_NOT_EXISTS => "Context变量未定义:%s",
    ERROR_SYS_NEEDPOST => "需要使用POST方法",
    ERROR_SYS_UNKNOWN => "操作失败",
    ERROR_SYS_DB_UID => "UID生成异常",

    //oss
    ERROR_OSS_CONNECT_FAILD=>"OSS连接失败:%s",
    ERROR_OSS_PUT_FAILD=>"上传失败:%s",
    ERROR_OSS_GET_FAILD=>"下载失败:%s",
    ERROR_OSS_DELETE_FAILD=>"删除失败:%s",
    ERROR_OSS_UNSUPPORTED_TYPE=>"不支持的格式",

    //守护
    ERROR_GUARD_NOT_EXIST=>"守护不存在",
    ERROR_GUARD_TYPE_MONTH=>"您已经购买了年守护未到期，不允许购买月守护",
    ERROR_BUY_GUARD=>"购买守护出错，请重试",
    ERROR_GUARD_DIAMONDS=>"星钻出错，请重试",
    ERROR_GUARD_NOT_SELF=>"不能守护自己哦~",

    //私密直播
    ERROR_BUY_PRIVACY_ROOM => "购买私密房间出错,请重试",
    ERROR_BUY_PRIVACY_ROOM_NOORDERID=> "购买私密直播订单为空,请稍后重试",
    ERROR_BUY_PRIVACY_ROOM_ADDLOG=> "添加购买日志出错,请稍后重试",
    ERROR_DELAY_PRIVACY_ROOM => "延长时间失败,请稍后重试",
    ERROR_MODIFY_PRICE_PRIVACY_ROOM => "修改价格失败，请稍候重试",

    //pk项目
    ERROR_MATCH_USER_IS_PRISON          => "您正在冷宫中",
    ERROR_MATCH_INVITEE_IS_PRISON       => "受邀用户在冷宫中",
    ERROR_MATCH_DURATION_NOT_EXIST      => "时长不存在",
    ERROR_MATCH_USER_IS_PK               => "您正在PK中或等待PK",
    ERROR_MATCH_INVITEE_IS_PK            => "受邀用户正在PK",
    ERROR_MATCH_USER_LIVE_NOT_ACTIVE    => "主播没开播",
    ERROR_MATCH_INVITEE_LIVE_NOT_ACTIVE    => "受邀者没开播",
    ERROR_MATCH_NOT_EXIST                => "PK场次不存在",
    ERROR_MATCH_ACCEPT_FALSE             => "PK接受失败",
    ERROR_MATCH_APPLY_FALSE             => "PK发起失败",
    ERROR_MATCH_ACCEPT_EXIST            => "该场PK已被接受或流局",
    ERROR_MATCH_IS_FLOW                  => "该次PK流局",
    ERROR_MATCH_ADD_PRISON_FALSE        => "添加冷宫失败",
    ERROR_MATCH_DEL_PRISON_FALSE        => "移除冷宫失败",
    ERROR_MATCH_INVITEE_NOT_MY          => "受邀用户不能是发起用户",
    ERROR_MATCH_NOT_ACCEPT              => "您不是受邀用户",
    ERROR_MATCH_USER_LIVE               => "用户正在pk,不可加入冷宫",
    ERROR_MATCH_INVITER_IS_PK          => "发起者正在PK",
    ERROR_MATCH_INVITER_TICKET_FALSE   => "发起者收入小于2000星票，还不能使用pk",
    ERROR_MATCH_INVITEE_TICKET_FALSE   => "受邀者或接受者收入小于2000星票，不能pk",

    //连麦
    ERROR_LINK_REPEAT_APPLY             => "重复申请",
    ERROR_LINK_ANCHOR_PERMISSIONS       => "主播没有连麦权限",
    ERROR_LINK_USER_PERMISSIONS         => "用户没有连麦权限",
    ERROR_LINK_APPLY_NOT_IN_LIVEROOM    => "用户不在直播间",


    //冲顶
    ERROR_SUMMIT_CODE_EXIST             => "您已使用过该邀请码",
    ERROR_SUMMIT_CODE_NOT_EXIST         => "无效邀请码",
    ERROR_SUMMIT_USER_IS_EXIST          => "自己不可以激活自己的邀请码",
    ERROR_SUMMIT_USER_IS_FALSE          => "你已经是老用户了",
    ERROR_SUMMIT_USER_IS_DIE_OUT        => "你已经被淘汰了",
    ERROR_SUMMIT_USER_IS_UNSTART        => "活动还未开启",
    ERROR_SUMMIT_USER_IS_ENDED            => "活动已结束",
    ERROR_SUMMIT_USER_QUESTION_NOT_SEND => "问题还未下发",
    ERROR_SUMMIT_USER_QUESTION_IS_END   => "答题时间已结束",
    ERROR_SUMMIT_USER_QUESTION_NOT_START=> "答题时间未开始",
    ERROR_SUMMIT_USER_QUESTION_ONLY_ONE_TIME => "一场比赛一道题每人只能答一次",
    ERROR_SUMMIT_REVIVAL_IS_EXIST       => "已领过复活卡",

    ERROR_SUMMIT_IS_DRAW                => '已经领取过奖励',

    //新版跑马游戏
    ERROR_SUMMIT_HANDBOOKINGER_TRACKNO_IS_NOT_EXIST => "押注马匹号不存在",
    ERROR_SUMMIT_HANDBOOKINGER_STAKE_TIME_OUT => "押注时间过期",
    ERROR_SUMMIT_HANDBOOKINGER_STAKE_AMOUNT_NOT_EXIST => "押注金额不存在",
    ERROR_SUMMIT_HANDBOOKINGER_DIAMOND_BALANCE_DUE  => "账户余额不足，赶快充值",

    //场控
    ERROR_PATROLLER_ADD => "添加场控失败",
    ERROR_PATROLLER_DEL => "删除场控失败",
    //custom
    ERROR_CUSTOM=>"%s",

    //回源鉴权
    AUTHORITY_WS_TIME_ERROR   => "超时",
    AUTHORITY_WS_SECRET_ERROR => "Secret错误",
    AUTHORITY_WS_LIVE_ERROR   => "非正常直播"

);

$LOG_PATH = "/usr/local/nginx/logs/";
$LOG = array(
    'level' => 0x07, // fatal, warning, notice
    'logfile' => $LOG_PATH . "api.dreamlive.tv.log", // test.log.wf will be the wf log file
    'split' => 0, // 0 not split, 1 split by day, 2 split by hour
    'others' => array(
        'oauth' => $LOG_PATH . "oauth.log",
        'sms' => $LOG_PATH . "sms.log",
        'call' => $LOG_PATH . "call.log",
        'payment' => $LOG_PATH . "payment.log",
        "payment_err" => $LOG_PATH . "payment.log",
        "redis_err" => $LOG_PATH . "redis.log",
        "token" => $LOG_PATH . "token.log",
        "session_err" => $LOG_PATH . "session.log",
        'forbidden_err' => $LOG_PATH . "forbidden_err.log",
        "blocked_err" => $LOG_PATH . "blocked.log",
        "bindvr_err" => $LOG_PATH . "bindvr.log",
        "task" => $LOG_PATH . "task.log",
        "task_err" => $LOG_PATH . "task_err.log",
        'task_exp'=> $LOG_PATH ."task_exp.log",
        "search" => $LOG_PATH . "search.log",
        "antispam" => $LOG_PATH . "antispam.log",
        "inchatroom" => $LOG_PATH . "inchatroom.log",
        "forbidden_log_err" => $LOG_PATH . "forbidden_log.log",
        "uid_worker_err" => $LOG_PATH . "uid_worker.log",
        "user_persist_get_error" => $LOG_PATH . "user_persist_get_error.log",
        "live_start_distribute" => $LOG_PATH . "live_start_distribute.log",
        "live_start_broadcast_room" => $LOG_PATH . "live_start_broadcast_room.log",
        "live_start_push_followers"    => $LOG_PATH . "live_start_push_followers.log",
        "quit_chat_room_worker"    => $LOG_PATH . "quit_chat_room_worker.log",
        "auto_praise"    => $LOG_PATH . "auto_praise.log",
        "distribute_praise" => $LOG_PATH . "distribute_praise.log",
        "storage_connect_err" => $LOG_PATH .  "storage_connect_err.log",
        "gettoken"    => $LOG_PATH . "gettoken.log",
        "getliveinfo"    => $LOG_PATH . "getliveinfo.log",
        "merge_olduser"    => $LOG_PATH . "merge_olduser.log",
        "chat_send"    => $LOG_PATH . "chat_send.log",
        "kafka"    => $LOG_PATH . "kafka.log",
        "kafka_join"    => $LOG_PATH . "kafka_join.log",
        "admin_push"    => $LOG_PATH . "admin_push.log",
        "kafka_empty"    => $LOG_PATH . "kafka_empty.log",
        "kafka_quit"    => $LOG_PATH . "kafka_quit.log",
        "counter_syc_db"    => $LOG_PATH . "countersycdb.log",
        "incr_exp"    => $LOG_PATH . "incr_exp.log",
        "msg"    => $LOG_PATH . "msg.log",
        "msg_err"=>$LOG_PATH."msg_err.log",
        "redis_error"=>$LOG_PATH."redis_error.log",
        "rongcloud_gettoken_error"=>$LOG_PATH."rongcloud_gettoken_error.log",
        "private_msg_err"=>$LOG_PATH."private_msg_err.log",
        "private_msg_err_bignotice"=>$LOG_PATH."private_msg_err_bignotice.log",
        "live_receive"    => $LOG_PATH . "live_receive.log",
        "live_receive1"    => $LOG_PATH . "live_receive1.log",
        "account_task_work" => $LOG_PATH . "account_task_work.log",
        "task_message"    => $LOG_PATH . "task_message.log",
        "online_error"    => $LOG_PATH . "online_error.log",
        "banker_message" => $LOG_PATH."banker_message.log",
        "followings_add_newsfeeds" => $LOG_PATH."followings_add_newsfeeds.log",
        'user_login_speed'=> $LOG_PATH ."user_login_speed.log",
        'privacy_error'=> $LOG_PATH ."privacy_error.log",
        'privacy_buy'=> $LOG_PATH ."privacy_buy.log",
        'private_send_log'=> $LOG_PATH ."private_send_log.log",
        'lotto_prize_log'=> $LOG_PATH ."lotto_prize.log",
        'audience_log'=> $LOG_PATH ."audience_log.log",
        'process_error'=> $LOG_PATH ."process_error.log",
        'rank_log'=> $LOG_PATH ."rank_log.log",
        'keyword_forbidden_log'=> $LOG_PATH ."keyword_forbidden_log.log",
        'answer_log'=> $LOG_PATH ."answer_log.log",
        'answer_sendcash_log'=> $LOG_PATH ."answer_sendcash_log.log",
        'filter_nickname_log' => $LOG_PATH ."filter_nickname_log.log",
        'pk_match_log'=> $LOG_PATH ."pk_match_log.log",
        'pk_match_worker_log'=> $LOG_PATH ."pk_match_worker_log.log",
        'login_bonus_log'=> $LOG_PATH ."login_bonus_log.log",
        'free_revival_card_log'=> $LOG_PATH ."free_revival_card_log.log",
        'send_message_log'=> $LOG_PATH ."send_message_log.log",
        'follow_notice_log'=> $LOG_PATH ."follow_notice_log.log",
        'account_task_work_ride_gift'=> $LOG_PATH ."account_task_work_ride_gift.log",
        "live_receive_audience_online" => $LOG_PATH ."live_receive_audience_online.log",
        "live_receive_audience_online_delete" => $LOG_PATH ."live_receive_audience_online_delete.log",
          'account_task_work_ride_gift'=> $LOG_PATH ."account_task_work_ride_gift.log",

        //测试oss用
        'storage_connect_err' => $LOG_PATH . "storage_connect_err.log",
        'storage_set_err' => $LOG_PATH . "storage_set_err.log",
        'storage_get_err' => $LOG_PATH . "storage_get_err.log",
        'storage_task_err'=> $LOG_PATH . "storage_task_err.log",

        'vip_send_ride'=> $LOG_PATH . "vip_sendride_err.log",
        "vip_level_month" => $LOG_PATH . "vip_level_month.log",

        "user_guard_activity_add_worker" => $LOG_PATH . "user_guard_activity_add_worker.log",
        "passport_user_login_position" => $LOG_PATH . "passport_user_login_position.log",
        "passport_user_login_position_err" => $LOG_PATH . "passport_user_login_position.log.wf",
        "alisms" => $LOG_PATH . "sms_alisms.log",
        "recall_err" => $LOG_PATH . "recall_err.log",

        //小程序通知
        "live_start_wx_notice" => $LOG_PATH . "live_start_wx_notice.log",
        "live_start_wx_notice_distribute" => $LOG_PATH . "live_start_wx_notice_distribute.log",
        "live_start_wx_notice_push"    => $LOG_PATH . "live_start_wx_notice_push.log",
    )
);

define("RULE_DIRTY_WORDS", "\r|\n|操逼|日逼|肛交|阴道|裸聊|撸管|鸡奸|口交|逼毛|黄片|毛片|阴茎|阴户|性交|强奸|草B|操B|轮奸|约炮|打炮|一夜情|法轮|福利|吞精|做爱|爱爱|鸡巴|鸡八|逼逼|舔逼|奶子|操你妈|聊性|被插|肏|屄|骚妇|炮友|骚逼|文爱|绳技|sm|SM|求包养|色聊|聊色|聊骚|干美女|一柱擎天|晨勃|网站|网址|黄网|骚b|色女|傻逼|大咪咪|白粉|枪支弹药|口jiao|操你|欠操|A片|3p|3P|图爱|文爱|色姐姐|欲女|床上功夫|口活儿|收奴|互图|母畜|六四|女奴|性奴隶|阴毛|搞基|车震|熟妇|人妻|援交|阴蒂|64学潮|89学潮|Islam|Mino|一中一台|丁关根|专制|东北独立|东土耳其斯坦|东方红时空|东方闪电|东突|东突厥斯坦|东突厥斯坦伊斯兰|东西南北论坛|两个中国|两岸三地论坛|中华人民正邪|中华养生益智功|中央政治局委员|中央政治局常务委员会委员|乔清晨|习近平|争鸣论坛|事实独立|人权|令计划|伊斯兰运动|传播谣言者|传谣|何德普|余英时|佛展千手法|保钓|俞正声|倪育贤|傅全有于永波|傅志寰|傅申奇|僵贼民|党中央|全能神|八九民运|六合彩|六四学潮|六四民主运动|六码|共党|共军|共匪|共狗|关卓中|凌锋|分裂|刘云山|刘奇葆|刘延东|刘淇|功友|功法|动乱|劳动教养所|北京之春|北京当局|北大三角地论坛|北美自由论坛|北美论坛|华夏文摘|华岳时事论坛|华语世界论坛|华通时事论坛|吴仪|吴官正|吴胜利|吴邦国|周永康|回良玉|国家安全|国家机密|国研新闻邮件|国贼|圣战思想|地下刊物|地下教会|境外分离势力|夜话紫禁城|大中华论坛|大中国论坛|大众真人真事|大参考|大史记|大家论坛|大红龙|大纪园|天安门一代|天安门事件|天安门屠杀|天安门录影带|姜春云|孙政才|孙春兰|孟建柱|宗教破坏|官方通知|实际神|尉健行|常万全|廖锡龙|建国党|异议人士|张万年|张又侠|张定发|张德江|张春贤|张立昌|张高丽|徐才厚|性交|性功能|性服务|恐怖活动|房峰辉|护法|政变|政治反对派|政治局常委|新疆和田县罕尔日克镇|新疆暴力|新疆独|新疆鄯善县|暴乱|暴力|暴徒|暴政|曹刚川|曾培炎|曾庆红|朱?基|李克强|李岚清|李建国|李洪志|李源潮|李瑞环|李继耐|李铁映|李长春|李鹏|杜青林|杨晶|枪支|枪枝|栗战书|梁光烈|民族分裂|民族矛盾|民猪|民运|民进党|民阵|江泽民|汪洋|法轮功|温家宝|游行|热比娅|热站政论网|独立台湾会|王乐泉|王兆国|王克|王岐山|王沪宁|王瑞林|田纪云|真善忍|积克馆|突厥斯坦|统独|罗干|胡春华|胡耀邦|胡锦涛|自民党|自焚|自由民主论坛|自由运动|舆论反制|范长龙|薄熙来|藏独|街铺|被捕|西藏独|西藏独立|许其亮|谢非|贺国强|贾庆林|赵乐际|赵克石|赵洪祝|赵紫阳|迟浩田|邓小平|邪教|郭伯雄|郭金龙|钱其琛|陈炳德|陈良宇|靖志远|韩正|马凯|马晓天|魏凤和|黄菊|换妻|习大大|国家");

define("RULE_PROTECT_WORDS", "巡查|手机用户|官方|管理|admin");

define("AUTH_CHECK_LOGIN",   1); //登录用户可用
define("AUTH_CHECK_POST",    2); //POST方式提交
define("AUTH_CHECK_FORBID",  3); //校验用户封禁

$API_AUTH_CONFIG = array(
    "/user/active" => array(),
    "/user/getCode" => array(),
    "/user/getEmailCode" => array(),
    "/user/fastLogin" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/user/getUserInfo" => array(AUTH_CHECK_FORBID),
    "/user/getMyUserInfo" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/user/register" => array(),
    "/user/registerEmail" => array(),
    "/user/login" => array(),
    "/user/resetPassword" => array(),
    "/user/setPassword" => array(AUTH_CHECK_LOGIN),
    "/user/bind" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/user/unbind" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/user/getBinds" => array(AUTH_CHECK_LOGIN),
    "/user/changeMobile" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/user/getVerifiedInfo" => array(AUTH_CHECK_LOGIN),
    "/user/checkNickname" => array(AUTH_CHECK_LOGIN),
    "/user/search" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/user/expRanking" => array(AUTH_CHECK_LOGIN),
    "/user/getUserAvatar" => array(),
    "/user/getOldUserInfo" => array(AUTH_CHECK_LOGIN),
    "/user/getMergeOldUserStatus" => array(AUTH_CHECK_LOGIN),
    "/user/mergeOldUser" => array(AUTH_CHECK_LOGIN),
    "/user/getVipInfo" => array(AUTH_CHECK_LOGIN),
    "/verified/getVerifiedInfo" => array(AUTH_CHECK_LOGIN),
    "/verified/modifyVerifiedInfo" => array(AUTH_CHECK_LOGIN),
    "/verified/withdrawal" => array(AUTH_CHECK_LOGIN),
    "/verified/getWithdrawalVerifiedInfo" => array(AUTH_CHECK_LOGIN),
    "/verified/modifyWithdrawal" => array(AUTH_CHECK_LOGIN),
    "/blocked/getBlocked" => array(AUTH_CHECK_LOGIN),
    "/blocked/add" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/blocked/cancel" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/blocked/isblocked" => array(AUTH_CHECK_LOGIN),
    "/blocked/getbids" => array(AUTH_CHECK_LOGIN),
    "/follow/getFollowings" => array(AUTH_CHECK_LOGIN),
    "/follow/getFollowers" => array(AUTH_CHECK_LOGIN),
    "/follow/add" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/follow/multiAdd" => array(AUTH_CHECK_LOGIN, AUTH_CHECK_FORBID),
    "/follow/cancel" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/follow/getFriends" => array(AUTH_CHECK_LOGIN),
    "/follow/isFriend" => array(AUTH_CHECK_LOGIN),
    "/follow/isFollowed" => array(AUTH_CHECK_LOGIN),
    "/follow/getUserFollowings" => array(AUTH_CHECK_LOGIN),
    "/follow/getUserFollowers" => array(AUTH_CHECK_LOGIN),
    "/follow/setOptionNotice" => array(AUTH_CHECK_LOGIN),
    "/profile/sync" => array(AUTH_CHECK_LOGIN),
    "/profile/getProfile" => array(AUTH_CHECK_LOGIN),
    "/forbidden/forbidden" => array(),
    "/forbidden/unforbidden" => array(),
    "/forbidden/isforbidden" => array(),
    "/forbidden/isforbiddenUsers" => array(),
    "/report/add" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/task/execute" => array(AUTH_CHECK_LOGIN),
    "/live/prepare" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/account/getAccountInfo" => array(AUTH_CHECK_LOGIN),
    "/account/transfer" => array(AUTH_CHECK_LOGIN),
    "/account/exchange" => array(AUTH_CHECK_LOGIN),
    "/account/search" => array(AUTH_CHECK_LOGIN),
    "/task/execute"  => array(AUTH_CHECK_LOGIN),
    "/live/start"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/stop"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/pause"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/resume"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/delete"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/praise"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/trace"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/setCover"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/live/changeSN"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/gift/send"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/message/getToken"  => array(AUTH_CHECK_LOGIN),
    "/message/send"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/status" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/myFamily" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/search" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/applyEmploye" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/acceptEmploye" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/rejectEmploye" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/applyList" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/employeList" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/employeInfo" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/applySearch" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/family/familyInfo" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/prop/send" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/horn/send" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/account/getAmountIncome" =>  array(AUTH_CHECK_LOGIN),
    "/account/getJournalList" =>  array(AUTH_CHECK_LOGIN),
    "/account/activeReg" => array(AUTH_CHECK_LOGIN),
    "/account/activeVote" => array(AUTH_CHECK_LOGIN),
    "/account/activeBallot" => array(AUTH_CHECK_LOGIN),
    "/account/diamondTransferBaiyingGold" => array(),
    "/withdraw/getWithdrawList" =>  array(AUTH_CHECK_LOGIN),
    "/withdraw/getWithdrawPrice" =>  array(AUTH_CHECK_LOGIN),
    "/withdraw/apply" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/paybind/add" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/paybind/checkCode" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/send" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/getAudience"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/addSilence"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/delSilence"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/kick" =>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/getPatrollers"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/addPatroller"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/delPatroller"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/isPatroller"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/isSilence"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/chat/isKicked"=>  array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/feed/getNewsFeeds"=>  array(AUTH_CHECK_LOGIN),
//"/feed/getUserFeeds"=> array(AUTH_CHECK_LOGIN),
    "/image/add"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/video/add"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/video/setCover"  => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/reply/add"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/reply/delete"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/reply/report"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/rank/getRanking"=> array(AUTH_CHECK_LOGIN),
    "/rank/getRankingElement"=> array(AUTH_CHECK_LOGIN),
    "/feedback/add"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/user/getPwdStatus"=> array(AUTH_CHECK_LOGIN),
    "/user/changePassword"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/client/reportLog"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),

    "/live/deleteReplay"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/video/praise"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/image/praise"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/image/delete"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/video/delete"=> array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/feed/getNewsFeedsList"=> array(AUTH_CHECK_LOGIN),
//"/feed/getUserFeeds" => array(AUTH_CHECK_LOGIN),
    "/live/replayNoTitle" => array(AUTH_CHECK_LOGIN),
    "/task/getSignList" => array(AUTH_CHECK_LOGIN),
    "/task/signTask" => array(AUTH_CHECK_LOGIN),
    "/task/getTaskList" => array(AUTH_CHECK_LOGIN),
    "/task/levelTask" => array(AUTH_CHECK_LOGIN),
    "/task/execute" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),

    "/verified/withdrawal" => array(AUTH_CHECK_LOGIN),
    "/verified/getWithdrawalVerifiedInfo" => array(AUTH_CHECK_LOGIN),
//"/verified/modifyWithdrawalStatus" => array(AUTH_CHECK_LOGIN),

    "/product/getProductList" => array(AUTH_CHECK_LOGIN),
    "/product/getPurchasedList" => array(AUTH_CHECK_LOGIN),
    "/product/buy" => array(AUTH_CHECK_POST, AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/product/use" => array(AUTH_CHECK_POST, AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/bag/getBagList"=>array(AUTH_CHECK_LOGIN),
    "/bag/useRide"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/bag/useGift"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/bag/getHornNum"=>array(AUTH_CHECK_LOGIN,),
    "/packet/send" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/packet/receive" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/packet/open" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/packet/getReceiveList" => array(AUTH_CHECK_LOGIN),
    "/packet/getPacket" => array(AUTH_CHECK_LOGIN),
    //游戏
    "/game/getGameList" => array(AUTH_CHECK_LOGIN),
    "/game/setGame" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/game/getGameInfo" => array(AUTH_CHECK_LOGIN),
    "/game/getGameState" => array(AUTH_CHECK_LOGIN),
    "/horseracing/banker" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/horseracing/stake" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/handbookinger/stake" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/horseracing/getGameResult" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/horseracing/starBanker" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/horseracing/starStake" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/horseracing/getStarGameResult" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/horseracing/getRankingInfo"=>array(AUTH_CHECK_LOGIN),
    "/account/starTransfer"=>array(),
    "/lotto/getPrize"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/lotto/getLottoLog"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/game/getLottoState"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //私密直播
    "/privacy/getConfig" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/privacy/start" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/privacy/delay" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/privacy/modifyPrice" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/privacy/preview" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/privacy/buy" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/online/getUsers" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //活动
    "/activity/register" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/activity/getActivityList" => array(AUTH_CHECK_LOGIN),
    "/activity/lotto" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/activity/vote" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/online/getUsers" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/king/getlist"=>array(AUTH_CHECK_LOGIN),
    //ktv
    "/music/addFavorite"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/music/delFavorite"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/music/getFavorites"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/music/checkFavorite"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/music/report"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),

    //pk
    "/match/apply"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/match/accept"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/match/getMatchList"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/match/getMyMatchList"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/match/rank"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/match/getMatchInfo"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),

    //星星兑换星钻
    "/account/starTransferDiamond"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/account/starDiamondList"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/account/iconDiamondList"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/account/exchange"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/account/shareDiamond"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //冲顶大会
    "/activityMummit/applyInviteCode"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/activityMummit/useInviteCode"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/activityMummit/getRank"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/activityMummit/getMatchInfo"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/activityMummit/freeRevivalCard"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/act/actChristmas"=>array(),
    //答题
    "/answer/submit"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //主播时长统计
    "/rank/liveTimeRank"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //老用户找回
    "/act/getLoginUserInfo"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //"/act/addUserRelation"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/act/recallAct"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //红蓝pk赛
    "/act/rbRegister"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //"/act/rbRank"=>array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    // 社团
    "/club/setUp" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //星钻兑换游戏币
    "/gameExchange/prepare" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/gameExchange/complete" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/gameExchange/verify" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/gameExchange/list" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    "/user/getGameUserInfo" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
    //新版跑马
    "/Handbookinger/stake" => array(AUTH_CHECK_LOGIN,AUTH_CHECK_FORBID),
);

// 超级热门主播 单直播间人数过5万
$SUPER_POPULAR_USERS = array(10005);

$API_ACCESS_CONFIG = array(
    "client"=>array(
        "token"=>"487670661e4cec46aba3759a03dcf323"
    ),
    "miniapp"=>array(
        "token"=>"c86f83bca350e8cb13b7b098d27a7f1e",
    ),
    "server"=>array(
        'token' => 'b95669d3840fa9c7358a8156d9743789',
        'hosts' => array(
            '192.168.1',
            '192.168.2',
            '127.0.0.1',
            '172.16.1',
            '172.16.0',
            '172.16.2',
            '172.16.3',
            '10.10.10',
            '114.240.13.141',
            '114.240.13.141',
            '60.205.204.142',
            '59.110.164.62',
            '59.110.166.3',
            '59.110.157.158',
            '59.110.163.38',
            '123.56.219.123',
            '60.202.205.60',
            '60.205.222.247',
            '59.110.162.162',
            '123.56.16.250',
            '101.200.40.230',
            '101.200.44.199',
            '60.205.184.119',
            '59.110.158.113',
            '123.56.8.112',
            '60.205.231.229',
            '123.56.23.44',
            '123.56.1.242',
            '101.200.34.237',
            '101.200.34.81',
            '111.196.216.23',
            '111.196.216.23',
            '220.194.45.226',//公司 39.155.172.30
            '101.201.114.214',//阿里云
            '119.80.181.174',//公司
            '123.126.111.69 ',//公司
            '123.56.17.237',
            '59.110.161.10',
            '101.200.35.116',
            '101.201.237.196',
            '60.205.124.250',
            '60.205.146.82',
            '101.201.210.246',
            '60.205.124.250',
            '123.56.204.56',
            '123.56.204.137',
            '101.201.75.226',
            '60.205.136.198',
            '10.10.10.174',
            '43.227.254.18',
            '101.201.209.184'//线上经验同步
        )
    )
);

$SNOW_FLAKE_ENABLE=false;
$SNOW_FLAKE_WORK_ID=1000;

//?主域名
define('MAIN_DOMAIN_NAME', 'yijianjiaoyou.com');
define('STATIC_DOMAIN_NAME', 'http://static.yijianjiaoyou.com');

$STATIC_DOMAIN = "http://static.".MAIN_DOMAIN_NAME;
$API_DOMAIN="http://api.".MAIN_DOMAIN_NAME;
$HTML5_DOMAIN="http://html5.".MAIN_DOMAIN_NAME;


define('APP_NAME_DEFAULT', 'dreamLive');
define('APP_NAME_DREAMING', 'zhuiMeng');
define('APP_NAME_GOLDENHORSE', 'starTv');
define('APP_NAME_MINIAPP', 'miniapp');
?>
