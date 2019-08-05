vim /usr/local/sphinx/etc/sphinx.conf
生成索引
sudo /usr/local/sphinx/bin/indexer --config /usr/local/sphinx/etc/sphinx.conf --all

启动sphinx
/usr/local/sphinx/bin/searchd --config /usr/local/sphinx/etc/sphinx.conf

登录sphinx数据库
mysql -h 127.0.0.1 -P 9306

select count(*) from master;