<?php namespace net\peacefulcraft\apirouter\spec\cli;

interface Command {

	/**
	 * Add a named arguement to this function
	 * 
	 * @param mixed $types A dummy arg that will be used for it's type-hint to determine expect argument type(s).
	 * @param bool $required Is this a required argument
	 * @param string $arg Arg name
	 * @return Command This command instance for Builder-style function chaining.
	 */
	public function addNamedArg(AcceptedTypes $types, bool $required, string $arg): Command;

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
	 * Function to run when this command is invoked.
	 * @param array $args Arguements used to invoke the command
	 * @return int A status code. Refer to shell / exec conventions for return codes.
	 */
	public function execute(array $args): int;
}

/**
 * Descriptive, dummy interface to allow for type widening on $types param in addArg
 */
interface AcceptedTypes {}

?>