<?php namespace net\peacefulcraft\apirouter\cli;

use net\peacefulcraft\apirouter\spec\cli\AcceptedTypes;
use net\peacefulcraft\apirouter\spec\cli\Command as CliCommand;

abstract class Command implements CliCommand {

	protected string $name;
	protected array $flags;
	protected array $args;
	protected callable|string $description;
	protected callable|string $help_message;

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function getName(): string { return $this->name; }

	public function addNamedFlag(AcceptedTypes $types, bool $required, string $arg): CliCommand {
		$this->flags['arg'] = ['types' => $types, 'required'=>$required, 'arg'=>$arg];
		return $this;
	}

	public function addPositionArg(AcceptedTypes $types, bool $required, string $arg): CliCommand {
		array_push($this->args, ['types' => $types, 'required'=>$required, 'arg'=>$arg]);
		return $this;
	}

	public function setDescription(string|callable $description): CliCommand {
		$this->description = $description;
		return $this;
	}

	public function setHelpMessage(string|callable $help_message): CliCommand {
		$this->help_message = $help_message;
		return $this;
	}

	public function parseParams(string $command): array {
		$firstFlag = strpos($command, '--', 0);
		$firstSpace = strpos($command, ' ', 0);
		$strlen = strlen($command);

		if ($firstFlag === false) {
			$firstFlag = $strlen;
			$flagsWithVals = null;
		} else {
			$flagsWithVals = trim(substr($command, $firstFlag));
			preg_match_all("/--(?<flag>[^=]+)=([\"])?(['])?(?<value>(?(2)([^\"]|\\\")+|(?(3)([^\']|\\\')+|\S+)))/", $flagsWithVals, $flagsWithVals);
		}

		if ($firstSpace === false) {
			$firstSpace = $strlen;
			if ($firstFlag === $firstSpace) {
				return ['args' => [], 'params' => []];
			}
			$commandArgs = '';
		} else {
			$commandArgs = trim(substr($command, $firstSpace, $firstFlag));
			$commandArgs = explode(' ', $commandArgs);
		}
	}
}

?>