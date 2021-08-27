<?php namespace net\peacefulcraft\apirouter\config;

use net\peacefulcraft\apirouter\spec\config\Configuration;
use RuntimeException;

class ImutableYAMLConfiguration implements Configuration {

	private array $_config = [];

	public function __construct(string $yaml_string) {
		$res = yaml_parse($yaml_string);
		if ($res === false) {
			throw new RuntimeException('Error parsing YAML string');
		}

		$this->_config = $res;
	}

	public function getProperty(string $property): mixed {
		$path = explode('.', $property);
		$path_count = count($path);
		$arr_ptr = $this->_config;

		for ($i=0; $i<$path_count; $i++) {
			$path_segment = $path[$i];

			if (array_key_exists($path_segment, $arr_ptr)) {
				// Check if this is our stop
				if (($i+1) === $path_count) {
					return $arr_ptr[$path_segment];

				// Advanced pointer deeper
				} else {
					$arr_ptr = $arr_ptr[$path_segment];
				}
			} else {
				return null;
			}
		}
	}

	public function setProperty(string $property, mixed $value): void {
		throw new RuntimeException('Attempted to write-back to ImmutableYAMLConfiguration Object');
	}
}

?>