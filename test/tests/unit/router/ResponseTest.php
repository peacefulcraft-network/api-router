<?php

use net\peacefulcraft\apirouter\render\JsonSerializableRenderingEngine;
use net\peacefulcraft\apirouter\render\PlainTextRenderingEngine;
use net\peacefulcraft\apirouter\router\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase {
	public function testResponseCodeAccessors(): void {
		$Response = new Response(Response::HTTP_EMPTY_RESPONSE, [], null);
		$this->assertEquals(Response::HTTP_EMPTY_RESPONSE, $Response->getHttpResponseCode(), 'HTTP response code did not matched expected value when accessed by gettor.');

		$Response->setHttpResponseCode(Response::HTTP_TEA_POT);
		$this->assertEquals(Response::HTTP_TEA_POT, $Response->getHttpResponseCode(), 'HTTP response code was not updated by call to setter.');
	
		// Ensure no other values were touched
		$this->assertEmpty($Response->getResponseHeaders(), 'HTTP headers array was modified by http response code accessors.');
		$this->assertNull($Response->getRenderEngine(), 'Response Render Engine was modified by http response code accessors.');
	}

	public function testResponseHeadersAccessors(): void {
		$headers = [ 'Content-type' => 'drink/tea', 'X-Custom' => 'custom header' ];
		$Response = new Response(Response::HTTP_TEA_POT, $headers, null);

		$this->assertEquals($headers, $Response->getResponseHeaders(), 'Response headers from getter do not match expected values.');
		$this->assertEquals($headers['Content-type'], $Response->getResponseHeader('Content-type'), 'Single header getter did not return expected header value.');
		$this->assertEquals($headers['X-Custom'], $Response->getResponseHeader('X-Custom'), 'Single header getter did not return expected header value.');

		$newContentType = 'drink/tea; green';
		$Response->setHeader('Content-type', $newContentType);
		$this->assertEquals($newContentType, $Response->getResponseHeader('Content-type'), 'Single header getter did not return modified header value.');
		$this->assertEquals($headers['X-Custom'], $Response->getResponseHeader('X-Custom'), 'Single header setter appears to have had side effects on other programmed headers.');

		$headers['Content-type'] = $newContentType;
		$this->assertEquals($headers, $Response->getResponseHeaders(), 'Response headers from getter do not match expected values. Single header setters appears to have had side effects on other programmed headers.');
	
		// Ensure no other values were touched
		$this->assertEquals(Response::HTTP_TEA_POT, $Response->getHttpResponseCode());
		$this->assertNull($Response->getRenderEngine());
	}

	public function testRenderEngineAccessors() {
		$Response = new Response(Response::HTTP_TEA_POT, [], new PlainTextRenderingEngine('Hello World!'));
		$this->assertInstanceOf(PlainTextRenderingEngine::class, $Response->getRenderEngine(), 'RenderEngine getter returned unexepected value after object instantiation.');
		
		$Response->setRenderEngine(new JsonSerializableRenderingEngine([]));
		$this->assertInstanceOf(JsonSerializableRenderingEngine::class, $Response->getRenderEngine(), 'RenderEngine getter returned unexpected value after call to setter.');

		// Ensure no other values were touched
		$this->assertEquals(Response::HTTP_TEA_POT, $Response->getHttpResponseCode());
		$this->assertEmpty($Response->getResponseHeaders());
	}
}

?>