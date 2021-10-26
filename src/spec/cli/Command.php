<?php namespace net\peacefulcraft\apirouter\spec\cli;

use net\peacefulcraft\apirouter\spec\cli\Terminal;

interface Command {

	/**
	 * @return string Command name, no prefix.
	 */
	public function getName(): string;

	/**
	 * Add a named flag to this function
	 * 
	 * @param mixed $types A list of accepted types. Types match PHP-lang official gettype names (int|mixed|boolean,...).
	 *                     Empty array will disable type checking.
	 * @param bool $required Is this a required argument
	 * @param string $arg Arg name
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function addNamedFlag(array $types, bool $required, string $arg): Command;

	/**
	 * Add a positional arguement. Positions are determined by position of addPositionArg() invocations.
	 * IE; addPositionArg(arg: 'first_arg')->addPositionArg(arg: 'second_arg')
	 * 
	 * @param array $types A list of accepted types. Types match PHP-lang official gettype names (int|mixed|bool,...).
	 *                     Empty array will disable type checking.
	 * @param bool $required Is this arg required. ALL required args must come first for positional arguements.
	 * @param string $arg Arguement name
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function addPositionArg(array $types, bool $required, string $arg): Command;

	/**
	 * Expose the Command description message to the CommandManager.
	 * 
	 * @return string to print as help message.
	 */
	public function getDescription(): string;

	/**
	 * Hook for help utility.
	 * @param string $description String to print as description.
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function setDescription(string $description): Command;

	/**
	 * Expose the Command help message to the CommandManager
	 * 
	 * @return string The help message provided by the user.
	 */
	public function getHelpMessage(): string;

	/**
	 * Hook for help utility.
	 * @param string $help_message String to print as help message.
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function setHelpMessage(string $help_message): Command;

	/**
	 * Parse args/flags from a user entered string. [command] [args...] [flags...]
	 * @param string $command Command supplied by user / script.
	 * 
	 * @return array An associative array containg 'args' => [] and 'flags'=> [].
	 */
	public function parseParams(string $command): array; 

	/**
	 * Execute this command object, parsing args and flags from the provided command string.
	 * @param string $command The string command supplied by the user
	 * 
	 * @return int A status code. Refer to shell / exec conventions for return codes. 
	 */
	public function execute(Terminal $Terminal, array $args, array $flags): int;
}

?>