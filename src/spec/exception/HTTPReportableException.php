<?php namespace net\peacefulcraft\apirouter\spec\exception;

use Throwable;

/**
 * 
 */
interface HTTPReportableException extends Throwable {
	
	/**
	 * HTTP Response code to respond with. This will likley differ from
	 * the internal Exception::getCode() code which should be reserved for
	 * internal error handling. Note that Exception::getCode() is often still
	 * displayed to the user and can be used for APM/support requets.
	 * 
	 * @return int A valid HTTP Response code. Usually of the 400 or 500 nature.
	 */
	public function getHTTPResponseCode(): int;

	/**
	 * A friendly message to report to the user. This value should not contain
	 * any technical, debug, or secret information. More in-depth technical reporting
	 * should use Exception::getMessage().
	 * 
	 * @param string An error message to report to the end-user.
	 */
	public function getHTTPResponseErrorMessage(): string;

}

?>