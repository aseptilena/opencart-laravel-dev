<?php 

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerBusinessInfo extends Autocontroller {
	public function index() {

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}

		$this->document->setTitle('開店');
		$this->data['heading_title'] = '開店';
		parent::index();

	}
	public function post() {
		$customer = Customer::find($this->customer->getId());

		$service = new ApplyService($customer, $this->request->post, $this);

		$this->error = $service->validate();
		if (count($this->error) == 0) {
			$service->apply();
			$this->response->redirect($this->url->link('account/signupsuccess'));
		}
	}
	public function content() {
		$data = array();
		$customer = Customer::find($this->customer->getId());
		$data['customer'] = $customer;
		$data['upgrade_histories'] = $customer->upgradeHistories;
		$data['profit_records'] = $customer->profit_records()->get();
		$data['ready_levels'] = $customer->getReadyLevels();

		$r = ViewManager::loadBlade('not-sure', 'business/info.blade.php', $data);
		return $r->render();
	}
}