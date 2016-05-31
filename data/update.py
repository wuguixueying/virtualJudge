#!/usr/bin/env python3
#coding:utf-8

import os;
import re;

if __name__ == '__main__':
	f = open('proxy','r');
	ip_list = [];
	for s in f:
		p = re.search(r':' , s);
		print(s[:p.start()]);
		ping = os.system('ping -w 1 %s' % s[:p.start()]);
		if ping == 0:
			ip_list.append(s);
	f.close();
	f = open('proxy','w');
	for i in ip_list:
		f.write(i)
	f.close();

