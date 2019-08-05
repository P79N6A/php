<?php

class DAOLink extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("link");
    }
    
    /**
     * 添加数据
     *
     * @param array $option
     */
    public function add($option)
    {
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 是否存在linkid
     *
     * @param int $author            
     * @param int $liveid            
     */
    public function isExistLinkid($author, $liveid, $linktype)
    {
        $sql = " select linkid from " . $this->getTableName() . " where author=? and liveid=? and linktype=? ";
        $result = $this->getRow($sql, array('author' => $author,'liveid' => $liveid,'linktype' => $linktype));
        if (isset($result['linkid']) && $result['linkid'] > 0) {
            return $result['linkid'];
        }
        return false;
    }
    

    /**
     * 创建连麦link
     *
     * @param int $author
     * @param int $type
     */
    public function addLink($author, $liveid, $linktype, $streamtype)
    {
        $option = array(
            'author'     => $author,
            'liveid'     => $liveid,
            'linktype'   => $linktype,
            'streamtype' => $streamtype,
            'addtime'    => date('Y-m-d H:i:s')
        );
        return $this->insert($this->getTableName(), $option);
    }
    
    /**
     * 获取连麦详情
     *
     * @param int $linkid
     */
    public function getLinkInfo($linkid)
    {
        $sql = " select * from " . $this->getTableName() . " where linkid=? ";
        return $this->getRow($sql, array('linkid' => $linkid));
    }
    
    /**
     * 批量获取link列表
     *
     * @param array $linkids
     */
    public function getBatchLinkList($linkids)
    {
        if(empty($linkids)) {
            return array();
        }
        $arrTemp = array();
        $sql = " select * from " . $this->getTableName() . " where linkid in (".implode(',', $linkids).") ";
        $result = $this->getAll($sql);
        if(!empty($result)) {
            foreach($result as $key=>$val){
                $arrTemp[$result[$key]['linkid']] = $val;
            }
        }
        return $arrTemp;
    }
    
}
