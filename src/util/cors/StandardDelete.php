<?php
namespace ncsa\phpmvj\util\cors;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\Response;

trait StandardDelete {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'DELETE');
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}