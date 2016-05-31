#!/usr/bin/env python3
#coding : utf-8

import random;
import hashlib;
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
from post import *;

user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) \
			  Chrome/49.0.2623.112 Safari/537.36'


class hdu_Post(Post):

	login_url = 'http://acm.hdu.edu.cn/userloginex.php?action=login';
	
	def __init__(self):
		
		super(hdu_Post , self).__init__();
		self.oj = 'hdu';

	
	def url_open(self,url):								#打开一个页面，html存到self.page 里面
		
		T = 10;											#设定尝试10次，失败就终止然后debug
		while T != 0:
			try:
				chose = random.randint(0 , len(ge.GetReq.proxies) - 1);			#从代理列表里面随机一个代理
				print(ge.GetReq.proxies[chose])
				req = requests.get(url = url , proxies = ge.GetReq.proxies[chose] , headers = self.headers , timeout = 2);
				req.encoding = 'gb2312';				#杭电的编码是gb2312的，比较坑爹
				self.page = req.text;
				return;

			except Exception as er:
				T = T - 1;
				print(str(er));
				print('捕获异常')
#				GetReq.chose = GetReq.chose + 1;
		print('失败');	
	
	def log_in(self , vir_num):
	
		self.num = vir_num;
		req		 = requests.Session();
		headers  = {'User-Agent' : user_agent}; 
		data     = {'username' : 'nyistvir%d' %vir_num , 'userpass' : 'caonimabi' , 'login' : 'Sign+In'};
		req.post(url = self.login_url , data = data , headers = self.headers , timeout = 3);
		self.session = req;

	def get_code(self):

		sql = SQL();
		data = sql.select_code(self.local_runid);
		
		self.data  = {'problemid' : data[0],'language': data[1],'usercode':data[2]};
		self.proid = int(data[0]);

	def post_code(self):

		sub_url = 'http://acm.hdu.edu.cn/submit.php?action=submit';
		req = self.session.post(url = sub_url , data = self.data , headers = self.headers);
		req.encoding = 'gbk';
		p = re.compile(r'No such problem');
		p = p.search(req.text);
		if p == None:
			return False;
		else:
			return True;

	def get_runid(self):
		
		end_url = 'http://acm.hdu.edu.cn/status.php';
		self.url_open(end_url);
		p = re.compile(r'<td height=22px>([\d]+)<[\d\D]*?nyistvir%d</a></td>' % self.num);
		p = p.search(self.page);
		
		return int(p.group(1));
	
	def get_end_url(self):

		s = 'http://acm.hdu.edu.cn/status.php?first=&pid=&user=nyistvir%d&lang=0&status=0' % self.num;
		return s;
	
	def get_ce_msg(self , runid):
		
		ce_url = 'http://acm.hdu.edu.cn/viewerror.php?rid=%d' % runid;
		self.url_open(ce_url);
		p = re.compile(r'<pre>[\d\D]*?</pre>');
		p = p.search(self.page);
		sql = SQL();
		sql.insert_ce(p.group(0) , self.local_runid);

	def get_result(self , runid):
	
		
		p = re.compile(r'<td height=22px>%d</td><td>[^<]*</td>[\d\D]*?<font[^>]*>([\d\D]+?)</font>[\d\D]*?</td><td><a href[^>]*>[\d]*</a></td><td>([^<]*)</td><td>([^<]*)</td><td>' % runid);
		
		p = p.search(self.page);
		s = [p.group(1) , p.group(2) , p.group(3)];
		if s[0] == 'Queuing':
			s[0] = '判题中...';
		if s[0] == 'Compiling':
			s[0] = '判题中...';
		if s[0] == 'Running':
			s[0] = '判题中...';
		if s[0] == 'Compilation Error':
			s[0] = 'CompileError';
		return s;
		
def get_pip(jb):
	
	while True:
		file_name = '../fifo/runing';
		with open(file_name , 'r') as f:
			s = f.read(10);
			jb.put(int(s));
		
def run(num , jb):
	
	while True:
		runing = hdu_Post();
		runing.get_local_runid(jb);
		runing.get_return(num);
		
if __name__ == '__main__':
	
	'''
	jb = queue.Queue();
	th_list = list();
	for i in range(1,6):
		th_list.append(th.Thread(target = run , args = (i , jb)));
		th_list[i - 1].setDaemon(True);
	

	for i in th_list:
		i.start();
	get_pip(jb);
	
	for i in th_list:
		i.join();

'''

	req = hdu_Post();
	req.log_in(1);
	req.local_runid = 225;
	req.get_code();
	req.post_code();
	runid = req.get_runid();
	end_url = req.get_end_url();
	req.url_open(end_url);
	result = req.get_result(runid);
	print(result);
