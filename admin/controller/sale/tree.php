<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\Deposit;
use App\Eloquent\DepositHistory;
use App\Eloquent\User;
use App\Eloquent\Ntree;
use App\Eloquent\Level;
use App\Eloquent\CalculateTree;
use App\Service\TreeContentService;
use App\Service\TreeHistoryService;
use App\Service\RemitService;

use App\View\ViewManager;

class ControllerSaleTree extends Controller {
	public $customer;

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

		$this->customer = Customer::find($this->request->get['customer_id']);

		$data['tree_content'] = $this->getContent($filter);

		$this->response->setOutput($this->load->view('sale/customer_tree.tpl', $data));
	}

	public function ajaxProfit($filter) {
		$this->customer = Customer::find($this->request->get['customer_id']);

		$filter = $this->handleFilter();

		echo $this->profitContent($filter);
		die();
	}
	public function ajaxBonus($filter) {
		$this->customer = Customer::find($this->request->get['customer_id']);

		$filter = $this->handleFilter();

		echo $this->bonusContent($filter);
		die();
	}
	public function handleFilter() {
		$filter = array();
		if ($this->request->get['date_to'] == 'all') {
			$now = new \DateTime('NOW');
			$now->modify('first day of this month');
			$date_to = $now->format('Y-m');
			$filter['date_to'] = $date_to;
			$filter['date_from'] = $this->customer->joining_month('string_only_month');
		}
		else {
			$filter['date_to'] = $this->request->get['date_to'];
			$filter['date_from'] = $this->request->get['date_from'];
		}

		return $filter;
	}

	public function getContent($filter) {

		$date_to = $filter['date_to'];
		$date_from = $filter['date_from'];

		$data['ntree'] = $this->ntreeContent($filter);
		$data['btree'] = $this->btreeContent($filter);

		$data['info'] = $this->infoContent();
		$data['profit'] = $this->profitContent($filter);
		$data['bonus'] = $this->bonusContent($filter);
		$data['deposit'] = $this->depositContent();
		$data['ntree_history'] = $this->ntreeHistory();
		$data['btree_history'] = $this->btreeHistory();


		$data['profit_dates'] = $this->customer->own_months_options();
		$data['bonus_dates'] = $this->customer->own_months_options();

		$data['profit_url'] = $this->url->link('sale/tree/ajaxProfit');
		$data['bonus_url'] = $this->url->link('sale/tree/ajaxBonus');
		$data['deposit_history_url'] = $this->url->link('sale/tree/ajaxDepositHistory');
		$data['draw_url'] = $this->url->link('sale/tree/ajaxDraw');
		$data['info_url'] = $this->url->link('sale/tree/ajaxInfo');
		$data['token'] = $this->session->data['token'];
		$data['customer_id'] = $this->request->get['customer_id'];

		$data['active_tab'] = 'tab-info';

		$data['tabs'] = array(
			['name' => 'Info', 'link' => 'tab-info'],
			['name' => 'Profit History', 'link' => 'tab-profit-history'],
			['name' => 'Bonus History', 'link' => 'tab-bonuse-history'],
			['name' => 'N Tree', 'link' => 'tab-ntree'],
			['name' => 'B Tree', 'link' => 'tab-btree'],
			['name' => 'Deposit', 'link' => 'tab-deposit'],
		);

		$r = ViewManager::loadBlade('not-sure', 'customer/content.blade.php', $data);
		$content = $r->render();

		return $content;
	}

	public function ntreeContent($filter) {
		$content = new TreeContentService($this->customer, $filter, 'ntree');
		return $content->getContent();
	}
	public function ntreeHistory() {
		$content = new TreeHistoryService($this->customer, 'ntree');
		return $content->getContent();
	}
	public function btreeContent($filter) {
		$content = new TreeContentService($this->customer, $filter, 'btree');
		return $content->getContent();
	}
	public function btreeHistory() {
		$content = new TreeHistoryService($this->customer, 'btree');
		return $content->getContent();
	}
	public function infoContent() {
		$data = array();
		$data['customer'] = $this->customer;
		$levels = Level::all();
		$data['levels'] = $levels;
		$data['upgrade_histories'] = $this->customer->upgradeHistories;
		$data['ready_levels'] = $this->customer->getReadyLevels();

		$r = ViewManager::loadBlade('not-sure', 'customer/info.blade.php', $data);
		return $r->render();
	}
	public function profitContent($filter) {
		$from = \DateTime::createFromFormat('Y-m-d H:i:s', $filter['date_from'].'-01 00:00:00');
		$to = \DateTime::createFromFormat('Y-m-d H:i:s', $filter['date_to'].'-01 00:00:00');
		$to->modify('+1 month');
		$to->modify('-1 second');

		$profit_histories = $this->customer->profit_histories_between($from, $to);
		$profit_sum = 0;
		foreach ($profit_histories as $i) {
			$profit_sum += $i->amount;
		}
		$data = array();
		$data['profit_histories'] = $profit_histories;
		$data['profit_sum'] = $profit_sum;
		$r = ViewManager::loadBlade('not-sure', 'customer/profit.blade.php', $data);
		return $r->render();
	}
	public function bonusContent($filter) {
		$bonus_histories = $this->customer->bonus_histories_between($filter['date_from'], $filter['date_to']);
		$bonus_sum = 0;
		foreach ($bonus_histories as $i) {
			$bonus_sum += $i->bonus;
		}
		$data['bonus_histories'] = $bonus_histories;
		$data['bonus_sum'] = $bonus_sum;
		$r = ViewManager::loadBlade('not-sure', 'customer/bonus.blade.php', $data);
		return $r->render();
	}
	public function depositContent() {
		$data['deposits'] = $this->customer->deposits;

		$r = ViewManager::loadBlade('not-sure', 'customer/deposit.blade.php', $data);
		return $r->render();
	}
	public function ajaxDepositHistory() {
		$deposit = Deposit::find($this->request->get['deposit_id']);
		$data['deposit'] = $deposit;
		$data['statuses'] = Deposit::all_status();
		$data['deposit_histories'] = $deposit->depositHistories;
		$r = ViewManager::loadBlade('not-sure', 'customer/deposit_history.blade.php', $data);
		echo $r->render();
		die();
	}
	public function ajaxDraw() {
		$user = User::find($this->user->getId());

		$service = new RemitService($user, $this->request->post);

		$validate = $service->validate();
		if (is_array($validate)) {
			echo json_encode($validate);
			die();
		}
		$service->remit();
		echo json_encode(['success' => true]);
		die();
	}
	public function ajaxInfo() {
		$customer = Customer::find($this->request->post['customer_id']);
		$level = Level::find($this->request->post['level_id']);
		if ($customer->level_id != $level->id) {
			$customer->level()->associate($level);
			$customer->save(); 
			
			$customer->upgradeHistories()->create([
				'record' => '管理員:'.$this->user->getId().'調整至「'.$level->title.'」'
				]);
		}
		echo json_encode(['success' => '更新成功!']);
		die();
	}
}