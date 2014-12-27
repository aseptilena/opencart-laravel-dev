<?php namespace App\Eloquent;

use Illuminate\Database\Capsule\Manager as Capsule;

class Member extends EncapsulatedEloquentBase
{
	protected $table = 'member';
	protected $primaryKey = 'member_id';
	protected $parentKey = 'parent_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	const LEFT = 'lft';
	const RIGHT = 'rgt';

	public function saveToRoot()
	{
		$this->{self::LEFT} = 1;
		$this->{self::RIGHT} = 2;
		$this->{$this->parentKey} = 0;
		$this->save();
	}

	public function addChild($child)
	{
		Capsule::transaction(function() use ($child)
		{
			$start = $this->{self::LEFT};
			$real_table = Capsule::getTablePrefix().$this->table;
			$left = self::LEFT;
			$right = self::RIGHT;

			Capsule::statement("UPDATE $real_table SET $left=$left+2 WHERE $left>$start;");
			Capsule::statement("UPDATE $real_table SET $right=$right+2 WHERE $right>$start;");

			$child->{self::LEFT} = $start + 1;
			$child->{self::RIGHT} = $start + 2;
			$child->{$this->parentKey} = $this->{$this->primaryKey};
			$child->save();

		});
	}
	public function removeChild()
	{

	}
	public function children()
	{
		$rows = Member::whereBetween(self::LEFT, array($this->{self::LEFT}, $this->{self::RIGHT}))->get();
		return $this->buildTree($rows);
	}

	protected function buildTree($items) {
		$childs = array();

		foreach($items as $item)
			$childs[$item->{$this->parentKey}][] = $item;

		foreach($items as $item) if (isset($childs[$item->{$this->primaryKey}]))
			$item->childs = $childs[$item->{$this->primaryKey}];

		return $childs[$this->{$this->parentKey}];
	}

	public function parent()
	{
		return Member::find($this->{$this->parentKey});
	}
	public function ancestors()
	{
		$rows = Member::where(self::LEFT, '<', $this->{self::LEFT})->where(self::RIGHT, '>', $this->{self::LEFT})->orderBy(self::LEFT, 'asc')->get();
		return $rows;
	}
	public function root()
	{

	}
	public function neighbors()
	{

	}

}