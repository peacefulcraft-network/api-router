<?php
namespace net\peacefulcraft\apirouter\router;

use net\peacefulcraft\apirouter\spec\render\RenderEngine;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class Response implements IResponse {
	private int $_httpResponseCode;
		public function getHttpResponseCode(): int { return $this->_httpResponseCode; }
		public function setHttpResponseCode(int $httpResponseCode): void { $this->_httpResponseCode = $httpResponseCode; }
	
	private array $_httpResponseHeaders;
		public function getResponseHeaders(): array { return $this->_httpResponseHeaders; }
		public function getResponseHeader(string $header): ?string {
			if (array_key_exists($header, $this->_httpResponseHeaders)) {
				return $this->_httpResponseHeaders[$header];
			} else {
				return null;
			}
		}
		/**
		 * Set an HTP response header.
		 * headers are not set a time of calling. They are set after a Controller or Middleware
		 * has returned a Response object, but before RenderEngine::render().
		 */
		public function setHeader($headerName, $headerValue): void {
			$this->_httpResponseHeaders[$headerName] = $headerValue;
		}

	private ?RenderEngine $_RenderEngine = null;
		public function getRenderEngine(): ?RenderEngine { return $this->_RenderEngine; }
		public function setRenderEngine(?RenderEngine $RenderEngine): void { $this->_RenderEngine = $RenderEngine; }


	public function __construct(int $httpResponseCode = 418, array $httpResponseHeaders = [], ?RenderEngine $RenderEngine=null) {
		$this->_httpResponseCode = $httpResponseCode;
		$this->_httpResponseHeaders = $httpResponseHeaders;
		$this->_RenderEngine = $RenderEngine;
	}
}

?>