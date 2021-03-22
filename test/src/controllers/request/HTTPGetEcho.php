<?php
namespace ncsa\phpmcj\test\controllers\request;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\RequestHandler;
use ncsa\phpmcj\router\Response;

class HTTPGetEcho implements RequestHandler {
	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($request->getUriParameters());
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}