<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\util\cors\StandardCORS;

class HTTPPostEcho implements RequestHandler {
	use StandardCORS;

	public function options(): void {
		$this->setCORSHeaders('POST, PUT, PATCH');
	}

	public function handle(): void {
		Application::getResponse()->setData(array_merge($_GET, $_POST));
	}
}