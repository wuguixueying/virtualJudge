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
import nyPost as ny;
import mySQL as sqll
from post import *;
import hduPost as hdu;

user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) \
			  Chrome/49.0.2623.112 Safari/537.36'


def get_pip(dis):													#这个是过去管道的数据的。dis是ioj-消息队列的映射
	
	sql = sqll.SQL();
	while True:
		file_name = '../fifo/runing';
		f = open(file_name , 'r');
		runid_list = f.readlines();
		f.close();
		for runid in runid_list:
			runid = int(runid);
			oj = sql.select_oj(runid);								#根据运行好得到这个oj是哪个
			print(runid);
			dis[oj].put(runid);										#根据映射表把运行好送入那个消息队列

def run(num , jb , post):											#参数是线程号，消息队列，和一个Post对象
	
	'''
	这个是线程统一函数。因为post对象的不同oj都有同样的方法，所以直接搞
	'''
	while True:

		runing = copy.deepcopy(post);								
		runing.get_local_runid(jb);
		runing.get_return(num);

def threads(dis ,runing):													
	
	th_list = list();
	for i in range(5):
		t = th.Thread(target = run , args = (i , dis[runing.oj] , runing));
		th_list.append(t);
		t.setDaemon(True);
	
	for a in th_list:
		a.start();
	
	return th_list;

		
if __name__ == '__main__':
	
	qu_list = list();
	for i in range(2):
		jb = queue.Queue();
		qu_list.append(jb);
	
	dis = {'nyist' : qu_list[0] , 'hdu' : qu_list[1]};									#定义一个组映射
	runing = ny.ny_Post();
	t = threads(dis , runing);
	runing = hdu.hdu_Post();
	t = threads(dis , runing);

	get_pip(dis);


