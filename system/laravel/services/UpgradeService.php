<?php namespace App\Service;

use App\Eloquent\Customer;
use App\Eloquent\Level;
use Illuminate\Database\Capsule\Manager as DB;

class UpgradeService
{
	protected $customer;

	protected $date;

	public function __construct($customer, $date)
	{
		$this->customer = $customer;
		$this->date = $date;
	}

	public function upgrade()
	{
		$customer = $this->customer;
		$higher_levels = Level::where('position', '>', $customer->level->position)->orderBy('position', 'desc')->get();
		$ready_levels = array();
		$pass_levels = array();
		foreach ($higher_levels as $level) {
			$pass = $level->pass($customer);
			if ($pass == 'pass') {
				$pass_levels[] = $level;
			}
			else if ($pass == 'next') {
				$ready_levels[] = $level;
			}
		}
		$customer->setPassLevels($pass_levels, $this->date);
		$customer->setReadyLevels($ready_levels);
		$customer->save();
	}
}