<?php
namespace net\peacefulcraft\apirouter\test\controllers\request;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\RequestHandler;
use net\peacefulcraft\apirouter\router\Response;

class HTTPPostEcho implements RequestHandler {
	public function handle(array $config, Request $request, Response $response): void {
		$response->setData(array_merge($request->getUriParameters(), $request->getBody()));
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}