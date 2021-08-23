<?php namespace net\peacefulcraft\apirouter\spec\cli;

interface Command {

	/**
	 * @return string Command name, no prefix.
	 */
	public function getName(): string;

	/**
	 * Add a named flag to this function
	 * 
	 * @param mixed $types A dummy arg that will be used for it's type-hint to determine expect argument type(s).
	 * @param bool $required Is this a required argument
	 * @param string $arg Arg name
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function addNamedFlag(AcceptedTypes $types, bool $required, string $arg): Command;

	/**
	 * Add a positional arguement. Positions are determined by position of addPositionArg() invocations.
	 * IE; addPositionArg(arg: 'first_arg')->addPositionArg(arg: 'second_arg')
	 * 
	 * @param AcceptedTypes $types See function docs.
	 * @param bool $required Is this arg required. ALL required args must come first for positional arguements.
	 * @param string $arg Arguement name
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function addPositionArg(AcceptedTypes $types, bool $required, string $arg): Command;

	/**
	 * Hook for help utility.
	 * @param string $description String to print as description.
	 * @param callable $description A function to execute that will print the description to STDOut.
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function setDescription(string|callable $description): Command;

	/**
	 * Hook for help utility.
	 * @param string $help_message String to print as help message.
	 * @param callable $help_message A function to execute that will print the help message to STDOut.
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function setHelpMessage(string|callable $help_message): Command;

	/**
	 * Parse params/args from a user entered string. [command] [args...] [params...]
	 * @param string $command Command supplied by user / script.
	 * 
	 * @return array An associative array containg 'args' => [] and 'params'=> [].
	 */
	public function parseParams(string $command): array; 

	/**
	 * Execute this command object, parsing args and flags from the provided command string.
	 * @param string $command The string command supplied by the user
	 * 
	 * @return int A status code. Refer to shell / exec conventions for return codes. 
	 */
	public function execute(array $args, array $flags): int;
}

/**
 * Descriptive, dummy interface to allow for type widening on $types param in addArg
 */
interface AcceptedTypes {}

?>