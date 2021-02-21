<?php
namespace ncsa\phpmvj\util\cors;

trait StandardPost {
	use StandardCORS;

	public function options(): void {
		$this->setCORSHeaders('POST');
	}
}