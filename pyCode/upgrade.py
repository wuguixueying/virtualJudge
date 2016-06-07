#!/usr/bin/env python3
#coding : utf-8

import copy;
import mySQL as sqll;
import sys;
import getReq as ge;
import re;
import os;
import multiprocessing as mu;
import threading as th;
import queue
import getNyist as ny;
import getHdu as hdu;

def run(jb, req_out):
	
	sql = sqll.SQL();
	while True:
		proid = jb.get();
		if proid == 'end':
			jb.put('end');		#接收到end信号将信号重新发回队列
			return;
		proid = int(proid);
		req = copy.deepcopy(req_out);
		req.get_request(proid);

		if req.end == True:
			print('end~~~');
			jb.put('end');		#向消息队列发送一个end信号
			return ;
		sql.insert(req);

def ny_thread():
	
	proid = 1;

	jb = queue.Queue();
	thlist = list();
	req = ny.GetReq_nyist();
	for i in range(10):
		t = th.Thread(target = run , args = (jb,req));
		thlist.append(t);
		t.setDaemon(True);
	
	while True:
		jb.put(str(proid));
		proid = proid + 1;
		if proid >= 1311:
			jb.put('end');
			break;

	for i in thlist:
		i.start();

	for i in thlist:
		i.join();

def hdu_thread():
	
	proid = 5000;

	jb = queue.Queue();
	thlist = list();
	req = hdu.GetReq_hdu();
	for i in range(100):
		t = th.Thread(target = run , args = (jb,req));
		thlist.append(t);
		t.setDaemon(True);

	num = req.get_end_num();
	while True:
		jb.put(str(proid));
		proid = proid + 1;
		if proid > num:
			jb.put('end');
			break;

	for i in thlist:
		i.start();

	for i in thlist:
		i.join();


if __name__ == "__main__":

	hdu_thread();
