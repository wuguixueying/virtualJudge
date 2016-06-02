#!/usr/bin/env python3
#coding : utf-8

'''
更新代理的模块
'''
import requests;
import os;
import re;
import urllib.request;
import threading as th;
import queue;
import mySQL as sqll
import sys;

#proxy_url = 'http://cn-proxy.com/'
# proxy_url = 'http://www.xicidaili.com/nn'
# proxy_url = 'http://www.youdaili.net/Daili/http/4500.html'
proxy_url = 'http://www.xicidaili.com/nt/'
user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'
headers = {'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8 ', 'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'}



def url_open(url):
		
	# headers = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'
	# req = urllib.request.Request(url);
	# req.add_header('User-Agent' , headers);
	# req = urllib.request.urlopen(req)
	# return req.read().decode('utf-8');
	req = requests.get(url = url , headers = headers);
	print(req.status_code);
	return req.text;

def find_ip(html):

	p = re.compile(r'((?<![0-9.])((2[0-4][0-9]|25[0-5]|[01]?[0-9]{1,2})\.){3}(2[0-4][0-9]|25[0-5]|[01]?[0-9]{1,2})(?![0-9.]))[^\d]*(\d*)<');
	ip_iter = p.finditer(html);
	ip_list = []
	for i in ip_iter:
		ip_list.append(i.group(1) + ':' + i.group(5))
	return ip_list;

def check(jbin , jbout , jbdel):

	while True:
		ip = jbin.get();
		if ip == 'end':
			jbin.put('end');
			return
		proxies = {'http' : ip};
		print(proxies);
		try:
			req = requests.get('http://acm.hdu.edu.cn/' , proxies = proxies , headers = headers , timeout = 2);
		except Exception as err:
			jbdel.put(ip);
			print('sfdsf');
			continue;
		print(req.status_code);
		print(ip);
		if req.status_code == 200:
			jbout.put(ip);

def th_check(ip_list):

	th_list = []
	jbin = queue.Queue()
	jbout = queue.Queue()
	jbdel = queue.Queue()
	for i in range(100):
		t = th.Thread(target=check, args=(jbin, jbout , jbdel));
		t.setDaemon(True);
		t.start();
		th_list.append(t);
	for i in ip_list:
		jbin.put(i);
	jbin.put('end');
	for i in th_list:
		i.join();
	sql = sqll.SQL()
#	while not jbout.empty():
#		sql.insert_ip(jbout.get());
	while not jbdel.empty():
	 	sql.del_ip(jbdel.get());
	 	print('del :')

if __name__ == "__main__":

	sql = sqll.SQL();
#	html = url_open(proxy_url)
#	ip_list = find_ip(html)
	ip_list = sql.find_ip();
	th_check(ip_list);
	ip_list = sql.find_ip();
	for i in ip_list:
		print(i);


	print('end')



