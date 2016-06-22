#!/usr/bin/env python3
#coding : utf-8

import mysql.connector;
import getReq as ge;
import sys;

class SQL:
	def connect(self):
		'''
		连接数据库
		'''
		try:
			s = SQL.db;					#判断一下是否加载了连接信息
		except AttributeError as at:
			SQL.load_db_connect();
		self.cnx = mysql.connector.connect(user = SQL.user,password \
				 = SQL.pwd , host = SQL.host,database = SQL.db);
		self.cursor = self.cnx.cursor();

#
#		可扩展为读取文件得到数据库连接信息
#
	def load_db_connect():
		
		SQL.user = 'root';
		SQL.pwd  = 'mysql123';
		SQL.host = '127.0.0.1';
		SQL.db   = 'virjudge';
	
	def close(self):
		self.cnx.commit();
		self.cursor.close()
		self.cnx.close();

	def update_sql(self , sql):				#sql语句会出现元字符去掉
		sql = sql.replace('\\','\\\\');
		sql = sql.replace('\'','\\\'');
		return sql;

	def un_public(self , oj , proid, runid):		#有些结果得到的是错误的未公开的题号
		
		update_sql = 'update run set result=\'该题未公开\' where runid=%d' % runid;
		self.connect();
		try:
			self.cursor.execute(update_sql);
		except mysql.connector.Error as err:
			print('更新失败');
			print('Error:{}'.format(err.msg));
			return ;
		self.close();

		update_sql = 'update nyproblem set problename=\'该题目尚未公开\' where problemid = \'%d\' and oj = \'%s\'' % (proid , oj);
		self.connect();
		try:
			self.cursor.execute(update_sql);
		except mysql.connector.Error as err:
			print('更新失败');
			print('Error:{}'.format(err.msg));
			return ;
		self.close();


	def select_no_return(self):							#给rejudge的查询所有判题中
		select_sql = 'select runid from run where result LIKE \'判题失败\'';
		self.connect();
		run_list = list();
		try:
			self.cursor.execute(select_sql);
			for a in self.cursor:
				run_list.append(int(a[0]));
			return run_list;
				
		except mysql.connector.Error as err:
			print('查询失败');
			print('Error:{}'.format(err.msg));
			return;

		self.close();

	def select_code(self , runid):						#查询提交题目的信息
		select_sql = 'select problemid,language,submit_code from run where runid=%d' % runid;
		self.connect();
		try:
			self.cursor.execute(select_sql);
			for a in self.cursor:
				return a;
		except mysql.connector.Error as err:
			print('查询失败');
			print('Error:{}'.format(err.msg));
			return;

		self.close();
	
	def select_oj(self , runid):						#判定某个题目属于哪个OJ
		select_sql = 'select oj from run where runid=%d' % runid;
		self.connect();
		try:
			self.cursor.execute(select_sql);
			for a in self.cursor:
				return a[0];
		except mysql.connector.Error as err:
			print('查询失败');
			print('Error:{}'.format(err.msg));
			return;

		self.close();

	def write_result(self , result , runid):			#判题结果写入数据库
		update_sql = 'update run set result=\'%s\',time=\'%s\',memory=\'%s\' where runid=%d' % (result[0] , result[1] , result[2] , runid);
		self.connect();
		try:
			self.cursor.execute(update_sql);
		except mysql.connector.Error as err:
			print('更新失败');
			print('Error:{}'.format(err.msg));
			return ;
		self.close();
	
	def insert_ce(self , msg , runid):					#把编译错误信息写入数据库
		
		msg = self.update_sql(msg);
		update_sql = 'update run set ceMessage=\'%s\' where runid=%d' % (msg , runid);
		self.connect();
		try:
			self.cursor.execute(update_sql);
		except mysql.connector.Error as err:
			print('更新失败');
			print('Error:{}'.format(err.msg));
			return ;
		self.close();

	def insert(self,req):								#把题目信息写入数据库

		select_sql = 'select * from nyproblem where problemid = \'%d\' and oj = \'%s\'' % (req.proid , req.oj);
		self.connect();
		exist = False;
		try:
			self.cursor.execute(select_sql);
			for i in self.cursor:
				exist = True;
		except mysql.connector.Error as err:
			print('查询失败');
			print('Error: {}'.format(err.msg));
			exit();
		self.close();

		if exist == True:
			self.connect();
			del_sql = 'delete from nyproblem where problemid = \'%d\' and oj = \'%s\'' % (req.proid , req.oj);
			try:
				self.cursor.execute(del_sql);
			except mysql.connector.Error as err:
				print('删除失败');
				print('Error: {}'.format(err.msg));
			self.close();

		req.title       = self.update_sql(req.title);
		req.description = self.update_sql(req.description);
		req.pro_input   = self.update_sql(req.pro_input);
		req.pro_output  = self.update_sql(req.pro_output);
		req.simple_in   = self.update_sql(req.simple_in);
		req.simple_out  = self.update_sql(req.simple_out);
		req.source      = self.update_sql(req.source);
		req.hint        = self.update_sql(req.hint);
		
		self.connect();
		insert_sql = '''insert into nyproblem (oj , problemid , problename ,time_limit , memory_limit , 
						description , pro_input , pro_output , simple_input , simple_output , source , hint) 
						values('%s' , '%d' , '%s' , '%s', '%s', '%s' , '%s' , '%s' , '%s', '%s', '%s', '%s')''' % (req.oj , 
						req.proid , req.title , req.time , req.memory , req.description , req.pro_input , req.pro_output , 
						req.simple_in ,req.simple_out , req.source , req.hint);
		try:
			self.cursor.execute(insert_sql);
		except mysql.connector.Error as err:
			sys.stderr.write('插入失败,题目是%d'%req.proid);
			sys.stderr.write(insert_sql);
			sys.stderr.write('Error : {}'.format(err.msg));
		self.close();

	def insert_ip(self, ip):
		'''
		把ip写到数据库里面
		'''
		self.connect();
		insert_sql = 'insert into proxy (ip) values(\'%s\')' % (ip);
		try:
			self.cursor.execute(insert_sql);
		except mysql.connector.Error as err:
			# sys.stderr.write('插入失败,题目是%d' % req.proid);
			sys.stderr.write(insert_sql);
			sys.stderr.write('Error : {}'.format(err.msg));
		self.close();

	def find_ip(self):
		select_sql = 'select ip from proxy'
		self.connect();
		ip_list = [];
		try:
			self.cursor.execute(select_sql);
			for a in self.cursor:
				ip_list.append(a[0]);
		except mysql.connector.Error as err:
			print('查询失败');
			print('Error:{}'.format(err.msg));
			return;
		self.close();
		return ip_list;

	def del_ip(self , ip):
		del_sql = 'DELETE FROM `proxy` WHERE ip=\'%s\'' % ip;
		self.connect();
		try:
			self.cursor.execute(del_sql);
		except mysql.connector.Error as err:
			# sys.stderr.write('插入失败,题目是%d' % req.proid);
			sys.stderr.write(del_sql);
			sys.stderr.write('Error : {}'.format(err.msg));
		self.close();
	

if __name__ == "__main__":
	
	req = ge.GetReq_nyist(1);
	print(req.page);
	sql = SQL();
	sql.insert(req);

	




