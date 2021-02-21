<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;

class HTTPGetEcho implements RequestHandler {
	public function handle(): void {
		Application::getResponse()->setData($_GET);
	}
}