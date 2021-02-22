<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\util\cors\StandardPut;

class HTTPFileUpload implements RequestHandler {
	use StandardPut;

	public function handle(): void {
		Application::getResponse()->setData($_FILES['file_contents']);
	}
}