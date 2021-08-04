<?php namespace net\peacefulcraft\apirouter\api;

use net\peacefulcraft\apirouter\router\Router;

/**
 * Plugin API extension that indicates the implementing plugin
 * will provide HTTP routes to the router. 
 */
interface ApplicationRouteProvider extends ApplicationPlugin {

	/**
	 * Plugin life-cycle hook which provides access to a wrapped-Router instance
	 * that the implementing plugin can use to register any web routes in provides.
	 */
	public function registerRoutes(Router $Router): void;
}

?>