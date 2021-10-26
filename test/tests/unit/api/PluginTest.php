<?php

use net\peacefulcraft\apirouter\Application;
use net\peacefulcraft\apirouter\render\PlainTextRenderingEngine;
use net\peacefulcraft\apirouter\router\RequestMethod;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\spec\application\WebApplication;
use net\peacefulcraft\apirouter\spec\application\WebLifecycleHook;
use net\peacefulcraft\apirouter\spec\ext\WebApplicationPlugin;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;

assert_options(ASSERT_EXCEPTION, 1);

class PluginTest extends ControllerTest {

	private static Application $Application;

	/**
	 * @beforeClass
	 */
	public static function prep() {
		SELF::$Application = new Application([]);
		SELF::$Application->usePlugin(new DummyPlugin());
	}

	public function testPluginLifecycleHandling(): void {
		$this->assertTrue(DummyPlugin::$DummyPluginDidBoot);

		@$Request = SELF::$Application->getRouter()->resolve(RequestMethod::GET, '/');
		@SELF::$Application->handleRequest($Request);

		$this->assertTrue(DummyPlugin::$HookBeforeRouteCalled);
		$this->assertTrue(DummyPlugin::$HookBeforeMiddlewareCalled);
		$this->assertTrue(DummyPlugin::$HookBeforeControllerCalled);
		$this->assertTrue(DummyPlugin::$HookBeforeFlushCalled);
		$this->assertTrue(DummyPlugin::$HookBeforeFlushCalled);
		$this->assertTrue(DummyPlugin::$HookAfterFlushCalled);
		$this->assertTrue(DummyPlugin::$HookBeforeTeardownCalled);
		$this->assertTrue(DummyPlugin::$DummyPluginDidTeardown);
	}
}

class DummyPlugin implements WebApplicationPlugin {

	public static bool $DummyPluginDidBoot = false;
	public static bool $DummyPluginDidTeardown = false;

	public static bool $HookBeforeRouteCalled = false;
	public static bool $HookBeforeMiddlewareCalled = false;
	public static bool $HookBeforeControllerCalled = false;
	public static bool $HookBeforeFlushCalled = false;
	public static bool $HookAfterFlushCalled = false;
	public static bool $HookBeforeTeardownCalled = false;

	public function getName(): string {
		return 'Dummy Plugin';
	}

	public function getPluginPrefix(): string {
		return 'dmy';
	}

	public function getVersion(): float {
		return 1.0;
	}

	public function pluginDepends(): array {
		return [];
	}

	public function startUp(WebApplication $Application): void {
		SELF::$DummyPluginDidBoot = true;
		$Application->registerWebLifecycleHook(WebLifecycleHook::BEFORE_REQUEST_ROUTE, function() {
			SELF::$HookBeforeRouteCalled = true;
			assert(SELF::$HookBeforeMiddlewareCalled === false);
			assert(SELF::$HookBeforeControllerCalled === false);
			assert(SELF::$HookBeforeFlushCalled === false);
			assert(SELF::$HookAfterFlushCalled === false);
			assert(SELF::$HookBeforeTeardownCalled === false);
		});

		$Application->registerWebLifecycleHook(WebLifecycleHook::BEFORE_MIDDLEWARE_EXEC, function() {
			SELF::$HookBeforeMiddlewareCalled = true;
			assert(SELF::$HookBeforeControllerCalled === false);
			assert(SELF::$HookBeforeFlushCalled === false);
			assert(SELF::$HookAfterFlushCalled === false);
			assert(SELF::$HookBeforeTeardownCalled === false);
		});

		$Application->registerWebLifecycleHook(WebLifecycleHook::BEFORE_CONTROLLER_EXEC, function() {
			SELF::$HookBeforeControllerCalled = true;
			assert(SELF::$HookBeforeFlushCalled === false);
			assert(SELF::$HookAfterFlushCalled === false);
			assert(SELF::$HookBeforeTeardownCalled === false);
		});

		$Application->registerWebLifecycleHook(WebLifecycleHook::BEFORE_RESPONSE_FLUSH, function() {
			SELF::$HookBeforeFlushCalled = true;
			assert(SELF::$HookAfterFlushCalled === false);
			assert(SELF::$HookBeforeTeardownCalled === false);
		});

		$Application->registerWebLifecycleHook(WebLifecycleHook::AFTER_RESPONSE_FLUSH, function() {
			SELF::$HookAfterFlushCalled = true;
			assert(SELF::$HookBeforeTeardownCalled === false);
		});

		$Application->registerWebLifecycleHook(WebLifecycleHook::BEFORE_TEARDOWN, function() {
			SELF::$HookBeforeTeardownCalled = true;
		});

		$Application->getRouter()->registerRoute(RequestMethod::GET, '/', [], new class implements Controller {
			public function handle(array $config, IRequest $Request): IResponse {
				return new Response(Response::HTTP_OK, [], new PlainTextRenderingEngine(''));
			}
		});
	}

	public function teardown(WebApplication $Application): void {
		SELF::$DummyPluginDidTeardown = true;
	}
}
?>