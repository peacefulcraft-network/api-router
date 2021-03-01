<?php
namespace ncsa\phpmvj\util\cors;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\Response;

trait StandardPut {
	use StandardCORS;

	public function options(array $config, Request $request, Response $response): void {
		$this->setCORSHeaders($request, $response, 'PUT');
	}
}