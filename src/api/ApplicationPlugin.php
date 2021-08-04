<?php namespace net\peacefulcraft\apirouter\api;

use net\peacefulcraft\apirouter\Application;

interface ApplicationPlugin {

	/**
	 * Prefix for plugin routes and commands to avoid collisions with first-party
	 * and other third-party resources that may exist in the Application.
	 */
	public function getPrefix(): string;

	/**
	 * Called when the web Application is booting.
	 * Perform route registration and internal plugin setup here.
	 * 
	 * @throws RuntimeException If the plugin failed to initialize.
	 */
	public function enablePlugin(Application $Application): void;

	/**
	 * Called when the web Application is shuting down. This is after the
	 * request has already been served.
	 * 
	 * Note if a severe PHP error occured which interrupted
	 * Application execution, this method will not be called.
	 * 
	 * @throws RuntimeException If the plugin encountered an error during shutdown.
	 */
	public function disablePlugin(): void;
}

?>