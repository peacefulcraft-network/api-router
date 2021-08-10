<?php namespace net\peacefulcraft\apirouter\spec\ext;

use net\peacefulcraft\apirouter\spec\ConsoleApplication;

interface ConsoleApplicationPlugin extends Plugin {

	/**
	 * Boot the plugin as part of ConsoleApplication startup.
	 * Most resources will not exist yet and no user input or invoking
	 * args will have been parsed yet. Use this to instantiate plugin resources.
	 */
	public function startUp(ConsoleApplication $Application): void;

	/**
	 * Shutdown the plugin as part of ConsoleApplication teardown.
	 * Use this to free any persistent resources that may have been
	 * allocated.
	 */
	public function teardown(ConsoleApplication $Application): void;

}

?>