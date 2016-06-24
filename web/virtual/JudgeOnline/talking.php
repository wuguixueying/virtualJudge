<?php
require_once "head.php";
require_once("left.php");
require_once("middle_start.php");
require_once('phpClass/mysql_php.php');
$mysql=new mysqlSupport;
$mysql->mysql_con();
$mysql->mysql_con_db();

if(!empty($_GET)){
	$problemid=$_GET['pro_id'];
	$sql="select * from nyproblem where problemid='{$problemid}'";
	$query=mysql_query($sql);
	$row=mysql_fetch_assoc($query);

	//查询留言
	$sql_message="select * from message where item_id='{$problemid}'";
	$query_message=mysql_query($sql_message);

	
}
?>
<link rel="stylesheet" href="css/talk.css" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<div id="problem_nav">
   <ul>
     <li id="problem_source"></li>
     <li id="problem_id"> </li>
     <li><a href=''>题目信息</a></li>
	 <li><a href=''>运行结果</a></li>
	 <li><a href=''>本题排行</a></li>
	 <li><a href=''>吐(B)槽(B)区</a></li>
   </ul>
</div> <!--end of problem_nav  -->
<div id="talk" style="text-align:center;">
这里是讨论区
</div>
<script>
	$(document).ready(function(){
	  $(".reply").click(function(){
	 // $(this).parent().parent().after($("#form_post_reply"));
	  $(this).parent().next().next().after($("#div_reply"));
	  $("#div_reply").show();
	  $("#replyto_one").show();
	  $("#form_post").hide();
	  $("#replyto_two").val($(this).attr("id"));
	  });
	});
</script>
<div id="main">
	<h2><a href="#"><?php echo '题目'.$problemid.' - '.$row['problename'] ?></a></h2>
	<hr style="color:red;" />
	<!-- 留言内容 -->
	<?php while($row_message=mysql_fetch_assoc($query_message)){ 
	?>
	<div class="pcomment-box" id="com0_<?php echo $row_message['id'] ?>" style="word-break:break-all">
	    <div class="pcomment-topic" style="height:20px">
		    <span style="float:left"><?php echo $row_message['id'] ?>楼&nbsp;&nbsp; <span> <?php echo $row_message['user_id'] ?> </span> </span> 
		    <span style="color:#00F; float:right; margin-right:10px;cursor:pointer;" class="reply" id="<?php echo $row_message['id'] ?>">回复</span>
		    <span style="color:#900;float:right;margin-right:10px"><?php echo date('Y-m-d H:i',$row_message['time']) ?></span> 
	    </div>
	    <div class="pcomment-body" style="text-indent:30px;">
		    <div><?php echo $row_message['content'] ?></div>
		    <div class="core_reply_wrapper">

		    	<ul>
		    	<?php 
		    	//查询回复
				$sql_reply="select * from reply where message_id=".$row_message['id'];
				$query_reply=mysql_query($sql_reply);

		    	while ($row_reply=mysql_fetch_assoc($query_reply)){ 
		    		//show_bug($row_reply);
		    		//show_bug($row_message);
		    		if($row_reply['message_id']==$row_message['id']){
		    		?>
		    		<li>
			    		<span style="color:#2d64b3"><?php echo $row_reply['reply_id'] ?>:</span>
			    		<span style="padding-left:10px;"><?php echo $row_reply['content'] ?><br></span>
			    		<span style="float:right;padding-right:15px;"><?php echo date('Y-m-d H:i',$row_reply['time']) ?></span><br>
			    	</li>
		    	<?php 
		    		}
		    	} 
		    	?>
		    	</ul>
		    </div>
		</div>
		<div class="clear"></div>

  	</div>
  	<?php } ?>
  	<!-- 留言内容结束 -->
  	<!-- 回复框开始 -->
  	<div id="div_reply" style="display:none; margin-left:20px;">
	  	<form id="form_post_reply" method="POST" action="dotalking.php?action=reply">
	        <div style="display:none" id="replyto_one">回复对象：
	          <input id="replyto_two" value="" name="message_id" type="text" style="width:50px;" onfocus="this.blur()">楼
	          <input value="<?php echo $_SESSION['userid'] ?>" name="reply_id" id="reply_id" type="hidden">
	        </div>
	        <?php if(empty($_SESSION['userid'])){?>
	         未登录 <a class="a-cursor" style="font-size:14px; font-weight:bold;" onclick="show('newcomment'); focuss('reply-text');" href="login.php"> 马上登录</a>
	          <div class="profile-box">
		          <div class="profile-body">
		            请先登录后即可回复内容！
		          </div>
			    </div>
	          <?php }else{ ?>
	            <div class="profile-box">
		          <div class="profile-body">
		            <div id="replys_1217"></div>
		            <span id="no_empty_code_1217" class="error" style="display:none">请检查: 1. 内容不能为空. 2. 内容长度不能超过500字<br>
		            </span> <span id="submiting_1217" class="error" style="display:none">提交中......<br>
		            </span>
		            <h3>发布留言</h3>
		            <textarea name="content" style="width:95%; height:150px" placeholder="请检查: 1. 内容不能为空. 2. 内容长度不能超过500字"></textarea>
		            <input value="确 定" class="btn" type="Submit">
		            <input value="重置" name="B2" class="btn" type="reset">
		          </div>
			    </div>
			    <?php } ?>
		</form>
		</div>
		<!-- 回复框结束 -->
	<!-- 留言框开始 -->
  	<form id="form_post" method="POST" action="dotalking.php">
        <div style="display:none" id="replyto0">回复对象：
          <input id="replyto" value="<?php echo $_SESSION['userid'] ?>" name="user_id" type="text">楼
          <input value="<?php echo $problemid; ?>" name="item_id" type="hidden">
        </div>
        <?php if(empty($_SESSION['userid'])){?>
         未登录 <a class="a-cursor" style="font-size:14px; font-weight:bold;" onclick="show('newcomment'); focuss('reply-text');" href="login.php"> 马上登录</a>
          <div class="profile-box">
	          <div class="profile-body">
	            请先登录后即可回复内容！
	          </div>
		    </div>
          <?php }else{ ?>
            <div class="profile-box">
	          <div class="profile-body">
	            <div id="replys_1217"></div>
	            <span id="no_empty_code_1217" class="error" style="display:none">请检查: 1. 内容不能为空. 2. 内容长度不能超过500字<br>
	            </span> <span id="submiting_1217" class="error" style="display:none">提交中......<br>
	            </span>
	            <h3>发布留言</h3>
	            <textarea name="content" style="width:95%; height:150px" placeholder="请检查: 1. 内容不能为空. 2. 内容长度不能超过500字"></textarea>
	            <input value="确 定" class="btn" type="Submit">
	            <input value="重置" name="B2" class="btn" type="reset">
	          </div>
		    </div>
		    <?php } ?>
  </form>
  <!-- 留言框结束 -->
</div>

<?php
require_once("middle_end.php");
require_once("right.php");
require_once "footer.php";
?>
