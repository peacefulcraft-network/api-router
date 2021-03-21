<?php
namespace ncsa\phpmcj\util\cors;

use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\Response;
use ncsa\phpmcj\router\Router;

trait StandardPatch {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'PATCH');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}