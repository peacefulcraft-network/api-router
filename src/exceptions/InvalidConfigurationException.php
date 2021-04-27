<?php
namespace net\peacefulcraft\apirouter\exceptions;

use Exception;

class InvalidConfigurationException extends Exception {
	public function __construct(string $invalid_value, string $message, int $code = 0, Exception $previous = null) {
		parent::__construct("Configuration Error: '" . $invalid_value . "' " . $message, $code, $previous);
	}
}