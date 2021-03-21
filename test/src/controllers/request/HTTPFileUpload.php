<?php
namespace ncsa\phpmcj\test\controllers\request;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\RequestHandler;
use ncsa\phpmcj\router\Response;
use ncsa\phpmcj\util\cors\StandardPut;

class HTTPFileUpload implements RequestHandler {
	use StandardPut;

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($_FILES['file_contents']);
	}
}