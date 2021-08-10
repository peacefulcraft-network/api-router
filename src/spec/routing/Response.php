<?php namespace net\peacefulcraft\apirouter\spec\router;

interface Response {

	/**
	 * @return int HTTP Response code that will/was sent back to the client.
	 */
	public function getResponseCode(): int;

	/**
	 * @param int $response_code HTTP response code to send back to client.
	 */
	public function setResponseCode(int $response_code): void;

	/**
	 * @return array Assoc-list of HTTP response headers that will be set by the Application when
	 *               writing the HTTP response out to the client.
	 */
	public function getResponseHeaders(): array;

	/**
	 * @param string $header HTTP response header value to retrieve.
	 * @return null|string Value of the requested header or null if it is not set.
	 */
	public function getResponseHeader(string $header): ?string;

	/**
	 * @param string $header HTTP response header to set
	 * @param null|string $value Value to set the header to. Null to unset. 
	 */
	public function setHeader(string $header, ?string $value): void;

	/**
	 * @return string The encoded response body that will be sent back to the client.
	 */
	public function getResponseBody(): string;

	/**
	 * @param string $body Encoded response body to send back to the client.
	 */
	public function setResponseBody(string $body): void;
}

?>