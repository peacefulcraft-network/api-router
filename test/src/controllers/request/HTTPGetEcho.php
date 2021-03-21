<?php
namespace ncsa\phpmcj\test\controllers\request;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\RequestHandler;
use ncsa\phpmcj\router\Response;
use ncsa\phpmcj\util\cors\StandardCORS;

class HTTPGetEcho implements RequestHandler {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'OPTIONS, GET, DELETE');
	}

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($request->getUriParameters());
	}
}