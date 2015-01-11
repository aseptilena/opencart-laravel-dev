<?php namespace App\Task;

use App\Eloquent\Customer;
use App\Eloquent\Task;
use App\Eloquent\Log;
use App\Service\GrantService;

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
		Task::create(['date' => $date, 'type' => 'grant']);
	}
}