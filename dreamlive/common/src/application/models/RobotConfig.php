<?php
class RobotConfig
{
    static private $chats = array(
        
        //加入房间
        'joinRoom'=>array(
            'tuhao'=>array('土豪来了','鼓掌','掌声在哪里','有钱人来了','666[1]','yoyoyo~')
        ),
        
        //送礼物
        'giveGift'=>array(
            //冰淇淋
            '3794'=>array('这冰淇淋好吃','冰淇淋','卧槽'),
            //顶你上热1
            '3823'=>array('顶你个肺','666[1]','好看'),
            //清凉一夏
            '3891'=>array('爽','这空调酷毙了','卧槽'),
            //追梦号
            '3701'=>array('这飞机好看','你看！灰机','卧槽','截图了','截图'),
            //碎屏猫
            '3766'=>array('哇塞，我屏幕碎了','我靠，睡了','好好看哦','好看','截图','我屏碎了','666','我心碎','牛逼，屏碎','[1]','[2]'),
            
            '3845'=>array('这车效果牛B，再来一个','卧槽，法拉利','跑车牛B！','截图了','截图','送礼就送保时捷，哈哈哈'),
            '3846'=>array('这车效果牛B，再来一个','卧槽，法拉克','跑车牛B！','截图了','截图'),
            '3847'=>array('这车效果牛B，再来一个','卧槽，法拉克','跑车牛B！','截图了','截图'),
        ),
        
        //公聊内容
        'publicChat'=>array(
            '1'=>array('111','111','1111','11','1'),
            '2'=>array('22','2222','2222','222222','2'),
            '6'=>array('66','666','6666','666666','6'),
        ),
        
        //家乡
        'getHome'=>array('主播！%s都有神马玩的','%s有啥好吃的？','%s','主播在%s？',
        '主播！%s都有哪些好玩的地方？','主播，%s都有什么特产',
        '主播，%s都有哪些特产','%s有什么好吃的吗？','主播是%s的？',
        '%s有什么好玩的么','%s什么好吃的呀','主播给推荐下%s，好玩的地方','你是%s的吗？',
        '%s老乡好呀','我也是%s人,[5]','%s是个好地方，哈哈'),  
        
        //性别
        'getSex'=>array(
            'F'=>array('主播漂亮啊','今天好漂亮','哇！主播好漂亮','漂亮','好美丽的主播[3]','主播漂亮赞一个','哇塞！好漂亮的主播','美女','美女主播','美女老家是哪里的'),
            'M'=>array('主播好帅','主播晒到爆炸','好帅！','帅！！！','今天好帅呀！','主播帅到炸！','帅哥好！','厉害了word哥')
        ),   

    //学生
        'getStudent'=>array('主播还是学生？','主播在哪里上学','主播学什么专业的？','主播学什么的','主播在哪读书'),
        
        //时间
        'getTime'=>array(
            '8'=>array('早上好！','主播早上好','主播好早呀','早起的主播有前途，哈哈哈','好','早','主播起床了'),
            '9'=>array('早上好！','主播早上好','主播不上班么','你好','好呀','nihao','您好','不上班？'),
            '10'=>array('主播不上班么','主播上午好','主播好','你好','主播好','好呀','吃早饭了吗','吃饭了么','吃饭没'),
            '11'=>array('主播好','主播好呀','好','主播！你好','你好呀','哈喽','hi','hello'),
            '12'=>array('主播吃午饭了么？','主播吃了吗','你好','主播hao','吃没','吃啥，吃啥！','[1]','[2]','[3]'),
            '13'=>array('主播吃午饭了么？','主播吃饭了吗','吃了么','吃饭','[3]','[4]','[5]'),
            '14'=>array('下午好','主播好','哈喽，下午好','我看看就走，哈哈哈','好呀','你好呀','吃饭了没','好的','好噢','尼好'),
            '15'=>array('下午好','主播好','主播,好','主播，好！','吃饭了吗','主播吃饭了么？','好呀','主播！你好','主播哪里人呀?','主播是哪里人啊？','好','主播那人？','主播哪人啊'),
            '16'=>array('主播下午好','主播你好呀','下午好','你都下午播的吗','你好','主播好呀','尼好','好好','hao!'),
            '17'=>array('主播下午好','你好','下午好','你都下午播的吗','您好','下午5点了','坐等下班，哈哈哈','主播好！！','好无聊啊，时间过得好慢'),
            '18'=>array('主播！吃饭了没','主播你好','下班！88','走了','又下午6点了','下班，拜拜','等下班，哈哈','[1]'),
            '19'=>array('主播吃饭了吗','吃饭了么','吃饭去了哦~！','主播好','主播,好','主播，好！','[2]'),
            '20'=>array('晚上好','主播晚上好','你好','您好','好','主播好','主播,好','主播，好！','主播今天要播到几点','主播都是晚上直播吗'),
            '21'=>array('主播！播到几点呀','主播晒到爆炸','主播帅！','几点下？','主播几点睡呀','我要睡了，88','我要睡了，拜拜','走了白白'),
            '22'=>array('主播还不休息','主播几点下呀','猪播好','hao!','主播都这么晚播的么','zhubo，好！'),
            '23'=>array('主播这么晚了还不睡啊！','主播几点下呀','主播这么晚了还没睡呀','晚安！走了','88，睡了','晚安，主播也早点休息'),
        ),     
        
        //通用
        'getCommon'=>array('您好','hello','赞一个！','赞！','赞赞赞！','你号','你好','主播！你好','主播哪里人呀?','主播是哪里人啊？',
        '好','主播那人？','主播哪人啊','主播干嘛的？','哪里的？','哈喽主播','好无聊呀','主播那边天气怎么样呀','主播，你是哪里的？能告诉我吗？','主播微信多少','嗨','手机没电了，下了'),
                              
    );
    
