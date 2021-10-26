<?php namespace net\peacefulcraft\apirouter\cli;

use net\peacefulcraft\apirouter\spec\cli\Command;
use net\peacefulcraft\apirouter\spec\cli\CommandManager as CliCommandManager;
use net\peacefulcraft\apirouter\spec\ext\Plugin;

class CommandManager implements CliCommandManager {

	private array $_commands = [];

	public function getCommand(string $name): ?Command {
		if (array_key_exists($name, $this->_commands)) {
			return $this->_commands[$name];
		}
		return null;
	}

	public function getCommands(): array {
		return $this->_commands;
	}

	public function registerCommand(Plugin $Provider, Command $Command): void {
		$this->_commands[$Provider->getPluginPrefix() . ':' . $Command->getName()] = $Command;
	}
}

?>