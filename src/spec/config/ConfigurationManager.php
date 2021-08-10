<?php namespace net\peacefulcraft\apirouter\spec\config;

interface ConfigurationManager {
	/**
	 * Register a new Configuration with the Application. Application-registered
	 * Configurations can be shared with other Plugins and are easily accessable
	 * throughout Application components.
	 * 
	 * @param string $name Friendly name / key to identify the Configuration object
	 * @param Configuration $config Configuration object to store
	 * @return string The prefixed name that the Configuration was stored under. This is the value
	 *                you should use in calls to (get|sunset)Configuration().
	 */
	public function registerConfiguration(string $name, Configuration $Config): string;

	/**
	 * Retrieve an Application-registered Configuration object
	 * 
	 * @return null If no Configuration with the given key is known to the ConfgurationManager
	 * @return Configuration The Configuration object that was registered with the given key.
	 */
	public function getConfiguration(string $name): ?Configuration;

	/**
	 * 'Sunset' or remove the indicated Configuration object.
	 * The object will no longer be accessable via getConfiguration().
	 * If another Plugin has stored a reference to the Configuration object,
	 * it will still be accessable. If this is undersierable, ensure to implement
	 * some sort of locking / illegalstate tracking in your implementation of
	 * (set|get)Property() on the Configuration object.
	 * 
	 * @param string $name Friendly name / key to identify the Configuration object
	 * @param Configuration $config Configuration object to store
	 * 
	 * @return null If no Configuration with the given key is known to the ConfgurationManager
	 * @return Configuration The Configuration object that was registered with the given key.
	 */
	public function sunsetConfiguration(string $name): ?Configuration;
}

?>