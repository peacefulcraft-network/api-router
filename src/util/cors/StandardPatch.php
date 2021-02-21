<?php
namespace ncsa\phpmvj\util\cors;

use ncsa\phpmvj\router\Router;

trait StandardPatch {
	use StandardCORS;

	public function options(): void {
		$this->setCORSHeaders('PATCH');
	}
}