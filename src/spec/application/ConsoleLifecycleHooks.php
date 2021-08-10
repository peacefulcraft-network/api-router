<?php namespace net\peacefulcraft\apirouter\spec\application;

interface ConsoleLifecycleHooks {
	/**
	 * Terminal has received a command, but not yet begun processing it.
	 * The command is only a string and no handler has been found yet.
	 * It is possible no handler exists, or if one does exist, the command
	 * is invalid.
	 */
	CONST BEFORE_COMMAND_MATCH = 'before_command_match';
	
	/**
	 * Terminal has received a command and has matched it to a handler.
	 * The handler has been setup and confirmed that the command
	 * conforms to arguement specifications. The command will
	 * execute after this hook-set is executed.
	 */
	CONST BEFORE_COMMAND_EXEC = 'before_command_exec';

	/**
	 * Terminal has received, matched, and executed a command.
	 * The command has finished executing. The only remaining
	 * step in command handling is releasing any resources
	 * that were allocated or reserved to execute the received command.
	 * Those resources will be released after this hook-set is executed.
	 */
	CONST BEFORE_COMMAND_RELEASE = 'before_command_release';
}

?>