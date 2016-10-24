<?php

namespace ProductSearcher\Controller;

use Mustache_Engine;
use ProductSearcher\Model\ProductsDataMapper;
use ProductSearcher\Model\ProductsManager;

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
		$productsManager = new ProductsManager($productsList);
		return $productsManager->searchProducts($searchTerm);
	}
}