<?php

namespace ProductSearcher\Model;

use ProductSearcher\Search\Search;

class ProductsManager {
	private $productsList;

	public function __construct(array $products) {
		$this->productsList = $products;
	}

	/**
	 * @param $searchTerm
	 *
	 * @return array
	 */
	public function searchProducts($searchTerm){
		$preparedResult = array();
		if(strlen($searchTerm) == 0){
			/** @var Product $product */
			foreach($this->productsList as $product){
				$preparedResult[] = $product->jsonSerialize();
			}
		}else{
			$searcher = new Search($searchTerm);
			$results = $searcher->run($this->productsList);

			foreach($results as $result){
				$product = $result['item'];
				/** @var Product $product*/
				$preparedResult[] = $product->jsonSerialize();
			}
		}

		return $preparedResult;
	}
}