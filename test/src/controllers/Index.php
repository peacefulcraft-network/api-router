<?php
namespace ncsa\phpmvj\test\controllers;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\util\cors\StandardGet;

class Index implements RequestHandler {
	use StandardGet;

	public function handle():void {
		Application::getResponse()->setData(['message'=>'Hello World!']);
	}
}

?>