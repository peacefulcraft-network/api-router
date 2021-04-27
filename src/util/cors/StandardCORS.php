<?php
namespace net\peacefulcraft\apirouter\util\cors;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;

trait StandardCORS {
	use ResolveAllowedOrigin;

	private function setCORSHeaders(Request $request, Response $response, string $methods): void {
		global $config;
		$allowed_origin = $this->_resolveAllowedOrigin($config['cors']['origin'], $request->getUri());

		$response->setHeader('Access-Control-Allow-Origin', $allowed_origin);
		$response->setHeader('Access-Control-Allow-Methods', 'OPTIONS, ' . strtoupper($methods));
	}
}