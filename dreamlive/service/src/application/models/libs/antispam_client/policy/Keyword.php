<?php
require_once "Policy.php";
class Keyword extends Policy
{
    public static function isDirty($content)
    {
        $keywords = array (
        0 => '招主播',
        1 => '兼职',
        2 => '映客',
        3 => '陌陌',
        4 => '秒拍',
        5 => '贾慶林',
        6 => '温宝宝',
        7 => '赵紫阳',
        8 => '习国母',
        9 => '汽狗',
        10 => '何挺表',
        11 => '蒋彦永',
        12 => '江贼民',
        13 => '余杰温',
        14 => '塭家宝',
        15 => '温加饱',
        17 => '李宏志',
        18 => '气枪',
        19 => '小穴',
        20 => '强奸',
        21 => '内射',
        22 => '火枪',
        23 => '六四',
        24 => '薄谷开来',
        25 => '李志',
        26 => '任铭',
        27 => '法轮功',
        28 => '高智晟',
        29 => '淫乱',
        30 => '做爱',
        31 => '冰毒',
        32 => '发瞟',
        33 => '李稅',
        34 => '穴图',
        35 => '騒逼',
        36 => '戈聰',
        37 => '穴淫',
        38 => '品屄',
        39 => '老溫',
        40 => '淫逼',
        41 => '淫姐',
        42 => '查屄',
        43 => '乔石',
        44 => '淫浪',
        45 => '肏他',
        46 => '玖评',
        47 => '淫流',
        48 => '肏日',
        49 => '何勇',
        50 => '淫糜',
        51 => '日肏',
        52 => '淫蜜',
        53 => '刁远凸',
        54 => '刁远突',
        55 => '刁远坑',
        56 => '袁奉初',
        57 => '刁太大',
        58 => '刁大犬',
        59 => '习土匪',
        60 => '日月泽',
        61 => '梁咏春',
        62 => '艹死你',
        63 => 'JB毛',
        64 => '我要撸',
        65 => '打手网',
        66 => '范承秀',
        67 => '发伦工',
        68 => '胡京涛',
        69 => '张筱雨',
        70 => '乱伦图',
        71 => '朱敏慧',
        72 => '习安安',
        74 => '郭立新',
        75 => '王登朝',
        76 => '江爷爷',
        77 => '轉灋輪',
        78 => '假庆亃',
        79 => '倒共风',
        80 => '艹妮码',
        81 => '日你B',
        82 => '伯熙来',
        83 => '胡锦淘',
        84 => '玛雅网',
        85 => '春水楼',
        86 => '朱敏惠',
        87 => '习大傻',
        88 => '辦証Q',
        90 => '维权会',
        91 => '曾庆哄',
        92 => '姜泽民',
        93 => '贾氢林',
        94 => '胡占凡',
        95 => '天安门',
        96 => '玩你妹',
        97 => '江陵肃',
        98 => '发轮共',
        99 => '搏熙来',
        100 => '胡猪席',
        101 => '操母狗',
        102 => '杜航伟',
        103 => '习太子',
        104 => '總經理',
        105 => '五毛D',
        106 => '幼幼女',
        107 => '槚庆崊',
        108 => '习江斗',
        109 => '不厚公',
        110 => '支联会',
        111 => '封从德',
        112 => '爱田由',
        113 => '发论工',
        114 => '胡主习',
        115 => '品香堂',
        116 => '群p网',
        117 => '习王储',
        118 => '五毛儿',
        119 => '朱虞夫',
        120 => '假普選',
        121 => '操呢妈',
        122 => '狂日你',
        123 => '周永坤',
        124 => '江蛤蟆',
        125 => '侯德健',
        126 => '罚轮公',
        127 => '胡住席',
        128 => '素人娘',
        129 => '色小说',
        130 => '习阿哥',
        131 => '杨恒均',
        132 => '陈主任',
        133 => '五毛们',
        134 => '秦永敏',
        135 => '卢宏军',
        136 => '甲庆啉',
        137 => '审不厚',
        138 => '程映虹',
        139 => '草干日',
        140 => '曰你姐',
        141 => '爱原翼',
        142 => '法丯轮',
        143 => '张晓军',
        144 => '胡祝席',
        145 => '蝴蝶逼',
        146 => '排泄图',
        147 => '辦假証',
        148 => '董文华',
        149 => '李洪志',
        150 => '胡文海',
        151 => '刘兴联',
        152 => '卢军宏',
        153 => '嘉庆冧',
        154 => '售假证',
        155 => '不厚案',
        156 => '六月四',
        157 => '戚春生',
        158 => '黄丝带',
        159 => '黄图哥',
        160 => '法囧轮',
        161 => '孙锡良',
        162 => '胡津涛',
        163 => '粉红穴',
        164 => '采花团',
        165 => '曾金燕',
        166 => '贾青邻',
        167 => '王冶坪',
        168 => '蛤蟆帝',
        169 => '法口轮',
        170 => '胡仅掏',
        171 => '流蜜汁',
        172 => '平野绫',
        173 => '黄之锋',
        174 => '习贝勒',
        175 => '胡谦慈',
        176 => '江责民',
        177 => '槚庆磷',
        178 => '赵家宪政',
        179 => '赵家反恐',
        180 => '习明泽',
        181 => '升汞',
        182 => '周永康',
        183 => '布苯丙胺',
        185 => '水果冰',
        186 => '刺刀',
        188 => '鸡巴',
        189 => '仿真枪',
        190 => 'C4炸药',
        191 => '提短枪',
        192 => '毛氏作风',
        193 => '维人被杀',
        194 => '中共喉舌',
        195 => '港民怒吼',
        196 => '警棍',
        197 => '肝源',
        198 => '喷雾迷葯',
        200 => '江系嫡系',
        201 => '硼烷',
        202 => '推翻中共',
        203 => '明镜邮报',
        204 => '反越示威',
        205 => '江懒蛤蟆',
        206 => '公盟李蔚',
        207 => '警棍卖',
        208 => '军枪',
        209 => '内蒙独立',
        210 => '提砷酸',
        211 => '中共黑暗',
        212 => '公盟不死',
        213 => '回汉纠纷',
        214 => '法轮大法',
        215 => '黄菊自杀',
        217 => '乙环利定',
        219 => '氧化铊',
        220 => '销售TNT',
        221 => '乙硼烷提',
        222 => '砷化氢',
        223 => '磷化锌提',
        224 => '牙签冰',
        225 => '杜冷丁',
        226 => '氯胺酮',
        227 => '磷化锌',
        228 => '54枪',
        229 => '羰基镍提',
        230 => '麻黄素',
        231 => '蒋捷连',
        232 => '烯丙胺',
        234 => '失忆水',
        235 => '气木仓',
        236 => '黄体酮',
        237 => '碘甲烷',
        238 => 'tnt炸弹',
        239 => '氯磺酸',
        240 => '黄樟素',
        241 => '赵常青',
        243 => '汽木仓',
        244 => '印客',
        245 => 'YOLO',
        246 => '网盘',
        257 => 'weixin',
        258 => 'luoliao',
        259 => '一对一',
        260 => '咖威信',
        261 => '迦薇',
        262 => '包刺激',
        263 => '包舒服'
        );
        
        $keyword_array = Policy::getKeywords();
        
        $length = mb_strlen($content);
        
        foreach($keyword_array as $words) {
            if(strpos($content, $words)  !== false && $length > 20) {
                return true;
            }
        }
        
        foreach($keywords as $keyword) {
            if(preg_match("/$keyword/iu", $content)) {
                return true;
            }
        }

        return false;
    }
}
?>