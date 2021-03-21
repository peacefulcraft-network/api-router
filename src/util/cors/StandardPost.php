<?php
namespace ncsa\phpmcj\util\cors;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\Response;

trait StandardPost {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'POST');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}