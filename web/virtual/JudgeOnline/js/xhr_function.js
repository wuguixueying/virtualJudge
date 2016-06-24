/*函数名:createXHR()
 *说明:这个函数首先检测原生XHR对象是否存在,如果存在则返回它的新实例。
 *如果原生对象不存在,则检测Active对象。如果两种对象都不存在,就会抛出
 *一个错误。
 *此函数是为了兼容IE7之前版本而编写的,因为在IE5中,XHR对象是通过MSXML
 *库中的一个ActiveX对象实现的,因此在IE中可能会遇到三种不同版本的XHR
 *对象,即MSXML2.XMLHttp,MSXML2.XMLHttp.3.0,MXLHttp.6.0
 *用法:在引用的文件中直接创建XHR对象,比如var xhr=createXHR();
 */
function a(){
alert("aaa");}
function createXHR(){ 
   if(typeof XMLHttpRequest !="undefined"){
      return new XMLHttpRequest();
   }else if(typeof ActiveXObject!="undefined"){
     if(typeof arguments.callee.activeXString !="string"){
      var versions=["MSXML2.XMLHttp","MSXML2.XMLHttp.3.0","MXLHttp.6.0"],i,len;
	  for(i=0,len=versions.length;i<len;i++){
	      try{
		    new ActiveXObject(versions[i]);
			arguments.callee.activeXString=versions[i];
			break;
		  }
		  catch(ex){
		  //跳过
		  }
       }

	 }
		return new ActiveXObject(arguments.callee.activeXString);
	  }
	  else{
	   throw new Error("No XHR object available");
	  }

   }


