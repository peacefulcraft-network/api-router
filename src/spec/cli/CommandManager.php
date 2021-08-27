<?php namespace net\peacefulcraft\apirouter\spec\cli;

use net\peacefulcraft\apirouter\spec\ext\Plugin;

interface CommandManager {

	/**
	 * Get a list of all registered Commands.
	 * 
	 * @return array List of all registered Commands.
	 */
	public function getCommands(): array;

	/**
	 * Get a regsitered Command with the provided fully-qualified name.
	 * 
	 * @param string $name Fully qualified Command name (prefix:command).
	 * @return null No such Command registered.
	 * @return Command The Command object with that name.
	 */
	public function getCommand(string $name): ?Command;

	/**
	 * Add Command to collection of available Commands.
	 * 
	 * @param Plugin $Plugin Instance of the Plugin from which this command originates.
	 * @param Command $Command Instance of the Command class which 
	 */
	public function registerCommand(Plugin $Provider, Command $Command): void;
}