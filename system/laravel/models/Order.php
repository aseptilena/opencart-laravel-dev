<?php namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Order extends Model
{
	protected $table = 'order';
	protected $primaryKey = 'order_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array();

	static public function total($start, $end)
	{
		$result = Order::where('order_status_id', '>', 0)->whereBetween('date_added', array($start, $end))->get(array(
				DB::raw('SUM(total)')
			));
		$result = $result[0];
		return $result['SUM(total)'];
	}
}