<?php
namespace ncsa\phpmvj\test\controllers\request;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\router\Response;
use ncsa\phpmvj\util\cors\StandardCORS;

class HTTPPostEcho implements RequestHandler {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'POST, PUT, PATCH');
	}

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData(array_merge($request->getUriParameters(), $request->getBody()));
	}
}