<?php namespace App\Task;

use App\Eloquent\Customer;
use App\Eloquent\Task;
use App\Eloquent\Log;
use App\Service\UpgradeService;

class UpgradeTask
{
	public function boot()
	{
		$date = \MyDateTime::init('first_day_of_now');
		if (Task::where('date', '=', $date)->where('type', '=', 'upgrade')->count() > 0) {
			Log::create(['record' => 'try to upgrade, but upgrade of this month already done']);
			return;
		}
		Log::create(['record' => 'start UpgradeTask']);

		$customer_offset = 0;
		while (true) {
			$customer = Customer::where('customer_id', '>', $customer_offset)->orderBy('customer_id', 'asc')->take(1)->first();
			if (!$customer) {
				break;
			}
			$customer_offset = $customer->customer_id;
			// echo "customer_id:$customer_offset<br>";

			$service = new UpgradeService($customer, $date);
			$service->upgrade();
		}
		Log::create(['record' => 'finish UpgradeTask']);
		Task::create(['date' => $date, 'type' => 'upgrade']);
	}
}