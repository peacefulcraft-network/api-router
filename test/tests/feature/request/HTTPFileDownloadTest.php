<?php

class HTTPFileDownloadTest extends ControllerTest {
	public function testTextFileDownload() {
		$this->markTestSkipped('Downloads');
		$curl = curl_init();
		$download_temp_path = sys_get_temp_dir() . '/text_test_file.png';
		$fp = fopen($download_temp_path, 'w+');
		$this->assertNotFalse($fp, 'Unable to open temp file location to save file.');

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/file-download/file_upload_test_txt_file.txt');
		curl_setopt($curl, CURLOPT_FILE, $fp);
		curl_exec($curl);
		
		$this->assertEquals(curl_errno($curl), 0);
		$this->assertFileExists($download_temp_path, 'File download location empty. File download probably failed.');
		$this->assertEquals('93', filesize($download_temp_path));
		$this->assertEquals('f00c5281f403b7d5956f98abaa555295', hash_file('md5', $download_temp_path));
		curl_close($curl);
	}

	public function testbinaryFileDownload() {
		$this->markTestSkipped('Downloads');
		$curl = curl_init();
		$download_temp_path = sys_get_temp_dir() . '/binary_test_file.png';
		$fp = fopen($download_temp_path, 'w+');
		$this->assertNotFalse($fp, 'Unable to open temp file location to save file.');

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/file-download/file_upload_test_binary_file.png');
		curl_setopt($curl, CURLOPT_FILE, $fp);
		curl_exec($curl);
		
		$this->assertEquals(curl_errno($curl), 0);
		$this->assertFileExists($download_temp_path, 'File download location empty. File download probably failed.');
		$this->assertEquals('445307', filesize($download_temp_path));
		$this->assertEquals('046a7141b614a3f3d0bf78100899f5e0', hash_file('md5', $download_temp_path));
		curl_close($curl);
	}
}
?>