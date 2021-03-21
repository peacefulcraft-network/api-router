<?php
namespace ncsa\phpmcj\test\controllers;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\RequestHandler;
use ncsa\phpmcj\router\Response;
use ncsa\phpmcj\util\cors\StandardGet;

class Index implements RequestHandler {
	use StandardGet;

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData(['message'=>'Hello World!']);
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}

?>