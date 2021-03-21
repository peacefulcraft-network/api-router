<?php
namespace ncsa\phpmcj\test\middleware;

use ncsa\phpmcj\router\Middleware;
use ncsa\phpmcj\router\Request;
use ncsa\phpmcj\router\Response;

class Neverware implements Middleware {

	public function run(Request $request, Response $response): bool {
		return false;	
	}
}

?>