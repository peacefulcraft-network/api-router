<?php namespace net\peacefulcraft\apirouter\api;

use net\peacefulcraft\apirouter\console\Console;

/**
 * Plugin API extension that indicates the implementing plugin will
 * provide utility commands to the Application console.
 */
interface ApplicationCommandProvider extends ApplicationPlugin {

	/**
	 * Plugin life-cycle hook that the Application will use to request
	 * that this plugin register any Commands which it provides.
	 */
	public function registerCommands(Console $Console): void;
}

?>