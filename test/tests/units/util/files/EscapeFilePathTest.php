<?php

use ncsa\phpmvj\util\files\EscapeFilePath;
use PHPUnit\Framework\TestCase;

class EscapeFilePathTest extends TestCase {
	use EscapeFilePath;

	public function testEscapeFilePathDoesntBreakAlreadySafeFileNames() {
		$filename = 'This_file_name_is_already_safe';
		$this->assertEquals($filename, $this->_escapeFilePath($filename));
	}

	public function testEscapeFilePathProtectsAgainstInvalidOrMaliciousFilePaths() {
		$this->assertEquals('This_file_name_is_malicious', $this->_escapeFilePath('./../This/file/name/is.malicious'));
	}
}
?>