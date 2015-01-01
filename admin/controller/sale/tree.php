<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\Ntree;
use App\Eloquent\Encapsulator;

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

		Encapsulator::init();
		$customer = Customer::find($this->request->get['customer_id']);

		$ntree_descendants = $customer->ntree->descendantsAndSelf()->with('customer')->get()->toHierarchy();

		$ntree_content = $this->treeHelper($ntree_descendants);
		$data['ntree'] = $ntree_content;

		$btree_descendants = $customer->btree->descendantsAndSelf()->with('customer')->get()->toHierarchy();

		$btree_content = $this->treeHelper($btree_descendants);
		$data['btree'] = $btree_content;

		$data['profit_histories'] = $customer->pv_histories()->get();

		$this->response->setOutput($this->load->view('sale/customer_tree.tpl', $data));
	}

	public function treeHelper($descendants) {
		$content = '<ul>';
		foreach ($descendants as $descendant) {
			$content .= '<li class="jstree-open" data-jstree=\'{"icon":"glyphicon glyphicon-leaf"}\'>'.$descendant->customer->customer_id.'&nbsp;'.$descendant->customer->firstname.'&nbsp;'.$descendant->customer->lastname.'&nbsp;個人PV:'.$descendant->customer->pv.'&nbsp;個人組織:'.$descendant->customer->total_pv;
			if ($descendant->children->count() > 0) {
				$content .= $this->treeHelper($descendant->children);
			}
			$content .= '</li>';
		}
		$content .= '</ul>';
		return $content;
	}
}