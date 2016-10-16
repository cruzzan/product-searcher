<?php

namespace ProductSearcher\Controller;

use Mustache_Engine;

class MainController {
	public function mainViewAction(Mustache_Engine $engine){
		return $engine->render('main', array());
	}
}