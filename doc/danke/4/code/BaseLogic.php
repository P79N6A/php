<?php // zhangwei@wutongwan.org

abstract class BaseLogic
{

    private $message = null;

    public static function instance()
    {
        return new static;
    }

    protected function setMessage($msg)
    {
        $this->message = $msg;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
