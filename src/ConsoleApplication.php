<?php
namespace ncsa\phpmcj;

use \ncsa\phpmcj\console\Console;

class ConsoleApplication {

	public function __construct(bool $interactive, array $directive_paths = []) {
		array_push($directive_paths, __DIR__ . '/console/directives');
		
		foreach($directive_paths as $directive_folder) {
			// Remove the trailing forward slash 
			if (substr($directive_folder, -1) === '/') {
				$directive_folder = substr($directive_folder, 0, strlen($directive_folder) - 1);
			}

			// Check all provided 
			$files = scandir($directive_folder);
			foreach($files as $directive_file) {
				if (strpos($directive_file, ".php") === false) {
					continue;
				}

				include($directive_folder . "/" . $directive_file);
			}
		}

		$console = new Console($interactive);
	}
}
?>