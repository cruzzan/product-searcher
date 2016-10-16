<?php

namespace ProductSearcher\Model;

use JsonSerializable;

class Product implements JsonSerializable{
	private $id;
	private $name;
	private $category;

	public function __construct($id, $name, $category) {
		$this->id = $id;
		$this->name = $name;
		$this->category = $category;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 *
	 * @return $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param mixed $category
	 *
	 * @return $this
	 */
	public function setCategory($category) {
		$this->category = $category;
		return $this;
	}

	public function jsonSerialize(){
		return array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			'category' => $this->getCategory()
		);
	}
}
