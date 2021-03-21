<?php
namespace ncsa\phpmvj\util\cors;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\Response;
use ncsa\phpmvj\router\Router;

trait StandardPatch {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'PATCH');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}