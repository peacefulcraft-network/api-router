<?php
namespace net\peacefulcraft\apirouter\test\controllers\request;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\spec\router\Controller;
use net\peacefulcraft\apirouter\router\Response;

class HTTPGetEcho implements Controller {
	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($request->getUriParameters());
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}