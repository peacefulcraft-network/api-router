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
}

?>