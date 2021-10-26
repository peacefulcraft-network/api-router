<?php namespace net\peacefulcraft\apirouter\exceptions;

use net\peacefulcraft\apirouter\exceptions\traits\HTTPReportableExceptionTrait;
use net\peacefulcraft\apirouter\spec\exception\HTTPReportableException;
use RuntimeException;
use Throwable;

/**
 * An Exception which the frame-work can auto-respond on error state.
 */
class HTTPSemanticRuntimeException extends RuntimeException implements HTTPReportableException {

	use HTTPReportableExceptionTrait;

	/**
	 * @param int $errorCode Descriptive code indicating the error that occured
	 * @param int $httpRespondeCode The Http status code to respond with, usually 4xx|5xx.
	 * @param string $errorMessage An technical description of what happened. (Not shown to end-user).
	 * @param string $httpReportableMessage A message to SHOW THE USER, explaining what happened and next steps.
	 */
	public function __construct(int $errorCode, int $httpRespondeCode, string $errorMessage, string $httpReportableMessage, ?Throwable $Previous=null) {
		$this->httpCode = $httpRespondeCode;
		$this->httpMessage = $httpReportableMessage;

		parent::__construct($errorMessage, $errorCode, $Previous);
	}
}