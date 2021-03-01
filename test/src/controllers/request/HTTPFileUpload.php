<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\router\Response;
use ncsa\phpmvj\util\cors\StandardPut;

class HTTPFileUpload implements RequestHandler {
	use StandardPut;

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($_FILES['file_contents']);
	}
}