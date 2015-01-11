<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Task\GrantTask;
use App\Task\UpgradeTask;
use App\Eloquent\Log;

class ControllerCommonCron extends Controller {

	public function index() {
		if (!isset($this->request->get['token'])) {
			Log::create(['record' => 'someone try to access cron, but token is missing']);
			echo 'failed';
			die();
		}
		if ($this->request->get['token'] != '0nxpp4w5e7mckdsl7si2w7plhzytksu79yay461om39bhv1y48sw58c867u8kae75ueqtrz00d0xb5s82bem2m9sqy9lgx5010cofoae9zxpv19kwb4zq3r8ezniw3e8wza5l7tu4gqbilxi631k5u8toh99wcg0t2fyx78it120pru8yjxrtt6on7tp39fecpuawyvqdo20ljuy8k7gr1p4cqwkpxgweo0af7117ap79gnhlnhl9oc6xbsgc83t8pejr2x88vcx0gxvgogkeg142odu0v2bt9fpm1eemnp8gifpzww7y98p6cr8qq7ggz6irzvezbyojkjizwb3m6gwc06z8h92sv2flgy9uue4l76uva4615bua11ww9nwsh6f2uluv7qhsv2j75h49ho9zktqztiexu21oz9bsbbl5pu24bgao9hzoby1l9cv2phrkigus4ykezp2njp8ew8avqncmwsmp8cieh05qxo0x5d34bgq') {
			Log::create(['record' => 'someone try to access cron, but token is incorrect']);
			echo 'failed';
			die();
		}
		Log::create(['record' => 'Token is passed']);

		$grantTask = new GrantTask();
		$grantTask->boot();
		
		$upgradeTask = new UpgradeTask();
		$upgradeTask->boot();
	}
}