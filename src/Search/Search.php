<?php

namespace ProductSearcher\Search;

class Search {
	private $originalSearchString;
	private $modifiedSearchString;
	private $excludesList;
	private $exactMatchList;

	public function __construct($searchString = "") {
		$this->originalSearchString = $searchString;
		if(!empty($this->originalSearchString)){
			$this->setUpExcludesList();
			$this->setUpExactMatchList();
			$this->setUpModifiedSearchString();
		}
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
	 * @param array $list
	 *
	 * @return array
	 */
	private function removeExcludeMatchesFromList(array $list){
		foreach($list as $key => $item){
			$toMatch = strtolower($item->toString());
			if(is_array($this->excludesList) && $this->matchArrayInString($toMatch, $this->excludesList)){
				unset($list[$key]);
			}
		}
		return $list;
	}

	/**
	 * @param array $list
	 *
	 * @return array
	 */
	private function removeUnmatchedHardMatchesFromList(array $list){
		foreach($list as $key => $item){
			$toMatch = strtolower($item->toString());
			if(is_array($this->exactMatchList) && !$this->matchArrayInString($toMatch, $this->exactMatchList)){
				unset($list[$key]);
			}
		}
		return $list;
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

	/**
	 * @param array $list
	 *
	 * @return array
	 */
	private function fuzzySearchList(array $list){
		$filteredList = array();
		foreach($list as $key => $item){
			$toMatch = strtolower($item->toString());
			$longestPossibleDist = max(strlen($toMatch), strlen($this->modifiedSearchString));
			$levDist = levenshtein($toMatch, $this->modifiedSearchString);
			if($levDist != -1){
				if(($levDist/$longestPossibleDist) * 100 < 90){
					$filteredList[] = array('levDist' => $levDist, 'item' => $item);
				}elseif($this->strContains($toMatch, $this->modifiedSearchString)){
					$filteredList[] = array('levDist' => $levDist, 'item' => $item);
				}
			}
		}
		return $filteredList;
	}

	/**
	 * @param array $unsortedList
	 *
	 * @return array
	 */
	private function sortListByLevDist(array $unsortedList){
		$levDists = array();
		$sortedList = $unsortedList;
		foreach($unsortedList as $key => $row){
			$levDists[$key] = $row['levDist'];
		}
		array_multisort($levDists, SORT_NUMERIC, SORT_ASC, $sortedList);

		return $sortedList;
	}

	/**
	 * @param array $list
	 *
	 * @return array
	 */
	public function run(array $list){
		$list = $this->removeExcludeMatchesFromList($list);
		$list = $this->removeUnmatchedHardMatchesFromList($list);

		$listWithLevDist = $this->fuzzySearchList($list);
		return $this->sortListByLevDist($listWithLevDist);
	}
}