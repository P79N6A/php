<?php

//多语言只支持两种
$SYSTERM_COMMON_LANGUAGE = array(
        "task_message"    => array(
            'china'    =>  array(
                "title" => array(
                        //系统任务
                        1    => "发礼物",
                        6   => "飞屏",
        
                        //阶梯任务
                        2   => "看播",
                        3   => "开播",
                        4   => "评论",
                        5   => "分享",
                        7   => "签到",
                        8   => "达到%d等级",
                            
                        //一次性任务
                        9   => "完善个人资料",
                        10  => "绑定电话",
                        11  => "个人认证",
                        12  => "学生认证",
                        13  => "首次充值",
                        14  => "关注%d个主播",
                        15  => "观看直播%d分钟",
                            
                        //日常任务    
                        16   => "每日关注%d个主播",
                        17   => "每日发%d个弹幕",
                        18   => "每日充值%d",
                        19   => "每日观看直播%d分钟",
                        20   => "每日观看%d个直播间",
                ),
                "content" => array(
                        //系统任务
                        1    => "恭喜你!已经完成发礼物任务，您将获得%s。",
                        6   => "恭喜你!已经完成飞屏任务，您将获得%s。",
        
                        //阶梯任务
                        2   => "恭喜你!已经完成观看直播任务，您将获得%s。",
                        3   => "恭喜你!已经完成开播任务，您将获得%s。",
                        4   => "恭喜你!已经完成评论任务，您将获得%s。",
                        5   => "恭喜你!已经完成分享任务，您将获得%s。",
                        7   => "恭喜你!已经完成签到任务，您将获得%s。",
                        8   => "恭喜你!已经完成等级任务，您将获得%s。",
                            
                        //一次性任务
                        9   => "恭喜你!已经完成完善个人资料任务，您将获得%s。",
                        10  => "恭喜你!已经完成绑定电话任务，您将获得%s。",
                        11  => "恭喜你!已经完成个人认证任务，您将获得%s。",
                        12  => "恭喜你!已经完成学生认证任务，您将获得%s。",
                        13  => "恭喜你!已经完成首次充值任务，您将获得%s。",
                        14  => "恭喜你!已经完成关注主播任务，您将获得%s。",
                        15  => "恭喜你!已经完成观看直播分钟数任务，您将获得%s。",
                            
                        //日常任务    
                        16   => "恭喜你!已经完成每天关注%d个主播任务，您将获得%s。",
                        17   => "恭喜你!已经完成每天发送%d个弹幕任务，您将获得%s。",
                        18   => "恭喜你!已经完成每天%d次充值任务，您将获得%s。",
                        19   => "恭喜你!已经完成每天观看%d分钟直播任务，您将获得%s。",
                        20   => "恭喜你!已经完成每天观看%d个直播任务，您将获得%s。",
                        
                        99   => "恭喜你!已经完成%s任务，您将获得%s。",//充值任务
                ),
                "value" => array(
                        "starlight"        => "星光%d",
                        "exp"              => "经验值%d",
                        "diamonds"        => "星钻%d",
                        "medal"            => "%s,%s",
                        "ride"            => "%s,有效期%d天",
                        "gift"            => "%s,数量%d",
                ),
            ),
            'abroad'=> array(
                "title" => array(
                    //系统任务
                    1    => "send gifts",
                    6   => "fly screen",
                
                    //阶梯任务
                    2   => "watching live",
                    3   => "begin to show",
                    4   => "comment",
                    5   => "share",
                    7   => "sign",
                    8   => "reach level %d",
                        
                    //一次性任务
                    9   => "improve personal data",
                    10  => "bind phone",
                    11  => "personal authentication",
                    12  => "student certification",
                    13  => "first recharge",
                    14  => "%d anchors",
                    15  => "watch live %d  minutes",
                        
                    //日常任务
                    16   => "Watch live %d minutes",
                    17   => "daily %d barrage",
                    18   => "daily recharge %d",
                    19   => "watch live %d minutes a day",
                    20   => "watch%d live broadcast every day",
                ),
                "content" => array(
                        //系统任务
                        1    => "Congratulations! %s, which has already completed the send gift assignment.",
                        6   => "Congratulations! %s, which has already completed the fly screen assignment.",
                        
                        //阶梯任务
                        2   => "Congratulations! %s, which has already completed the watching live assignment.",
                        3   => "Congratulations! %s, which has already completed begin to show assignment.",
                        4   => "Congratulations! %s, which has already completed comment assignment.",
                        5   => "Congratulations! %s, which has already completed shareing assignment.",
                        7   => "Congratulations! %s, which has already completed the sign assignment.",
                        8   => "Congratulations! %s, which has already completed the level assignment.",
                            
                        //一次性任务
                        9   => "Congratulations! %s, which has already completed improve personal data assignment.",
                        10  => "Congratulations! %s, which has already completed bind mobile assignment.",
                        11  => "Congratulations! %s, which has already completed Personal authentication assignment.",
                        12  => "Congratulations! %s, which has already completed Student certification assignment.",
                        13  => "Congratulations! %s, which has already completed first deposit assignment.",
                        14  => "Congratulations! %s, which has already completed following anchor assignment.",
                        15  => "Congratulations! %s, which has already completed watching live assignment.",
                            
                        //日常任务
                        16   => "Congratulations! You have completed the task of paying attention to the%d anchor every day, you will get%s.",
                        17   => "Congratulations! You have completed the%d barrage mission every day, you will get%s.",
                        18   => "Congratulations! You have completed the%d recharge every day, you will get%s.",
                        19   => "Congratulations! You have finished watching the%d minute live broadcast every day, you will get%s.",
                        20   => "Congratulations! You have finished watching%d live missions every day, you will get%s.",
                        
                        99   => "Congratulations! You have finished %s task，you will get %s。",//充值任务
                ),
                "value"    => array(
                        "starlight"        => "the%d starlight",
                        "exp"              => "experience value%d",
                        "diamonds"        => "the%d diamond",
                        "medal"            => "%s,%s",
                        "ride"            => "%s,expire %d days",
                        "gift"            => "%s,number %d",
                ),
            ),
        ),
);