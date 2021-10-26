<?php
namespace net\peacefulcraft\apirouter\test\controllers\request;

use net\peacefulcraft\apirouter\render\JsonSerializableRenderingEngine;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class HTTPGetEcho implements Controller {

	public function handle(array $config, IRequest $Request): IResponse {
		$JSON = new JsonSerializableRenderingEngine($Request->getUriParameters());
		return new Response(Response::HTTP_OK, [], $JSON);
	}

}