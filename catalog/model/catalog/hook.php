<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;

class ModelCatalogHook extends Model {
	public function customerCanApply() {
		$customer = Customer::find($this->customer->getId());
		return is_null($customer->user);
	}
	public function getVendorId() {
		$customer = Customer::find($this->customer->getId());
		$user = $customer->user;
		if (!$user) {
			return 0;
		}
		return $user->vendor->vendor_id;
	}
}