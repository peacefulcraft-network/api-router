<?php
namespace ncsa\phpmvj\test\middleware;

use ncsa\phpmvj\router\Middleware;
use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\Response;

class Neverware implements Middleware {

	public function run(Request $request, Response $response): bool {
		return false;	
	}
}

?>