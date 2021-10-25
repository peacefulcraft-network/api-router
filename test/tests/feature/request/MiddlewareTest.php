<?php

use net\peacefulcraft\apirouter\router\Response;

class MiddlewareTest extends ControllerTest {

	public function testMiddlewareFalseTerminatesRequest() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/never-ware');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);

		$expected = new Response(418, [], 0, '');
		$expected = json_encode($expected);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(418, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}

	public function testMiddlewareTrueContinuesRequest() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/always-ware');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);

        $expected = new Response(200, array("message" => "Hello World!"));
		$expected = json_encode($expected);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}
}
?>