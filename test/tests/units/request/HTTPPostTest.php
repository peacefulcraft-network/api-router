<?php

use ncsa\phpmvj\router\Response;

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
}

?>