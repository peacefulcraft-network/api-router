<?php namespace net\peacefulcraft\apirouter\spec\resource;

interface ResourceManager {
	/**
	 * Register a new Resource with the Application. Application-registered
	 * Resources can be shared with other Plugins and are easily accessable
	 * throughout Application components.
	 * 
	 * @param string $name Friendly name / key to identify the Resource object
	 * @param ApplicationResource $config Resource object to store
	 * @return string The prefixed name that the Resource was stored under. This is the value
	 *                you should use in calls to (get|sunset)Resource().
	 */
	public function registerResource(string $name, ApplicationResource $Config): string;

	/**
	 * Retrieve an Application-registered Resource object
	 * 
	 * @return null If no Resource with the given key is known to the ConfgurationManager
	 * @return ApplicationResource The Resource object that was registered with the given key.
	 */
	public function getResource(string $name): ?ApplicationResource;

	/**
	 * 'Sunset' or remove the indicated Resource object.
	 * The object will no longer be accessable via getResource().
	 * If another Plugin has stored a reference to the Resource object,
	 * it will still be accessable. If this is undersierable, ensure to implement
	 * some sort of locking / illegalstate tracking in your implementation of
	 * (set|get)Property() on the Resource object.
	 * 
	 * @param string $name Friendly name / key to identify the Resource object
	 * @param Resource $config Resource object to store
	 * 
	 * @return null If no Resource with the given key is known to the ConfgurationManager
	 * @return ApplicationResource The Resource object that was registered with the given key.
	 */
	public function sunsetResource(string $name): ?ApplicationResource;

}