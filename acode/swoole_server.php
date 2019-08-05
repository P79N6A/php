<?php
/**
Swoole功能介绍

包含以下几个特色功能：

1、 类似ORM的数据查询，提供SQL封装器，让MySQL的SQL与PHP的Array，会话，Cache无缝结合。

2、App MVC分层结构，有效的程序结构分层，提高程序的可维护性和扩展性，实现低耦合，基于接口开发。

3、集成大量，实用的功能，比如方便的数据库操作，模板操作，缓存操作，系统配置，表单处理，分页，数据调用，字典操作，上传处理，内容编辑，调试等。

4、模板-数据反射系统，可以直接在模板中调用数据，提供很多标签，可是无需修改程序，只修改模板，即可实现网站各类更新维护工作。

另外的几个功能

1、Swoole包含了大量类，提供众多的功能扩展，基本上Web开发能够用到的功能类，大部分都可以在Swoole框架中找到。

2、Swoole拥有插件系统，Fckeditor、Adodb、pscws中文分词、中文全文索引系统、最新的Key-Value数据库思想，TokyoTyrant，可以无限扩展框架的功能。
**/

class Server
{
	private $serv;
	public function __construct()
	{
		$this->serv = new swoole_server("0.0.0.0", 9501);
		$this->serv->set(array(
				'worker_num' => 1,
				'daemonize' => false,
				'max_request' => 10000,
				'dispatch_mode' => 2,
				'debug_mode' => 1
			)
		);
		$this->serv->on('Start', array($this, 'onStart'));
		$this->serv->on('Connect', array($this, 'onConnect'));
		$this->serv->on('Receive', array($this, 'onReceive'));
		$this->serv->on('Close', array($this, 'onClose'));
		$this->serv->start();
	}

	public function onStart($serv)
	{
		echo "Start\n";
	}

	public function onConnect($serv, $fd, $from_id)
	{
		$serv->send($fd, "Hello {$fd}!");
	}

	public function onReceive(swoole_server $serv, $fd, $from_id, $data)
	{
		echo "Get Message From Client {$fd}:{$data}\n";
	}

	public function onClose($serv, $fd, $from_id)
	{
		echo "Client {$fd} close connection\n";
	}
}
// 启动服务器
$server = new Server();