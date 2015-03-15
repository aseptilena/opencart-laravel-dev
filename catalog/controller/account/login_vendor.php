<?php 

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\Language;
use App\Service\ApplyService;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerAccountLoginVendor extends Autocontroller {
	public function index() {

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}
		$customer = Customer::find($this->customer->getId());
		if (is_null($customer->user)) {
			$this->response->redirect($this->url->link('common/home'));
		}

		$this->document->setTitle('登入商店');
		$this->data['heading_title'] = '登入商店';
		parent::index();

	}
	public function content() {
		foreach ($this->request->post as $key => $value) {
			$data[$key] = $value;
		}
		foreach ($this->error as $key => $value) {
			$data[('error_'.$key)] = $value;
		}
		$data['opencart'] = $this;
		$data['admin_url'] = HTTP_SERVER.'admin';

		$r = ViewManager::loadBlade('not-sure', 'vendor/login/view.blade.php', $data);
		return $r->render();
	}
}