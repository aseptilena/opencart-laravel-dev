<?php namespace App\Service;

use App\Eloquent\Customer;
use Illuminate\Database\Capsule\Manager as DB;
use App\View\ViewManager;

class TreeContentService
{
	protected $customer;

	protected $filter;

	protected $tree;

	public function __construct($customer, $filter, $tree)
	{
		$this->customer = $customer;
		$this->filter = $filter;
		$this->tree = $tree;
	}

	public function getContent()
	{
		$data = array();
		$period = array(
			'start' => $this->filter['date_to'],
			'end' => $this->filter['date_from'],
			);

		if ($this->tree == 'ntree') {
			if (!$this->customer->ntree) {
				return 'No Ntree';
			}
			$descendants = $this->customer->ntreeDescendantsAndSelfWithCustomer();
		}
		else if ($this->tree == 'btree') {
			if (!$this->customer->btree) {
				return 'No Btree';
			}
			$descendants = $this->customer->btreeDescendantsAndSelfWithCustomer();
		}

		foreach ($descendants as &$descendant) {
			$descendant->customer->consumption = $descendant->customer->profit_record_summary($period)->consumption;
			if ($this->tree == 'ntree') {
				$descendant->customer->team_consumption = $descendant->customer->team_consumption($period);
			}
		}
		reset($descendant);

		$hierarchy = $descendants->toHierarchy();

		if ($this->tree == 'ntree') {
			$view = 'customer/ntree.blade.php';
		}
		else if ($this->tree == 'btree') {
			$view = 'customer/btree.blade.php';
		}

		return $this->treeHelper($hierarchy, $view);
	}

	protected function treeHelper($descendants, $view)
	{
		$content = '<ul>';
		foreach ($descendants as $descendant) {
			$data['customer'] = $descendant->customer;
			$r = ViewManager::loadBlade('not-sure', $view, $data);
			$content .= $r->render();
			if ($descendant->children->count() > 0) {
				$content .= $this->treeHelper($descendant->children, $view);
			}
			$content .= '</li>';
		}
		$content .= '</ul>';
		return $content;
	}
}