<?php
  require_once('head.php');
  require_once('left.php');
  require_once("middle_start.php");
  $refeURL=urlencode($_SERVER['HTTP_REFERER']); /*链接到登录页面的前一个页面的url地址*/
  //当从激活页面跳转过来时,取得的$refeURL将为一个空值
?>
 <div id="refe_id" style="display:none"><?php echo $refeURL; ?></div> <!--将前一个页面的url地址信息输出到页面中,方便js获取该url地址信息,为了隐藏该信息,将div块设置为不显示-->
 <div id="user_log">
   <form>
   <p>
   <h1 style="margin-left:100px;font-size:24px">用户登录</h1>
   </p>
   <p id="user_p">
    <label for="log_user">用户：</label>
    <input type="text" id="log_user" name="log_user" placeholder="请输入用户名" class="auth" />
    <span class="error"></span>
    <span class="error_two"></span>
	</p>
   <p id="pw_p">
	<label for="log_pw">密码：</label>
	<input type="password" id="log_pw" name="log_pw" placeholder="请输入密码" class="auth" />
   <span class="error"></span>
   <span class="error_two"></span>
	</p>
   <p>
	<input type="reset" id="log_reset" name="red" value="重置"/>
	<input type="button" id="log_sub" name="sub" value="登入"/>
  </p>
</form>
</div> <!--end of user_log-->
<!--<script type="text/javascript" src="js/login.js"></script>-->
<script type="text/javascript" src="js/jquery.md5.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
   var referURL=$("#refe_id").html(); //获取到链接到当前页面的前一个页面的url地址
  //用户名和密码不正确时,登录按钮设置为不可用
  $("#log_sub").attr("disabled",true);//只有用户和密码验证正确时,登录按钮才可用
   var user_verify=false;//用户名正确时,该变量值赋值为true
  //验证用户名
  $("#log_user").blur(function(){
      var user_val=$(this).val();
      var data ='user_name=' +user_val;
      $.ajax({
        type:"POST",
        url:"validate.php",
        data:data,
        success:function(html) {
          if(html=='用户名正确'){
		    user_verify=true;//改变变量的状态
            $('#user_p .error').text(html);
            $('#user_p .error').show();
            $('#user_p .error_two').hide();
            $(this).data({'s':1});
          }else if(html<=0){
            $('#user_p .error_two').text('用户名不存在');
            $('#user_p .error_two').show();
            $('#user_p .error').hide();
            $(this).data({'s':0});
          }
          else{
            $('#user_p .error_two').text(html);
            $('#user_p .error_two').show();
            $('#user_p .error').hide();
            $(this).data({'s':0});
          }
        }
      });
  });
  //验证用户名密码
  $("#log_pw").blur(function(){
      var user_val=$("#log_user").val();
      var pw_val=$(this).val();
      //var data ='pw_name=' +pw_val;
      $.ajax({
        type:"POST",
        url:"pw_validate.php",
        data:{"pw_name":pw_val,"user_name":user_val},
        success:function(html) {
          if(html=='密码正确'){
		    if(user_verify){ //用户名验证正确,密码验证正确,登录按钮变为可用
			$("#log_sub").removeAttr("disabled");
			}
            $('#pw_p .error').text(html);
            $('#pw_p .error').show();
            $('#pw_p .error_two').hide();
            $(this).data({'s':1});
          }else if(html<=0){
            $('#pw_p .error_two').text('密码错误');
            $('#pw_p .error_two').show();
            $('#pw_p .error').hide();
            $(this).data({'s':0});
          }
          else{
            $('#pw_p .error_two').text(html);
            $('#pw_p .error_two').show();
            $('#pw_p .error').hide();
            $(this).data({'s':0});
          }
        }
      });
  });
    
  //提交按钮
  $("#log_sub").click(function(){
    var l_user=$("#log_user").val();
	var l_pw=$("#log_pw").val();
	var url="verifyEmail.php";
	$.post(url,{"userid":l_user,"password":l_pw},function(ret){
		  if(typeof ret=="string"){
		     if(ret=="verify"){
			    if(referURL==""){ //若链接到本页面的上一个页面的URL为空值,则参数referURL的值设为1
				referURL="1";
				}
			    location.href="login_message.php?referURL="+referURL; 
			 }else if(ret=="noVerify"){
			   alert("邮箱未激活,请先激活邮箱");
			 }else if(ret='error'){
			   alert("登录异常,请重新登录");
			 }
		  }
		});
  });
</script>
<?php 
require_once("middle_end.php");
require_once("right.php");
require_once('footer.php');
?>
