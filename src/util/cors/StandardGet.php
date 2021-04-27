<?php
namespace net\peacefulcraft\apirouter\util\cors;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;

trait StandardGet {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'GET');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}