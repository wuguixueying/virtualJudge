window.onload=function(){
   var formid=document.getElementById("reg_form");
   var btn=document.getElementById('reg_sub');
   btn.addEventListener("click",function(){
	var xhr=createXHR();  //创建xmlHttpRequest对象
   xhr.onreadystatechange=function(){ //必须在调用open函数之前指定onreadystatechange事件处理程序才能确保跨浏///览器兼容性
    if(xhr.readyState==4){
     if((xhr.status>=200&&xhr.status<300)||xhr.status==304){
       var ret=xhr.responseText;
	   var data=JSON.parse(ret);
	   if(typeof data=="object"){
	     alert("注册成功,正跳转到个人主页");
	     window.location.href="personal.php";
	   }
}
   else
   {
     console.log("Request was unsuccessful:"+xhr.status);
   }
   }
}
var url="profile.php";
xhr.open("POST",url,true); //参数3表示是否开启异步
xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
xhr.send(serialize(formid));   
		   });
}  
