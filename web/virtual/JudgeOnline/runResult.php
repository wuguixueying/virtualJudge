<meta charset="utf-8">
<?php
 require_once('head.php');
 require_once('phpClass/mysql_php.php');
 require_once('phpClass/fenye_php.php');
  $mysql=new mysqlSupport;
  $mysql->mysql_con(); //链接服务器
  $mysql->mysql_con_db(); //链接数据库
  $page=new PageSupport(25); //每个页面最大的记录数
     if(isset($_GET['current_page'])) //获取到当前页面的值
      {     $page->current_page=intval($_GET['current_page']);
     }else{ $page->current_page=1;}
	   
    if(isset($_GET['userid'])&&isset($_GET['acAll'])){//查看个人的所有通过运行
			$per_u=$_GET['userid'];
			$url=$page->addURLParam($_SERVER['PHP_SELF'],"userid",$per_u);
			$url=$page->addURLParam($url,"acAll","on");
			$page->sql="SELECT * FROM run WHERE userid='$per_u' AND result LIKE 'A%' ORDER BY runid DESC";
	}else if(isset($_GET['userid'])&&isset($_GET['runAll'])){ //查看个人的所有运行
	        $per_u=$_GET['userid'];
		    $page->sql="SELECT * FROM run WHERE userid='$per_u' ORDER BY runid DESC";
    	    $url=$page->addURLParam($_SERVER['PHP_SELF'],"userid",$per_u);
	        $url=$page->addURLParam($url,"runAll","on");
	}else if(isset($_GET['pro_source'])&&isset($_GET['pro_id'])){ //查看该题的所有通过运行
	        $oj1=$_GET['pro_source'];
	        $id1=$_GET['pro_id'];
	        $page->sql="SELECT * FROM run WHERE oj='$oj1' AND problemid='$id1' ORDER BY runid DESC";
            $url=$page->addURLParam($_SERVER['PHP_SELF'],"pro_source",$oj1);
	        $url=$page->addURLParam($url,"pro_id",$id1);
			require_once('problem_nav.php');
	}else{
            $page->sql="SELECT * FROM run ORDER BY runid DESC"; //查看所有运行
	        $url=$_SERVER['PHP_SELF'];
	}
  $page->read_data();
  $res=$page->result;
  $page->URL=$url;
?>
  <div id="run_content">
<?php
  $page->standard_navigate();
?>
 <div id="run_nav">
   <ul class="run_nav_class">
    <li class=run_runid>运行号</li>
	<li class=run_user>用户</li>
	<li class=run_oj>来源</li>
	<li class="run_proid">题号</li>
	<li class="run_pro">题目</li>
	<li class="run_result">结果</li>
    <li class="run_time">时间</li>
	<li class="run_memory">内存</li>
	<li class="run_language">语言</li>
    <li class="run_subtime">提交时间</li>
  </ul>
 </div>
