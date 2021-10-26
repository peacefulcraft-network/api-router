<?php
namespace net\peacefulcraft\apirouter\test\controllers;

use net\peacefulcraft\apirouter\render\PlainTextRenderingEngine;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class Index implements Controller {

	public function handle(array $config, IRequest $Request): IResponse {
		$HTML = new PlainTextRenderingEngine("<h1>Hello World!</h1>");
		return new Response(Response::HTTP_OK, [], $HTML);
	}

}

?>