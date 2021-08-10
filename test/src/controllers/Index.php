<?php
namespace net\peacefulcraft\apirouter\test\controllers;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\spec\router\Controller;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\util\cors\StandardGet;

class Index implements Controller {
	use StandardGet;

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData(['message'=>'Hello World!']);
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}

?>