window.onload=function(){
   var btn=document.getElementById('log_sub');
   var userid=document.getElementById('log_user');
   var pwid=document.getElementById('log_pw');
   btn.addEventListener("click",function(){
		/*alert(userid.value);
		alert(pwid.value);*/
	var xhr=createXHR();  //创建xmlHttpRequest对象
   xhr.onreadystatechange=function(){ //必须在调用open函数之前指定onreadystatechange事件处理程序才能确保跨浏
    //览器兼容性
    if(xhr.readyState==4){
     if((xhr.status>=200&&xhr.status<300)||xhr.status==304){
       var data=xhr.responseText;
              if(typeof data=="string"){
			     if(data=="loginSucceed"){
				  location.href="personal.php";
				 }else if(data=="userNoExist"){
                   alert("不存在该用户!");
				 }else if(data=="passwordIncorret"){
				   alert("密码错误!");
				 }else if(data=="emailNoVerify"){
				  alert("邮箱未激活,请先激活邮箱!");
				 }
			 } 
}
   else
   { 
     alert("请求错误,服务器无法响应!");
     console.log("Request was unsuccessful:"+xhr.status);
   }
   }
}
var url="login_deal.php";
url=addURLParam(url,"log_user",userid.value);
url=addURLParam(url,"log_pw",hex_md5(pwid.value));
xhr.open("GET",url,true); //参数3表示是否开启异步 
xhr.send(null);
		   });
}  
