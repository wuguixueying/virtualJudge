<?php
 SESSION_START();
 require_once("phpClass/mysql_php.php");
 $mysql=new mysqlSupport;
 $mysql->mysql_con(); //链接服务器
 $mysql->mysql_con_db();//链接数据库
   if(isset($_POST['userid'])&&isset($_POST['password'])){
   $userid=$_POST['userid'];
   $pw=md5($_POST['password']);
   $sql="SELECT * FROM user WHERE userid='$userid' AND password='$pw'";
   $res=mysql_query($sql);
   $arr=mysql_fetch_array($res);
   if($arr['email_tag']==1){
       date_default_timezone_set('Asia/Shanghai');
       $time=date('Y-m-d H:i:s',time());
	   $sql="UPDATE user SET login_time='$time' WHERE userid='$userid'";
	   @mysql_query($sql);
	   $_SESSION['userid']=$userid;
      echo "verify";
   }else{
     echo "noVerify";
   }
  }else{
    echo "error";
  }
 



?>
