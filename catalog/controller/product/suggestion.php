<?php 

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Service\SuggestionService;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerProductSuggestion extends Autocontroller {
	public function index() {
		$this->document->setTitle('商品建議');
		$this->data['heading_title'] = '商品建議';
		parent::index();

	}
	public function post() {
		$customer = Customer::find($this->customer->getId());

		$service = new SuggestionService($this->request->post, $this);

		$this->error = $service->validate();
		if (count($this->error) == 0) {
			$service->send();
			$this->response->redirect($this->url->link('product/suggestion/success'));
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

		$r = ViewManager::loadBlade('not-sure', 'suggestion/form.blade.php', $data);
		return $r->render();
	}
	public function success() {
		$this->document->setTitle('商品建議');
		$this->data['heading_title'] = '商品建議';
		$this->start();

		$data['opencart'] = $this;

		$r = ViewManager::loadBlade('not-sure', 'suggestion/success.blade.php', $data);
		$this->data['content'] = $r->render();

		$this->end();
	}
}