<?php

class HTTPGetTest extends ControllerTest {
	public function testApplicationShouldParseUrlParamatersToGlobalGet() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/echo?user=ncsa&actions=delete');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);

		$expected = json_encode(['user'=>'ncsa', 'actions'=>'delete']);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}
}

?>