<?php
namespace ncsa\phpmvj\util\cors;

trait StandardDelete {
	use StandardCORS;

	public function options(): void {
		$this->setCORSHeaders('DELETE');
	}
}