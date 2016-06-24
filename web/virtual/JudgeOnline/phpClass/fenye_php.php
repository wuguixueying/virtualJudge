<?php
 /*
  *更新时间：2016年5月1日上午,编辑完成,所有方法未经测试。
  *参考：http://www.cnblogs.com/lovening/archive/2010/09/19/1831317.html
  *更新时间：2016年5月3日17:00,完成类中所有方法的测试。by-lhs.
  *更新时间：2016年5月3日21:33,给类添加了full_navigate()方法,测试中出现BUG,暂时不用使用
  *更新时间:2016年5月25日,给类添加了addURLParam()方法,已完成测试
  */


  /*
  *类名:PageSupport
  *功能:分页显示mysql数据库中的数据
  */
class PageSupport{

	private $sql;//所要显示数据的sql查询语句
    private $page_size; //每页显示最多行数

	private $start_index; //所要显示记录的首行序号
	private $total_records; //记录总数
	private $current_records; //本页读取的记录数
	private $result;    //读出结果

	private $total_pages; //总页数
	private $current_page; //当前页数
	private $display_count=30; //显示的前几页和后几页
	private $arr_page_query; //包含分页显示所需要传递的参数

	private $first;
	private $previous;
	private $nex;
	private $last;
 
	private $URL; 
	/*函数名:__construct() 
	 *说明：构造函数
     *@param $ppage_size:每页显示最多行数
	 *@return 
	 */
  public function __construct($ppage_size){
    $this->page_size=$ppage_size;
	$this->start_index=0;
  }

  /*
   *魔术方法:__get() 
   *说明：获取类中私有属性的值
   *@param $prorerty_name 属性名
   *@return $this->$property_name/NULL 属性值或   *空值
   */
  private function __get($property_name)
  {
   if(isset($this->$property_name)){
      return ($this->$property_name);
   }
   else
   {
    return (NULL);
   }
  
  }

  /*
   *魔术方法:__set
   *说明：设置私有属性的值
   *@param $property_name 属性名
   *@param $value 属性值
   *@return 
   */
  private function __set($property_name,$value){
   $this->$property_name=$value;
  }

 /*
  *方法:read_data()
  *说明:根据sql查询语句从表中读取相应的记录
  *@param
  *@return result 二维数组 使用limit分页后的记录
 */
  function read_data(){
     $psql=$this->sql;
     $result=mysql_query($psql)or die(mysql_error());
      $this->total_records=mysql_num_rows($result);
       //使用limit语句实现分页
        if($this->total_records>0){
           $this->start_index=($this->current_page-1)*$this->page_size;
           $psql=$this->sql." LIMIT ".$this->start_index.",".$this->page_size;
           $result=mysql_query($psql)or die(mysql_error());
           $this->current_records=mysql_num_rows($reslut);
            //将查询结果放在result数组中
                $i=0;
             while($row=mysql_fetch_array($result)){
                 $this->result[$i++]=$row; } 
                 $this->total_pages=ceil($this->total_records/$this->page_size);
                 $this->first=1;
                 $this->previous=$this->current_page-1;
                 $this->previous=$this->previous<1?1:$this->previous;
                 $this->nex=$this->current_page+1;
                 $this->nex=$this->nex>$this->total_pages?$this->total_pages:$this->nex;
                 $this->last=$this->total_pages;
      }
     }

  /*方法名:addURLParam()
   *说明:为URL地址添加参数方法
   *@param $url string url地址
   *@param $attribute string 属性
   *@param $value string 值
   *@return $url string 返回改造过后的url地址
   */
   function addURLParam($url,$attribute,$value){
      $url=strrpos($url,'?')?$url."&".$attribute."=".$value:$url."?".$attribute."=".$value;
      //若url字符串中出现?,则添加&,否则添加?
	  return $url;
   }


  /*方法名:standard_navigate()
   *说明：实现 首页 下页 上页 尾页 导航形式,适合页数比较少的页面
   *@param
   *@return
   */
   function standard_navigate()
   {
	 $this->URL=strrpos($this->URL,'?')?$this->URL."&":$this->URL."?";
	echo '<a style="text-decoration:none"href="'.$this->URL.'current_page='.$this->first.'">首页&nbsp;</a>';
	echo '<a style="text-decoration:none"href="'.$this->URL.'current_page='.$this->nex.'">下页&nbsp;</a>';
	echo '<a style="text-decoration:none"href="'.$this->URL.'current_page='.$this->previous.'">上页&nbsp;</a>';
	echo '<a style="text-decoration:none"href="'.$this->URL.'current_page='.$this->last.'">尾页</a>';
 echo '&nbsp;当前页&nbsp;'.$this->current_page.'&nbsp;&nbsp;总共为&nbsp;'.$this->total_pages.'页';

   }
   /*
	*方法名：full_navigate()
	*说明：实现 <上一页 1 2 3 5 6 7 8 9 10 下一页>导航形式,适合页数比较多的页面
	*@param 
	*@return 
	*/
   function full_navigate(){

	echo '<a href="'.$_SERVER['PHP_SELF'].'?current_page='.$this->previous.'"><上页</a>';
    $counter=10; //计数器
	$tag; 
	for($i=$this->current_page;$i>0;$i--) //当前页的左边显示6条
    	{
	       $counter-=1;
		  if($i==$this->current_page){
	     echo '<a style="font-weight:bold" "href="'.$_SERVER['PHP_SELF'].'?current_page='.$i.'">'.$i.'</a>&nbsp';}
		  else{ echo '<a href="'.$_SERVER['PHP_SELF'].'?current_page='.$i.'">'.$i.'</a>&nbsp';}   
		   if($counter==4) { 
				   $tag=$i; //标记左边跳出时$i的值
				   break;
			   }
      	}
	   if(!$i){$tag=0;} //当$i的值为零时,说明当前页的左边页已显示完毕,即使右边还有剩余$counter值,也不在显示
	  /* for($i=$tag;$i<$this->current_page;$i++)
	   {
	     if($i=0){$i+=1;}
	   if($i==$this->current_page){
	     echo '<a style="font-weight:bold" "href="'.$_SERVER['PHP_SELF'].'?current_page='.$i.'">'.$i.'</a>&nbsp';}
		 else{ echo '<a href="'.$_SERVER['PHP_SELF'].'?current_page='.$i.'">'.$i.'</a>&nbsp';}   
	   }
*/

	for($i=$this->current_page+1;$i<=$this->total_pages;$i++) //当前页的左边显示4条
     	{
	      $counter-=1;
	       echo '<a href="'.$_SERVER['PHP_SELF'].'?current_page='.$i.'">'.$i.'</a>&nbsp';
	      if(!$counter){break;}
    	}
	  //  if($counter!=0&&$tag!=0) //当前页的右边不显示数时,应该把剩下的$counter值继续让给左边显示
	  //  {
	 //	for($i=$tag-1;$i>0;$i--) 
     //	{
	 //      $counter-=1;
	 //      echo '<a href="'.$_SERVER['PHP_SELF'].'?current_page='.$i.'">'.$i.'</a>&nbsp';
	 //        if(!$counter){break;}
     // 	} 
	// }
    echo '<a href="'.$_SERVER['PHP_SELF'].'?current_page='.$this->nex.'">下页></a>';
   }
}
?>
