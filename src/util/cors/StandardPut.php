<?php
namespace ncsa\phpmvj\util\cors;

trait StandardPut {
	use StandardCORS;

	public function options(): void {
		$this->setCORSHeaders('PUT');
	}
}