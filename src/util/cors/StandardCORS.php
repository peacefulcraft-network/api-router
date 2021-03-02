<?php
namespace ncsa\phpmvj\util\cors;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\Response;

trait StandardCORS {
	use ResolveAllowedOrigin;

	private function setCORSHeaders(Request $request, Response $response, string $methods): void {
		global $config;
		$allowed_origin = $this->_resolveAllowedOrigin($config['cors']['origin'], $request->getUri());

		$response->setHeader('Access-Control-Allow-Origin', $allowed_origin);
		$response->setHeader('Access-Control-Allow-Methods', 'OPTIONS, ' . strtoupper($methods));
	}
}