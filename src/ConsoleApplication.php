<?php
namespace net\peacefulcraft\apirouter;

use \net\peacefulcraft\apirouter\console\Console;
use RuntimeException;

class ConsoleApplication {

	public function __construct(bool $interactive, array $directive_paths = []) {
		array_push($directive_paths, __DIR__ . '/console/directives');
		
		foreach($directive_paths as $directive_set) {
			$this->loadDirective($directive_set);
		}

		$console = new Console($interactive);
	}

	public function loadDirective(string $path): void {
		if (is_dir($path)) {
			// Remove the trailing forward slash 
			if (substr($path, -1) === '/') {
				$path = substr($path, 0, strlen($path) - 1);
			}

			// Check all provided 
			$files = scandir($path);
			foreach($files as $directive_file) {
				if (strpos($directive_file, ".php") === false) {
					continue;
				}

				include("$path/$directive_file");
			}
		} elseif (is_file($path)) {
			include($path);
		} else {
			throw new RuntimeException("File $path not found. Unable to load as directive.");
		}
	}
}
?>