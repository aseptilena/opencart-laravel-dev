<?php namespace App\Service;

use App\Eloquent\Customer;
use Illuminate\Database\Capsule\Manager as DB;
use App\View\ViewManager;

class TreeContentService
{
	protected $customer;

	protected $filter;

	public function __construct($customer, $filter)
	{
		$this->customer = $customer;
		$this->filter = $filter;
	}

	public function getContent()
	{
		$data = array();
		$date_to = $this->filter['date_to'];
		$date_from = $this->filter['date_from'];

		$ntree_descendants = $this->customer->ntree->descendantsAndSelf()->with('customer')->get();
		foreach ($ntree_descendants as &$descendant) {
			$descendant->customer->consumption = $descendant->customer->profit_record_summary($date_from, $date_to)['SUM(consumption)'];
		}
		reset($descendant);
		foreach ($ntree_descendants as &$descendant) {
			$descendant->customer->sum = $this->calculateTotal($descendant, $ntree_descendants);
		}
		reset($descendant);
		$ntree_hierarchy = $ntree_descendants->toHierarchy();

		$ntree_content = $this->treeHelper($ntree_hierarchy, $date_from, $date_to);
		$data['ntree'] = $ntree_content;

		$btree_descendants = $this->customer->btree->descendantsAndSelf()->with('customer')->get();
		foreach ($btree_descendants as &$descendant) {
			$descendant->customer->consumption = $descendant->customer->profit_record_summary($date_from, $date_to)['SUM(consumption)'];
		}
		reset($descendant);
		foreach ($btree_descendants as &$descendant) {
			$descendant->customer->sum = $this->calculateTotal($descendant, $btree_descendants);
		}
		reset($descendant);
		$btree_hierarchy = $btree_descendants->toHierarchy();

		$btree_content = $this->treeHelper($btree_hierarchy, $date_from, $date_to);
		$data['btree'] = $btree_content;

		return $data;
	}

	protected function treeHelper($descendants, $date_from, $date_to)
	{
		$content = '<ul>';
		foreach ($descendants as $descendant) {
			$data['customer'] = $descendant->customer;
			$data['date_from'] = $date_from;
			$data['date_to'] = $date_to;
			$r = ViewManager::loadBlade('not-sure', 'tree.blade.php', $data);
			$content .= $r->render();
			if ($descendant->children->count() > 0) {
				$content .= $this->treeHelper($descendant->children, $date_from, $date_to);
			}
			$content .= '</li>';
		}
		$content .= '</ul>';
		return $content;
	}

	protected function calculateTotal($node, $descendants)
	{
		$sum = 0;
		foreach ($descendants as $descendant) {
			if ($node->lft <= $descendant->lft && $descendant->lft <= $node->rgt) {
				$sum += $descendant->customer->consumption;
			}
		}
		return $sum;
	}
}