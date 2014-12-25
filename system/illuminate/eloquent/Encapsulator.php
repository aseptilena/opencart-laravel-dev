<?php  namespace App\Eloquent; 

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

/**
 * Class Encapsulator
 *
 * @author Original encapsulation pattern contributed by Kayla Daniels
 * @package App\Eloquent
 */
class Encapsulator
{
	private static $conn;

	private function __construct() {}

	static public function init()
	{
		if (is_null(self::$conn))
		{
			$capsule = new Capsule;

			$capsule->addConnection([
				'driver'    => 'mysql',
				'host'      => 'localhost',
				'database'  => 'opencart_2011',
				'username'  => 'root',
				'password'  => 'root',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => 'oc_',
			]);

			$capsule->setEventDispatcher(new Dispatcher(new Container));

			$capsule->setAsGlobal();

			$capsule->bootEloquent();
		}
	}
}
