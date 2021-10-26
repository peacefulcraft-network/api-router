<?php
namespace net\peacefulcraft\apirouter\test\controllers\request;

use net\peacefulcraft\apirouter\render\JsonSerializableRenderingEngine;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class HTTPPostEcho implements Controller {

	public function handle(array $config, IRequest $Request): IResponse {
		$JSON = new JsonSerializableRenderingEngine(array_merge($Request->getUriParameters(), $Request->getBody()));
		return new Response(Response::HTTP_OK, [], $JSON);
	}

}