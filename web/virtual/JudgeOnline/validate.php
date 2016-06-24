<?php
header('Content-Type:text/html;charset=utf-8');
require_once "phpClass/mysql_php.php";
function show_bug($msg){
	echo "<pre>";
	print_r($msg);
	echo "</pre>";
}

$user_name = $_POST['user_name'];
//$pw_val = $_POST['pw_val'];

//数据库操作
$mysql=new mysqlSupport();
$mysql->mysql_con();//数据库连接		
$mysql->mysql_con_db();//数据库打开
$sql="select userid,password from user";
$query=mysql_query($sql);
$user_array=array();
while($row=mysql_fetch_row($query)){
	$user_array[]=$row['0'];
	
}
foreach ($user_array as $key=>$val) {
	if(!empty($user_name)){
		if($val=="$user_name"){
			echo "用户名正确";exit;
		}else{
			echo "";
		}
	}else{
		echo "用户名不存在";exit;
	}
}
// if($row['1']==md5($pw_val)){
// 	echo "密码正确";exit;
// }else{
// 	echo "密码错误";exit;
// }
?>

