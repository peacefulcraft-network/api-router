<?php

use ncsa\phpmvj\router\Response;

class HTTPOptionsTest extends ControllerTest {
	public function testApplicationShouldReturnCORSHeaders() {
		global $config;
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/options');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
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
		$this->assertEquals('OPTIONS, GET, DELETE, POST', $result['Access-Control-Allow-Methods']);
		$this->assertEquals($config['cors']['max-age'], $result['Access-Control-Max-Age']);
		curl_close($curl);
	}
}

?>