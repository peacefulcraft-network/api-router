<?php namespace net\peacefulcraft\apirouter\spec\ext;

interface Plugin {
	/**
	 * Debug / informational value. Friendly name for the plugin.
	 */
	public function getName(): string;

	/**
	 * Prefix to prepend routes, command, and resources with.
	 * Generally this is a value that is close to the plugin name,
	 * URI safe, all lower-case, and short.
	 * 
	 * IE: For a Job-queue plugin, something like 'queue' or 'jobqueue'
	 * would be good here.
	 */
	public function getPluginPrefix(): string;

	/**
	 * Plugin version
	 */
	public function getVersion(): float;

	/**
	 * Specify a list of Plugin::classes which this Plugin depends on.
	 * This Plugin will be started after and torndown before
	 * any of the listed Plugins. If an indicated Plugin is not installed,
	 * this Plugin will still be started. It is up to the developer to controll
	 * their Plugin's behavior if a dependecy does not exist. The same policy
	 * is in effect for plugins which contain version mismatches. A warning will
	 * be logged indicating a dependecy could not be fulfilled if a required plugin
	 * was not found.
	 */
	public function pluginDepends(): array;
}