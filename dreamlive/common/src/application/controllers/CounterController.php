<?php
class CounterController extends BaseController
{
    const PATTERN_TYPE      = '#^[a-z0-9_]{1,15}$#i';

    const PATTERN_RELATEID  = '#^[a-z0-9_]{1,30}$#i';

    public function increaseAction()
    {
        $type       = $this->getParam('type');
        $relateid   = $this->getParam('relateid');
        $number     = $this->getParam('number', 1);

        $this->checkPublicParams($type);
        $this->checkRelateid($relateid);

        Interceptor::ensureNotFalse(is_numeric($number) && $number > 0, ERROR_PARAM_INVALID_FORMAT, 'number');

        $total = Counter::increase($type, $relateid, $number);
        Interceptor::ensureNotFalse($total >= 0, ERROR_BIZ_COUNTER_IS_NEGATIVE);
        if ($total !== false) {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $number, 'microtime' => round(microtime(true) * 1000)));
        }
        $this->render(
            array(
            'result' => (string) max(0, $total)
            )
        );
    }

    public function decreaseAction()
    {
        $type       = $this->getParam('type');
        $relateid   = $this->getParam('relateid');

        $this->checkPublicParams($type);
        $this->checkRelateid($relateid);

        $number = $this->getParam('number', 1);
        Interceptor::ensureNotFalse(is_numeric($number) && $number > 0, ERROR_PARAM_INVALID_FORMAT, 'number');

        $total = Counter::decrease($type, $relateid, $number);
        Interceptor::ensureNotFalse($total >= 0, ERROR_BIZ_COUNTER_IS_NEGATIVE);
        if ($total !== false) {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $number, 'microtime' => round(microtime(true) * 1000)));
        }
        
        $this->render(
            array(
            'result' => (string) $total
            )
        );
    }

    public function setAction()
    {
        $type       = $this->getParam('type');
        $relateid   = $this->getParam('relateid');

        $this->checkPublicParams($type);
        $this->checkRelateid($relateid);

        $number = $this->getParam('number', 1);
        Interceptor::ensureNotFalse(is_numeric($number) && $number >= 0, ERROR_PARAM_INVALID_FORMAT, 'number');

        $total = Counter::set($type, $relateid, $number);
        if ($total !== false) {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $number, 'microtime' => round(microtime(true) * 1000)));
        }
        Interceptor::ensureNotFalse($total == $number, ERROR_BIZ_COUNTER_BUSY_RETRY);

        $this->render();
    }

    public function setexAction()
    {
        $type       = $this->getParam('type');
        $relateid   = $this->getParam('relateid');
        $expire     = $this->getParam('expire');

        $this->checkPublicParams($type);
        $this->checkRelateid($relateid);
        Interceptor::ensureNotFalse(is_numeric($expire) && $expire > 0, ERROR_PARAM_INVALID_FORMAT, 'expire');

        $number = $this->getParam('number', 1);
        Interceptor::ensureNotFalse(is_numeric($number) && $number >= 0, ERROR_PARAM_INVALID_FORMAT, 'number');

        $total = Counter::setex($type, $relateid, $number, $expire);
        Interceptor::ensureNotFalse($total == $number, ERROR_BIZ_COUNTER_BUSY_RETRY);
        if ($total !== false) {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance('dream')->addTask('counter_change', array('type' => $type, 'relateid' => $relateid, 'value' => $number, 'microtime' => round(microtime(true) * 1000)));
        }
        
        $this->render();
    }

    public function expireAction()
    {
        $type       = $this->getParam('type');
        $relateid   = $this->getParam('relateid');
        $expire     = $this->getParam('expire');

        $this->checkPublicParams($type);
        $this->checkRelateid($relateid);

        Interceptor::ensureNotFalse(is_numeric($expire) && $expire > 0, ERROR_PARAM_INVALID_FORMAT, 'expire');

        $result = Counter::expire($type, $relateid, $expire);

        Interceptor::ensureNotFalse($result, ERROR_BIZ_COUNTER_BUSY_RETRY);

        $this->render();
    }

    public function getAction()
    {
        $type       = $this->getParam('type');
        $relateid   = $this->getParam('relateid');

        $this->checkPublicParams($type);
        $this->checkRelateid($relateid);

        $number = Counter::get($type, $relateid);
        if ($number === false) {
            $number = 0;
        }

        $this->render(
            array(
            'result' => (string) max(0, $number)
            )
        );
    }

    public function getsAction()
    {
        $type       = $this->getParam('type');
        $relateids  = $this->getParam('relateids');

        Interceptor::ensureNotEmpty($relateids, ERROR_PARAM_IS_EMPTY, 'relateids');

        $this->checkPublicParams($type);

        ! is_array($relateids) && $relateids = explode(',', $relateids);
        foreach ($relateids as $relateid) {
            $this->checkRelateid($relateid);
        }

        $results = Counter::gets($type, $relateids);
        Interceptor::ensureNotFalse($results, ERROR_BIZ_COUNTER_BUSY_RETRY);

        foreach ($results as $relateid => $number) {
            if ($number === false) {
                $number = 0;
            }

            $results[$relateid] = (string) max(0, $number);
        }

        $this->render($results);
    }

    public function mixedAction()
    {
        $types      = $this->getParam('types');
        $relateids  = $this->getParam('relateids');

        ! is_array($types) && $types = explode(',', $types);
        ! is_array($relateids) && $relateids = explode(',', $relateids);

        Interceptor::ensureNotFalse(count($types) * count($relateids) <= 100, ERROR_PARAM_INVALID_FORMAT, 'types*relateids must less than 100');

        $this->checkPublicParams($types);
        $this->checkRelateid($relateids);

        $results = Counter::mixed($types, $relateids);
        Interceptor::ensureNotFalse($results, ERROR_BIZ_COUNTER_BUSY_RETRY);

        foreach ($results as $relateid => $numbers) {
            foreach ($numbers as $k => $v) {
                if ($v === false || $v < 0) {
                    $v = 0;
                }
                $numbers[$k] = (string) $v;
            }
            $results[$relateid] = $numbers;
        }

        $this->render($results);
    }

    private function checkPublicParams($type)
    {
        if (is_array($type)) {
            foreach ($type as $t) {
                Interceptor::ensureNotFalse(preg_match(self::PATTERN_TYPE, $t) > 0, ERROR_PARAM_INVALID_FORMAT, 'types');
            }
        } else {
            Interceptor::ensureNotFalse(preg_match(self::PATTERN_TYPE, $type) > 0, ERROR_PARAM_INVALID_FORMAT, 'type');
        }
    }

    private function checkRelateid($relateid)
    {
        if (is_array($relateid)) {
            foreach ($relateid as $r) {
                Interceptor::ensureNotFalse(preg_match(self::PATTERN_RELATEID, $r) > 0, ERROR_PARAM_INVALID_FORMAT, 'relateids');
            }
        } else {
            Interceptor::ensureNotFalse(preg_match(self::PATTERN_RELATEID, $relateid) > 0, ERROR_PARAM_INVALID_FORMAT, 'relateid');
        }
    }
}
?>
