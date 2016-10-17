<?php

namespace ProductSearcher\Controller;

use Mustache_Engine;
use ProductSearcher\Model\Product;
use ProductSearcher\Model\ProductsDataMapper;
use ProductSearcher\Model\ProductsSearchManager;

class SearchController {
	public function searchAction(Mustache_Engine $engine, ProductsDataMapper $productsDataMapper, $searchTerm){
		return $engine->render(
			'resultsPartial',
			array(
				'products' => $this->filterProducts(
					$productsDataMapper->findAll(),
					$searchTerm
				)
			)
		);
	}

	protected function filterProducts(array $productsList, $searchTerm){
		$productsSearchManager = new ProductsSearchManager($productsList, $searchTerm);
		return $productsSearchManager->getResults();
	}
}