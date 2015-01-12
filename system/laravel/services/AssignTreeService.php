<?php namespace App\Service;

use App\Eloquent\Customer;
use App\Eloquent\Btree;
use App\Eloquent\Ntree;
use App\Eloquent\Log;

class AssignTreeService
{
	protected $customer;

	protected $promo_customer;

	public function __construct($customer, $promo_customer)
	{
		$this->customer = $customer;
		$this->promo_customer = $promo_customer;
	}

	public function assign()
	{
		$this->assignNtree();
		$this->assignBtree();
	}
	public function assignNtree()
	{
		if (!$this->promo_customer->ntree) {
			$this->customer->pcustomer_id = $this->promo_customer->customer_id;
			$this->customer->save();
			Log::create(['record' => 'can not find ntree of '.$this->promo_customer->customer_id.' for '.$this->customer->customer_id]);
			return false;
		}
		$child = Ntree::create(['name' => 'Child']);
		$child->customer()->associate($this->customer);
		$child->save();
		$child->makeChildOf($this->promo_customer->ntree);
	}
	public function assignBtree()
	{
		if (!$this->promo_customer->btree) {
			$this->customer->pcustomer_id = $this->promo_customer->customer_id;
			$this->customer->save();
			Log::create(['record' => 'can not find btree of '.$this->promo_customer->customer_id.' for '.$this->customer->customer_id]);
			return false;
		}
		$parent = $this->findBtreeParent();
		$child = Btree::create(['name' => 'Child']);
		$child->customer()->associate($this->customer);
		$child->save();
		$child->makeChildOf($parent);
	}

	public function findBtreeParent()
	{
		$descendants = $this->promo_customer->btreeDescendantsAndSelfWithCustomer()->toHierarchy();
		return $this->findBtreePosition($descendants);
	}
	public function findBtreePosition($descendants)
	{
		foreach ($descendants as $descendant) {
			if ($descendant->children->count() == 0) {
				return $descendant;
			}
		}
		foreach ($descendants as $descendant) {
			if ($descendant->children->count() == 1) {
				return $descendant;
			}
		}
		$collects = array();
		foreach ($descendants as $descendant) {
			foreach ($descendant->children as $child) {
				$collects[] = $child;
			}
		}
		return $this->findBtreePosition($collects);
	}
}