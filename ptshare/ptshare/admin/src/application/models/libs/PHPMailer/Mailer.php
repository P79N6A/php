<?php

class Mailer
{
    private $host='';
    private $username='';
    private $pwd='';
    private $nickname='';
    
    public function __construct($host,$username,$pwd,$nickname='') {
        $this->host=$host;
        $this->username=$username;
        $this->pwd=$pwd;
        $this->nickname=$nickname;
    }
    
    public function send($to,$subject,$body,$attach)
    {
        require('PHPMailer/class.phpmailer.php');  
  
        $mail = new PHPMailer(); //实例化  

        $mail->IsSMTP(); // 启用SMTP  
        //$mail->SMTPDebug = 1;
        $mail->Host = $this->host; //"smtp.exmail.qq.com"; //SMTP服务器 163邮箱例子  

        $mail->Port = 465;  //邮件发送端口  
        $mail->SMTPAuth   = true;  //启用SMTP认证  
        $mail->SMTPSecure = "ssl"; 

        $mail->CharSet  = "UTF-8"; //字符集  
        $mail->Encoding = "base64"; //编码方式  

        $mail->Username =  $this->username; //"wuhongjie@dreamlive.tv";  //你的邮箱  
        $mail->Password = $this->pwd; // "Wuhongjie123";  //你的密码  
        $mail->Subject = $subject; //邮件标题  

        $mail->From = $this->username; // "wuhongjie@dreamlive.tv";  //发件人地址（也就是你的邮箱）  
        $mail->FromName = $this->nickname; //"hongjie";   //发件人姓名  
        if(is_array($to))
        {
            foreach ($to as $t)
            {
                $mail->AddAddress($t);    //添加收件人1（地址，昵称）  
            }
        }
        else
        {
            $mail->AddAddress($to);
        }
        
        if(is_array($attach))
        {
            foreach ($attach as $att)
            {
                $mail->AddAttachment($att); // 添加附件,并指定名称  
            }
        }
        else
        {
           
            $mail->AddAttachment($attach); // 添加附件,并指定名称  
        }
        
        $mail->IsHTML(true); //支持html格式内容  
       // $mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片  
        $mail->Body = $body;

        //$mail->Send();
        
        //发送  
        if(!$mail->Send()) {  
            $this->log('withdraw_email.log',$mail->ErrorInfo);
        } 
    }

	public function send_yjj($to,$subject,$body)
    {
        require('PHPMailer/class.phpmailer.php');  
  
        $mail = new PHPMailer(); //实例化  

        $mail->IsSMTP(); // 启用SMTP  
        //$mail->SMTPDebug = 1;
        $mail->Host = $this->host; //"smtp.exmail.qq.com"; //SMTP服务器 163邮箱例子  

        $mail->Port = 465;  //邮件发送端口  
        $mail->SMTPAuth   = true;  //启用SMTP认证  
        $mail->SMTPSecure = "ssl"; 

        $mail->CharSet  = "UTF-8"; //字符集  
        $mail->Encoding = "base64"; //编码方式  

        $mail->Username =  $this->username; //"wuhongjie@dreamlive.tv";  //你的邮箱  
        $mail->Password = $this->pwd; // "Wuhongjie123";  //你的密码  
        $mail->Subject = $subject; //邮件标题  

        $mail->From = $this->username; // "wuhongjie@dreamlive.tv";  //发件人地址（也就是你的邮箱）  
        $mail->FromName = $this->nickname; //"hongjie";   //发件人姓名  
        if(is_array($to))
        {
            foreach ($to as $t)
            {
                $mail->AddAddress($t);    //添加收件人1（地址，昵称）  
            }
        }
        else
        {
            $mail->AddAddress($to);
        }
        
        
        
        $mail->IsHTML(true); //支持html格式内容  
       // $mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片  
        $mail->Body = $body;

        //$mail->Send();
        
        //发送  
        if(!$mail->Send()) {  
            $this->log('withdraw_email.log',$mail->ErrorInfo);
        } 
    }
    
    function log($file,$msg,$split=2)
    {
        $GLOBALS['LOG'] = array(
         'level' => 0x07,
         'logfile' => '/tmp/'. $file,
         'split' => $split,
        );

        $str= "\r\n\r\n".$msg."\r\n";
        $str.="\r\n=============================================================================================\r\n";
        Logger::notice($str);
    }
    
    
}
