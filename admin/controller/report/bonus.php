<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\ProfitRecord;
use App\Eloquent\Order;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerReportBonus extends Controller {
	public function index() {
		$this->document->setTitle('會員等級');

		$data['content'] = $this->getView();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('common/blade.tpl', $data));
	}
	
	public function getView() {

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = (object)[
			'text' => 'Home',
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		];

		$data['breadcrumbs'][] = (object)[
			'text' => '紅利會員數據',
			'href' => $this->url->link('sale/level', 'token=' . $this->session->data['token'], 'SSL')
		];

		$filter_month = 'all';
		if (isset($this->request->get['filter_month'])) {
			$filter_month = $this->request->get['filter_month'];
		}

		$data['filter_month'] = $filter_month;
		$data['months'] = MyDateTime::fromToNowOption(START_DATE);
		$data['opencart'] = $this;

		if ($filter_month == 'all') {
			$start = MyDateTime::convertString(substr(START_DATE, 0, 7));
			$end = MyDateTime::init('first_day_of_now');
		}
		else {
			$start = MyDateTime::convertString($filter_month);
			$end = MyDateTime::convertString($filter_month);
		}

		$end->modify('+1 month');

		$data['order_total'] = Order::total($start, $end);

		$data['profit_summary'] = ProfitRecord::summary($start, $end);

		$data['total_customer'] = Customer::where('date_added', '<', $end)->count();
		$data['new_customer'] = Customer::whereBetween('date_added', array($start, $end))->count();

		$r = ViewManager::loadBlade('not-sure', 'report/bonus.blade.php', $data);
		return $r->render();
	}
}