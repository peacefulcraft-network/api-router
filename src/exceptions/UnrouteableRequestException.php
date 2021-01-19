<?php
namespace ncsa\phpmvj\exceptions;

use Exception;

class UnrouteableRequestException extends Exception {
  public function __construct(string $message, int $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}