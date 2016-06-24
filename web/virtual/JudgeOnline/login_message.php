<meta charset="utf-8">
<?php
require_once('head.php');
require_once("middle_start.php");
     if(isset($_GET['currentURL'])){
      $url=$_GET['currentURL'];
     }else if(isset($_GET['referURL'])){
      $url=$_GET['referURL'];
	  }
     

      $loginURL="http://".$_SERVER['SERVER_NAME']."/virtual/JudgeOnline/login.php";//登录页面的url
      $registerURL="http://".$_SERVER['SERVER_NAME']."/virtual/JudgeOnline/register.php";//注册页面的url
     if($url==$loginURL||$url==$registerURL||$url=="1"){ //当页面是从login.php或register.php跳转过来时,应跳转到个人页面,当$url的值为1时,链接到login.php页面的上一个页面为激活页面
      $url="personal.php?userid=".urlencode($_SESSION['userid']);
	  echo <<<Eof
     <div id="login_message">
     登录成功,正跳转至个人主页...
     </div>
Eof;
      header("Refresh:1;$url");
     }else{
	   echo <<<Eof
     <div id="login_message">
     登录成功,3秒后跳转至登录前页面...
     </div>
Eof;
       header("Refresh:3;$url");
	 }
require_once("middle_end.php");
require_once("right.php");
require_once('footer.php');
?>
