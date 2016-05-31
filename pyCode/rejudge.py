#!/usr/bin/env python3
# coding : utf-8
import os;
import multiprocessing as mu;
import mySQL as sqll;

def run(runid):
	f = open('../fifo/runing' , 'w');
	f.write(runid);
	print(runid);
	f.close()

if __name__ == '__main__':
	sql = sqll.SQL();
	a = sql.select_no_return();
	poll = mu.Pool(10);

	for i in a:
		runid = '%d\n' % i;
		poll.apply_async(run , args = (runid ,));

	poll.close();
	poll.join();
		

