<?php

use ProductSearcher\Controller\MainController;
use ProductSearcher\Controller\SearchController;
use Slim\Http\Request;

if(!isset($app)){
	return false;
}
$app->get('/', function ($request, $response, $args) {
	$controller = new MainController();
	return $controller->mainViewAction($this->mustache, $this->productDataMapper);
});
$app->get('/search', function (Request $request, $response, $args) {
	$controller = new SearchController();
	$query = $request->getQueryParams();
	return $controller->searchAction($this->mustache, $this->productDataMapper, $query['searchTerm']);
});
