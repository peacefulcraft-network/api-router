<?php
namespace ncsa\phpmvj\router;

class Response implements \JsonSerializable{
  private $_errorCode;
  
  private $_errorMessage;

  private $_data;

  public function __construct(array $data = [], int $errorCode = 0, string $errorMessage = '') {
    $this->_data = $data;
    $this->_errorCode = $errorCode;
    $this->_errorMessage = $errorMessage;
  }

  public function jsonSerialize() {
    return [
      'error_no'=>$this->_errorCode,
      'error' => $this->_errorMessage,
      'data' => $this->_data
    ];
  }

  public static function setHTTPResponseCode(int $code) {
    http_response_code($code);
  }

  public const HTTP_OK = 200;
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
  public const HTTP_RATE_LIMIT = 429;
  public const HTTP_INTERNAL_ERROR = 500;
  public const HTTP_BAD_GATEWAY = 502;
  public const HTTP_SERVICE_UNAVAILABLE = 503;
  public const HTTP_GATEWAY_TIMEOUT = 504;

}