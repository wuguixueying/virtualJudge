<?php
require_once('head.php');
require_once("left.php");
require_once("middle_start.php");
require_once "phpClass/mysql_php.php";
 if(isset($_GET['userid'])){
  $u=urldecode($_GET['userid']);
  $m=new mysqlSupport;
  $m->mysql_con();//链接服务器
  $m->mysql_con_db();//链接数据库
  $sql="SELECT * FROM user WHERE userid='$u'";
  $r=mysql_query($sql);
  $arr=mysql_fetch_array($r);
  $sql01="SELECT * FROM run WHERE userid='$u' AND result LIKE 'A%' GROUP BY oj,problemid";
  $r01=mysql_query($sql01);
  $totalAcNums=mysql_num_rows($r01);
  /*函数returnNotAc()
   *@param  string $user 用户名
   *@return array  $notAcArr 返回没有AC的oj,题号组成的数组
   */
  function returnNotAc($user){
	  $i=0; //初始化$notAcArr数组的下标为0
	 /*查询result为非AC的记录,并以oj和题号进行分组.要得到用户正在攻克的题,需要删除用户AC了的题*/
	 $sql01="SELECT * FROM run WHERE userid='$user' AND result NOT LIKE 'A%' GROUP BY oj,problemid";
	 $res01=mysql_query($sql01);
      while($arr01=mysql_fetch_array($res01)){
		$arr_oj=$arr01['oj'];
		$arr_problemid=$arr01['problemid'];
		/*查询该题用户是否AC过*/
		$sql02="SELECT * FROM run WHERE userid='$user' AND oj='$arr_oj' AND problemid='$arr_problemid' AND result LIKE 'A%'";
	    $res02=mysql_query($sql02);
		$arr02=mysql_fetch_array($res02);
		  if($arr02['runid']){ //如果存在这样的题,则说明该记录的题用户已AC掉,不属于正在攻克的题
		     continue;
	     }else{
	       $notAcArr[$i]['oj']=$arr_oj;
		   $notAcArr[$i++]['problemid']=$arr_problemid;
		}
	 }
	     return $notAcArr;
  }
  $isToConquer=returnNotAc($u);//得到用户正在攻克的题
 }
?>
<div id="personMes">
   <h3><?php echo $arr['userid']; ?>的个人主页</h3>
   <span>邮箱:<?php echo$arr['email'];?></span><br/>
   <span>注册时间:<?php echo $arr['register_time'];?></span><br/>
   <span>最近登录时间:<?php echo $arr['login_time'];?></span>
   <div id="ac_pro">
       <span>已AC的题:<?php echo $totalAcNums;?></span><br/>
	   <?php
	      while($arr01=mysql_fetch_array($r01)){
		     echo "<span>".$arr01['oj']."</span>&nbsp;";
		echo '<a href="problem.php?pro_source='.$arr01['oj'].'&pro_id='.$arr01['problemid'].'">'.$arr01['problemid'].'</a>&nbsp&nbsp;';			 		  
		  }
	   ?>
   </div>
   <div id="noAc_pro">
     <span>正在攻克的题：<?php echo count($isToConquer)."<br/>";?></span>
      <?php
	         for($i=0;$i<count($isToConquer);$i++){
		       echo "<span>".$isToConquer[$i]['oj']."</span>&nbsp;";
	           echo '<a href="problem.php?pro_source='.$isToConquer[$i]['oj'].'&pro_id='.$isToConquer[$i]['problemid'].'">'.$isToConquer[$i]['problemid'].'</a>&nbsp&nbsp;';			 		  
			 }
	   ?>

   </div>
   <a href="runResult.php?userid=<?php echo $arr['userid'];?>&acAll=on">查看所有通过运行</a>&nbsp;&nbsp;
 <a href="runResult.php?userid=<?php echo $arr['userid'];?>&runAll=on">查看所有运行</a>
<div>
<style type="text/css">
  #personMes a{
   text-decoration:none;
  }
</style>
<?php
require_once("middle_end.php");
require_once("right.php");
require_once('footer.php');
?>
