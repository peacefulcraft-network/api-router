<?php
namespace ncsa\phpmcj\test\controllers\request;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\RequestHandler;
use ncsa\phpmcj\router\Response;

class HTTPPostEcho implements RequestHandler {
	public function handle(array $config, Request $request, Response $response): void {
		$response->setData(array_merge($request->getUriParameters(), $request->getBody()));
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}