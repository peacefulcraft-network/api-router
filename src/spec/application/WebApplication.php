<?php namespace net\peacefulcraft\apirouter\spec\application;

use net\peacefulcraft\apirouter\spec\router\Request;
use net\peacefulcraft\apirouter\spec\router\Response;
use net\peacefulcraft\apirouter\spec\router\Router;

interface WebApplication extends Application {

	public function getRouter(): ?Router;

	public function getRequest(): ?Request;

	public function getResponseBody(): ?Response;

	public function disableAllOutput(): void;

	public function disableHeaderOutput(): void;

	public function disableBodyOutput(): void;

	public function registerWebLifecycleHook(WebLifecycleHooks $hook, callable $callabe): void;
}

?>