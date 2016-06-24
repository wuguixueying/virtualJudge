<?php
header('Content-Type:text/html;charset=utf-8');
require_once "phpClass/mysql_php.php";
function show_bug($msg){
	echo "<pre>";
	print_r($msg);
	echo "</pre>";
}

$user_name = $_POST['user_name'];
$pw_name = md5($_POST['pw_name']);

//数据库操作
$mysql=new mysqlSupport();
$mysql->mysql_con();//数据库连接		
$mysql->mysql_con_db();//数据库打开
$sql="select userid,password from user where userid='{$user_name}'";
$query=mysql_query($sql);
$user_array=array();
$row=mysql_fetch_row($query);
$user_pw_name=$row['1'];
	if(!empty($pw_name)){
		if($user_pw_name=="$pw_name"){
			echo "密码正确";exit;
		}else{
			echo "";
		}
	}else{
		echo "密码错误";exit;
	}
?>

