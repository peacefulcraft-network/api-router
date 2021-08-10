<?php namespace net\peacefulcraft\apirouter\spec\router;

interface Response {

	public function getResponseCode(): int;

	public function getResponseHeader(string $header): ?string;

	public function setHeader(string $header, string $value): void;

	public function getResponseBody(): string;

	public function setResponseBody(string $body): void;
}

?>