<?php

namespace ProductSearcher\Model;

class ProductsSearchManager {
	private $originalSearchString;
	private $productsList;
	private $modifiedSearchString;
	private $excludesList;
	private $exactMatchList;

	public function __construct(array $products, $searchString = "") {
		$this->productsList = $products;
		$this->originalSearchString = $searchString;
		$this->setUpExcludesList();
		$this->setUpExactMatchList();
		$this->setUpModifiedSearchString();
	}

	/**
	 * Helper method to find strings to be excluded during the search
	 */
	protected function setUpExcludesList(){
		$matches = array();
		if(preg_match_all('/\s-([\w|\d|\S]*)/', $this->originalSearchString, $matches)){
			$this->excludesList = array_map('strtolower', $matches[1]);
		}
	}

	/**
	 * Helper method to find strings to be exactly matched during the search
	 */
	protected function setUpExactMatchList(){
		$matches = array();
		if(preg_match_all('/"{1}([\w|\d|\s]*)"{1}/', $this->originalSearchString, $matches)){
			$this->exactMatchList = array_map('strtolower', $matches[1]);
		}
	}

	/**
	 * Helper method to remove the exact match strings and the exclude strings
	 */
	protected function setUpModifiedSearchString(){
		$modifiedString = strtolower($this->originalSearchString);

		if(is_array($this->excludesList)) {
			foreach($this->excludesList as $excludeString) {
				$modifiedString = str_replace(" -".$excludeString, "", $modifiedString);
			}
		}

		if(is_array($this->exactMatchList)) {
			foreach($this->exactMatchList as $matchString) {
				$modifiedString = str_replace("\"".$matchString."\"", "", $modifiedString);
			}
		}

		$this->modifiedSearchString = strtolower(rtrim($modifiedString));
	}

	/**
	 * @return array
	 */
	public function getResults(){
		$productResultsUnSorted = array();

		if(strlen($this->originalSearchString) == 0){
			/** @var Product $product */
			foreach($this->productsList as $product){
				return $product->jsonSerialize();
			}
		}

		/** @var Product $product */
		foreach($this->productsList as $product){
			$toMatch = strtolower($product->getId()." ".$product->getCategory()." ".$product->getName());
			if(is_array($this->exactMatchList) && !$this->matchArrayInString($toMatch, $this->exactMatchList)){
				continue;
			}

			if(is_array($this->excludesList) && $this->matchArrayInString($toMatch, $this->excludesList)){
				continue;
			}

			if(-1 == $levDist = levenshtein($toMatch, $this->modifiedSearchString)){
				continue;
			}

			if(strlen($this->modifiedSearchString) !== 0){
				if(!$this->matchArrayInString($toMatch, explode(" ", $this->modifiedSearchString))){
					continue;
				}
			}

			$productResultsUnSorted[] = array('product' => $product->jsonSerialize(), 'levDist' => $levDist);
		}

		return $this->sortByLevDist($productResultsUnSorted);
	}

	/**
	 * @param string $haystack
	 * @param $needles
	 *
	 * @return bool
	 */
	private function matchArrayInString($haystack, $needles){
		$matchFound = false;
		if(is_array($needles)) {
			foreach($needles as $needle) {
				$matchFound = $this->strContains($haystack, $needle);
			}
		}

		return $matchFound;
	}

	/**
	 * @param $haystack
	 * @param $needle
	 *
	 * @return bool
	 */
	private function strContains($haystack, $needle){
		$match = strpos($haystack, $needle);
		if($match === false && $match !== 0){
			return false;
		}
		return true;
	}

	private function sortByLevDist($unsortedList){
		$levDistList = array();
		$tempProductsList = array();
		$productsList = array();

		foreach($unsortedList as $key => $item){
			$levDistList[$key] = $item['levDist'];
			$tempProductsList[$key] = $item['product'];
		}

		asort($levDistList);

		foreach($levDistList as $key => $value){
			$productsList[] = $tempProductsList[$key];
		}

		return $productsList;
	}
}