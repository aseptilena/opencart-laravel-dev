<?php namespace App\Service;

use App\Eloquent\Customer;
use App\Eloquent\Btree;
use App\Eloquent\CalculateTree;
use Illuminate\Database\Capsule\Manager as DB;

class BtreeService
{
	protected $customer;

	protected $position;

	protected $descendants;

	public function __construct($customer)
	{
		$this->customer = $customer;
	}

	
	public function bulidBtree()
	{
		DB::table('calculate_trees')->truncate();
		$descendants = $this->customer->btree->descendantsAndSelf()->with('customer')->get();
		$lft = $this->customer->btree->lft - 1;
		$depth = $this->customer->btree->depth;
		foreach ($descendants as $descendant) {
			$parent_id = $descendant->customer_id == $this->customer->customer_id ? null : $descendant->parent_id;
			DB::table('calculate_trees')->insert([
				'id' => $descendant->id,
				'parent_id' => $parent_id,
				'lft' => $descendant->lft - $lft,
				'rgt' => $descendant->rgt - $lft,
				'depth' => $descendant->depth - $depth,
				'customer_id' => $descendant->customer_id,
			]);
		}
	}
	public function doBtree()
	{
		$root = CalculateTree::root();
		$descendants = $root->descendantsAndSelf()->with('customer')->get();
		$this->iterateBtree($descendants);
	}

	public function iterateBtree($descendants) {
		$flag = false;
		foreach ($descendants as $descendant) {
			if ($descendant->customer->accumulated_consumption() < 1000) {
				$flag = true;
				$this->position = 0;
				$k = $descendant->descendantsAndSelf()->with('customer')->get()->toHierarchy();
				$this->indexPosition($k);
				$this->changePosition($descendant);
				break;
			}
		}
		if ($flag) {
			$this->doBtree();
		}
	}

	public function indexPosition($descendants) {
		foreach ($descendants as $descendant) {
			$tree = CalculateTree::find($descendant->id);
			$tree->position = $this->position;
			$tree->save();
			$this->position ++;
		}
		$collects = array();
		
		foreach ($descendants as $descendant) {
			if ($descendant->children->count() > 0) {
				$collects[] = $descendant->children[0];
			}
		}
		foreach ($descendants as $descendant) {
			if ($descendant->children->count() > 1) {
				$collects[] = $descendant->children[1];
			}
		}
		if (count($collects) > 0) {
			$this->indexPosition($collects);
		}
	}

	public function changePosition($root) {
		$tree = CalculateTree::find($root->id);
		$descendants = $tree->descendantsAndSelf()->orderBy('position', 'asc')->get();

		$collects = array();
		foreach ($descendants as $descendant) {
			$collects[] = $descendant;
		}

		$sort = array();
		foreach ($collects as $key => $row)
			$sort[$key] = $row->position;
		array_multisort($sort, SORT_ASC, $collects);

		$count = count($collects);
		for ($i=0; $i < $count; $i++) {
			$descendant = $collects[$i];
			if ($i == $count -1) {
				DB::table('calculate_trees')
					->where('id', $descendant->id)
					->delete();
				DB::table('calculate_trees')
					->update(array('position' => null));
				CalculateTree::rebuild(true);
				break;
			}
			$next = $collects[$i+1];
			DB::table('calculate_trees')
				->where('id', $descendant->id)
				->update(array('customer_id' => $next->customer_id));
		}
	}
}