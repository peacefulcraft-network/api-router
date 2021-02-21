<?php
namespace ncsa\phpmvj\test\controllers;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;

class Index implements RequestHandler {
	public function handle():void {
		Application::getResponse()->setData(['message'=>'Hello World!']);
	}
}

?>