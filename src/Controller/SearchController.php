<?php

namespace ProductSearcher\Controller;

use Mustache_Engine;
use ProductSearcher\Model\Product;
use ProductSearcher\Model\ProductsDataMapper;

class SearchController {
	public function searchAction(Mustache_Engine $engine, ProductsDataMapper $productsDataMapper, $searchTerm){
		$results = array();

		/** @var Product $item */
		foreach($productsDataMapper->findAll() as $item){
			if(strlen($searchTerm) == 0){
				$results[] = $item->jsonSerialize();
			}else{
				if($this->isMatch($item, $searchTerm)){
					$results[] = $item->jsonSerialize();
				}
			}
		}

		return $engine->render('resultsPartial', array('products' => $results));
	}

	protected function isMatch(Product $product, $stringToMatch){
		if(stristr($product->getName(), $stringToMatch)){
			return true;
		}
		return false;
	}
}