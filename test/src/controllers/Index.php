<?php
namespace ncsa\phpmvj\test\controllers;

use ncsa\phpmvj\router\RequestHandler;
use ncsa\phpmvj\router\Response;

class Index implements RequestHandler {
	public function handle():Response {
		return new Response(["message" => "Hello World!"]);
	}
}

?>