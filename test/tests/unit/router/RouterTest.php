<?php

use net\peacefulcraft\apirouter\router\RequestMethod;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\router\Router;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;
use PHPUnit\Framework\TestCase;

class DaDa implements Controller {
	public function handle(array $config, IRequest $request): IResponse { return new Response(); }
}

class RouterRoutingTest extends TestCase {

	public function testStaticPathMatching() {
		/*
			Setup Router and distinct Controllers so we can
			check that we get the correct Object back for each route.
		*/
		$router = new Router();

		$L0Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/', [], $L0Anon);

		$L1Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/deeper', [], $L1Anon);

		$L2Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/even/deeper', [], $L2Anon);

		$L3Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/you/get/the/idea', [], $L3Anon);

		/*
			Test that the registered routes return their correct controller.
		*/
		$Request = $router->resolve('/');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L0Anon::class, $Request->getController()::class);

		$Request = $router->resolve('/deeper');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L1Anon::class, $Request->getController()::class);


		$Request = $router->resolve('/even/deeper');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L2Anon::class, $Request->getController()::class);


		$Request = $router->resolve('/you/get/the/idea');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L3Anon::class, $Request->getController()::class);

	}

	public function testDynamicPathMatching() {
		/*
			Setup Router and distinct Controllers so we can
			check that we get the correct Object back for each route.
		*/
		$router = new Router();

		$L0Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/', [], $L0Anon);

		$L1Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/:val1', [], $L1Anon);

		$L2Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/even/deeper', [], $L2Anon);

		$L3Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/even/trickier/complex/:val1', [], $L3Anon);
		
		$L4Anon = new class extends DaDa {};
		$router->registerRoute(RequestMethod::OTHER, '/even/:val1/:val2/idea', [], $L4Anon);

		/*
			Test that the registered routes return their correct controller.
		*/
		$Request = $router->resolve('/');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L0Anon::class, $Request->getController()::class);

		$Request = $router->resolve('/deeper');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L1Anon::class, $Request->getController()::class);
		$this->assertEquals('deeper', $Request->getUriParameters()['val1']);

		$Request = $router->resolve('/even/deeper');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L2Anon::class, $Request->getController()::class);

		$Request = $router->resolve('/even/trickier/complex/idea');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L3Anon::class, $Request->getController()::class);
		$this->assertEquals('idea', $Request->getUriParameters()['val1']);

		$Request = $router->resolve('/even/more/complex%20path%20segment/idea');
		$this->assertNotNull($Request);
		$this->assertNotNull($Request->getController());
		$this->assertEquals($L4Anon::class, $Request->getController()::class);
		$this->assertEquals('more', $Request->getUriParameters()['val1']);
		$this->assertEquals('complex path segment', $Request->getUriParameters()['val2']);
	}
}