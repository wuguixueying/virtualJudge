function serialize(form){
   var parts=[],
	   field=null,
	   i,
	   len,
	   j,
	   optlen,
	   option,
	   optvalue;
    for(i=0,len=form.elements.length;i<len;i++){
	   field=form.elements[i];
	   switch(field.type){
	     case "select-one":
		 case "select-multiple":
			 if(field.name.length){
    			 for(j=0,optlen=field.options.length;j<optlen;j++){
			       option=field.options[j];
				    if(option.selected){
			          optvalue="";
					  if(option.hasAttribute){
					    optvalue=(option.hasAttribute("value")?option.value:option.text);
					  }else{
					  optvalue=(option.attributes["value"].specified?option.value:option.text);
					  }
					}
				 
				 }		 
			 }    
			 break;
            case undefined: //字段集
			case "file" :   //文件输入
		    case "submit": //提交按钮
			case "reset": //重置按钮
			case "button": //自定义按钮
			 break;
            case "radio": //单选按钮
			case "checkbox": //复选框
			   if(!field.checked){break;}
			     /*执行默认操作*/
			default:
			   //不包含没有名字的表单字段
			   if(field.type=="password"){ field.value=hex_md5(field.value);} //如果是密码字段,则在post之前进//行md5加密
			   if(field.name.length){
			   parts.push(encodeURIComponent(field.name)+"="+encodeURIComponent(field.value));
			   }
	   }

	}
     return parts.join("&");
}
