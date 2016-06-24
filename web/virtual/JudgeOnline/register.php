<?php
require_once('head.php');
require_once("left.php");
require_once("middle_start.php");
?>
<div id="user_reg">
<div id="div_form">
<form action="register_verify.php?action=reg" id="reg_form" method="post">
<p>	
<h1 style="margin-left:100px;font-size:24px">用户注册</h1>
</p>
<P>
	<label for="user"><span>*</span>用<a style="padding-left:20px;"></a>户:</label>
	<input type="text" name="userid" id="user" class="auth" placeholder="请输入用户名" />
	<span class='error'>用户名长度至少4位</span><br>
</p>
<P>
	<label for="pw"><span>*</span>密<a style="padding-left:20px;"></a>码:</label>
	<input type="password" name="pw" id="pw" class="auth" placeholder="请输入密码" />    
	<span class='error'>密码长度至少6位</span><br>
</p>
<p>
	<label for="email"><span>*</span>邮<a style="padding-left:20px;"></a>箱:</label>
	<input type="text" name="email" id="email" class="auth" placeholder="请输入邮箱" />
	<span class='error'>邮箱格式不正确</span><br>
<p/>
<p>
    <label for="pw"><span>*</span>验证码:</label>
	<input type="text" name="yzm" placeholder="请输入验证码" />
	<img src="yanzheng.php" onclick="this.src='yanzheng.php?id'+Math.random()" style="position:relative;top:8px" />
	<span style="color:red;">(*点击图片即可刷新验证码*)</span>
</p>
<p>
	<input type="reset" name="ret" value="重置" id="reg_reset"/>
	<input type="button" name="reg_sub" value="注册" id="reg_sub"/>
</p>
</form>
</div>
</div> <!--end of user_reg-->
<!--<script type="text/javascript" src="js/register.js"></script>
-->
<script type="text/javascript" src="js/md5.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
	//验证用户名
	$("#user").blur(function(){
		val=$(this).val();
		if(val.length<4){
			$(this).next().show();
			$(this).data({'s':0});
		}else{
			$(this).next().hide();
			$(this).data({'s':1});
		}
	});
	//验证密码
	$("#pw").blur(function(){
		val=$(this).val();
		if(val.length<6){
			$(this).next().show();
			$(this).data({'s':0});
		}else{
			$(this).next().hide();
			$(this).data({'s':1});
		}
	});
	//验证邮箱
	$("#email").blur(function(){
		val=$(this).val();
		if(!val.match(/^\w+@\w+\.\w+$/i)){
			$(this).next().show();
			$(this).data({'s':0});
		}else{
			$(this).next().hide();
			$(this).data({'s':1});
		}
	});
	//提交按钮
	$("#reg_sub").click(function(){
		$("#user").blur();
		$("#pw").blur();
		$("#email").blur();
							    
		tot=0;
		$(".auth").each(function(){
			tot+=$(this).data('s');
		});

		if(tot==3){
			$('form').submit();
		}
	});
</script>
<?php
require_once("middle_end.php");
require_once("right.php");
require_once('footer.php');
?>
