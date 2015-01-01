<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Product;

class ModelCatalogHook extends Model {
	public function productSave($get, $post) {
		$product = Product::find($get['product_id']);
		$product->bonus = $post['bonus'];
		$product->save();
	}
	public function productInfo($product_id) {
		return Product::find($product_id);
	}
}