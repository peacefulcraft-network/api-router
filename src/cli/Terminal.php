<?php namespace net\peacefulcraft\apirouter\cli;

use net\peacefulcraft\apirouter\spec\cli\CommandManager;
use net\peacefulcraft\apirouter\spec\cli\Format;
use net\peacefulcraft\apirouter\spec\cli\Terminal as CliTerminal;

class Terminal implements CliTerminal {

	private CommandManager $_CommandManager;
	private mixed $_stdErr; // resource
	private mixed $_stdIn;  // resource
	private mixed $_stdOut; // resource
	private bool $_running = true;

	public function getSTDErr(): mixed {
		return $this->_stdErr;
	}

	public function getSTDIn(): mixed {
		return $this->_stdIn;
	}

	public function getSTDOut(): mixed {
		return $this->_stdOut;
	}

	public function print(string $string): void {
		fwrite($this->_stdOut, $string);
	}

	public function printLn(string $string): void {
		fwrite($this->_stdOut, $string . PHP_EOL);
	}

	public function printError(string $string): void {
		fwrite($this->_stdErr, $string);
	}

	public function printErrorLn(string $string): void {
		fwrite($this->_stdErr, $string . PHP_EOL);
	}

	public function cursorSet(int $row, int $col): void {
		fwrite($this->_stdOut, "\e[${row};${col}H");
	}

	/**
	 * All resources should be opened before given to __construct() and REALLY should be open
	 * before trying to run the prompt or run a command. Resources will not be closed by Terminal.
	 * 
	 * @param CommandManager $CommandManager
	 * @param mixed $stdError A writeable PHP resource
	 * @param mixed $stdIn A readable PHP resource
	 * @param mixed $stdError A writeable PHP resource
	 */
	public function __construct(CommandManager $CommandManager, mixed $stdErr = STDERR, mixed $stdIn = STDIN, mixed $stdOut = STDOUT) {
		$this->_CommandManager = $CommandManager;
		$this->_stdErr = $stdErr;
		$this->_stdIn = $stdIn;
		$this->_stdOut = $stdOut;
	}

	public function runInteractivePrompt(): void {
		$this->printLn(Format::COLOR_BLUE . 'Interaction Application Console');

		if ($this->_stdIn === false) {
			$this->printLn(Format::COLOR_RED . 'Error opening input stream - exiting.');
			$this->_running = false;
		}

		$command_list = $this->_CommandManager->getCommands();

		while($this->_running) {
			$this->print(Format::COLOR_GREEN . '> ', false);
			$this->_last_input = trim(fgets($this->_stdIn, 1024));
			$this->_last_args = explode(' ', $this->_last_input);
			$this->_last_command = array_shift($this->_last_args);

			// If no prefix was specified, default to STD command prefix
			if (strpos($this->_last_command, ':') === false) { $this->_last_command = 'std:' . $this->_last_command; }

			if (array_key_exists($this->_last_command, $command_list)) {
				$Command = $command_list[$this->_last_command];
				$parsed_params = $Command->parseParams($this->_last_command);
				$command_list[$this->_last_command]->execute($this, $parsed_params['args'], $parsed_params['flags']);
			} else {
				$this->printErrorLn(Format::COLOR_RED . 'Unknown command!' . Format::COLOR_RESET . ' Use ' . Format::COLOR_LIGHT_PURPLE . 'help' . Format::COLOR_RESET .' to see available commands.');
			}
		}

		$this->printLn(Format::FMT_RESET_ALL . Format::COLOR_RESET);
	}

	public function runCommand(string $commandString): int {
		$Command = $this->matchCommand($commandString);
		$parsed_params = $Command->parseParams($commandString);
		return $Command->execute($this, $parsed_params['args'], $parsed_params['flags']);
	}

	private function matchCommand(string $command): ?Command {
		$firstSpace = strpos($command, ' ');
		if ($firstSpace === false) {
			$firstSpace = strlen($command);
		}

		$commandName = substr($command, 0, $firstSpace);
		return $this->_CommandManager->getCommand($commandName);
	}

	public function shutdown(): void {
		$this->_running = false;
	}
}

?>