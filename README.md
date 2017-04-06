# virtualJudge
虚拟在线测评系统，通过爬虫向其他OJ交题并返回结果

环境:
ubuntu 14.04 apache 2.4 php 5.5 mysql 5.5 python 3.4 # 本机默认python还是2.7

第三方模块:

python connector mysql:

wget https://cdn.mysql.com//Downloads/Connector-Python/mysql-connector-python_2.1.5-1ubuntu14.04_all.deb

sudo dpkg -i mysql-connector-python-py3_2.1.5-1ubuntu14.04_all.deb

其他发行版去 http://dev.mysql.com/downloads/connector/python/ 下载



requests:

sudo apt-get install python3-pip

sudo pip3 install requests

其他版本去 http://docs.python-requests.org/zh_CN/latest/user/install.html 自行根据文档下载

由于管道文件没找到方法上传至GIT，这里手动创建

cd virtualJudge/

mkdir fifo

cd fifo

sudo mkfifo runing

chown www-data runing #ubuntu apache/nginx 的默认用户是www-data 让PHP可写


