<!--
杭电显示runtime_error有bug,$result中的空格和case中空格不相等
-->
<?php
 require_once('head.php');
 require_once('left.php');
 require_once('middle_start.php');
 require_once("phpClass/mysql_php.php");
  $mysql=new mysqlSupport;
  $mysql->mysql_con(); //链接服务器
  $mysql->mysql_con_db(); //链接数据库
  /*
   *函数名:remoce_str
   *说明:去除字符串中的(和)特殊字符
   *@param $str 需要去除特殊字符的字符串
   *@return $ret 去除特殊字符后的字符串
   */
  function remove_str($str){
    for($i=0;$i<strlen($str);$i++){
	   if($str[$i]=="("||$str[$i]==")"){
		   continue;
	   }
	   $ret.=$str[$i];
	  	}
       return $ret;  
  }
  if(isset($_GET['runid'])){
    $runid=$_GET['runid'];
	$sql0="SELECT * FROM run WHERE runid='$runid'";
	$res=mysql_query($sql0);
	$arr=mysql_fetch_array($res);
	$result=$arr['result'];
   $result=strip_tags($arr['result']); //去除字符串中的html标签
  $result=remove_str($result); //去除字符串中特殊字符
   /***********************************/
  // echo $result;
  /* echo "<br/>";
   echo strlen($result)."<br/>";
   $str="Runtime Error ACCESS_VIOLATION";
   echo strlen($str);
   echo "<br/>";
   for($i=0,$len=strlen($str);$i<$len;$i++){
      if($result[$i]!=$str[$i]){
	    echo $i;
	  }
   }*/
   /***********************************/
      echo <<<Eof
		<div id="error">
		 <span>运行时间:{$arr['submit_time']}</span>
		 <span>用户:{$arr['userid']}</span>
Eof;

	 if($result=="判题失败"){
   	   echo <<<Eof
        <br/><p style="color:red">服务器繁忙,请稍后交题!</p>	   
Eof;

	 }else if($result=="CompileError"||$result=="CompilationError"){ 
	   echo <<<Eof
	   <pre class='message'>{$arr['ceMessage']}</pre>
Eof;
	 }else{
	     if($arr['oj']=='nyist'){
		   switch($result){
		     case "WrongAnswer":
		        $sql="SELECT WA FROM nyistResult";
				break;
			 case "RuntimeError":
				$sql="SELECT RE FROM nyistResult";
				break;
			 case "TimeLimitExceeded":
				$sql="SELECT TLE FROM nyistResult";
				break;
			 case "MemoryLimitExceeded":
                $sql="SELECT MLE FROM nyistResult";
				break;
			 case "OutputLimitExceeded":
                $sql="SELECT OLE FROM nyistResult";
				break;
			 case "SystemError":
                 $sql="SELECT SE FROM nyistResult";
		  }
		 }else if($arr['oj']=='hdu'){
		 /*hdu在这写代码*/
               switch ($result){
                case "Wrong Answer":
                  $sql="SELECT WA FROM hduResult";
                  break;
                case "Runtime Error":
                  $sql="SELECT RE FROM hduResult";
                  break;
                case "Runtime Error ACCESS_VIOLATION":
                  $sql="SELECT RE_AV FROM hduResult";
                  break;
                case "Runtime Error ARRAY_BOUNDS_EXCEEDED":
                  $sql="SELECT RE_ABE FROM hduResult";
                  break;
                case "Runtime Error FLOAT_DENORMAL_OPERAND":
                  $sql="SELECT RE_FDO FROM hduResult";
                  break;
                case "Runtime Error FLOAT_DIVIDE_BY_ZERO":
                  $sql="SELECT RE_FDBZ FROM hduResult";
                  break;
                case "Runtime Error FLOAT_OVERFLOW":
                   $sql="SELECT RE_FO FROM hduResult";
                   break;
                case "Runtime Error FLOAT_UNDERFLOW":
                   $sql="SELECT RE_FU FROM hduResult";
                   break;
                case "Runtime Error INTEGER_DIVIDE_BY_ZERO":
                   $sql="SELECT RE_IDBZ FROM hduResult";
                   break;
                case "Runtime Error INTEGER_OVERFLOW":
                   $sql="SELECT RE_IO FROM hduResult";
                   break;
                case "Runtime Error STACK_OVERFLOW":
                   $sql="SELECT RE_SO FROM hduResult";
                   break;
                case "Time Limit Exceeded":
                   $sql="SELECT TLE FROM hduResult";
                   break;
                case "Memory Limit Exceeded":
                   $sql="SELECT MLE FROM hduResult";
                   break;
                case "Output Limit Exceeded":
                   $sql="SELECT OLE FROM hduResult";
                   break;
                case "System Error":
                   $sql="SELECT SE FROM hduResult";
                   break;
                case "Out Of Contest Time":
                   $sql="SELECT OOCT FROM hduResult";
				   break;
			    case "Queuing":
                   $sql="SELECT Q FROM hduResult";
				   break;
                case "Compiling":
                   $sql="SELECT C FROM hduResult";
				   break;
                case "Running":
                   $sql="SELECT R FROM hduResult";
			 }
		 }
	  $res2=mysql_query($sql);
	  $arr2=mysql_fetch_array($res2);
	 // print_r($arr2);
	  echo "<p class='message'>".$arr2[0]."<p>";
	 }
  }else{
   exit(0); //退出当前脚本
  }
?>
<style type="text/css">
.message{
border:1px solid gray;
font-size:14px;
}
</style>
<?php
 require_once("right.php");
 require_once("middle_end.php");
 require_once("footer.php");
?>
