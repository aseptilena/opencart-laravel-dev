<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Product;
use App\Eloquent\Customer;
use App\Eloquent\Upload;
use App\Service\AssignTreeService;

class ModelToolHook extends Model {
	public function afterAddCustomer($customer_id, $data) {
		if ($data['promo'] != '') {
			$promo = $data['promo'];
		}
		else if (isset($this->session->data['promo'])) {
			$promo = $this->session->data['promo'];
		}
		if (isset($promo)) {
			$promo_customer = Customer::where('promo', '=', $promo)->first();
			if (!$promo_customer) {
				$promo_customer = Customer::find(ROOT_CUSTOMER);
			}
		}
		else {
			$promo_customer = Customer::find(ROOT_CUSTOMER);
		}
		$customer = Customer::find($customer_id);
		$service = new AssignTreeService($customer, $promo_customer);
		$service->assign();

		$customer->promo = Customer::generatePromo();
		$customer->level_id = 1;
		$customer->save();
	}
	public function validateRegister(&$error, $data) {
		if ($data['promo'] != '') {
			$promo = $data['promo'];
			$customer = Customer::where('promo', '=', $promo)->first();
			if (!$customer) {
				$error['promo'] = '找不到推薦碼對應的會員';
			}
		}
	}
	public function getUpload($custom_field) {
		return Upload::where('code', '=', $custom_field)->first();
	}
	public function getCooperation($promo) {
		return Customer::where('promo', '=', $promo)->first();
	}
}