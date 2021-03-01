<?php
namespace ncsa\phpmvj\test\controllers;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\router\Response;
use ncsa\phpmvj\util\cors\StandardGet;

class Index implements RequestHandler {
	use StandardGet;

	public function handle(array $config, Request $request, Response $response): void {
		$response->setData(['message'=>'Hello World!']);
	}
}

?>