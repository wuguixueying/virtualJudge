<?php
 SESSION_START();
 require_once('phpClass/mysql_php.php');
 $m=new mysqlSupport;
 $m->mysql_con();
 $m->mysql_con_db();
if(isset($_GET['log_user'])&&isset($_GET['log_pw'])) 
 { 
       $deal_user=$_GET['log_user'];//得到登录用户值
       $deal_pw=$_GET['log_pw'];//得到用户密码
       $sql="SELECT * FROM user WHERE userid='$deal_user'";
       $ret=@mysql_query($sql);
	   $user=mysql_fetch_array($ret);
	   if($user['userid']==$deal_user){//验证是否存在用户
       $sql="SELECT * FROM user WHERE userid='$deal_user' AND password='$deal_pw'"; 
	   $ret=@mysql_query($sql);
	   $pw=mysql_fetch_array($ret);
	  if($pw['userid']==$deal_user){ //验证密码是否正确
	     $sql="SELECT * FROM user WHERE userid='$deal_user' AND password='$deal_pw' AND email_tag=1";
		     $ret=@mysql_query($sql);
			 $email=mysql_fetch_array($ret);
		           if($email['userid']==$deal_user){ //验证邮箱是否激活
                   date_default_timezone_set('Asia/Shanghai');
                   $time=date('Y-m-d H:i:s',time());
	               $sql="UPDATE user SET login_time='$time' WHERE userid='$deal_user'";//更新最新登录时间
	               @mysql_query($sql);
			           $_SESSION['userid']=$deal_user;
					   echo "loginSucceed";
		             	}else{echo "emailNoVerify";}
	            }else{echo "passwordIncorret";}
   } else{echo "userNoExist";}
    
}

?>
