<?php
namespace net\peacefulcraft\apirouter\util\cors;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;

trait StandardPost {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'POST');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}