<?php

namespace ProductSearcher\Model;

class ProductsDataMapper {
	private $file;

	public function __construct($file) {
		$this->file = $file;
	}

	public function findAll(){
		$products = array();
		$productsFromFile = json_decode(file_get_contents('data/products.json'));

		foreach($productsFromFile as $item){
			$products[] = new Product($item->produkt_id, $item->produkt_namn, $item->kategori_namn);
		}
		return $products;
	}
}