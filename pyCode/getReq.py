#!/usr/bin/env python3
#coding : utf-8

import requests;
import re;
import urllib.request;
import random;
import mySQL as sqll;

"""

这个是urlopen基础类,所有的urlopen都是继承此类
请保证../data/proxy这个文件存在,代理信息保存在此文件里面，定期更新

"""

user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 \
			 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'
http_home  = 'var/www/html/';
local      = 'http://121.42.158.99/virtual/';

class GetReq:

	headers = {'User-Agent':user_agent};
	
	def __init__(self):
		
		GetReq.get_proxies();
		self.pro_url = '';								#基类里面就有他，子类一定要重新赋值

	def get_proxies():									#加载代理模块
		try:
			s = GetReq.proxies[0];						#如果这个变量不存在可能会抛出异常
		except:											#还不太理解静态方法和类方法，先写个破玩意用这，留着以后理解了再改
			print('加载代理');
			GetReq.proxies = [{}];
			sql = sqll.SQL()
			ip_list = sql.find_ip();
			for i in ip_list:
				if i[0] == '#':
					continue;
				else:
					GetReq.proxies.append({'http':i});

	def url_open(self,url):								#打开一个页面，html存到self.page 里面
		
		T = 10;											#设定尝试10次，失败就终止然后debug
		while T != 0:
			try:
				chose = random.randint(0 , len(GetReq.proxies) - 1);			#从代理列表里面随机一个代理
				print(GetReq.proxies[chose])
				req = requests.get(url = url , proxies = GetReq.proxies[chose] , headers = self.headers , timeout = 8);
				self.page = req.text;
				if req.status_code != 200:
					continue;
				return;

			except Exception as er:
				T = T - 1;
				print(str(er));
				print('捕获异常')
#				GetReq.chose = GetReq.chose + 1;
		print('失败');
	
	'''
	子类才会调用的模块，得到的子类那个OJ的题目号位proid的题目所有信息
	结果就是这个对象保存了所有的信息，然后和传本身给sql 类插入数据库
	
	写在基类里面是为了减轻子类编写的负担
	'''
	
	def get_request(self , proid):				

		self.proid = proid
		pro_url = self.pro_url + str(proid);
		self.url_open(pro_url);
		self.pub = True;
		self.end = False;
		if self.public_pro():				#有些题目是未公开的
			if self.end_pro():				#判断结束
				self.end = True;
			else:							#获取页面的全部信息

				self.get_title();
				self.get_page();
				self.update_source();
				self.update_pic();
				self.get_time();
				self.get_memory();
				self.get_description();
				self.get_input();
				self.get_output();
				self.get_simple_in();
				self.get_simple_out();
				self.get_source();
				self.get_hint();

		else:
			self.pub         = False;
			self.title       = '该题目尚未公开';
			self.page		 = '';
			self.time        = '';
			self.pro_input   = '';
			self.memory      = '';
			self.description = '';
			self.pro_output  = '';
			self.simple_in   = '';
			self.simple_out  = '';
			self.source      = '';
			self.hint        = '';

	'''
	下面这些方法都需要在子类里面重写
	
	'''

	def get_title(self):							#标题
		pass;
				
	def get_page(self):								#页面信息
		pass;
	
	def update_source(self):						#更新来源，因为来源都是那个oj的相对路径
		pass;

	def update_pic(self):							#更新图片路径，有些图片也是相对路径
		pass
	
	def get_time(self):								#时间限制
		pass

	def get_memory(self):							#内存限制
		pass;

	def get_description(self):						#描述
		pass;

	def get_input(self):							#输入描述
		pass

	def get_output(self):							#输出描述
		pass
	
	def get_simple_in(self):						#样例输入
		pass

	def get_simple_out(self):						#样例输出
		pass
				
	def get_source(self):							#来源
		pass;
				
	def get_hint(self):								#提示
		pass
	
	def public_pro(self):							#判断题目是否公开
		pass;
	
	def end_pro(self):								#判断是否结束
		pass
if __name__ == '__main__':
	req = GetReq();
	req.url_open('http://acm.nyist.net/JudgeOnline/problemset.php');
	with open('../123.html' , 'w') as f:
		f.write(req.page);



