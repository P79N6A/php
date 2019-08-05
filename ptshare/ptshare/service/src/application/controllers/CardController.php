<?php
class CardController extends BaseController
{
	public function getListAction()
	{
		$uid    = $this->getParam("uid")    ? intval($this->getParam("uid")) : 0;
		$offset = $this->getParam("offset") ? (int) ($this->getParam("offset")) : 0;
		$num    = $this->getParam("num")    ? intval($this->getParam("num")) : 20;
		$userid = Context::get("userid");

		if (empty($userid)) {
			$userid = $uid;
		}

		Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

		list($list,$total,$offset,$more) = Card::getCardList($userid, $num, $offset);

		$this->render(array('list' => $list, 'total' => $total, 'offset'=>$offset));
	}


	public function addAction()
	{
		$userid = Context::get("userid");
		$cover 	= $this->getParam("cover") 	? trim($this->getParam("cover")) 	: '';
		$text 	= $this->getParam("text") 	? trim($this->getParam("text")) 	: '';
		$type   = $this->getParam("type") 	? trim($this->getParam("type")) 	: '';

		Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
		Interceptor::ensureNotEmpty($cover, ERROR_PARAM_INVALID_FORMAT, '');
		Interceptor::ensureNotEmpty($text, ERROR_PARAM_INVALID_FORMAT, '');
		Interceptor::ensureNotEmpty($type, ERROR_PARAM_INVALID_FORMAT, '');

		$model_card = new Card();

		$this->render($model_card->add($userid, $cover, $type, $text));
	}


	public function delAction()
	{
		$userid  = Context::get("userid");
		$id		 = $this->getParam("id") ? (int) ($this->getParam("id")) : 0;

		Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");
		Interceptor::ensureNotFalse(is_numeric($id) && $id > 0, ERROR_PARAM_INVALID_FORMAT, "id");

		$cardinfo= Card::getInfo($id);

		Interceptor::ensureNotEmpty($cardinfo, ERROR_BIZ_CARD_NOT_EXIST, '');

		Interceptor::ensureNotFalse(($userid == $cardinfo['uid']), ERROR_BIZ_CARD_NOT_SELF, "userid");

		$this->render(Card::del($id));
	}


	public function detailAction()
	{
		$id		 = $this->getParam("id") ? (int) ($this->getParam("id")) : 0;

		Interceptor::ensureNotFalse(is_numeric($id) && $id > 0, ERROR_PARAM_INVALID_FORMAT, "id");

		$cardinfo = Card::getInfo($id);

		Interceptor::ensureNotEmpty($cardinfo, ERROR_BIZ_CARD_NOT_EXIST, '');

		$this->render($cardinfo);
	}
}