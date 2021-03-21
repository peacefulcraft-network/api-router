<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\router\Response;
use ncsa\phpmvj\util\cors\StandardCORS;

class HTTPGetEcho implements RequestHandler {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'OPTIONS, GET, DELETE');
	}

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($request->getUriParameters());
	}
}