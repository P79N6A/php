<?php


class GameController extends BaseController
{
    /**
     * 游戏列表接口
     */
    public function getGameListAction()
    {
        $info = Game::getGameList();

        $this->render($info);
    }
    /*
     * 获取游戏信息
     * */
    public function getGameInfoAction()
    {
        $gameid = intval($this->getParam('gameid'));
        //$uid=Context::get('userid',0);
        Interceptor::ensureNotFalse($gameid > 0, ERROR_PARAM_INVALID_FORMAT, "gameid");
        $info = Game::getGameInfo($gameid);

        $this->render($info);
    }

    /**
     * 获取当前游戏状态
     */
    public function getGameStateAction()
    {
        $version=Context::get('version');
        $platform=Context::get('platform');
        if ($platform=='ios'&&$version=='2.6.2') {
            $this->render();
        }
        $gameid = intval($this->getParam('gameid'));
        Interceptor::ensureNotFalse($gameid > 0, ERROR_PARAM_INVALID_FORMAT, "gameid");
        $info = Game::getGameState($gameid);
        $this->render($info);
    }

    public function addAction()
    {
        $name = trim(strip_tags($this->getParam('name', '')));
        $icon = trim(strip_tags($this->getParam('icon', '')));
        $type = trim(strip_tags($this->getParam('type', '')));
        $isshow = trim(strip_tags($this->getParam('isshow', '')));
        $extends = trim(strip_tags($this->getParam('extends', '')));
        $h5_url = trim($this->getParam('h5_url', ''));
        $weight = intval($this->getParam('weight', 0));
        $fullscreen=intval($this->getParam('fullscreen', 0));

        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, 'name');
        Interceptor::ensureNotEmpty($icon, ERROR_PARAM_IS_EMPTY, 'icon');
        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, 'type');
        Interceptor::ensureNotFalse(in_array($isshow, ['Y', 'N']), ERROR_PARAM_INVALID_FORMAT, 'isshow');

        /*$dao_game = new DAOGame();
        $gameid = $dao_game->addGame($name, $icon, $type, $isshow, $extends, $h5_url, $weight,$fullscreen);*/
        $gameInfo=Game::addGame($name, $icon, $type, $isshow, $extends, $h5_url, $weight, $fullscreen);
        if(!empty($gameInfo)) {
            $cache      = Cache::getInstance("REDIS_CONF_CACHE");
            $cache ->set('horseracing_gamelist', json_encode($gameInfo));
        }

        $this->render(['gameid' => $gameInfo['gameid']]);
    }

    public function updateBaseInfoAction()
    {
        $gameid = trim($this->getParam('gameid', ''));
        $name = trim(strip_tags($this->getParam('name', '')));
        $icon = trim(strip_tags($this->getParam('icon', '')));
        $type = trim(strip_tags($this->getParam('type', '')));
        $isshow = trim(strip_tags($this->getParam('isshow', '')));
        $h5_url = trim($this->getParam('h5_url', ''));
        $weight = intval($this->getParam('weight', ''));
        $fullscreen=intval($this->getParam('fullscreen', 0));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $gameid) > 0, ERROR_PARAM_INVALID_FORMAT, 'gameid');
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, 'name');
        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, 'type');
        Interceptor::ensureNotFalse(in_array($isshow, ['Y', 'N']), ERROR_PARAM_INVALID_FORMAT, 'isshow');

        $dao_game = new DAOGame();
        $row = $dao_game->updateBaseInfo($gameid, $name, $icon, $type, $isshow, '', $h5_url, $weight, $fullscreen);
        if($row) {
            $gameInfo   = $dao_game->getGameInfos();
            $cache      = Cache::getInstance("REDIS_CONF_CACHE");
            $cache ->set('horseracing_gamelist', json_encode($gameInfo));
        }

        $this->render(['gameid' => $gameid, 'row' => $row]);
    }

    public function updateExtendsAction()
    {
        $gameid = trim($this->getParam('gameid', ''));
        $extends = trim(strip_tags($this->getParam('extends', '')));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $gameid) > 0, ERROR_PARAM_INVALID_FORMAT, 'gameid');
        json_decode($extends);
        Interceptor::ensureNotFalse(json_last_error() == JSON_ERROR_NONE, ERROR_PARAM_INVALID_FORMAT, 'extends');

        $dao_game = new DAOGame();
        $row = $dao_game->updateExtendsInfo($gameid, $extends);
        if($row) {
            $gameInfo   = $dao_game->getGameInfos();
            $cache      = Cache::getInstance("REDIS_CONF_CACHE");
            $cache ->set('horseracing_gamelist', json_encode($gameInfo));
        }

        $this->render(['gameid' => $gameid, 'row' => $row]);
    }

    public function addGameRebotAction()
    {
        $uid = trim($this->getParam('uid', ''));
        $type = trim($this->getParam('type', '0'));
        $currency = trim($this->getParam('currency', '2'));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse(in_array($type, [1, 2]), ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse(in_array($currency, [2, 4]), ERROR_PARAM_INVALID_FORMAT, 'currency');

        if ($type == 1) {
            if($currency == 2) {
                $dao_robot = new DAOGameRobots();
            } else {
                $dao_robot = new DAOGameRobotsStar();
            }

            Interceptor::ensureEmpty($dao_robot->isRunRobot($uid), ERROR_BIZ_GAME_ROBOTS_EXIST, 'uid');
            $result = $dao_robot->addRobot($uid);
        } else {
            if($currency == 2) {
                $dao_robot = new DAOGameOperation();
            } else {
                $dao_robot = new DAOGameOperationStar();
            }

            Interceptor::ensureEmpty($dao_robot->isRunAccount($uid), ERROR_BIZ_GAME_ROBOTS_EXIST, 'uid');
            $result = $dao_robot->addOperation($uid);
        }

        $this->render(['id' => $result]);
    }

    public function deleteGameRebotAction()
    {
        $uid = trim($this->getParam('uid', ''));
        $type = trim($this->getParam('type', '0'));
        $currency = trim($this->getParam('currency', '2'));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse(in_array($type, [1, 2]), ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse(in_array($currency, [2, 4]), ERROR_PARAM_INVALID_FORMAT, 'currency');

        if ($type == 1) {
            if($currency == 2) {
                $dao_robot = new DAOGameRobots();
            } else {
                $dao_robot = new DAOGameRobotsStar();
            }

            $result = $dao_robot->deleteRobot($uid);
        } else {
            if($currency == 2) {
                $dao_robot = new DAOGameOperation();
            } else {
                $dao_robot = new DAOGameOperationStar();
            }

            $result = $dao_robot->deleteOperation($uid);
        }

        $this->render(['row' => $result]);
    }

    public function addRewardAction()
    {
        $giftid = trim(strip_tags($this->getParam('giftid', '')));
        $lower = trim(strip_tags($this->getParam('lower', '')));
        $upper = trim(strip_tags($this->getParam('upper', '')));
        $orderid = (int)$this->getParam('orderid', '');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $giftid) > 0, ERROR_PARAM_INVALID_FORMAT, 'giftid');
        Interceptor::ensureNotFalse($lower < $upper, ERROR_PARAM_INVALID_FORMAT, $lower);

        $dao_reward = new DAORewardConfig();
        $result = $dao_reward->addConfig($giftid, $lower, $upper, $orderid);

        $this->render(['id' => $result]);
    }

    public function deleteRewardAction()
    {
        $id = trim(strip_tags($this->getParam('id', '')));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $id) > 0, ERROR_PARAM_INVALID_FORMAT, 'id');

        $dao_reward = new DAORewardConfig();
        $result = $dao_reward->deleteConfig($id);

        $this->render(['row' => $result]);
    }

    public function getLottoStateAction()
    {
        $uid=Context::get('userid', 0);
        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        $this->render(Game::getLottoState($uid));
    }
}