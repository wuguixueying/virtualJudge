<?php
 require_once('phpClass/mysql_php.php');
 $mysql=new mysqlSupport;
 $mysql->mysql_con();    //链接服务器
 $mysql->mysql_con_db(); //链接数据库
	if(isset($_GET['runid'])){
	 $runid=$_GET['runid'];
	 $sql="UPDATE run SET result='判题失败' WHERE runid='$runid'";
	 $ret=mysql_query($sql);
	  if($ret){
	    echo 1;
	  }
	}else{
	exit(0);//退出当前脚本
	}





?>
