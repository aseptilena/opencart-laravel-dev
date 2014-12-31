<?php 

use Baum\Node;
use Baum\BaumServiceProvider;
use App\Eloquent\Encapsulator;

class Tree extends Node
{
	// protected $table = 'trees';

	// protected $primaryKey = 'tree_id';

	// protected $parentColumn = 'parent_id';

	// protected $leftColumn = 'lft';

	// protected $rightColumn = 'rgt';

	// protected $depthColumn = 'depth';

	protected $orderColumn = 'position';

	// protected $guarded = array('tree_id', 'parent_id', 'lft', 'rgt');

	// const CREATED_AT = 'date_added';
	// const UPDATED_AT = 'date_modified';
    protected $softDelete = false;

	// public function __construct(array $attributes = array())
	// {
	// 	Encapsulator::init();

	// 	parent::__construct($attributes);
	// }

	// protected static function boot() {
	// 	parent::boot();
	// 	echo 'hey boot!!<br>';

	// 	static::creating(function($node) {
	// 		echo 'hey creating!!<br>';
	// 		$node->setDefaultLeftAndRight();
	// 	});

	// 	static::saving(function($node) {
	// 		$node->storeNewParent();
	// 	});

	// 	static::saved(function($node) {
	// 		$node->moveToNewParent();
	// 		$node->setDepth();
	// 	});

	// 	static::deleting(function($node) {
	// 		$node->destroyDescendants();
	// 	});

	// 	if ( static::softDeletesEnabled() ) {
	// 		static::restoring(function($node) {
	// 			$node->shiftSiblingsForRestore();
	// 		});

	// 		static::restored(function($node) {
	// 			$node->restoreDescendants();
	// 		});
	// 	}
	// }
}