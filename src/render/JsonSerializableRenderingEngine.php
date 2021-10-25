<?php namespace net\peacefulcraft\apirouter\render;

use net\peacefulcraft\apirouter\exceptions\RenderingEngineException;
use net\peacefulcraft\apirouter\spec\render\RenderEngine;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class JsonSerializableRenderingEngine implements RenderEngine {

	private mixed $_content;

	public function __construct(mixed $content) {
		$this->_content = $content;
	}

	public function &render(IResponse $Response): string {
		$Response->setHeader('Content-Type', 'application/json');

		$json = json_encode($this->_content);
		if ($json === false) {
			throw new RenderingEngineException('Provided failed to json_encode. ' . json_last_error_msg(), json_last_error());
		}

		return $json;
	}
}

?>