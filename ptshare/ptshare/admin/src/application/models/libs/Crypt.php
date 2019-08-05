<?php
/**
 * Crypt by AES 
 */
class Crypt
{
    private static $aes;

    public static function getAes()
    {/*{{{*/
        if (!isset(self::$aes)) {
            self::$aes = new Crypt_AES();
        }

        return self::$aes;
    }/*}}}*/

    public static function encrypt($content, $password=null)
    {/*{{{*/
        $_aes=self::getAes();
        $_aes->setKey($password);
        return bin2hex($_aes->encrypt($content));
    }/*}}}*/

    public static function decrypt($cryptext, $password=null)
    {/*{{{*/
        $_aes=self::getAes();
        $_aes->setKey($password);
        return $_aes->decrypt(self::_hex2bin($cryptext));
    }/*}}}*/

    private static function _hex2bin($hexdata)
    {/*{{{*/
        $bindata="";
        $l=strlen($hexdata);
        for ($i=0;$i<$l;$i+=2) {
            $bindata.=chr(hexdec(substr($hexdata,$i,2)));
        }
        return $bindata;
    }/*}}}*/
}
