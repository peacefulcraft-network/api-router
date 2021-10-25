<?php namespace net\peacefulcraft\apirouter\render;

use net\peacefulcraft\apirouter\spec\render\RenderEngine;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class PlainTextRenderingEngine implements RenderEngine {

	private string $_content;

	public function __construct(string $content) {
		$this->_content = $content;
	}

	public function &render(IResponse $Response): string {
		$Response->setHeader('Content-Type', 'text/plain');
		
		return $this->_content;
	}
}

?>