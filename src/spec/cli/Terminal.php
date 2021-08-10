<?php namespace net\peacefulcraft\apirouter\spec\terminal;

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
}

?>