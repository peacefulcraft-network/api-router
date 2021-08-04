<?php namespace net\peacefulcraft\apirouter\console;

use net\peacefulcraft\apirouter\api\ApplicationCommandProvider;
use net\peacefulcraft\apirouter\Application;
use net\peacefulcraft\apirouter\console\directives\Clear;
use net\peacefulcraft\apirouter\console\directives\ExitDirective;
use net\peacefulcraft\apirouter\console\directives\Help;

class APIRouterSTDPlugin implements ApplicationCommandProvider {

	public function getPrefix(): string { return 'std'; }

	public function enablePlugin(Application $Application): void {}

	public function disablePlugin(): void {}

	public function registerCommands(Console $Console): void {
		$Console->registerCommand($this, new Clear());
		$Console->registerCommand($this, new ExitDirective());
		$Console->registerCommand($this, new Help());
	}
}

?>