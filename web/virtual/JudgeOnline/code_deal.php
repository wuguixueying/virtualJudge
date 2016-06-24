<?php
  session_start();
  require_once('phpClass/mysql_php.php');
	$db=new mysqlSupport;
	$db->mysql_con();
	$db->mysql_con_db();
	$oj=$_POST['oj'];
	$problemid=$_POST['problemid'];
	$title=$_POST['problemname'];
	$language=$_POST['lan'];
	$code=$_POST['code'];
	$code=addcslashes($code,"\\");
	$code=addcslashes($code,"'");
	date_default_timezone_set('Asia/Shanghai');
	$time=date('Y-m-d H:i:s',time());
	$user=$_SESSION['userid'];
    $sql="INSERT INTO run(runid,oj,problemid,problename,language,submit_time,submit_code,userid) VALUES('','$oj','$problemid','$title','$language','$time','$code','$user')";//将交题信息插入到run表
    $ret=mysql_query($sql);
		 if($ret){ //插入成功时
			$sql="SELECT LAST_INSERT_ID()";
			$r=mysql_query($sql);
			$arr=mysql_fetch_array($r);
			$str=$arr[0]; //获取当前最大运行号
//将运行号写入管道,规定写入管道的数据为字符串形式,并加\n结尾
  if(!(is_string($str))){ //判断$str是否为字符串
      $str=strval($str); //非字符串类型转换字符串类型
  }
    $str=$str."\n";
	$fileURL="/home/adc/virtualJudge/fifo/runing"; //有名管道路径
	$file=fopen($fileURL,"w"); //以只写的方式打开管道
	$zijie=fwrite($file,$str);
    fclose($file);
	if($zijie){
	  echo "1";
	}else{echo "0";}
  } 
else{ //插入不成功时
    echo "0";
  }
 
?>
