<?php 

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerBusinessBonus extends Autocontroller {
	public function index() {

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}

		$this->customer_model = Customer::find($this->customer->getId());

		$this->document->setTitle('開店');
		$this->data['heading_title'] = '開店';
		parent::index();

	}
	public function handleFilter() {
		$filter = array();
		if (!isset($this->request->get['date_to'])) {
			$now = new \DateTime('NOW');
			$now->modify('first day of this month');
			$date_to = $now->format('Y-m');

			$filter['date_to'] = $date_to;
			$filter['date_from'] = $date_to;
		}
		else if ($this->request->get['date_to'] == 'all') {
			$now = new \DateTime('NOW');
			$now->modify('first day of this month');
			$date_to = $now->format('Y-m');
			$filter['date_to'] = $date_to;
			$filter['date_from'] = $this->customer_model->joining_month('string_only_month');
		}
		else {
			$filter['date_to'] = $this->request->get['date_to'];
			$filter['date_from'] = $this->request->get['date_from'];
		}

		return $filter;
	}
	public function content() {

		$filter = $this->handleFilter();

		$bonus_histories = $this->customer_model->bonus_histories_between($filter['date_from'], $filter['date_to']);
		$bonus_sum = 0;
		foreach ($bonus_histories as $i) {
			$bonus_sum += $i->bonus;
		}
		$data['bonus_histories'] = $bonus_histories;
		$data['bonus_sum'] = $bonus_sum;
		$data['dates'] = $this->customer_model->own_months_options();
		$data['selected_dates'] = isset($this->request->get['date_to']) ? $this->request->get['date_to'] : $data['dates'][1]['value'];

		$data['dates'] = $this->customer_model->own_months_options();
		$data['selected_dates'] = isset($this->request->get['date_to']) ? $this->request->get['date_to'] : $data['dates'][1]['value'];

		$data['opencart'] = $this;
		$r = ViewManager::loadBlade('not-sure', 'business/bonus.blade.php', $data);
		return $r->render();
	}
}