<?php
    for($i=0;$i<count($res);$i++){
	   echo '<ul class="run_recode">';
	      if(isset($_SESSION['userid'])){
	     //用户登录时根据权限判断是否显示运行号超链接
			  $user=$_SESSION['userid']; //当前登录的用户
			  $poj=$res[$i]['oj']; //当前运行结果记录的oj
			  $pid=$res[$i]['problemid'];//当前运行结果的题号
		      $sql="SELECT * FROM run WHERE userid='$user' AND oj='$poj' AND problemid='$pid'";
		      $user_res=mysql_query($sql);
		      $user_arr=mysql_fetch_array($user_res);
		      if($user_arr['runid']){ //如果用户做过此题
		          if($res[$i]['userid']==$user){ //当前运行记录是否属于该登录用户,已登录用户对自己所有的运行记录有权限查看
					  echo <<<EOF
					  <li class="run_runid"><a id="{$i}runid" href="code.php?runid={$res[$i]['runid']}">{$res[$i]['runid']}</a></li>
EOF;
				  }else{ //记录不属于已登录用户
                  $sql="SELECT * FROM run WHERE userid='$user' AND oj='$poj' AND problemid='$pid' AND result LIKE 'A%'";
				 $ureturn=mysql_query($sql);
				 $rarr=mysql_fetch_array($ureturn);
				 if($rarr['runid']){ //如果登录用户AC了此题,有权限查看其它用户任何运行结果的代码
				echo <<<Eof
				 <li class="run_runid"><a id="{$i}runid" href="code.php?runid={$res[$i]['runid']}">{$res[$i]['runid']}</a></li>
Eof;
				 }else{ //如果已登录用户未AC此题,无权查看其它用户任何运行代码
				echo <<<Eof
				 <li class="run_runid" id="{$i}runid">{$res[$i]['runid']}</li>
Eof;

				 }
				  }
		      }else{//如果用户未做过此题,运行号显示为不可链接
              	echo <<<Eof
				 <li class="run_runid" id="{$i}runid">{$res[$i]['runid']}</li>
Eof;
		   }
	   }
	    else{ //用户未登录时,运行号超链接不可用
		      	echo <<<Eof
				 <li class="run_runid" id="{$i}runid">{$res[$i]['runid']}</li>
Eof;
	   } //以下字段的显示权限对所有用户都相同(包括未登录的用户)
	         echo '<li class="run_user"><a href="personal.php?userid='.urlencode($res[$i]['userid']).'">'.$res[$i]['userid'].'</a></li>';
	   echo <<<EOF
	        <li class="run_oj">{$res[$i]['oj']}</li>
	        <li class="run_proid">{$res[$i]['problemid']}</li>
EOF;
	        
	        if($res[$i]['result']=='该题未公开'){//如果提交题时,实体oj已禁用该题,则超链接不可用
		echo '<li class="run_pro">'.$res[$i]['problename'].'</li>';			
			}else{
				echo '<li class="run_pro"><a href="problem.php?pro_source='.$res[$i]['oj'].'&pro_id='.$res[$i]['problemid'].'">'.$res[$i]['problename'].'</a></li>';	
			}
			/**************************显示结果*****************************/
	       if($res[$i]['result']==""){
		      	echo <<<Eof
				 <li class='run_result' id="{$i}">判题中...</li>
Eof;
		   }
	        else if($res[$i]['result'][0]=='A'||$res[$i]['result']=='该题未公开'){
	            	echo <<<Eof
				 <li class='run_result' id="{$i}">{$res[$i]['result']}</li>
Eof;
			}
		    else{
				echo <<<Eof
				 <li class="run_result"><a id="{$i}" href="result_tip.php?runid={$res[$i]['runid']}">{$res[$i]['result']}</a></li>
Eof;
			}
	        /********************显示时间****************/
			if($res[$i]['time']==""){
				   	echo <<<Eof
				 <li class="run_time" id="{$i}time">--</li>
Eof;

	           }else{ 
                      	echo <<<Eof
				 <li class="run_time" id="{$i}time">{$res[$i]['time']}</li>
Eof;

	             }  
	       if($res[$i]['memory']==""){
 	echo <<<Eof
				 <li class="run_memory" id="{$i}memory">--</li>
Eof;


	          }else{
                   	echo <<<Eof
				 <li class="run_memory" id="{$i}memory">{$res[$i]['memory']}</li>
Eof;
	  }        
		       if($res[$i]['oj']=='nyist'){ //nyist的语言不需要映射
                  echo <<<Eof
		        <li class="run_language">{$res[$i]['language']}</li> 
Eof;
			   }else if($res[$i]['oj']=='hdu'){ //杭电需完成语言的映射
			       switch($res[$i]['language']){
					   case 0:
						   $hdu_lan="G++";
						   break;
					   case 1:
						   $hdu_lan="GCC";
						   break;
                       case 2:
						   $hdu_lan="C++";
						   break;
					   case 3:
						   $hdu_lan="C";
						   break;
                      case 4:
						   $hdu_lan="Pascal";
						   break;
                      case 5:
					       $hdu_lan="java";
						   break;
                      case 6:
						   $hdu_lan="C#";
				   } 
				 echo <<<Eof
		        <li class="run_language">{$hdu_lan}</li> 
Eof;

			   }
	           echo <<<Eof
		                 <li class="run_subtime">{$res[$i]['submit_time']}</li>
						 </ul>
Eof;

 }

