<?php

namespace ProductSearcher\Controller;

use Mustache_Engine;
use ProductSearcher\Model\Product;
use ProductSearcher\Model\ProductsDataMapper;

class MainController {
	public function mainViewAction(Mustache_Engine $engine, ProductsDataMapper $productsDataMapper){
		$products = array();
		/** @var Product $product */
		foreach($productsDataMapper->findAll() as $product){
			$products[] = $product->jsonSerialize();
		}
		return $engine->render('main', array('products' => $products));
	}
}