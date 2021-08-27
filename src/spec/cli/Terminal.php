<?php namespace net\peacefulcraft\apirouter\spec\cli;

interface Terminal {

	/**
	 * @return resource For STDErr
	 * @return bool False if resource doesn't exist
	 */
	public function getSTDErr(): mixed;

	/**
	 * @return resource For STDIn
	 * @return bool False if resource doesn't exist
	 */
	public function getSTDIn(): mixed;

	/**
	 * @return resource For STDOut
	 * @return bool False if resource doesn't exist
	 */
	public function getSTDOut(): mixed;

	/**
	 * Print to STDOut
	 * @param string $string Text to print
	 */
	public function print(string $string): void;

	/**
	 * Print to STDOut and append PHP_EOL
	 * @param string $string Text to print
	 */
	public function printLn(string $string): void;

	/**
	 * Print to STDErr
	 * @param string $string Text to print
	 */
	public function printError(string $string): void;

	/**
	 * Print to STDErr and append PHP_EOL
	 * @param string $string Text to print
	 */
	public function printErrorLn(string $string): void;

	/**
	 * Starts an interactive prompt.
	 */
	public function runInteractivePrompt(): void;

	/**
	 * Run the provided command with args and flags, one-off.
	 * 
	 * @return int The exit/return code from the executed command
	 */
	public function runCommand(string $command): int;

	public function shutdown(): void;
}

?>