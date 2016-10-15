<?php

namespace ProductSearcher;

use Slim\App;
use Slim\Container;
use Mustache_Engine;
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
$container['mustache'] = function(){
	$mustache = new Mustache_Engine();
	return $mustache;
};


