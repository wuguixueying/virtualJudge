#!/usr/bin/env python3
#coding : utf-8

import copy;
import threading as th;
import queue;
import requests;
import re;
import sys;
import getReq as ge;
import time;
import os;
import mySQL as sqll;


user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) \
			  Chrome/49.0.2623.112 Safari/537.36'


class Post(ge.GetReq):
	
	'''
	这个类是规范每个提交的模板的。
	不可以实例化

	'''
	url = 'http://';
	end_url = 'http://'
	sub_url = 'http://'
	login_url = 'http://';
	def __init__(self):
		
		super(Post , self).__init__();

	def write_sql(self , s):
		
		sql = sqll.SQL();
		sql.write_result(s ,self.local_runid);
	
	def get_local_runid(self , jb):						#接收信号	
		
		self.local_runid = jb.get(); 

	def get_return(self , num):						

		sql = sqll.SQL();
		self.get_code();
		self.log_in(num);
		if self.post_code():
			sql.un_public(self.oj , self.proid , self.local_runid);
			return;
		end_url = self.get_end_url();
		runid   = self.get_runid();
		for i in range(10):							#设定得到结果10次,每次延时1秒，如何得不到结果就设定为提交失败
			self.url_open(end_url);
			result = self.get_result(runid);
			if result[0] == '判题中...':
				time.sleep(1);
				continue;
			else:	
				if result[0] == 'CompileError':
					self.get_ce_msg(runid);
				self.write_sql(result);
				return;
		
		result = ['判题失败' , '0' , '0'];
		self.write_sql(reqult);
		print('提交失败');

	'''
	这些方法在子类里面实现，填入正则表达式
	'''
	def get_code(self):
		pass;
	
	def get_ce_msg(self):
		pass;

	def log_in(self,num):
		pass;
	
	def post_code(self):
		pass;
		
	def get_end_url(self):
		pass;
	
	def get_result(self , runid):	
		pass;
	
	def get_runid(self):
		pass;


