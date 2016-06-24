<?php
header('Content-Type:text/html;charset=utf-8');
require_once "phpClass/mysql_php.php";
function show_bug($msg){
	echo "<pre>";
	print_r($msg);
	echo "</pre>";
} 
   if(isset($_GET)){
	    $mysql=new mysqlSupport();
   if($_GET['userid']==$_COOKIE['oj_userid']&&$_GET['sign']==$_COOKIE['oj_sign']){
		$mysql->mysql_con();//数据库连接		
		$mysql->mysql_con_db();//数据库打开
		$sql="update user set email_tag=1 where userid='{$_GET[userid]}'";
		$query=mysql_query($sql);
		if($query){
			echo "<script>alert('邮箱激活成功!');</script>";
			header("Refresh:0,'login.php'");
		}
	}
}
?>
