<?php namespace net\peacefulcraft\apirouter\spec\router;

interface Request {
	
	public function getPath(): string;

	public function getUriParameters(): array;

	public function getBody(): array;

	public function getController(): null|string|Controller;
}

?>