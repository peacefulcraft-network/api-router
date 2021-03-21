<?php
namespace ncsa\phpmcj\console\directives;

use \ncsa\phpmcj\console\Console;
use \ncsa\phpmcj\console\Directive;

class ExitDirective implements Directive {
	public function getName():string { return "exit"; }

	public function getDescription(): string { return "Terminate Interactive Console"; }

	public function printHelpMessage(): void { Console::printLine("[light_blue]exit - " . $this->getDescription()); }

	public function getArgs():array { return []; }

	public function execute(Console $console, array $args):int {
		return 0;
	}
}
?>