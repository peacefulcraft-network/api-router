<?php

use net\peacefulcraft\apirouter\router\Response;

class HTTPFileUploadTest extends ControllerTest {
	public function testTextFileUpload() {
		$curl = curl_init();
		$file = curl_file_create(__DIR__ . '/../../resources/request/file_upload_test_txt_file.txt', 'text/plain');
		$post_data = ['file_contents' => $file];

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/file-upload');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		$result = json_decode(curl_exec($curl), true);
		
		$this->assertEquals('file_upload_test_txt_file.txt', $result['data']['name']);
		$this->assertEquals('text/plain', $result['data']['type']);
		$this->assertEquals('93', $result['data']['size']);
		$this->assertEquals(curl_errno($curl), 0);
		
		curl_close($curl);
	}

	public function testbinaryFileUpload() {
		$curl = curl_init();
		$file = curl_file_create(__DIR__ . '/../../resources/request/file_upload_test_binary_file.png', 'image/png');
		$post_data = ['file_contents' => $file];

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/file-upload');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		$result = json_decode(curl_exec($curl), true);
		
		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals('file_upload_test_binary_file.png', $result['data']['name']);
		$this->assertEquals('image/png', $result['data']['type']);
		$this->assertEquals('445307', $result['data']['size']);
		
		curl_close($curl);
	}
}
?>