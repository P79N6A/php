<?php

class ForgePublicId extends BaseLogic
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function publicId()
    {
        return base_convert(static::optimus()->encode($this->id), 10, static::optimusBase());
    }

    protected static function optimusBase()
    {
        return 10;
    }

    /**
     * 如果安全性比较高，可以重写此方法。
     * @return Optimus|null
     */
    protected static function optimus()
    {
        static $static = null;
        if (!$static) {
            $static = new Optimus(2004033439, 711081055, 1317505404);
        }
        return $static;
    }

    public static function optimusDecode($pubID)
    {
        return static::optimus()->decode(base_convert($pubID, static::optimusBase(), 10));
    }
}
