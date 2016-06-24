<?php
 SESSION_START();
 /*
  *首页和比赛模块暂时不写
  */
 $list_nav=array();
// $list_nav["home"]="home.php";
 $list_nav["problemList"]="problemSet.php";
 $list_nav["runResult"]="runResult.php";
 $list_nav["ranking"]="ranking.php";
// $list_nav["competition"]="competition.php";
// $list_nav["addCompetition"]="addCompetition.php";
//数组格式化函数
function show_bug($msg){
	echo "<pre>";
	print_r($msg);
	echo "</pre>";
}
date_default_timezone_set('PRC');//其中PRC为“中华人民共和国”

?>
<!DOCTYPE HTML>
<html>
  <head>
     <meta charset="utf-8">
	 <title>VirtualJudgeOnline</title>
     <link href="css/head.css" rel="stylesheet" type="text/css"/>
	 <!--高亮显示代码插件所要用到的样式-->
     <link href="syntaxhighlighter_3.0.83/styles/shCoreEclipse.css" rel="stylesheet" type="text/css"/>
     <!-- <link href="syntaxhighlighter_3.0.83/styles/shCoreEclipse.css" rel="stylesheet" type="text/css"/>-->
    <!--  -->
     <link href="css/problem.css" rel="stylesheet" type="text/css"/>
     <link href="css/problemSet.css" rel="stylesheet" type="text/css"/>
     <link href="css/register.css" rel="stylesheet" type="text/css"/>
     <link href="css/login.css" rel="stylesheet" type="text/css"/>
     <link href="css/home.css" rel="stylesheet" type="text/css"/>
	 <script type="text/javascript" src="js/xhr_function.js"></script>
     <script type="text/javascript" src="js/get_addURLParam_function.js"></script>
     <script type="text/javascript" src="js/serialize_function.js"></script>
     <script type="text/javascript" src="js/md5.js"></script>
	 <script type="text/javascript" src="js/jquery.js"></script>
	 <!--引入计算字符在内存中所占字节数文件-->
     <script type="text/javascript" src="js/sizeof.js"></script>
	 <!--高亮显示代码需要用到的js文件-->
     <script type="text/javascript" src="syntaxhighlighter_3.0.83/scripts/shCore.js"></script>
     <!--初始化高亮显示插件的必须代码,必须放到head中,引入文件之后 -->
     <script type="text/javascript">SyntaxHighlighter.all();</script>
     <!--消除右上角广告-->
     <script type="text/javascript">SyntaxHighlighter.defaults['toolbar']=false;</script>
     <script type="text/javascript" src="syntaxhighlighter_3.0.83/scripts/shBrushJava.js"></script>
     <script type="text/javascript" src="syntaxhighlighter_3.0.83/scripts/shBrushCpp.js"></script>
     <script type="text/javascript" src="syntaxhighlighter_3.0.83/scripts/shBrushCSharp.js"></script>
 </head>
  <body scroll='no'>
    <div id="nav">
    <!--<span id="img1"><img src="img/logo.png"></span>-->
	  <ul id="header">
       <!--  <li><a href=' --><?php// echo $list_nav["home"]; ?><!--'>首页</a></li>-->
		 <li><a href='<?php echo $list_nav["problemList"];?>'>题目列表</a></li>
		 <li><a href='<?php echo $list_nav["runResult"];?>'>运行结果</a></li>
         <li><a href='<?php echo $list_nav["ranking"];?>'>排行榜</a></li>
		<!-- <li><a href='--><?php// echo $list_nav["competition"];?><!--'>比赛</a></li>-->
		<!-- <li><a href='--><?php //echo $list_nav["addCompetition"];?><!--'>添加比赛</a></li>-->
	  </ul>
	  <div id="log_reg" style="display:inline;">
	      <?php
             if(isset($_SESSION['userid'])){			 
        echo '<p style="margin:30px;float:right"><a id="person"href="personal.php?userid='.urlencode($_SESSION['userid']).'">'.$_SESSION['userid'].'</a>&nbsp;';        
		echo "<a id='log_out' href='logout.php'>退出</a></p>";
			 }
		    else{
			  echo '<p style="margin:30px;float:right"><a href="login.php" id="log">登入</a>';
			  echo '<a href="register.php" id="reg">注册</a></p>';
			}
		   ?>
	  </div> <!--注册登录模块,用户未登录时显示登录注册链接,用户登入时显示用户链接-->
	</div> <!-- end of nav -->
    <div id="main">
