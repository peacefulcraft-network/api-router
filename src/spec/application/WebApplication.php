<?php namespace net\peacefulcraft\apirouter\spec\application;

use net\peacefulcraft\apirouter\spec\ext\WebApplicationPlugin;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;
use net\peacefulcraft\apirouter\spec\route\IRouter;
use net\peacefulcraft\apirouter\spec\route\Response;

interface WebApplication extends Application {

	/**
	 * @return null|Router Router instance for this Application, or null if one doesn't exist.
	 */
	public function getRouter(): ?IRouter;

	/**
	 * @return null|Router Request instance for this (HTTP) request lifecyle, or null if one doesn't exist.
	 */
	public function getRequest(): ?IRequest;

	/**
	 * @return null|Response Response instance that describes how the Application will respond to the client
	 *                       at the end of the request handling lifecycle.
	 */
	public function getResponse(): ?IResponse;

	/**
	 * Register a callback function to be invoked once the Application reaches the specified stage
	 * in the request-handling lifecycle.
	 * 
	 * @param string $Hook Lifecycle stage at which this callback should be registered.
	 * @param callable $callabe The function or method to execute. No arguements are passed. Return values are ignored.
	 */
	public function registerWebLifecycleHook(string $hook, callable $callabe): void;

	public function usePlugin(WebApplicationPlugin $Plugin): void;

	public function handleRequest(IRequest $Request=null): void;
}

?>