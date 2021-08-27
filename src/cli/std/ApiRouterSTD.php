<?php namespace net\peacefulcraft\apirouter\cli\std;

use net\peacefulcraft\apirouter\cli\std\commands\Clear;
use net\peacefulcraft\apirouter\cli\std\commands\Disco;
use net\peacefulcraft\apirouter\cli\std\commands\ExitDirective;
use net\peacefulcraft\apirouter\cli\std\commands\Help;
use net\peacefulcraft\apirouter\spec\application\ConsoleApplication;
use net\peacefulcraft\apirouter\spec\ext\ConsoleApplicationPlugin;

class ApiRouterSTD implements ConsoleApplicationPlugin {

	public function getName(): string { return 'APIRouter Standard Plugin Library'; }

	public function getPluginPrefix(): string { return 'std'; }

	public function getVersion(): float { return 1.0; }

	public function pluginDepends(): array { return []; }

	public function startUp(ConsoleApplication $Application): void {
		$CM = $Application->getCommandManager();

		$CM->registerCommand($this, new Clear());
		$CM->registerCommand($this, new Help($CM));
		$CM->registerCommand($this, new ExitDirective());
		$CM->registerCommand($this, new Disco());
	}

	public function teardown(ConsoleApplication $Application): void {
		
	}

}

?>