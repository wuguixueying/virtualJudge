<?php
  require_once('head.php');
  session_destroy();
  if(isset( $_SERVER['HTTP_REFERER'])){
    $referer_url=$_SERVER['HTTP_REFERER'];
}
  else{
   $referer_url='home.php';
  }
//不是所有的用户代理(浏览器)都会设置HTTP_REFERE这个变量
//而且还可以手工修改它的值,因此这个变量不总是真实正确的.
  header("location:$referer_url");
  require_once('footer.php');
?>
