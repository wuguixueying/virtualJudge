<?php
  /*类名：mysqlSupport
   *说明：实现链接数据库的封装
   *author:lhs
   *更新时间：2016年5月2日16点28分。类编辑完成,所有方法未经测试。by-lhs。
   *更新时间：2016年5月3日12点00分,类中所有方法测试完毕,可用。by-lhs。
   *更新时间:mysql_query_result()函数有bug,建议不使用!by-lhs 2016/5/17
   */
   class mysqlSupport{
      private $mysql_server;
	  private $mysql_user;
	  private $mysql_password;
	  private $mysql_database;
	  private $con;    //链接mysql服务器时返回的句柄
      private $db;     //链接数据库时返回的句柄
      private $result; //存储查询返回记录结果的二维数组         
 
	  /*方法:__construct()
	   *说明：构造函数,用来给类属性赋初值,若要使用缺省值,则实例化类时不传入任何参数
	   *@param $server string  服务器名称
	   *@param $user   string  用户名
	   *@param $pw     string  用户密码
	   *@param $DB    string 数据库名称
	   */
	    public function __construct($server="localhost",$user="root",$pw="mysql123",$DB="virjudge"){
	    $this->mysql_server=$server;
		$this->mysql_user=$user;
		$this->mysql_password=$pw;
		$this->mysql_database=$DB;
	  }

	  /*
	   *方法：mysql_con()
	   *说明：链接指定的mysql服务器
	   */
      public function mysql_con(){
	    $this->con=mysql_connect($this->mysql_server,$this->mysql_user,$this->mysql_password);
		mysql_query("set names utf8");
        if(!$this->con){   //链接失败时放回false值
		die(mysql_error());
		}
	  }
      
     /*
	  *方法名：mysql_con_db()
	  *说明：链接指定数据库
	  */
      public function mysql_con_db()
	  {
	    $this->db=mysql_select_db($this->mysql_database);
	    if(!$this->db){
		 die(mysql_error());
		}
	  }
   
     /*
	  *方法名：mysqlClose()
	  *说明：关闭非持久的mysql连接
	  */
     public function mysqlClose(){
	  if(!mysql_close($this->con)){
		  die(mysql_error());
	  }
	 }

	 /*
	  *方法名：mysql_query_result()
	  *说明：返回sql查询结果集
	  *@param $sql string sql查询语句
	  *return rusult 数字数组或关联数组(从结果集中取出的)
	  */
	 public function mysql_query_result($sql){
	  $res=mysql_query($sql);
	  $i=0;
	  while($row=mysql_fetch_array($res)){
	    $this->result[$i++]=$row;
	 }
	  return $this->result;
	 }
   
   }
 /* $mysql=new mysqlSupport;
  $mysql->mysql_con();
  $mysql->mysql_con_db();
  $mysql->mysqlClose();*/
?>
