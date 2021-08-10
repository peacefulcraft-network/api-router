<?php namespace net\peacefulcraft\apirouter\spec\cli;

use net\peacefulcraft\apirouter\spec\application\Application;
use net\peacefulcraft\apirouter\spec\ext\Plugin;

interface CommandManager {

	/**
	 * Add Command to collection of available Commands.
	 * 
	 * @param Plugin|Application $Plugin Instance of the Plugin from which this command originates.
	 * @param Command $Command Instance of the Command class which 
	 */
	public function registerCommand(Application|Plugin $Provider, Command $Command): void;

}