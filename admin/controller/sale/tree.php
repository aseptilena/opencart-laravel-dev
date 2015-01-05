<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\Ntree;
use App\Eloquent\CalculateTree;
use App\Service\TreeContentService;

use App\View\ViewManager;

class ControllerSaleTree extends Controller {
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) {

		} else {
			$data['success'] = '';
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$data['error_warning'] = $this->language->get('error_permission');
		} else {
			$data['error_warning'] = '';
		}

		$filter = array();
		$now = new \DateTime('NOW');
		$now->modify('first day of this month');
		$date_to = $now->format('Y-m');

		$filter['date_to'] = $date_to;
		$filter['date_from'] = $date_to;

		$customer = Customer::find($this->request->get['customer_id']);

		$data['profit_dates'] = $customer->own_months_options();
		$data['url_get_content'] = $this->url->link('sale/tree/ajaxContent');
		$data['token'] = $this->session->data['token'];
		$data['customer_id'] = $this->request->get['customer_id'];

		$filter['active_tab'] = 'tab-profit-history';

		$data['tree_content'] = $this->getContent($filter);

		$this->response->setOutput($this->load->view('sale/customer_tree.tpl', $data));
	}

	public function ajaxContent($filter) {
		$customer = Customer::find($this->request->get['customer_id']);
		if ($this->request->get['date_to'] == 'all') {
			$now = new \DateTime('NOW');
			$now->modify('first day of this month');
			$date_to = $now->format('Y-m');
			$filter['date_to'] = $date_to;
			$filter['date_from'] = $customer->joining_month('string_only_month');
		}
		else {
			$filter['date_to'] = $this->request->get['date_to'];
			$filter['date_from'] = $this->request->get['date_from'];
		}
		$filter['active_tab'] = $this->request->get['active_tab'];

		echo $this->getContent($filter);
		die();
	}

	public function getContent($filter) {

		$customer = Customer::find($this->request->get['customer_id']);

		$date_to = $filter['date_to'];
		$date_from = $filter['date_from'];

		$tree_content = new TreeContentService($customer, $filter);
		$tree_content = $tree_content->getContent();

		$data['ntree'] = $tree_content['ntree'];
		$data['btree'] = $tree_content['btree'];

		$bonus_histories = $customer->bonus_histories_between($date_from, $date_to);
		$bonus_sum = 0;
		foreach ($bonus_histories as $i) {
			$bonus_sum += $i->bonus;
		}
		$data['bonus_histories'] = $bonus_histories;
		$data['bonus_sum'] = $bonus_sum;

		$from = \DateTime::createFromFormat('Y-m-d H:i:s', $date_from.'-01 00:00:00');
		$to = \DateTime::createFromFormat('Y-m-d H:i:s', $date_to.'-01 00:00:00');
		$to->modify('+1 month');
		$to->modify('-1 second');

		$profit_histories = $customer->profit_histories_between($from, $to);
		$profit_sum = 0;
		foreach ($profit_histories as $i) {
			$profit_sum += $i->amount;
		}
		$data['profit_histories'] = $profit_histories;
		$data['profit_sum'] = $profit_sum;

		$data['active_tab'] = $filter['active_tab'];

		$data['tabs'] = array(
			['name' => 'Profit History', 'link' => 'tab-profit-history'],
			['name' => 'Bonus History', 'link' => 'tab-bonuse-history'],
			['name' => 'N Tree', 'link' => 'tab-ntree'],
			['name' => 'B Tree', 'link' => 'tab-btree'],
		);

		$r = ViewManager::loadBlade('not-sure', 'tree_content.blade.php', $data);
		$content = $r->render();

		return $content;
	}

}