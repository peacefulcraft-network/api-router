<?php
namespace ncsa\phpmcj\console\directives;

use \ncsa\phpmcj\console\Console;
use \ncsa\phpmcj\console\Directive;

class Clear implements Directive {
	public function getName():string { return "clear"; }

	public function getDescription(): string { return "Clear terminal output"; }

	public function printHelpMessage(): void { Console::printLine("[light_blue]clear - " . $this->getDescription()); }

	public function getArgs():array { return []; }

	public function execute(Console $console, array $args):int {
		if ($console->isUnix()) {
			system('clear');
		} else {
			system('cls');
		}
		return 0;
	}
}
?>