<?php

namespace ProductSearcher;

use Slim\App;
use Slim\Container;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use ProductSearcher\Model\ProductsDataMapper;
use Symfony\Component\ClassLoader\Psr4ClassLoader as ClassLoader;

$loader = new ClassLoader();
$loader->addPrefix('ProductSearcher\\', __DIR__);
$loader->register();

// Config settings
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

// Container setup
$container = new Container(array(
	'settings' => $config
));

// Set up Slim app
$app = new App($container);
$container = $app->getContainer();

// Add mustache enginge to container
$container['mustache'] = function(){
	$mustache = new Mustache_Engine(array(
		'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/View')
	));
	return $mustache;
};

// Add path to the datafile to the container
$container['dataFile'] = 'data/products.json';

// Add the productDataMapper to the container
$container['productDataMapper'] = new ProductsDataMapper($container['dataFile']);
