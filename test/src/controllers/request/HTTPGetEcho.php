<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\util\cors\StandardCORS;
use ncsa\phpmvj\util\cors\StandardGet;

class HTTPGetEcho implements RequestHandler {
	use StandardCORS;

	public function options(): void {
		$this->setCORSHeaders('GET, DELETE');
	}

	public function handle(): void {
		Application::getResponse()->setData($_GET);
	}
}