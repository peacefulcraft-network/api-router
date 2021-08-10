<?php namespace net\peacefulcraft\apirouter\spec\router;

interface BodyParser {

	public function parse(?string $body): mixed;

}

?>