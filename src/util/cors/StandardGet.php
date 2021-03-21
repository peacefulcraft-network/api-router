<?php
namespace ncsa\phpmcj\util\cors;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\Response;

trait StandardGet {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'GET');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}