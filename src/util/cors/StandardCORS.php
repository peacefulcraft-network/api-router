<?php
namespace ncsa\phpmvj\util\cors;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\router\Router;

trait StandardCORS {
	use ResolveAllowedOrigin;

	private function setCORSHeaders(string $methods): void {
		global $config;
		$allowed_origin = $this->_resolveAllowedOrigin($config['cors']['origin'], Router::getUri());

		Application::getResponse()->setHeader('Access-Control-Allow-Origin', $allowed_origin);
		if (Router::isPreflight()) {
			Application::getResponse()->setHeader('Access-Control-Allow-Methods', 'OPTIONS, ' . strtoupper($methods));
		}
	}
}