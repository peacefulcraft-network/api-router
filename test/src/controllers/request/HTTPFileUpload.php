<?php
namespace net\peacefulcraft\apirouter\test\controllers\request;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\util\cors\StandardPut;

class HTTPFileUpload implements Controller {
	use StandardPut;

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($_FILES['file_contents']);
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}