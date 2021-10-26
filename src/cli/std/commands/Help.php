<?php namespace net\peacefulcraft\apirouter\cli\std\commands;

use net\peacefulcraft\apirouter\cli\Command;
use net\peacefulcraft\apirouter\spec\cli\CommandManager;
use net\peacefulcraft\apirouter\spec\cli\Format;
use net\peacefulcraft\apirouter\spec\cli\Terminal;

class Help extends Command {

	private CommandManager $_CommnadManager;

	public function __construct(CommandManager $CommnadManager) {
		parent::__construct('help');

		$this->_CommnadManager = $CommnadManager;
		
		$this->setDescription('Lists all available commands and provide information about requested command.')
         ->setHelpMessage('help [command]');
	}

	public function execute(Terminal $Terminal, array $args, array $flags): int {
		// Check if user asked about a specific command
		if (count($args) > 0) {
			$RequestedCommand = $this->_CommnadManager->getCommand($args[0]);

			// Unknown command, return error code
			if ($RequestedCommand === null) {
				$Terminal->printErrorLn(Format::COLOR_RED . 'Unknown command' . Format::COLOR_LIGHT_BLUE . ' ' . $args[0]);
				return 1;

			} else {
				$Terminal->printLn(Format::COLOR_LIGHT_BLUE . $RequestedCommand->getName());
				$Terminal->printLn($RequestedCommand->getDescription());
				$Terminal->print($RequestedCommand->getHelpMessage());
			}

		// User asked for all commands
		} else {
			foreach($this->_CommnadManager->getCommands() as $fqName => $Command) {
				$Terminal->printLn(Format::COLOR_LIGHT_BLUE . $fqName . Format::COLOR_RESET . ' ' . $Command->getDescription());
			}
		}

		return 0;
	}
}

?>