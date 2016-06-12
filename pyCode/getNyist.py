#!/usr/bin/env python3
#coding : utf-8

import requests;
import re;
import random;					
from  getReq import *;
import sys;


user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 \
			 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'
http_home  = 'var/www/html/';
local      = 'http://121.42.158.99/virtual/';


class GetReq_nyist(GetReq):     #这个是NYISTOJ爬虫的特定代码
	
	oj_url = 'http://acm.nyist.net'
	oj	   = 'nyist';

	def __init__(self):
		
		super(GetReq_nyist,self).__init__();
		self.pro_url = 'http://acm.nyist.net/JudgeOnline/problem.php?pid=';
	
	def public_pro(self):
	
		p = re.search(r'(尚未公开)[^$]*<a href="problemset.php">正在跳转到problemset.php</a>',self.page);
		if p == None:
			return True;
		else:
			return False;


	def end_pro(self):
		
		p = re.search(r'题目不存在[^$]*<a href="problemset.php">正在跳转到problemset.php</a>',self.page);
		if p == None:
			return False;
		else:
			return True;
	
	
	def get_title(self):
		
		try:
			p = re.search(r'<H2>([^&][\d\D]*)</H2>',self.page);
			self.title = p.group(1);
		except Exception as err:
			sys.stderr.write('这个是第%d题\n' % self.proid + str(err));
			sys.stderr.write(self.page);
			exit();

	def get_page(self):			#把HTML页面里面初步
		
		p = re.compile(r'(<DIV class="problem-ins">[\d\D]*)<DT>上传者</DT>');
		p = p.search(self.page);
		self.page = p.group(1);

	def update_source(self):	#NYOJ的来源都是OJ的相对路径，这里改成绝对路径
		
		p = re.compile(r'<DT>来源</DT>[^<]+<DD><a href="([^"]+)">');
		m = p.search(self.page);
		if m != None:
			self.page = self.page[:m.start(1)] + \
						self.oj_url + self.page[m.start(1):];

#有些图片的地址也是NYOJ的相对路径
	def update_pic(self):
		
		p = re.compile(r'<img.*?src="([^"]+)"?');
		pic_list = p.finditer(self.page);
		for i in pic_list:
			print(i.group(1));
			if re.search(r'file',i.group(1)):
				continue;
			if re.search(r'http',i.group(1)) == None:
				m = re.search(r'%s'%i.group(1) , self.page);
				self.page = self.page[:m.start()] + self.oj_url + self.page[m.start():];

	def get_time(self):

		p = re.compile(r'problem\[time_limit\]">([^<]*)<');
		p = p.search(self.page);
		self.time = p.group(1);
	
	def get_memory(self):
		
		p = re.compile(r'problem\[memory_limit\]">([\d]*)<');
		p = p.search(self.page);
		self.memory = p.group(1);	

	def get_description(self):
		
		p = re.compile(r'<DT>描述 </DT>[^<]*<DD>([\d\D]*?)</DD>');
		p = p.search(self.page);
		self.description = p.group(1);

	def get_input(self):
		
		p = re.compile(r'<DT>输入</DT>[^<]*<DD>([^<]*)<');
		p = p.search(self.page);
		self.pro_input = p.group(1);

	def get_output(self):
		
		p = re.compile(r'<DT>输出</DT>[^<]*<DD>([^<]*)<');
		p = p.search(self.page);
		self.pro_output = p.group(1);

	def get_simple_in(self):
		
		p = re.compile(r'<PRE id="sample_input">[^<]*</PRE>');
		p = p.search(self.page);
		self.simple_in = p.group();

	def get_simple_out(self):

		p = re.compile(r'<PRE id="sample_output">[^<]*</PRE>');
		p = p.search(self.page);
		self.simple_out = p.group();

	def get_source(self):
		
		p = re.compile(r'<DT>来源</DT>[^<]*<DD>(<a href="[^<]*</a>)<');
		p = p.search(self.page);
		if p == None:
			self.source = '';
		else:
			self.source = p.group(1);

	def get_hint(self):
		
		p = re.compile(r'<DT>提示</DT>[^<]*<DD>([\d\D]*?)</DD>');
		p = p.search(self.page);
		if p == None:
			self.hint = '';
		else:
			self.hint = p.group(1);

if __name__ == "__main__":

   
	node = GetReq_nyist();
	node.get_request(3);
	file_name = '../123.html';
	f = open(file_name,'w');
	f.write(node.page);
	f.close();

	'''
	node = GetReq_nyist();
    node.get_request(2);
    print(node.page);

'''




