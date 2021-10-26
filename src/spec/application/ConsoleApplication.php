<?php namespace net\peacefulcraft\apirouter\spec\application;

use net\peacefulcraft\apirouter\spec\cli\CommandManager;
use net\peacefulcraft\apirouter\spec\cli\Terminal;
use net\peacefulcraft\apirouter\spec\ext\ConsoleApplicationPlugin;

interface ConsoleApplication extends Application {
	
	public function getTerminal(): Terminal;

	public function getCommandManager(): CommandManager;

	public function registerHook(string $hook): void;

	public function usePlugin(ConsoleApplicationPlugin $Plugin): void;
}

?>