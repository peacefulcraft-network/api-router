<?php namespace net\peacefulcraft\apirouter\spec\application;

use net\peacefulcraft\apirouter\spec\ext\PluginManager;

interface Application {
	/**
	 * @return PluginManager The PluginManager instance for this Application instance.
	 */
	public function getPluginManager(): PluginManager;
}

?>