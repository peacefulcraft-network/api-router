<?php namespace net\peacefulcraft\apirouter\test\api;

use net\peacefulcraft\apirouter\api\ApplicationCommandProvider;
use net\peacefulcraft\apirouter\api\ApplicationRouteProvider;
use net\peacefulcraft\apirouter\Application;
use net\peacefulcraft\apirouter\console\Command;
use net\peacefulcraft\apirouter\console\Console;
use net\peacefulcraft\apirouter\spec\router\Controller;
use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\RequestMethod;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\router\Router;

class DummyPlugin implements ApplicationRouteProvider, ApplicationCommandProvider {

	public bool $enableWasInvoked = false;
	public bool $disableWasInvoked = false;

	public function getPrefix(): string { return 'dummyplugin'; }

	public function enablePlugin(Application $Application): void {
		$this->enableWasInvoked = true;
	}

	public function disablePlugin(): void {
		$this->disableWasInvoked = true;
	}

	public function registerRoutes(Router $Router): void {
		$Router->registerRoute(RequestMethod::GET, '/plugintest', [], DummyPluginRoute::class);
	}

	public function registerCommands(Console $Console): void {
		$Console->registerCommand($this, new DummyCommand());
	}
}

class DummyPluginRoute implements Controller {
	public function handle(array $config, Request $request, Response $response): void {
		$response->setData(['OK']);
		$response->setHttpResponseCode(Response::HTTP_OK);
	}
}

class DummyCommand implements Command {

	public function getName(): string { return 'foobar'; }

	public function getDescription(): string { return 'Used for unit testing'; }

	public function printHelpMessage(): void {
		Console::printLine('Useful information goes here');
	}

	public function getArgs(): array { return []; }

	public function execute(array $config, Console $console, array $args): int {
		return 418;
	}
}
?>