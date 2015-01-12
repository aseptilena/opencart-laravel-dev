<?php namespace App\Task;

use App\Eloquent\Customer;
use App\Eloquent\Task;
use App\Eloquent\Log;
use App\Eloquent\Level;
use App\Eloquent\ProfitRecord;
use App\Service\GrantService;
use App\Service\GrantLeaderService;
use Illuminate\Database\Capsule\Manager as DB;

class GrantTask
{
	public function boot()
	{
		$date = \MyDateTime::init('first_day_of_now');
		if (Task::where('date', '=', $date)->where('type', '=', 'grant')->count() > 0) {
			Log::create(['record' => 'try to grant, but grant of this month already done']);
			return;
		}
		Log::create(['record' => 'start GrantTask']);

		$customer_offset = 0;
		while (true) {
			$customer = Customer::where('customer_id', '>', $customer_offset)->orderBy('customer_id', 'asc')->take(1)->first();
			if (!$customer) {
				break;
			}
			$customer_offset = $customer->customer_id;
			// echo "customer_id:$customer_offset<br>";
			$service = new GrantService($customer, $date, true);
			$service->grant();
		}
		Log::create(['record' => 'finish GrantTask']);



		$leader_levels = Level::leaderLevels();
		$summary = ProfitRecord::summary($date, $date);
		if ($summary->consumption * 0.5 < $summary->bonus) {
			Log::create(['record' => 'bonus greater than consumption 50 percents, do not grant leader']);
			return;
		}
		$grant_money = $summary->consumption * 0.5 - $summary->bonus;
		Log::create(['record' => 'start grant leader']);

		$total_consumption = ProfitRecord::leaderConsumption($date, $date, $leader_levels);

		$customer_offset = 0;
		while (true) {
			$customer = Customer::where('customer_id', '>', $customer_offset)->whereIn('level_id', $leader_levels)->orderBy('customer_id', 'asc')->take(1)->first();
			if (!$customer) {
				break;
			}
			$customer_offset = $customer->customer_id;
			$service = new GrantLeaderService($customer, $date, $grant_money, $total_consumption);
			$service->grantLeaderBonus();
		}

		Task::create(['date' => $date, 'type' => 'grant']);
	}
}