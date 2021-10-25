<?php

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\RequestMethod;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;
use net\peacefulcraft\apirouter\test\middleware\Alwaysware;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {

	public function testRequestMethodAccessors() {
		$Request = new Request('/', [], '');

		$get = new RequestMethod(RequestMethod::GET);
		$Request->setEMethod($get);
		$this->assertEquals($get, $Request->getEMethod());
	}

	public function testPathAssessors() {
		$Request = new Request('/', [], '');
		$this->assertEquals('/', $Request->getPath());
	}

	public function testUriParameterAccessors() {
		$Request = new Request('/', [], '');
		$this->assertEmpty($Request->getUriParameters());

		$uriParameters = [ 'test' => 'value1' ];
		$Request->setUriParameters($uriParameters);

		$this->assertEquals($uriParameters, $Request->getUriParameters());
	}

	public function testBodyAccessors() {
		$Request = new Request('/', [], '');
		$this->assertEmpty($Request->getBody());

		$body = [ 'test' => 'value1' ];
		$Request->setBody($body);

		$this->assertEquals($body, $Request->getBody());
	}

	public function testMiddlewareAsseccors() {
		$Request = new Request('/', [], '');
		$this->assertEmpty($Request->getMiddleware());

		$Middleware = [ new Alwaysware() ];
		$Request = new Request('/', $Middleware, '');
		$this->assertEquals($Middleware, $Request->getMiddleware());
	}

	public function testControllerAccessor() {
		$Controller = new class implements Controller {
			public function handle(array $config, IRequest $Request): IResponse {
				return new Response();
			}
		};

		$Request = new Request('/', [], $Controller);
		$this->assertEquals($Controller, $Request->getController());
	}
}

?>