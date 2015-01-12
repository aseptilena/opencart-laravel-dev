<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\Ntree;
use App\Eloquent\Btree;
use App\Eloquent\Level;

use App\View\ViewManager;
use App\Service\BtreeService;
use App\Service\UpgradeService;

class ControllerAccountTree extends Controller
{
	public function index() {

		$this->document->setTitle($this->language->get('PV'));
		$this->document->addScript('catalog/view/javascript/jquery/jstree/jstree.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/jstree/themes/default/style.min.css');

		$customer = Customer::find(2);

		// $this_month = new DateTime('NOW');
		// $this_month->modify('first day of this month');
		// $customer->grantNtreeBonus($this_month, true);
		// $service = new UpgradeService($customer);
		// $service->upgrade();
// Ntree::rebuild(true);
echo 'end';
		return;
		// $customer->consume(100);

		$now_month = new DateTime('NOW');
		$now_month->modify('first day of this month');

		// $customer->grantNtreeBonus($now_month, true);
		// $customer->grantBtreeBonus($now_month, true);


		// $service = new BtreeService($customer);
		// $service->bulidBtree();
		// $service->doBtree();

		echo 'ewr';
		return;


		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/tree.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/tree.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/tree.tpl', $data));
		}
	}
}