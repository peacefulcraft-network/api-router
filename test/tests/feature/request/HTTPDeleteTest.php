<?php

class HTTPDeleteTest extends ControllerTest {
	public function testApplicationShouldParseUrlParamatersToGlobalGet() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo?user=ncsa&actions=delete');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$result = curl_exec($curl);

		$expected = json_encode([ 'user'=>'ncsa', 'actions'=>'delete' ]);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}

	public function testApplicationShouldIgnoreRequestBodyButParseUrlParamatersToGlobalGet() {
		$curl = curl_init();
		$requestData = ['user'=>'ncsa', 'actions'=>'delete'];
		$requestDataString = json_encode($requestData);

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo?query=can%20you%20parse%20url%20paramaters%20on%20the%20post%20body%20too');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($requestDataString)]);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $requestDataString);
		$result = curl_exec($curl);

		$expected = json_encode(['query'=> 'can you parse url paramaters on the post body too']);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}
}

?>