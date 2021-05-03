<?php
namespace net\peacefulcraft\apirouter\console\directives;

use \net\peacefulcraft\apirouter\console\Console;
use \net\peacefulcraft\apirouter\console\Command;

class Clear implements Command {
	public function getName():string { return "clear"; }

	public function getDescription(): string { return "Clear terminal output"; }

	public function printHelpMessage(): void { Console::printLine("[light_blue]clear [normal]- " . $this->getDescription()); }

	public function getArgs():array { return []; }

	public function execute(array $config, Console $console, array $args):int {
		if ($console->isUnix()) {
			system('clear');
		} else {
			system('cls');
		}
		return 0;
	}
}
?>