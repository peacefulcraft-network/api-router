<?php

use ncsa\phpmcj\router\Response;

class HTTPPostTest extends ControllerTest {
	public function testApplicationShouldParseTextualPostBodyToGlobalPost() {
		$curl = curl_init();
		$requestData = ['user'=>'ncsa', 'actions'=>'delete'];
		$requestDataString = json_encode($requestData);

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($requestDataString)]);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestDataString);
		$result = curl_exec($curl);

		$expected = new Response(200, $requestData, 0, '');
		$expected = json_encode($expected);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}

	public function testApplicationShouldParseTextualPostBodyToGlobalPostAndUrlParamatersToGlobalGet() {
		$curl = curl_init();
		$requestData = ['user'=>'ncsa', 'actions'=>'delete'];
		$requestDataString = json_encode($requestData);

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo?query=can%20you%20parse%20url%20paramaters%20on%20the%20post%20body%20too');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($requestDataString)]);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestDataString);
		$result = curl_exec($curl);

		$expected = new Response(200, [
			'query'=> 'can you parse url paramaters on the post body too',
			'user'=>'ncsa',
			'actions'=>'delete'
		], 0, '');
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
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
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