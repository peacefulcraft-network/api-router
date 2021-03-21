<?php
namespace ncsa\phpmcj\exceptions;

use Exception;

class ValidationException extends Exception {
	public function __construct(string $message, int $code, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}