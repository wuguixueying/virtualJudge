<?php
 SESSION_START();
 require_once('phpClass/mysql_php.php');
 $user=$_POST['userid'];
 $password=$_POST['pw'];
 $email=$_POST['email'];
 $mysql=new mysqlSupport;
 $mysql->mysql_con();
 $mysql->mysql_con_db();
 if(isset($user)&&isset($password)&&isset($email)){
   $sql="INSERT INTO user(userid,password,email) VALUES('$user','$password','$email')";
   $res=mysql_query($sql);
   if($res){
	 $sql="SELECT userid FROM user WHERE userid='$user'";
	 $re=$mysql->mysql_query_result($sql);
	  $log_user['userid']=$re[0]["userid"] ;
	  $_SESSION['userid']=$log_user['userid'];
	  $ret=json_encode($log_user);
	  echo $ret;
   }
 } 
?>

