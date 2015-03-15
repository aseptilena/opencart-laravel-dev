<?php 

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\Language;
use App\Service\ApplyService;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerAccountApplyVendor extends Autocontroller {
	public function index() {

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}
		$customer = Customer::find($this->customer->getId());
		if (!is_null($customer->user)) {
			$this->response->redirect($this->url->link('common/home'));
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
		foreach ($this->request->post as $key => $value) {
			$data[$key] = $value;
		}
		foreach ($this->error as $key => $value) {
			$data[('error_'.$key)] = $value;
		}
		$data['opencart'] = $this;

		$r = ViewManager::loadBlade('not-sure', 'vendor/apply/view.blade.php', $data);
		return $r->render();
	}
}