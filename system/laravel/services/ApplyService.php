<?php namespace App\Service;

use App\Eloquent\Customer;
use App\Eloquent\User;

class ApplyService
{
	protected $customer;

	protected $params;

	protected $opencart;

	public function __construct($customer, $params, $opencart)
	{
		$this->customer = $customer;
		$this->params = $params;
		$this->opencart = $opencart;
	}
	public function validate()
	{
		$error = array();

		$user_count = User::where('username', '=', $this->params['username'])->count();
		if ($user_count > 0) {
			$error['username'] = '重複了';
		}
		if ((utf8_strlen($this->params['username']) < 2) || (utf8_strlen($this->params['username']) > 96)) {
      		$error['username'] = '帳號請在2之128字之內';
    	}
		if ((utf8_strlen($this->params['company']) < 2) || (utf8_strlen($this->params['company']) > 128)) {
      		$error['company'] = '商店名稱密碼請在2之128字之內';
    	}
    	if ((utf8_strlen($this->params['password']) < 4) || (utf8_strlen($this->params['password']) > 20)) {
      		$error['password'] = '密碼請在4之20字之內';
    	}

		return $error;
	}
	public function apply()
	{
		if ($this->opencart->config->get('mvd_signup_auto_approval')) {
			if (!file_exists(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $this->params['username']))) {
				mkdir(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $this->params['username']), 0777);
			}
		}

		$customer = $this->customer;

		$columns = ['bank_name', 'iban', 'swift_bic', 'tax_id', 'bank_address', 'fax', 'company_id', 'store_url', 'store_description'];
		foreach ($columns as $column) {
			$this->params[$column] = '';
		}

		$columns = ['firstname', 'lastname', 'email', 'telephone'];
		foreach ($columns as $column) {
			$this->params[$column] = $customer->{$column};
		}

		$address = $customer->address;
		$columns = ['address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id'];
		foreach ($columns as $column) {
			$this->params[$column] = $address->{$column};
		}
		$this->params['paypal'] = $customer->email;
		$this->params['singup_plan'] = '2:0:1:0:5';
		$this->params['hsignup_plan'] = 'Gold (5%) - 99999';
		$this->params['payment_method'] = 0;

		$this->opencart->load->model('account/signup');
		$this->opencart->model_account_signup->addVendorSignUp($this->params);
	}
}