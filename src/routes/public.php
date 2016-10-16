<?php

use ProductSearcher\Controller\MainController;

if(!isset($app)){
	return false;
}
$app->get('/', function ($request, $response, $args) {
	$controller = new MainController();
	return $controller->mainViewAction($this->mustache, $this->productDataMapper);
});
