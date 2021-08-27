<?php namespace net\peacefulcraft\apirouter\spec\ext;

use net\peacefulcraft\apirouter\spec\application\WebApplication;

interface WebApplicationPlugin extends Plugin {
	/**
	 * Boot the plugin during Application startup.
	 * Use this to setup plugin resources and register handlers.
	 * Most things like the Router, Response, or Request won't exist yet.
	 * Resource and Plugin Managers are safe to use and interact with.
	 */
	public function startUp(WebApplication $Application): void;

	/**
	 * Shutdown the plugin during Application teardown.
	 * Use this to release any resources that the plugin may have registered.
	 * 
	 * Router, Response, and Request objects will exists, but all output
	 * has already been sent. This method should not attempt to output
	 * anything as part of a request.
	 */
	public function teardown(WebApplication $Application): void;
}

?>