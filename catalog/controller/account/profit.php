<?php

require_once(DIR_SYSTEM.'illuminate/load.php');

use App\Eloquent\Encapsulator;
use App\Eloquent\Customer;
use App\Eloquent\Address;

// use Illuminate\Validation\Factory;
use App\Validation\RegisterValidator;


class ControllerAccountProfit extends Controller {
	public function index() {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		$this->load->language('account/register');

		$messages = $this->validateMessages();
		$field = array(
			'firstname' => 'wqwjriqwljrliqwjerijwirjeqwlirjqwirjlqwijrileqwjrilwqejlirjil',
			'lastname' => '',
			'email' => 'asjfil@asjfli.corliwjriqwjrilqjril',
			);

		$stub = new RegisterValidator();
		$stub->with($field, $messages)->passes();
		print_r($stub->errors());

		// $register_data = array(
		// 	'firstname' => 'Steve',
		// 	'lastname' => 'Lee',
		// 	'email' => 'demo@gmail.com',
		// 	'password' => '12345678',
		// 	'ip' => $this->request->server['REMOTE_ADDR']
		// 	);
		// $customer = Customer::register($register_data);

		// $address = Address::find(1);
		// print_r($address->getData());

		// $customer = Customer::find(2);
		// print_r($customer->getAddresses());

		// $customer = new Customer;
		// $customer->email = 'John';
		// $customer->save();
		// $customer->addAddress(array('address_1'=>'Taipei'), true);

		// $customers = Customer::all();

		// foreach ($customers as $customer) {
		// 	echo 'firstname:'.$customer->firstname.'<br/>';
		// 	echo 'default address:'.$customer->address->address_1.'<br/>';

		// 	// Encapsulator::init()->connection()->enableQueryLog();

		// 	echo 'addresses:'.'<br/>';
		// 	foreach ($customer->addresses as $address) {
		// 		echo $address->address_1.'<br/>';
		// 	}
		// 	echo '<br/>';
		// }

		echo 'End';
	}


	public function validate() {
	}
	public function validateMessages()
	{
		return array(
			'firstname.required' => $this->language->get('error_firstname'),
			'firstname.between' => $this->language->get('error_firstname'),
			'lastname.required' => $this->language->get('error_lastname'),
			'lastname.between' => $this->language->get('error_lastname'),
			'email.max' => $this->language->get('error_email'),
			'email.email' => $this->language->get('error_email'),
			);
	}
}