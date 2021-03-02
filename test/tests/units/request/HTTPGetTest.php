<?php

use ncsa\phpmvj\router\Response;

class HTTPGetTest extends ControllerTest {
	public function testApplicationShouldParseUrlParamatersToGlobalGet() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo?user=ncsa&actions=delete');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);

		$expected = new Response(200, ['user'=>'ncsa', 'actions'=>'delete'], 0, '');
		$expected = json_encode($expected);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}

	public function testApplicationShouldReturnCORSHeaders() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$result = explode(PHP_EOL, trim(curl_exec($curl)));

		foreach($result as $header) {
			$header = explode(":", $header);
			if (count($header) > 1) {
				$result[trim($header[0])] = trim($header[1]);
				// Header-Name = Header value
			// Declarative, header is not a k/v
			} else {
				$result[trim($header[0])] = trim($header[0]);
				// Header-name = Header-name
			}
		}

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals('*', $result['Access-Control-Allow-Origin']);
		curl_close($curl);
	}
}

?>