<?php namespace net\peacefulcraft\apirouter\exceptions\traits;

/**
 * Implementation of HTTPReportableException
 */
trait HTTPReportableExceptionTrait {
	protected int $httpCode;
		public function getHTTPResponseCode(): int {
			return $this->httpCode;
		}

	protected string $httpMessage;
		public function getHTTPResponseErrorMessage(): string {
			return $this->httpMessage;
		}
}

?>