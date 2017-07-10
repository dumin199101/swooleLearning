<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/28
 * Time: 10:09
 */
class SendMail {
    private $_mail;
    public $_mail_config;

    public  function __construct(){
        //导入PHPMailer
        include_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';
        $this->_mail =  new \PHPMailer();
        $this->_mail_config = array(
            'HOST'=>'smtp.163.com',
            'SENDER'=>'dumin199101@163.com',
            'PWD'=>'dumin199101', //注意163邮箱使用的是客户端授权密码，非邮箱登录密码
        );
    }

    /**
     * 发送邮件
     * @param  [type] $toEmail  [收件人邮箱]
     * @param  [type] $subject  [邮箱标题]
     * @param  [type] $content  [邮箱正文]
     * @return [boolean] $flag  [发送状态]
     */
    public  function sendMail($toEmail,$subject,$content){
        $this->_mail->isSMTP();   // 设定使用SMTP服务
        $this->_mail->Host = $this->_mail_config['HOST']; // SMTP 服务器
        $this->_mail->SMTPAuth = true;  // 启用 SMTP 验证功能
        $this->_mail->SMTPSecure = "ssl"; //开启ssl认证
        $this->_mail->Port       = 465;          // SMTP服务器的端口号,默认465
        $this->_mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
        $this->_mail->Username = $this->_mail_config['SENDER'];  // SMTP服务器用户名
        $this->_mail->Password = $this->_mail_config['PWD'];  // SMTP服务器密码
        //发件人
        $this->_mail->setFrom($this->_mail_config['SENDER'], $this->_mail_config['SENDER']);  // 设置发件人地址和名称
        //收件人
        $this->_mail->addAddress($toEmail,$toEmail); // 设置收件人地址和名称
        $this->_mail->isHTML(true);  //HTML格式
        $this->_mail->Subject = $subject;  // 设置邮件标题
        $this->_mail->Body    = $content;  //设置邮件内容，这里可以使HTML字符串
//        $this->_mail->SMTPDebug = 1;
//        $this->_mail->msgHTML($content);  // 设置邮件内容:可以导入HTML文件
        return $this->_mail->send();
    }

}