<?php namespace net\peacefulcraft\apirouter;

use net\peacefulcraft\apirouter\cli\CommandManager as CliCommandManager;
use net\peacefulcraft\apirouter\cli\Terminal;
use net\peacefulcraft\apirouter\spec\application\ConsoleApplication as ApplicationConsoleApplication;
use net\peacefulcraft\apirouter\spec\application\ConsoleLifecycleHooks;
use net\peacefulcraft\apirouter\spec\cli\CommandManager;
use net\peacefulcraft\apirouter\spec\cli\Terminal as CliTerminal;
use net\peacefulcraft\apirouter\spec\ext\ConsoleApplicationPlugin;

class ConsoleApplication implements ApplicationConsoleApplication {

	private CliTerminal $_Terminal;
	private CommandManager $_CommandManager;

	private array $_plugins = [];

	public function getTerminal(): CliTerminal {
		return $this->_Terminal;
	}

	public function getCommandManager(): CommandManager {
		return $this->_CommandManager;
	}

	public function __construct() {
		$this->_CommandManager = new CliCommandManager();
	
		$this->_Terminal = new Terminal($this->_CommandManager);
	}

	public function __destruct() {
		foreach ($this->_plugins as $Plugin) {
			$Plugin->teardown($this);
		}
	}

	public function registerHook(ConsoleLifecycleHooks $hook): void {
		
	}

	public function usePlugin(ConsoleApplicationPlugin $Plugin): void {
		array_push($this->_plugins, $Plugin);

		$Plugin->startUp($this);
	}
}

?>