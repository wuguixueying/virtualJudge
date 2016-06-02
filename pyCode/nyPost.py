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
from mySQL import *;
from post import *;

user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) \
			  Chrome/49.0.2623.112 Safari/537.36'


class ny_Post(Post):

	login_url = 'http://acm.nyist.net/JudgeOnline/dologin.php';
	
	def __init__(self):
		
		super(ny_Post , self).__init__();
		self.oj = 'nyist';

	def log_in(self , vir_num):
	
		self.num = vir_num;
		req		 = requests.Session();
		headers  = {'User-Agent' : user_agent}; 
		data     = {'userid' : 'vir%d' % vir_num , 'password' : 'caonimabi'};
		req.post(url = self.login_url , data = data , headers = self.headers);
		self.session = req;

	def get_code(self):

		sql = SQL();
		data = sql.select_code(self.local_runid);
		
		self.data  = {'language': data[1],'code':data[2]};
		self.proid = int(data[0]);

	def post_code(self):

		sub_url = 'http://acm.nyist.net/JudgeOnline/submit.php?pid=' + str(self.proid);
		req = self.session.post(url = sub_url , data = self.data , headers = self.headers);
		p = re.compile(r'题目号有误');
		p = p.search(req.text);
		if p == None:
			return False;
		else:
			return True;

	def get_runid(self):
		
		end_url = 'http://acm.nyist.net/JudgeOnline/status.php';
		self.url_open(end_url);
		p = re.compile(r'>([\d]*)</td>[^<]*<td class="u-name"><a target="_blank"[^h]*href="profile.php\?userid=vir%d">' % self.num);
		p = p.search(self.page);			
		return int(p.group(1));
	
	def get_end_url(self):

		s = 'http://acm.nyist.net/JudgeOnline/status.php?do=search&pid=&userid=vir%d&language=0&result=0' % self.num;
		return s;
	
	def get_ce_msg(self , runid):
		
		ce_url = 'http://acm.nyist.net/JudgeOnline/CE.php?runid=%d' % runid;
		self.url_open(ce_url);
		p = re.compile(r'<div style=" width:[^>]*>(错误[\d\D]*?)</div>');
		p = p.search(self.page);
		sql = SQL();
		sql.insert_ce(p.group(1) , self.local_runid);

	def get_result(self , runid):
		
		p = re.compile(r'<td>%d</td>[^<]*<td class="u-name"><a target="_blank" href="profile.php\?userid=vir%d">vir%d</a></td><td><[^>]*>[^<]*</a></td><td>[^<]*<[^>]*>([^<]*)<[^\d]*?([\d-]+)[^\d]*?([\d-]+)' % (runid , self.num , self.num));
		p = p.search(self.page);
		return [p.group(1) , p.group(2) , p.group(3)];

def get_pip(jb):
	
	while True:
		file_name = '../fifo/runing';
		with open(file_name , 'r') as f:
			s = f.read(10);
			jb.put(int(s));
		
def run(num , jb):
	
	while True:
		runing = ny_Post();
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

	req = ny_Post();
	req.get_ce_msg(1589924);
