<?php namespace net\peacefulcraft\apirouter\cli\std\commands;

use net\peacefulcraft\apirouter\cli\Command;
use net\peacefulcraft\apirouter\spec\cli\Terminal;

class Clear extends Command {

	public function __construct() {
		parent::__construct('clear');
		
		$this->setDescription('Clears STDOut.')
         ->setHelpMessage('clear');


		$this->addNamedFlag(['string'], true, 'name')
			   ->addNamedFlag(['double', 'int'], false, '');
	}

	public function execute(Terminal $Terminal, array $args, array $flags): int {
		$code = 1;
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			system('cls', $code);
		} else {
			system('clear', $code);
		}
		return $code;
	}
}
?>