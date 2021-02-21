<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;

class HTTPPostEcho implements RequestHandler {
	public function handle(): void {
		Application::getResponse()->setData(array_merge($_GET, $_POST));
	}
}