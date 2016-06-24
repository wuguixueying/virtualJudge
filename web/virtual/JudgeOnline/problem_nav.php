<?php
  require_once('phpClass/mysql_php.php');
  $my=new mysqlSupport;
  $my->mysql_con();
  $my->mysql_con_db();
  $problem_nav['content']='problem.php';
  $problem_nav['status']='runResult.php';
  $problem_nav['problemrank']='problemrank.php';
  $problem_nav['talking']='talking.php';
 if(isset($_GET['pro_source'])&&isset($_GET['pro_id'])){
    $problemId=$_GET['pro_id'];
    $problemSource=$_GET['pro_source'];
    $sql="SELECT * FROM nyproblem WHERE problemid='$problemId' AND oj='$problemSource'";
    $res=$my->mysql_query_result($sql);
  }
?>
<div id="problem_nav">
   <ul>
     <li id="problem_source"><?php echo $res[0]['oj']?></li>
     <li id="problem_id"><?php echo $res[0]['problemid'];?> </li>
     <li><a href='<?php echo $problem_nav["content"];?>?pro_source=<?php echo $problemSource?>&pro_id=<?php echo $problemId;?>'>题目信息</a></li>
	 <li><a href='<?php echo $problem_nav["status"];?>?pro_source=<?php echo $problemSource?>&pro_id=<?php echo $problemId;?>'>运行结果</a></li>
	 <li><a href='<?php echo $problem_nav["problemrank"];?>?pro_source=<?php echo $problemSource?>&pro_id=<?php echo $problemId;?>'>本题排行</a></li>
	 <li><a href='<?php echo $problem_nav["talking"];?>?pro_source=<?php echo $problemSource?>&pro_id=<?php echo $problemId;?>'>讨论区</a></li>
   </ul>
</div> <!--end of problem_nav  -->
