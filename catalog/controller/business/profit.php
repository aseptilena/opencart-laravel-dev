<?php 

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerBusinessProfit extends Autocontroller {
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

		$from = \DateTime::createFromFormat('Y-m-d H:i:s', $filter['date_from'].'-01 00:00:00');
		$to = \DateTime::createFromFormat('Y-m-d H:i:s', $filter['date_to'].'-01 00:00:00');
		$to->modify('+1 month');
		$to->modify('-1 second');

		$profit_histories = $this->customer_model->profit_histories_between($from, $to);
		$profit_sum = 0;
		foreach ($profit_histories as $i) {
			$profit_sum += $i->amount;
		}
		$data = array();
		$data['profit_histories'] = $profit_histories;
		$data['profit_sum'] = $profit_sum;
		$data['dates'] = $this->customer_model->own_months_options();
		$data['selected_dates'] = isset($this->request->get['date_to']) ? $this->request->get['date_to'] : $data['dates'][1]['value'];

		$data['opencart'] = $this;
		$r = ViewManager::loadBlade('not-sure', 'business/profit.blade.php', $data);
		return $r->render();
	}
}