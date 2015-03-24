<?php 

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\View\ViewManager;
use App\Service\TreeContentService;
use App\Service\TreeHistoryService;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerBusinessNtree extends Autocontroller {
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

		$data['ntree'] = $this->ntreeContent($filter);
		$data['ntree_history'] = $this->ntreeHistory();
		$r = ViewManager::loadBlade('not-sure', 'business/ntree.blade.php', $data);
		return $r->render();
	}
	public function ntreeContent($filter) {
		$content = new TreeContentService($this->customer_model, $filter, 'ntree');
		return $content->getContent();
	}
	public function ntreeHistory() {
		$content = new TreeHistoryService($this->customer_model, 'ntree');
		return $content->getContent();
	}
	public function end() {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/blade.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/blade2.tpl', $this->data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/blade2.tpl', $this->data));
		}
	}
}