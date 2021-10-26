<?php

use net\peacefulcraft\apirouter\router\Response;

class MiddlewareTest extends ControllerTest {

	public function testMiddlewareFalseTerminatesRequest() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/never-ware');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_exec($curl);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(Response::HTTP_NOT_PERMITTED, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		curl_close($curl);
	}

	public function testMiddlewareTrueContinuesRequest() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/always-ware');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals('<h1>Hello World!</h1>', $result);
		curl_close($curl);
	}
}
?>