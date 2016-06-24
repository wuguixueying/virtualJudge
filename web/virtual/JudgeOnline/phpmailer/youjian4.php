<?php
header('Content-Type:text/html;charset=utf-8');
require_once('class.phpmailer.php');

$mail = new PHPMailer(); //实例化
$mail->IsSMTP(); // 启用SMTP
$mail->Host = "smtp.sina.com"; //SMTP服务器 以163邮箱为例子
$mail->Port = 25;  //邮件发送端口
$mail->SMTPAuth   = true;  //启用SMTP认证

$mail->CharSet  = "UTF-8"; //字符集
$mail->Encoding = "base64"; //编码方式

$mail->Username = "yld15236018201@sina.com";  //你的邮箱
$mail->Password = "yld5613643";  //你的密码
$mail->Subject = "你好"; //邮件标题

$mail->From = "yld15236018201@sina.com";  //发件人地址（也就是你的邮箱）
$mail->FromName = "雍立东";  //发件人姓名  中文名在垃圾箱   英文名正常

$address = "1647398574@qq.com";//收件人email
$mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

//$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
$mail->IsHTML(true); //支持html格式内容
//$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
$hehe='123';
$mail->Body = '
<b>'.$hehe.'，您好：</b><br>
感谢您加入OJ，请点击下面链接激活您的OJ帐号<br>
<a href="http://www.faisco.cn/edm.jsp?d=102006&amp;s=10&amp;a=9417491&amp" target="_blank">http://www.faisco.cn/act.jsp?acct=lbxzfr2016&amp;sign=ckL6HtlEAs-HkmbixLGbJ</a><br><br>
请登录后管理您的网站：<br>
登录地址：<a href="http://121.42.158.99/virtual/JudgeOnline/login.php" target="_blank">http://121.42.158.99/virtual/JudgeOnline/login.php</a><br>
OJ帐号：lbxzfr2016<br>
'; 
if(!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  } else {
  echo "Message sent!恭喜，邮件发送成功！";
  }
?>