?>
</div>
<style type="text/css">
#run_content{
margin-left:80px;

}
.run_nav_class,.run_recode{
 overflow:auto;
}
.run_nav_class li,.run_recode li{
  font-size:14px;
  list-style:none;
  float:left;
  height:auto;
 /*border:0.001em solid gray;*/
}
.run_recode li{
/*border-top:1px solid gray;*/
}
.run_recode li a{
text-decoration:none;
}
.run_runid{
 width:70px;
}
.run_user{
 width:100px;
}
.run_oj,.run_proid{
width:70px;
}
.run_pro{
width:260px;
}
.run_result{
width:300px;
}
.run_time,.run_memory,.run_language{
width:50px;
}
.run_subtime{
width:150px;
}
</style>
<script type="text/javascript">
          function refreshResult(j){
		  var count=0;//记录setInterval函数调用的次数
	      var  resultVal=$('#'+j).html();
		  if(resultVal=='判题中...'){ 
          var t=window.setInterval(function(){
		//将返回结果通过jqury插入到标签中
	    //记录中result的id统一为k,runid的id统一为k+runid,time的id统一为k+time
		//memory的字段统一为k+memory,其中k是li标签在ul中出现的次序
		  var  ri=$("#"+j+"runid").html();//取得运行号
	      count+=1; //调用次数加1
		if(count==120){ //如果经过一分钟还在刷新结果,则判为判题失败,并更新数据库的字段为“判题失败”
		 $("#"+j).html("判题失败"); //给运行结果赋值
		 $("#"+j+"time").html("--");//给时间赋值
		 $("#"+j+"memory").html("--");//给内存赋值
		 window.clearInterval(t); //移除定时器
		 var URL="updateResult.php?runid="+ri;
		 $.get(URL,function(ret){
			 console.log(ret);
			 });
		}
        var  url="returnResult.php?runid="+ri; 
	    $.get(url,function(ret){
		if(typeof ret!="object"){
	    ret=JSON.parse(ret);//将json字符串转化成js对象
		}
		console.log(ret[0]);
		console.log(ret[1]);
		console.log(ret[2]);
        if(ret[0]==null){ //返回结果为空,判题还没结果继续刷新
		 return;  //结束函数		
		}else if(ret[0]=="该题未公开"||ret[0]=="判题失败"){
		 $("#"+j).html(ret[0]); //给运行结果赋值
		 $("#"+j+"time").html("--");//给时间赋值
		 $("#"+j+"memory").html("--");//给内存赋值
		 window.clearInterval(t); //移除定时器 
		}else{ //剩下的情况都是有结果的
		 $("#"+j).html(ret[0]); //给运行结果赋值
		 $("#"+j+"time").html(ret[1]);//给时间赋值
		 $("#"+j+"memory").html(ret[2]);//给内存赋值
		 window.clearInterval(t); //移除定时器	
		}
		});		  		  
				  },500); //如果判题结果未出来,每隔0.5s调用ajax方法刷新一次结果
		    }
	 }
     $(function(){
		/*******分页页面最大记录数需写入配置文件中啊**************/
		for(i=0;i<25;i++){ //i的最大值为分页页面的记录数,需写入配置文件中,有待修改
	      refreshResult(i);
		 } 
			});
</script>
<?php
 require_once('footer.php');
?>
