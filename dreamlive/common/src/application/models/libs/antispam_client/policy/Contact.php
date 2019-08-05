<?php
require_once "Policy.php";

class Contact extends Policy
{
    public function isDirty($content, $options = array())
    {
        if(strlen($content) < 6) {
            return false;
        }
        
        $pattern = "/\w+\.(top|com|net|org|edu|gov|int|mil|cn|com.cn|net.cn|gov.cn|org.cn|tel|biz|cc|tv|info|name|hk|mobi|asia|cd|travel|pro|museum|coop|aero|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cf|cg|ch|ci|ck|cl|cm|cn|co|cq|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|es|et|ev|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gp|gr|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|ml|mm|mn|mo|mp|mq|mr|ms|mt|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|qa|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|va|vc|ve|vg|vn|vu|wf|ws|ye|yu|za|zm|zr|zw)/iu";
        
        if(preg_match($pattern, $content) && $this->findKeywords($content)) {
            return true;
        }
        
        $content = $this->format($content);

        $patterns = array(
        "/(qq|扣扣|加Q|\+Q|加叩|加VX|加V|Q)(号|号码|號|:)?\s*\d+/iu",
        "/手机(号|号码|號)?\s*:?(\d{11})/iu",
        "/(微博|weibo|围脖)(号|号码|號)?:?\s*[0-9z-zA-Z_\-]+/iu",
        "/(微信|weixin|威信|魏信|薇信|巍信)(号|号码|號|:)?\s*[0-9z-zA-Z_\-]+/iu",
        "/(加微)(伽徽)(咖威信)(号|号码|號)?\s*:?[0-9z-zA-Z_\-]+/iu",
        "/(Q|QQ|YY|陌陌|股票)群(号|号码|號|:)?\s*[0-9z-zA-Z_\-]+/iu",
        "/快手(号|号码|號)?\s*:?(\d+)/iu",
        "/陌陌(号|号码|號)?\s*:?(\d+)/iu",
        "/(yy|歪歪|微)(号|号码|號)?\s*:?([0-9z-zA-Z_\-]+)/iu",
        "/(电话|TEL|热线)(号|号码|號)?\s*:?(\d+)/iu",
        );

        foreach($patterns as $pattern) {
            if(preg_match($pattern, $content) && $this->findKeywords($content)) {
                return true;
            }
        }
        
        return false;
    }
    
    
    public function findKeywords($content)
    {
        $keyword_array = Policy::getKeywords();
        
        foreach($keyword_array as $words) {
            if(strpos($content, $words)  !== false) {
                return true;
            }
        }
        
        return false;
    }
}
?>