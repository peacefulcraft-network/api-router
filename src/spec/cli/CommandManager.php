<?php namespace net\peacefulcraft\apirouter\spec\cli;

use net\peacefulcraft\apirouter\spec\ext\Plugin;

interface CommandManager {

	public function registerCommand(Plugin $Plugin, Command $Command): void;

}