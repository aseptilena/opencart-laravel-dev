<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\Ntree;
use App\Eloquent\CalculateTree;

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
			$r = ViewManager::loadBlade('not-sure-what-this-does', 'tree.blade.php', array('descendant' => $descendant ));
			$content .= $r->render();
			if ($descendant->children->count() > 0) {
				$content .= $this->treeHelper($descendant->children);
			}
			$content .= '</li>';
		}
		$content .= '</ul>';
		return $content;
	}
}