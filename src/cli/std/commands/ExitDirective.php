<?php namespace net\peacefulcraft\apirouter\cli\std\commands;

use net\peacefulcraft\apirouter\cli\Command;
use net\peacefulcraft\apirouter\spec\cli\Terminal;

class ExitDirective extends Command {

	public function __construct() {
		parent::__construct('exit');

		$this->setDescription('Exists the interactive console')
		     ->setHelpMessage('exit');
	}

	/**
	 * Request that the terminal shutdown after it finishes it's
	 * current execution loop.
	 */
	public function execute(Terminal $Terminal, array $args, array $flags): int {
		$Terminal->shutdown();
		return 0;
	}

}