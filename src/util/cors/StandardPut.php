<?php
namespace net\peacefulcraft\apirouter\util\cors;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;

trait StandardPut {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'PUT');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}