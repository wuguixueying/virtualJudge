<?php
  require_once('head.php');
  require_once("left.php");
  require_once("middle_start.php");
  require_once('problem_nav.php');
  if(!$res[0]['problemid']||$res[0]['problename']=='该题目尚未公开'){
    header("location:serch_error.php");
  }
	  ?>
<h2 id="title"><?php echo $res[0]['problename'];?></h2>
<div id="neck">
    <span id="time_limit">
	 <h4>时间限制：</h4><?php echo $res[0]['time_limit'];?>
	</span> 
	<span id="memory_limit">
	<h4>内存限制：</h4><?php echo $res[0]['memory_limit'];?>
	</span>
</div> <!--end of neck-->
	<div id="description">
	<h4> 描述：</h4><?php echo $res[0]['description'];?>
	</div>
	<div id="pro_input">
	<h4>输入：</h4><?php echo $res[0]['pro_input'];?>
	</div>
	<div id="pro_output">
   <h4> 输出：</h4><?php echo $res[0]['pro_output'];?>
	</div>
	<div id="simple_input">
	<h4> 样例输入：</h4><?php echo $res[0]['simple_input'];?>
	</div>
	<div id="simple_output">
	<h4>样例输出：</h4><?php echo $res[0]['simple_output'];?>
	</div>
	<div id="hint">
	<h4>提示：</h4><?php echo $res[0]['hint'];?>
	</div>
	<div id="source">
	<h4>来源：</h4><?php echo $res[0]['source'];?>
	</div>
<div id="problem_sub">
<form>
   <?php
      if($res[0]['oj']=='nyist'){
	 echo <<<EOF
   <select id="language">
   <option value="C/C++">C/C++</option>
   <option value="java">java</option>
  </select>
EOF;
	  }else if($res[0]['oj']=='hdu'){
	  echo <<<EOF
	 <select id="language">
   <option value="0">G++</option>
   <option value="1">GCC</option>
   <option value="2">C++</option>
   <option value="3">C</option>
   <option value="4">Pascal</option>
   <option value="5">java</option>
   <option value="6">C#</option>
  </select> 
EOF;
	  }

   ?>
 <input type="button" value="提交" id="code_sub"/><br/>
  <textarea id="code"></textarea> 
</form>
</div> <!--end of problem_sub-->
<div id="p_login" style="display:none">
<span>亲,请先登录再交题哦</span>
<form id="p_form1">
用户名:<input type="text" name="p_user" id="p_user" /><br/>
密码:<input type="password" name="p_pw" id="p_pw" /></br/>
<input type="reset" name="p_reset" id="p_reset" value="重置"/>
<input type="button" name="p_submit" id="p_submit" value="登录" />
</form>
</div>
<!--交题时检测用户是否登录,如果未登录则弹出登录框-->
<script type="text/javascript">
$(function(){
	  var currentURL=encodeURIComponent(window.location.href); //获取本页面的url信息,包括?后面的参数,在使用get传递时需要对url进行编码
	$("#code_sub").bind("click",function(){
		if(!$("#person").length){ //检测用户是否登入
		$("#p_login").show(); //显示登录框
		}
		else{
       var p_oj=$("#problem_source").html();
	   var p_id=$("#problem_id").html();
	   var p_ti=$("#title").html();
       var p_lan=$("#language option:selected").val();
	   var p_code=$("#code").val();
	   if(p_code==""){
	    alert("提交内容不能为空!");
	   }else{
	       var size=sizeof(p_code,"utf-8"); 
		   if(size<50){
		    alert("代码字节长度少于50,请重新提交!");
		   }else{ 
	     $.post("code_deal.php",{"oj":p_oj,"problemid":p_id,"problemname":p_ti,"lan":p_lan,"code":p_code},function(ret){
		   console.log(ret);
		   if(typeof ret=="string"){
		   ret=parseInt(ret);}
		   if(ret){
		   window.location.href="runResult.php";
		   }else{
		    alert("交题异常,请稍后提交!");
		   }
		   }); }
	    } 
	   }
		});
	$('#p_submit').bind("click",function(){
		 var u=$('#p_user').val();
		 var p=hex_md5($('#p_pw').val());
		$.get("login_deal.php?log_user="+u+"&log_pw="+p,function(data){
			 if(typeof data=="string"){
			     if(data=="loginSucceed"){
				  location.href="login_message.php?currentURL="+currentURL;
				 }else if(data=="userNoExist"){
                   alert("不存在该用户!");
				 }else if(data=="passwordIncorret"){
				   alert("密码错误!");
				 }else if(data=="emailNoVerify"){
				  alert("邮箱未激活,请先激活邮箱!");
				 }
			 } 
			}); 
		});
		});

</script>
<?php
 require_once("middle_end.php");
 require_once("right.php");
 require_once('footer.php');
?>
