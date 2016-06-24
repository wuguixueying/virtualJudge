<?php
  require_once('head.php');
  require_once('left.php');
  require_once('middle_start.php');
  require_once('phpClass/mysql_php.php');
   $mysql=new mysqlSupport;//实例化mysqlSupport类
   $mysql->mysql_con();//链接服务器
   $mysql->mysql_con_db();//链接数据库
   if(isset($_GET['runid'])){
      $runid=$_GET['runid'];
	  $sql="SELECT * FROM run WHERE runid='$runid'";
      $res=mysql_query($sql);
      $arr=mysql_fetch_array($res);
	    echo "<h3 id='code_title'>查看代码</h3>";
		echo "运行号:<span>".$arr['runid']."</span>用户名:<span>".$arr['userid']."</span>结果:<span>".$arr['result']."</span>";
	    $code=htmlspecialchars($arr['submit_code']); /*将预定义的字符<和>转化成html实体*/
		if($arr['language']=='C/C++'||$arr['language']==2||$arr['language']==3||$arr['language']==0||$arr['language']==1||$arr['language']==4){ //显示c/c++,pascal代码
		echo "<pre class='brush:cpp'>".$code."</pre>";
	    }else if($arr['language']=='java'||$arr['language']==5){ //显示java代码
		echo "<pre class='brush:java'>".$code."</pre>";
		}else if($arr['language']==6){ //显示C#代码
			echo "<pre class='brush:csharp'>".$code."</pre>";	
		} 
		/*
		*更多语言的高亮显示在这拓展
		*/
 }else{
     echo <<<Eof
       <div id='codeError'>
	      出错了
       </div>
Eof;
  
  }
 require_once('middle_end.php');
 require_once('footer.php');
?>
