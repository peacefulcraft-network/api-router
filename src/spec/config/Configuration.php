<?php namespace net\peacefulcraft\apirouter\spec\config;

/**
 * A very basic API for intra-Application component and cross-plugin configuration communication.
 * Note that by convention developers should stive to load Configurations during Application startUp()
 * into memory and, if a save process is needed, write back to persistent store(s) during teardown().
 * Performing blocking disk/networking tasks in (get|set)Property calls is discouraged.
 */
interface Configuration {

	/**
	 * Return a value matching the given key.
	 * Value may not exist in which case null should be returned.
	 * Value may be of any type. See Plugin specific documentation
	 * and implementation to see what properties and return types
	 * to expect.
	 * 
	 * @param string $property The property to fetch.
	 * @return mixed Coresponding property value. Plugin maintainer can choose
	 *               what value to return in the event of DNE. This may include
	 *               a thrown exception, a default value, or an invalid value in
	 *               the given context.
	 */
	public function getProperty(string $property): mixed;

	/**
	 * Configurations support write-back through this method. Plugins
	 * can choose to do nothing here if they do not wish to support
	 * Configuration writeback. It is probably more performant to only
	 * write to an in-memory representation of the Plugin's configuration
	 * and save during the Plugin teardown() process.
	 */
	public function setProperty(string $property, mixed $value): void;
}