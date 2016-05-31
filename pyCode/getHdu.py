#!/usr/bin/env python3
#coding : utf-8

import requests;
import re;
import random;					
from  getReq import *;
import queue;
import threading as th;
import copy;
import sys

user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 \
			 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'
http_home  = 'var/www/html/';
local      = 'http://121.42.158.99/virtual/';


class GetReq_hdu(GetReq):  
	
	oj_url = 'http://acm.hdu.edu.cn'
	oj	   = 'hdu';

	def __init__(self):
		
		super(GetReq_hdu,self).__init__();
		self.pro_url = 'http://acm.hdu.edu.cn/showproblem.php?pid=';
	
	def url_open(self,url):								#打开一个页面，html存到self.page 里面
		
		T = 10;											#设定尝试10次，失败就终止然后debug
		while T != 0:
			try:
				chose = random.randint(0 , len(GetReq.proxies) - 1);			#从代理列表里面随机一个代理
				print(GetReq.proxies[chose])
				req = requests.get(url = url , proxies = GetReq.proxies[chose] , headers = self.headers , timeout = 2);
				req.encoding = 'gb2312';				#杭电的编码是gb2312的，比较坑爹
				self.page = req.text;
				return;

			except Exception as er:
				T = T - 1;
				print(str(er));
				print('捕获异常')
#				GetReq.chose = GetReq.chose + 1;
		print('失败');	
	def ch_code(self , string):
		
#uncode = string.encode('ISO-8859-1');
#		utfcode = uncode.decode('gb2312');
#uncode = string.encode('ISO-8859-1');
#		utfcode = uncode.decode('utf-8');
		return string.replace('gb2312' , 'utf-8');

	def get_end_num(self):

		page_url = 'http://acm.hdu.edu.cn/listproblem.php?vol=1';
		self.url_open(page_url);
		p = re.compile(r'<font size=3>[\d\D]*?([\d]+)</a></font></p><br></td></tr><tr>');
		p = p.search(self.page);
		page_url = 'http://acm.hdu.edu.cn/listproblem.php?vol=' + p.group(1);
		self.url_open(page_url);
		self.page =  self.ch_code(self.page);
		p = re.compile(r'p\(\d,([\d]+),-1,');
		find_list = p.findall(self.page);
		max_num = 1;
		for i in find_list:
			max_num = max(max_num , int(i));
		return max_num;

	def public_pro(self):
	
		p = re.search(r'System Message' , self.page);
		if p == None:
			return True;
		else:
			return False;


	def end_pro(self):
		
		return False;
	
	def get_title(self):

		self.page = self.ch_code(self.page);
		p = re.compile(r'<h1[^>]+>([^<]+)<');
		p = p.search(self.page);
		self.title = p.group(1);

	def get_page(self):			#把HTML页面里面初步
		p = re.compile(r'(<h1[\d\D]*)<a href=\'statistic');
		p = p.search(self.page);

		self.page = p.group(1);

	def update_source(self):	#NYOJ的来源都是OJ的相对路径，这里改成绝对路径
		p = re.compile(r'Source</div>[\d\D]*?<a href="([^"]*)"');
		m = p.search(self.page);
		if m != None:
			self.page = self.page[:m.start(1)] + \
						self.oj_url + self.page[m.start(1):];

	
	'''
	有些图片的地址也是hdu的相对路径,hdu的图片路径太坑爹了
	'''
	def update_pic(self):
		p = re.compile(r'<img.*?src=(["./]*?(/data[^>]+))')
		pic_list = p.finditer(self.page);
		for i in pic_list:
			if re.search(r'http' , i.group(1)):
				continue;
			if i.group(2) == None:
				continue;
			self.page = self.page.replace(i.group(1) , self.oj_url + i.group(2));
			print(i.group(1))
			print(i.group(2));

	def get_time(self):

		p = re.compile(r'Time Limit: ([^&]+)');
		p = p.search(self.page);
		self.time = p.group(1);
	
	def get_memory(self):
		
		p = re.compile(r'Memory Limit: ([^<]+)<');
		p = p.search(self.page);
		self.memory = p.group(1);	

	def get_description(self):
	
		p = re.compile(r'Description</div> <div class=panel_content>([\d\D]*?)</div>');
		p = p.search(self.page);
		if p == None:
			self.description = '';
			return;

		self.description = p.group(1);

	def get_input(self):
		
		p = re.compile(r'Input</div> <div class=panel_content>([\d\D]+?)</div>');
		p = p.search(self.page);
		try:
			self.pro_input = p.group(1);
		except Exception as err:
			self.pro_input = '';	

	def get_output(self):
		
		p = re.compile(r'Output</div> <div class=panel_content>([\d\D]+?)</div>');
		p = p.search(self.page);
		if p == None:
			self.pro_output = '';
			return;

		self.pro_output = p.group(1);

	def get_simple_in(self):
		
		p = re.compile(r'Sample Input</div>([\d\D]+?</div></pre></div>)');
		p = p.search(self.page);
		if p == None:
			self.simple_in = '';
			return;

		self.simple_in = p.group(1);

	def get_simple_out(self):
		
		p = re.compile(r'Sample Output</div>([\d\D]+?</div></pre></div>)');
		p = p.search(self.page);
		if p == None:
			self.simple_out = '';
			return;

		self.simple_out = p.group(1);
		
	def get_source(self):
		
		p = re.compile(r'Source</div>[\d\D]*?(<a href="[\d\D]+?</a>)');
		p = p.search(self.page);
		if p == None:
			self.source = '';
		else:
			self.source = p.group(1);

	def get_hint(self):
		
		self.hint = '';
		
def run(jb , req_out):
	
	while True:
		proid = jb.get();
		if proid == 'end':
			jb.put('end');
			return;
		node = copy.deepcopy(req_out);
		url = node.pro_url + proid;
		node.url_open(url);
		if node.public_pro():
			print(proid);
			jb.put('end');
			return;


if __name__ == "__main__":

	'''
	jb = queue.Queue();
	node = GetReq_hdu();
	for i in range(1000,5695):
		jb.put(str(i));
	jb.put('end');

	th_list = list();
	for i in range(500):
		t = th.Thread(target = run , args = (jb , node));
		t.setDaemon(True);
		t.start();
		th_list.append(t);

	for i in th_list:
		i.join();
	'''

	proid = 5541;
	node = GetReq_hdu();
	max_num = node.get_end_num();
	url = 'http://acm.hdu.edu.cn/showproblem.php?pid=' + str(proid);
	node.url_open(url);
	node.get_title();
	node.get_page();
	node.update_source();
	node.update_pic();
	node.get_time();
	node.get_memory()
	node.get_description()
	node.get_input();
	node.get_output()
	node.get_simple_in()
	node.get_simple_out()
	node.get_source();
	file_name = '../123.html';
	f = open(file_name,'w');
	f.write(node.page);
	f.close();

	'''
	node = GetReq_hdu();
    node.url;
    print(node.page);
'''





