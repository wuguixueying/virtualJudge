<?php  
header('Content-Type:text/html;charset=utf-8');
require_once('phpClass/mysql_php.php');
$mysql=new mysqlSupport;
$mysql->mysql_con();
$mysql->mysql_con_db();

//数组格式化函数
function show_bug($msg){
	echo "<pre>";
	print_r($msg);
	echo "</pre>";
}
if(empty($_GET['action'])){
	if(!empty($_POST)){
		$item_id=$_POST['item_id'];//题目id
		$user_id=$_POST['user_id'];//用户id
		$content=$_POST['content'];//留言内容
		$time=time();//时间
		$sql="insert into message(item_id,user_id,content,time) values('{$item_id}','{$user_id}','{$content}','{$time}')";
		$query=mysql_query($sql);
		if($query){
			echo "<script>alert('发布成功');location.href='talking.php?problemid=$item_id'</script>";
		}
	}
}
//回复在这
if($_GET['action']=='reply'){
	if(!empty($_POST)){
		$message_id=$_POST['message_id'];//消息id
		$reply_id=$_POST['reply_id'];//用户id
		$content=$_POST['content'];//回复内容
		$time=time();//时间
		$sql="insert into reply(message_id,reply_id,content,time) values('{$message_id}','{$reply_id}','{$content}','{$time}')";
		$query=mysql_query($sql);
		if($query){
			echo "<script>alert('回复成功');history.back();</script>";
		}
	}
}
?>
