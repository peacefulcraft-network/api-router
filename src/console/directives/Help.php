<?php
namespace net\peacefulcraft\apirouter\console\directives;

use \net\peacefulcraft\apirouter\console\Console;
use \net\peacefulcraft\apirouter\console\Command;

class Help implements Command {
	public function getName():string { return "help"; }

	public function getDescription(): string { return "Prints help messages for other directives"; }

	public function printHelpMessage(): void { Console::printLine("[light_blue]help [normal][directive]"); }

	public function getArgs():array { return []; }

	public function execute(array $config, Console $console, array $args):int {
		if (count($args) > 0) {
			if (isset($console->getActiveDirectives()[$args[0]])) {
				$console->getActiveDirectives()[$args[0]]->printHelpMessage();
			} else {
				Console::printLine("[red]Unknown directive [light_blue]" . $args[0]);
			}
		} else {
			foreach($console->getActiveDirectives() as $name => $directive) {
				Console::printLine("[light_blue]" . $name . "[normal] " . $directive->getDescription());
			}
		}
		return 0;
	}
}
?>