<?php namespace net\peacefulcraft\apirouter\spec\router;

interface BodyParser {

	/**
	 * @param null|string $body Contents of php://input.
	 * @return array Parsed contents of body.
	 */
	public function parse(?string $body): array;

}

?>