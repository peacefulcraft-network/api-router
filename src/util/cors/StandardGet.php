<?php
namespace ncsa\phpmvj\util\cors;

trait StandardGet {
	use StandardCORS;

	public function options(): void {
		$this->setCORSHeaders('GET');
	}
}