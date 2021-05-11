<?php
namespace net\peacefulcraft\apirouter\router;

class Response implements \JsonSerializable{
	private $_isRaw;
		public function setResponseTypeRaw(bool $isRaw): void { $this->_isRaw = $isRaw; }
		public function getResponseTypeRaw(): bool { return $this->_isRaw; }

	private $_httpResponseCode;
		public function getHttpResponseCode(): int { return $this->_httpResponseCode; }
	
	private $_responseHeaders;
		public function getResponseHeaders(): array { return $this->_responseHeaders; }

	private $_errorCode;
		public function setErrorCode(int $errorCode): void { $this->_errorCode = $errorCode; }
		public function getErrorCode(): int {return $this->_errorCode; }
	
	private $_errorMessage;
		public function setErrorMessage(string $errorMessage): void { $this->_errorMessage = $errorMessage; }
		public function getErrorMessage(): string { return $this->_errorMessage; }

	private $_data;
		public function setData(array $data): void { $this->_data = $data; }
		public function getData(): array { return $this->_data; }

	public function __construct(int $httpResponseCode = 418, array $data = [], int $errorCode = 0, string $errorMessage = '') {
		$this->_isRaw = false;
		$this->setHttpResponseCode($httpResponseCode);
		$this->_data = $data;
		$this->_errorCode = $errorCode;
		$this->_errorMessage = $errorMessage;
	}

	public function setHttpResponseCode(int $httpResponseCode): void {
		$this->_httpResponseCode = $httpResponseCode;
		http_response_code($httpResponseCode);

		// If empty response, disable framework output
		if ($httpResponseCode === Response::HTTP_EMPTY_RESPONSE) {
			$this->setResponseTypeRaw(true);
		}
	}

	/**
	 * Set an HTTP Resonse header.
	 * This method exists primarly for accounting. Having all headers
	 * be set by one method means we can more easily track when and what
	 * headers are set for logging and debugging.
	 */
	public function setHeader($headerName, $headerValue): void {
		$this->_responseHeaders[$headerName] = $headerValue;
		header($headerName . ': ' . $headerValue);
	}

	public function jsonSerialize() {
		return [
			'error_no'=>$this->_errorCode,
			'error' => $this->_errorMessage,
			'data' => $this->_data
		];
	}

	/**
	 * If Response is configured as raw ($_isRaw), Response is muted
	 * and output is presumed to be printed elsewhere in the application.
	 */
	public function __toString() {
		if ($this->_isRaw) {
			return '';
		} else {
			return json_encode($this);
		}
	}

	public const HTTP_OK = 200;
	public const HTTP_CREATED = 201;
	public const HTTP_ACCEPTED = 202;
	public const HTTP_EMPTY_RESPONSE = 204;
	public const HTTP_REDIRECT_PERMANENTLY = 301;
	public const HTTP_REDIRECT_TEMPORARLY = 302;
	public const HTTP_REDIRECT_JUST_THIS_ONCE = 303;
	public const HTTP_NOT_MODIFIED = 304;
	public const HTTP_BAD_REQUEST = 400;
	public const HTTP_UNAUTHORIZED = 401;
	public const HTTP_NOT_PERMITTED = 403;
	public const HTTP_NOT_FOUND = 404;
	public const HTTP_TIMEOUT = 408;
	public const HTTP_TEA_POT = 418;
	public const HTTP_RATE_LIMIT = 429;
	public const HTTP_INTERNAL_ERROR = 500;
	public const HTTP_BAD_GATEWAY = 502;
	public const HTTP_SERVICE_UNAVAILABLE = 503;
	public const HTTP_GATEWAY_TIMEOUT = 504;

}
