<?php
require_once('head.php');
require_once("left.php");
require_once("middle_start.php");
require_once('phpmailer/class.phpmailer.php');
require_once "phpClass/mysql_php.php";
//验证码验证
if(!empty($_GET['action'])){
	if($_GET['action']=='reg'){
		if(empty($_POST['yzm'])){
			echo "<script>alert('验证码不能为空');history.back();</script>";exit;
		}
		if($_POST['yzm']!=$_SESSION['code']){
			echo "<script>alert('验证码不正确');history.back();</script>";exit;
		}
	}
}
if(!empty($_POST)){
	$mysql=new mysqlSupport();
	$mysql->mysql_con();//数据库连接        
	$mysql->mysql_con_db();//数据库打开

	$userid=$_POST['userid'];
	$password=md5($_POST['pw']);
	$email=$_POST['email'];
    date_default_timezone_set('Asia/Shanghai');
    $time=date('Y-m-d H:i:s',time());
	$register_time=$time;
	$sql="insert into user(userid,password,email,register_time) values('{$userid}','{$password}','{$email}','{$register_time}')";
	$query=mysql_query($sql);
	if($query){
		echo "<script>alert('注册成功');</script>";
	}

	$userid=$_POST['userid'];
	if(isset($userid)){
		$sign=time();	
	}
	setcookie("oj_userid",$userid,time()+86400);
	setcookie("oj_sign",$sign,time()+86400);
	$mail = new PHPMailer(); //实例化
	$mail->IsSMTP(); // 启用SMTP
	$mail->Host = "smtp.sina.com"; //SMTP服务器 以163邮箱为例子
	$mail->Port = 25;  //邮件发送端口
	$mail->SMTPAuth   = true;  //启用SMTP认证
	$mail->CharSet  = "UTF-8"; //字符集
	$mail->Encoding = "base64"; //编码方式
	$mail->Username = "lbxz2605416379@sina.com";  //你的邮箱
	$mail->Password = "123456789";  //你的密码
	$mail->Subject = "你好"; //邮件标题
	$mail->From = "lbxz2605416379@sina.com";  //发件人地址（也就是你的邮箱）
	$mail->FromName = "雍立东";  //发件人姓名  中文名在垃圾箱   英文名正常
	$address = $_POST['email'];//收件人email
	$mail->AddAddress($address, "亲");//添加收件人（地址，昵称）
	$mail->IsHTML(true); //支持html格式内容
	$mail->Body ="<b>{$userid},您好：</b><br />
		感谢您加入OJ，请点击下面链接激活您的OJ帐号<br>
		<a href='http://121.42.158.99/virtual/JudgeOnline/jihuo.php?userid={$userid}&sign={$sign}' target='_blank'>
		http://121.42.158.99/virtual/JudgeOnline/register_verify.php?userid={$userid};sign={$sign}
</a><br><br>
	请登录后管理您的网站：<br>
	登录地址：<a href='http://121.42.158.99/virtual/JudgeOnline/login.php' target='_blank'>
	http://121.42.158.99/virtual/JudgeOnline/login.php
	</a><br>
	OJ帐号：{$userid}
<br>
	"; 
	if(isset($userid)){
		if(!$mail->Send()) {
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo "<p>Message sent!恭喜，<b><span style='color:red;'>激活邮件</span></b>发送成功！</p>";
		}
	}
}
?>
<style>
h1{
	text-align:center;
}
p{
	text-align:center;
	border:1px solid black;
}
</style>
<div>
<?php if(!empty($_POST)){ ?>
<p>
<h1><span style="color:red;"><?php echo $_POST['userid'] ?></span>,欢迎您注册oj系统！请到邮箱(<?php echo $_POST['email'] ?>)激活账号！。</h1>
<h1>[&nbsp;&nbsp;提示：如果收件箱里面看不到邮件！则邮件可能到了邮箱里的<span style="color:red;">垃圾箱</span>&nbsp;&nbsp;]</h1>
</p>
<?php }else{ ?>
<p>
<h1><span style="color:red;"></span>,欢迎大家[ 注册 ]OJ账号！和小伙伴们一起决战题海！。</h1>
</p>
<?php } 
require_once("middle_end.php");
require_once("right.php");
require_once('footer.php');
?>
