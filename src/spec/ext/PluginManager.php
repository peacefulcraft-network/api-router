<?php namespace net\peacefulcraft\apirouter\spec\ext;

use net\peacefulcraft\apirouter\spec\config\ConfigurationManager;
use net\peacefulcraft\apirouter\spec\resource\ResourceManager;

interface PluginManager {
	public function usePlugin(Plugin $Plugin): void;

	/**
	 * Returns the ConfigurationManager instance associated with the given $Plugin
	 * 
	 * @param Plugin $Plugin Instance of the Plugin who's ConfigurationManager you need. (Almost always your Plugin's).
	 * @return ConfigurationManager The ConfigurationManager instance associated with the given $Plugin
	 */
	public function getConfigurationManager(Plugin $Plugin): ConfigurationManager;

	/**
	 * Returns the ResourceManager instance associated with the given $Plugin
	 * 
	 * @param Plugin $Plugin Instance of the Plugin who's ResourceManager you need. (Almost always your Plugin's).
	 * @return ResourceManager The ResourceManager instance associated with the given $Plugin
	 */
	public function getResourceManager(Plugin $Plugin): ResourceManager;
}

?>