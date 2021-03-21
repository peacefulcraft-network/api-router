<?php

use ncsa\phpmcj\router\RequestMethod;
use ncsa\phpmcj\router\Router;
use PHPUnit\Framework\TestCase;

class RouterRoutingTest extends TestCase {

	public function testStaticPathMatching() {
		$router = new Router();
		$router->registerRoute(RequestMethod::OTHER, '/', [], '\level0');
		$router->registerRoute(RequestMethod::OTHER, '/deeper', [], '\level0\level1');
		$router->registerRoute(RequestMethod::OTHER, '/even/deeper', [], '\level0\level1\level2');
		$router->registerRoute(RequestMethod::OTHER, '/you/get/the/idea', [], '\level0\level1\level2\level3');
		$handler = $router->resolve('/');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0', $handler->getMatchedHandler());

		$handler = $router->resolve('/deeper');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1', $handler->getMatchedHandler());

		$handler = $router->resolve('/even/deeper');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1\level2', $handler->getMatchedHandler());

		$handler = $router->resolve('/you/get/the/idea');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1\level2\level3', $handler->getMatchedHandler());
	}

	public function testDynamicPathMatching() {
		$router = new Router();
		$router->registerRoute(RequestMethod::OTHER, '/', [], '\level0');
		$router->registerRoute(RequestMethod::OTHER, '/:val1', [], '\level0\level1');
		$router->registerRoute(RequestMethod::OTHER, '/even/deeper', [], '\level0\level1\level2');
		$router->registerRoute(RequestMethod::OTHER, '/even/trickier/complex/:val1', [], '\level0\level1\level2\level3');
		$router->registerRoute(RequestMethod::OTHER, '/even/:val1/:val2/idea', [], '\level0\level1\level2\level3');

		$handler = $router->resolve('/');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0', $handler->getMatchedHandler());

		$handler = $router->resolve('/deeper');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1', $handler->getMatchedHandler());
		$this->assertEquals('deeper', $handler->getUriParameters()['val1']);

		$handler = $router->resolve('/even/deeper');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1\level2', $handler->getMatchedHandler());

		$handler = $router->resolve('/even/trickier/complex/idea');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1\level2\level3', $handler->getMatchedHandler());
		$this->assertEquals('idea', $handler->getUriParameters()['val1']);

		$handler = $router->resolve('/even/more/complex/idea');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1\level2\level3', $handler->getMatchedHandler());
		$this->assertEquals('more', $handler->getUriParameters()['val1']);
		$this->assertEquals('complex', $handler->getUriParameters()['val2']);

		$handler = $router->resolve('/even/more/complex path segment/idea');
		$this->assertTrue($handler->hasMatchedHandler());
		$this->assertEquals('\level0\level1\level2\level3', $handler->getMatchedHandler());
		$this->assertEquals('more', $handler->getUriParameters()['val1']);
		$this->assertEquals('complex path segment', $handler->getUriParameters()['val2']);
	}

}