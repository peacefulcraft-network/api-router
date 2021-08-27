<?php namespace net\peacefulcraft\apirouter\spec\application;

use net\peacefulcraft\apirouter\spec\ext\WebApplicationPlugin;
use net\peacefulcraft\apirouter\spec\router\Request;
use net\peacefulcraft\apirouter\spec\router\Response;
use net\peacefulcraft\apirouter\spec\router\Router;

interface WebApplication extends Application {

	/**
	 * @return null|Router Router instance for this Application, or null if one doesn't exist.
	 */
	public function getRouter(): ?Router;

	/**
	 * @return null|Router Request instance for this (HTTP) request lifecyle, or null if one doesn't exist.
	 */
	public function getRequest(): ?Request;

	/**
	 * @return null|Response Response instance that describes how the Application will respond to the client
	 *                       at the end of the request handling lifecycle.
	 */
	public function getResponse(): ?Response;

	/**
	 * Disable Application-managed header and body output for the current Request/Response
	 */
	public function disableAllOutput(): void;

	/**
	 * Disable Application-managed header output for the current Request/Response.
	 */
	public function disableHeaderOutput(): void;

	/**
	 * Disable Application-managed body output for the current Request/Response.
	 */
	public function disableBodyOutput(): void;

	/**
	 * Register a callback function to be invoked once the Application reaches the specified stage
	 * in the request-handling lifecycle.
	 * 
	 * @param WebLifecycleHook $Hook Lifecycle stage at which this callback should be registered.
	 * @param callable $callabe The function or method to execute. No arguements are passed. Return values are ignored.
	 */
	public function registerWebLifecycleHook(WebLifecycleHook $Hook, callable $callabe): void;

	public function usePlugin(WebApplicationPlugin $Plugin): void;
}

?>