<meta charset="utf-8">
<?php
 require_once('head.php');
 require_once("left.php");
 require_once("middle_start.php");
 require_once('phpClass/mysql_php.php');
 require_once('phpClass/fenye_php.php');
  $mysql=new mysqlSupport;
  $mysql->mysql_con();  //链接服务器
  $mysql->mysql_con_db();//链接数据库
  $page=new PageSupport(100); 
  if(isset($_GET['current_page']))
   {
   $page->current_page=intval($_GET['current_page']);
   }
  else{$page->current_page=1;}
  $page->sql="SELECT * FROM nyproblem";
  $page->read_data();
  $res=$page->result;
  $page->standard_navigate();
  echo <<<Eof
	<div id="problem_serch">
	  <form id="f1" action="problem.php?ac=on1" method="GET">
		按来源和题号搜索: 
	  来源<input type="text" name="pro_source" id="pro_source"/>
	  题号<input type="text" name="pro_id" id="pro_id" />
	  <input type="submit" value="搜索" name="btn1" id="btn1"/>
	  </form>
	 <!--<form id="f2" action="problem.php?ac=on2" method="GET">
       按标题搜索:<input type="text" name="pro_title" id="pro_title" />
	   <input type="submit" value="搜索" name="bnt2" id="btn2"/> 
      </form> -->
    </div>
Eof;
  echo "<ul class='problemset_nav'>";
  echo "<li>通过</li>";
  echo "<li>来源</li>";
  echo "<li>题号</li>";
  echo "<li>标题</li>";
  echo "<li>通过率</li>";
  echo "</ul>";

  for($i=0;$i<count($res);$i++){
	  if($res[$i]['problename']!="该题目尚未公开"){
    echo "<ul class='problemset_nav'>";
	/****************************************************************/
	if(isset($_SESSION['userid'])){ //判断用户是否登录,在登录状态下显示用户的做题状况
	  $oj=$res[$i]['oj'];
	  $problemid=$res[$i]['problemid'];
      $userid=$_SESSION['userid'];
	  $SQL="SELECT * FROM run WHERE oj='$oj' AND problemid='$problemid' AND userid='$userid'";
	  $res01=mysql_query($SQL);
	  $arr01=mysql_fetch_array($res01);
	  if($arr01['userid']){ //判断用户是否做过该题,即运行结果表里面是否有该用户的做题记录
	   $SQL2="SELECT * FROM run 
		       WHERE userid='$userid' 
		       AND oj='$oj'
		       AND problemid='$problemid'
		       AND result LIKE 'A%'";
	   $res02=mysql_query($SQL2);
	   $arr2=mysql_fetch_array($res02);
	   if($arr2['userid']){ //判断用户是否AC此题,即如果run表中存在用户的AC结果的记录,显示已AC
	    echo "<li><img src='http://acm.nyist.net/JudgeOnline/files/1.png' /></li>";
	   }else{ //没有AC记录,说明用户正在攻克
	    echo "<li><img src='http://acm.nyist.net/JudgeOnline/files/2.png' /></li>";
	   }
	  }else{echo "<li></li>";} //登录但未做过此题不显示做题情况

	}else{echo "<li></li>";} //未登录不显示用户做题情况
	/******************************************************************/
	echo "<li>".$res[$i]['oj']."</li>";
	echo "<li>".$res[$i]['problemid']."</li>";
	echo "<li id='problemset_problemname'><a href='problem.php?pro_source=".$res[$i]['oj']."&pro_id=".$res[$i]['problemid']."'>".$res[$i]['problename']."</a></li>";
	echo "</ul>";
  }
  }
 $page->standard_navigate();  /*又问题啊这边*/
/* echo <<<Eof
  <script type="text/javascript" src="js/problemSet.js"> </script>
Eof;*/
 require_once("middle_end.php");
 require_once("right.php");
 require_once('footer.php');
?>
