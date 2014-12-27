<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Member;
use Illuminate\Database\Capsule\Manager as Capsule;

class ControllerAccountTree extends Controller
{
	public function index() {
		// $root = Member::find(35);

		// $child = new Member;
		// $child->name = 'Daan';
		// $child->save();

		// $root->addChild($child);


		$root = Member::find(38);
		// print_r($root->children());
		print_r($root->parent());
		// foreach ($root->children() as $child) {
		// 	echo $child->name;
		// }
	}
}