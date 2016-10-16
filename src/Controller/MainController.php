<?php

namespace ProductSearcher\Controller;

use Mustache_Engine;
use ProductSearcher\Model\ProductsDataMapper;

class MainController {
	public function mainViewAction(Mustache_Engine $engine, ProductsDataMapper $productsDataMapper){
		return $engine->render('main', array('products' => $productsDataMapper->findAll()));
	}
}