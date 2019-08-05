<?php
ini_set('date.timezone','Asia/Shanghai'); // 'Asia/Shanghai' 为上海时区
require("./../vendor/autoload.php");

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\AbstractProcessingHandler;
class PDOHandler extends AbstractProcessingHandler
{
	private $initialized = false;
	private $pdo;
	private $statement;
	public function __construct(PDO $pdo, $level = Logger::DEBUG, $bubble = true)
	{
		$this->pdo = $pdo;
		parent::__construct($level, $bubble);
	}
	protected function write(array $record)
	{
		if (!$this->initialized) {
			$this->initialize();
		}
		$this->statement->execute(array(
			'channel' => $record['channel'],
			'level' => $record['level'],
			'message' => $record['formatted'],
			'time' => $record['datetime']->format('U'),
		));
	}
	private function initialize()
	{
		$this->pdo->exec(
			'CREATE TABLE IF NOT EXISTS monolog '
			.'(channel VARCHAR(255), level INTEGER, message LONGTEXT, time INTEGER UNSIGNED)'
		);
		$this->statement = $this->pdo->prepare(
			'INSERT INTO monolog (channel, level, message, time) VALUES (:channel, :level, :message, :time)'
		);
	}
}
// 创建日志频道
$log = new Logger('name');
$log->pushHandler(new StreamHandler(__DIR__.'/xubaoguo.log', Logger::WARNING));

// 添加日志记录
$log->addWarning('Foo');
$log->addError('Bar');

$log->pushHandler(new PDOHandler(new PDO('sqlite:logs.sqlite')));

// You can now use your logger
$log->addInfo('My logger is now ready');

use Monolog\Handler\SocketHandler;

// Create the logger
$logger = new Logger('my_logger');

// Create the handler
$handler = new SocketHandler('unix:///var/log/httpd_app_log.socket');
$handler->setPersistent(true);

// Now add the handler
$logger->pushHandler($handler, Logger::DEBUG);

// You can now use your logger
$logger->addInfo('My logger is now ready');