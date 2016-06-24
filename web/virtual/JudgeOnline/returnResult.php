<?php
 require_once('phpClass/mysql_php.php');
 $mysql=new mysqlSupport; 
 $mysql->mysql_con();     //链接服务器
 $mysql->mysql_con_db(); //链接数据库
	if(isset($_GET['runid'])){
        $r=$_GET['runid'];
    }else{
	  $r="";
	}
 $sql="SELECT * FROM run WHERE runid='$r'";
 $ret=mysql_query($sql);
 $arr=mysql_fetch_array($ret);
	if($arr['runid']){
	   $return[0]=$arr['result']; //取得结果
	   $return[1]=$arr['time'];    //取得运行时间
	   $return[2]=$arr['memory']; //取得运行内存
	   echo json_encode($return); //将返回数组进行json编码
	  }

?>
