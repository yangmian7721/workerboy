<?php
namespace GatewayWorker\Lib;
/**
 * 数据库类
 * @author walkor <walkor@workerman.net>
 */
class Db {
	/**
	 * 实例数组
	 * @var array
	 */
	protected static $instance = array();

	/**
	 * 获取实例
	 * @param string $config_name
	 * @throws \Exception
	 */
	public static function instance($config_name)
	{
		$db_config = \Config::get('workerboy.db.' . $config_name);
		if (!$db_config)
		{
			echo "workerboy.db.$config_name not set\n";
			throw new \Exception("workerboy.db.$config_name not set\n");
		}

		if (empty(self::$instance[ $config_name ]))
		{
			$config                         = $db_config;
			self::$instance[ $config_name ] = new \GatewayWorker\Lib\DbConnection(
				$config['host'],
				$config['port'],
				$config['user'],
				$config['password'],
				$config['dbname']
			);
		}

		return self::$instance[ $config_name ];
	}

	/**
	 * 关闭数据库实例
	 * @param string $config_name
	 */
	public static function close($config_name)
	{
		if (isset(self::$instance[ $config_name ]))
		{
			self::$instance[ $config_name ]->closeConnection();
			self::$instance[ $config_name ] = null;
		}
	}

	/**
	 * 关闭所有数据库实例
	 */
	public static function closeAll()
	{
		foreach (self::$instance as $connection)
		{
			$connection->closeConnection();
		}
		self::$instance = array();
	}
}