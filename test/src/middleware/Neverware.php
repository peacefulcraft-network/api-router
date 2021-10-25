<?php
namespace net\peacefulcraft\apirouter\test\middleware;

use net\peacefulcraft\apirouter\render\PlainTextRenderingEngine;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\Middleware;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class Neverware implements Middleware {

	public function run(array $config, IRequest $request): IResponse {
		return new Response(Response::HTTP_EMPTY_RESPONSE, [], new PlainTextRenderingEngine(""));
	}
}

?>