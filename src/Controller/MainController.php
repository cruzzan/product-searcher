<?php

namespace ProductSearcher\Controller;

use Slim\Http\Response;
use Mustache_Engine;

class MainController {
	public function mainViewAction(Mustache_Engine $engine){
		return $engine->render("<h1>Main controllern fungerar</h1>", array());
	}
}