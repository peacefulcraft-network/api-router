<?php
namespace net\peacefulcraft\apirouter\exceptions;

use Exception;
use net\peacefulcraft\apirouter\exceptions\traits\HTTPReportableExceptionTrait;
use net\peacefulcraft\apirouter\spec\route\Response;
use RuntimeException;

/**
 * Semantic RuntimeException for user-input validation errors.
 * This Exception is parsable by Application->respondToRequestWithException(). Values
 * provided will likley be passed back directly to the client if this Exception Throws up
 * to Application::handleRequest()
 */
class ValidationException extends RuntimeException {

	use HTTPReportableExceptionTrait;

	/**
	 * @param string $message Validation message. This will probably be reported to the client.
	 * @param int $code Code for this validation error. This will probably be reported to the client.
	 * @param Exception $previous A previous Exception that triggered or led to this Exception.
	 */
	public function __construct(string $message, int $code, Exception $previous = null) {
		$this->httpCode = Response::HTTP_BAD_REQUEST;
		$this->httpMessage = $message;
		parent::__construct($message, $code, $previous);
	}
}