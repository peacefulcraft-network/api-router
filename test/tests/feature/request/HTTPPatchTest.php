<?php

class HTTPPatchTest extends ControllerTest {
	public function testApplicationShouldParseTextualPatchBodyToGlobalPost() {
		$curl = curl_init();
		$requestData = ['user'=>'ncsa', 'actions'=>'delete'];
		$requestDataString = json_encode($requestData);

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($requestDataString)]);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestDataString);
		$result = curl_exec($curl);

		$expected = json_encode($requestData);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}

	public function testApplicationShouldParseTextualPatchBodyToGlobalPostAndUrlParamatersToGlobalGet() {
		$curl = curl_init();
		$requestData = ['user'=>'ncsa', 'actions'=>'delete'];
		$requestDataString = json_encode($requestData);

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo?query=can%20you%20parse%20url%20paramaters%20on%20the%20post%20body%20too');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($requestDataString)]);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestDataString);
		$result = curl_exec($curl);

		$expected = json_encode([
			'query'=> 'can you parse url paramaters on the post body too',
			'user'=>'ncsa',
			'actions'=>'delete'
		]);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}
}

?>