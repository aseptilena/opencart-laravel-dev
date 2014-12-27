<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Member;
use Illuminate\Database\Capsule\Manager as Capsule;

class ControllerAccountTree extends Controller
{
	public function index() {
		// $root = Member::find(34);

		// $child = new Member;
		// $child->name = 'Miaoli';
		// $child->save();

		// $root->addChild($child);


		$root = Member::find(34);
		print_r($root->children());
		// foreach ($root->children() as $child) {
		// 	echo $child->name;
		// }
	}
}