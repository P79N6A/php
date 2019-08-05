<?php
class UsersDonate
{

    protected $daoUsersDonate;

    public function __construct()
    {
        $this->daoUsersDonate = new DAOUsersDonate();
    }

    // 捐赠
    public function add($userid, $contact)
    {

        try{

            $insertId = $this->daoUsersDonate->add($userid, $contact);

            $contact = json_decode($contact, true);

            // 推送捐赠成功消息
            $type = WxProgram::TYPE_DONATE_ADD_SUCCESS;

            $languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
            $remark = $languageConfig['wx_program_template_msg'][$type]['remark'];

            $data = [
                Order::getOrderId(),
                $contact['contact_city'].$contact['contact_county'].$contact['contact_address'],
                $remark
            ];

            $wxProgram = new WxProgram();
            $wxProgram->sendTemplateMessage($userid, $type, $data);

            return $insertId;

        } catch (Exception $exception){

            throw new BizException(ERROR_MALL_SELL_ADD);

        }

    }


    //
    public function getList($userid, $limit, $offset)
    {
        return $this->daoUsersDonate->getList($userid, $limit, $offset);
    }





}