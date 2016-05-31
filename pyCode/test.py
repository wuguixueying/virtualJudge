#!/usr/bin/env python3
# coding : utf-8
import os;
from sql import SQL;

sql = SQL();
sql.connect();
s = sql.select_code(96);
print(s)
sql.close()
