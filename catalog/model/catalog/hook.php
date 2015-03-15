<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;

class ModelCatalogHook extends Model {
	public function customerCanApply() {
		$customer = Customer::find($this->customer->getId());
		return is_null($customer->user);
	}
}