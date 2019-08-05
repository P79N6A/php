<?php
class Sphinx
{
    private $_config = array();
    private $_sph    = array();
    private $_conn   = null;

    public static function getInstance()
    {
        static $instance;

        if(!isset($instance)) {
            $instance = new Search();
        }

        return $instance;
    }

    public function __construct()
    {
        $this->_config = Context::getConfig("SPHINX_CONF");
        $this->connect();
    }

    function connect($adaptor = null)
    {
        try {
            $sc = new SphinxClient();
            $sc->SetServer($this->_config['host'], $this->_config['port']);
            //$sc->SetMatchMode(SPH_MATCH_EXTENDED);
            $sc->SetArrayResult(true);
        } catch (Exception $e) {
            throw new BizException(ERROR_SYS_DB_SQL, $e->getMessage());
        }
        $this->_sph = $sc;
    }

    public function sphinx($key, $offset, $limit)
    {
        $index = 'master';
        $words = $this->Word($key);

        $this->_sph->SetLimits($offset, $limit);
        $spx = $this->_sph->Query($words, $index);

        $res = array();
        if($spx['matches']) {
            foreach($spx['matches'] as $val){
                $res[] = $val['id'];
            }
        }

        return $res;
    }

    private static function Word($key)
    {
        $so = scws_new();
        $so->set_charset('utf-8');
        //默认词库
        $so->add_dict(ini_get('scws.default.fpath') . '/dict.utf8.xdb');
        //自定义词库
        // $so->add_dict('./dd.txt',SCWS_XDICT_TXT);
        //默认规则
        $so->set_rule(ini_get('scws.default.fpath') . '/rules.utf8.ini');

        //设定分词返回结果时是否去除一些特殊的标点符号
        //$so->set_ignore(true);

        //设定分词返回结果时是否复式分割，如“中国人”返回“中国＋人＋中国人”三个词。
        // 按位异或的 1 | 2 | 4 | 8 分别表示: 短词 | 二元 | 主要单字 | 所有单字
        //1,2,4,8 分别对应常量 SCWS_MULTI_SHORT SCWS_MULTI_DUALITY SCWS_MULTI_ZMAIN SCWS_MULTI_ZALL
        $so->set_multi(false);

        //设定是否将闲散文字自动以二字分词法聚合
        $so->set_duality(false);

        //设定搜索词
        $so->send_text($key);
        $words_array = $so->get_result();
        $words[] = $key;
        foreach($words_array as $v)
        {
            $words[] = '('.$v['word'].')';
        }
        $so->close();

        return implode('|', $words);
    }

    public function searchVal($searchType, $searchVal, $start=0, $limit=30)
    {
        $cond = array();
        if(!empty($searchVal) && $searchType=='nickname') {
            $searchVal = trim(strip_tags($searchVal));

            $searchVal = $this->sphinx($searchVal, $start, $limit);
            $searchType='uid';
        }

        if(!empty($searchVal) && in_array($searchType, array('uid', 'liveid'))) {
            if(!is_array($searchVal) && $searchType=='liveid') {
                $searchVal = intval($searchVal);
            }
            if(!is_array($searchVal) && $searchType=='uid') {
                $tmp = intval($searchVal);
                unset($searchVal);
                $searchVal[] = $tmp;
            }else{
                $searchVal = $searchVal;
            }
        }

        $cond[$searchType] = $searchVal;
        return $cond;
    }
}