    /**
     * 加入房间
     *
     * @param  $type string 用户类型
     * @return
     */ 
    static public function joinRoom($type)
    {
        $content = '';
        switch($type){
        case 'tuhao':
            $content = self::$chats['joinRoom']['tuhao'];
            $content = self::contentFactory($content);
            break;
        }
        return $content;
    }
    
    /**
     * 送礼物
     *
     * @param  $giftid int 礼物id
     * @return
     */ 
    static public function giveGift($giftid)
    {
        $content = isset(self::$chats['giveGift'][$giftid]) ? self::$chats['giveGift'][$giftid]: '';
        $content = self::contentFactory($content);
        return $content;
    }
    
    /**
     * 公聊内容
     *
     * @param  $content string 内容
     * @return string
     */ 
    static public function publicChat($content)
    {
        $str_split = str_split($content);
        $size_arr = array_count_values($str_split);
        $first_arr = max($size_arr);
        $str = '';
        
        if($first_arr == count($str_split)) {
            switch($str_split[0]){
            case '1':
                $str = self::$chats['publicChat']['1'];
                $str = self::contentFactory($str);
                break;
                
            case '2':
                $str = self::$chats['publicChat']['2'];
                $str = self::contentFactory($str);
                break;
                
            case '6':
                $str = self::$chats['publicChat']['6'];
                $str = self::contentFactory($str);
                break;
            }            
        }
        

        return $str;
    }
    
    /**
     * 获取家乡地址
     */ 
    static public function getHome($addr)
    {
        if(empty($addr)) {
            return;
        }
        $content = self::$chats['getHome'];
        $content = self::contentFactory($content);
        return sprintf($content, $addr);
    }
    
    /**
     * 获取学生主播
     */ 
    static public function getStudent($content)
    {
        $content = self::$chats['getStudent'];
        $content = self::contentFactory($content);
        return $content;
    }

    /**
     * 性别
     */ 
    static public function getSex($type)
    {
        $content = '';
        switch($type){
        case 'F':
            $content = self::$chats['getSex']['F'];
            $content = self::contentFactory($content);
            break;
        
        case 'M':
            $content = self::$chats['getSex']['M'];
            $content = self::contentFactory($content);
            break;
        }
        return $content;
    }
    
    /**
     * 时间
     */ 
    static public function getTime()
    {
        $h = intval(date('H'));
        $content = isset(self::$chats['getTime'][$h]) ? self::$chats['getTime'][$h] : '';
        $content = self::contentFactory($content);
        return $content;
    }
    
    /**
     * 通用
     */ 
    static public function getCommon()
    {
        $content = self::$chats['getCommon'];
        $content = self::contentFactory($content);
        return $content;
    }
    
    /**
     * 内容工厂
     *
     * @param  $contents array 内容集合
     * @return string
     */ 
    static private function contentFactory($contents)
    {
        $str = '';
        if(!empty($contents)) {
            $len = count($contents);
            $index = mt_rand(0, $len - 1);
            $str = $contents[$index];
        }
        return $str;
    }
}

?>