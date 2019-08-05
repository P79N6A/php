<?php
class MessageController extends BaseController
{

    protected $userid = 0;
    public function __construct()
    {
        parent::__construct();
        $this->userid = Context::get("userid");
    }

    public function getMessageListAction()
    {
        $userid = $this->userid;
        $offset = $this->getParam("offset", 0);
        $limit  = $this->getParam("num", 7);

        Interceptor::ensureNotFalse($limit > 0, ERROR_PARAM_INVALID_FORMAT, 'limit');
        Interceptor::ensureNotFalse(is_numeric($offset), ERROR_PARAM_INVALID_FORMAT, 'offset');

        $modelSell = new Message($userid);

        $result = $modelSell->getList($userid, $limit, $offset);

        $this->render([ 'list' => $result['list'], 'offset' => $result['offset'], 'more' => $result['more']]);

    }

    public function getUnReadTotalAction()
    {
        $unReadMessageTotal = 0;
        if ($this->userid) {
            $messageModel = new Message($this->userid);
            $unReadMessageTotal = $messageModel->getUnReadTotal();
        }

        $this->render(['total' => $unReadMessageTotal]);

    }

    public function addFormIdAction()
    {
    	$userid = $this->userid;
    	$formid = $this->getParam("wx_program_form_id") ? trim($this->getParam("wx_program_form_id")) : '';
    	
    	
    	if (empty($formid) || empty($userid)) {
    		$this->render();
    	}
    	
    	$this->render(UserForms::add($userid, $formid));
    }

}