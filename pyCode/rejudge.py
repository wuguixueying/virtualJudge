#!/usr/bin/env python3
# coding : utf-8
import os;
import multiprocessing as mu;
import mySQL as sqll;

"""
def run(runid):
	
	print(runid);
	f = open('../fifo/runing' , 'w');
	print(runid);
	f.write(runid);
	f.close()

if __name__ == '__main__':
	sql = sqll.SQL();
	a = sql.select_no_return();
	print(a)
	poll = mu.Pool(10);

	for i in a:
		runid = '%d\n' % i;
		poll.apply_async(run , args = (runid ,));

	poll.close();
	poll.join();
"""

if __name__ == "__main__":
	sql = sqll.SQL();
	a = sql.select_no_return();
	for i in a:
		runid = '%d\n' % i;
		f = open('../fifo/runing' , 'w');
		print(runid);
		f.write(runid);
		f.close()

