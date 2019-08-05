<?php
class Card
{
	public static function getCardList($uid, $num, $offset)
	{
		$arrTemp = array();

		$DAOcard = new DAOCard();
		$list = $DAOcard->getCardList($uid, $num, $offset);

		foreach ($list as $key => $val) {
			$temp = array(
					'id' 	  => $list[$key]['id'],
					'uid' 	  => $list[$key]['uid'],
					'cover'   => $list[$key]['cover'],
					'text'   => $list[$key]['text'],
					'type'    => $list[$key]['type'],
					'addtime' => $list[$key]['addtime'],
			);
			$arrTemp[] = $temp;
			$offset = $val['id'];
		}

		$total = $DAOcard->getCardTotal($uid);

		return array($arrTemp, $total, $offset);
	}

	public function add($uid, $cover, $type, $text)
	{
		$DAOcard = new DAOCard();

		return $DAOcard->add($uid, $cover, $type, $text);
	}

	public static function getInfo($id)
	{
		$cache = Cache::getInstance("REDIS_CONF_CACHE");
		$card_info = $cache->hgetall("hmset_card_info_" . $id);

		if (empty($card_info)) {
			$DAOcard = new DAOCard();

			$card_info = $DAOcard->getCardInfoById($id);

			$cache->hmset("hmset_card_info_" . $id, $card_info);
			$cache->expire("hmset_card_info_" . $id, 86400);
		}

		return $card_info;
	}


	public static function del($id)
	{
		$DAOcard = new DAOCard();
		$cache = Cache::getInstance("REDIS_CONF_CACHE");

		if ($DAOcard->remove($id)) {
			$cache->del("hmset_card_info_" . $id);
		}
		
		

		return true;
	}
}