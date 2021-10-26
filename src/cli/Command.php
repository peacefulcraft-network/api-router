<?php namespace net\peacefulcraft\apirouter\cli;

use net\peacefulcraft\apirouter\spec\cli\Command as CliCommand;

abstract class Command implements CliCommand {

	private string $_name = '';
	private array $_flags = [];
	private array $_args = [];
	private string $_description = '';
	private string $_help_message = '';

	public function __construct(string $name) {
		$this->_name = $name;
	}

	public function getName(): string { return $this->_name; }

	public function addNamedFlag(array $types, bool $required, string $name): CliCommand {
		$this->_flags[$name] = ['types' => $types, 'required'=>$required];
		return $this;
	}

	public function addPositionArg(array $types, bool $required, string $name): CliCommand {
		array_push($this->_args, ['types' => $types, 'required'=>$required, 'name'=>$name]);
		return $this;
	}

	public function getDescription(): string {
		return $this->_description;
	}

	public function setDescription(string $description): CliCommand {
		$this->_description = $description;
		return $this;
	}

	public function getHelpMessage(): string {
		return $this->_help_message;
	}

	public function setHelpMessage(string $help_message): CliCommand {
		$this->_help_message = $help_message;
		return $this;
	}

	public function parseParams(string $command): array {
		$firstFlag = strpos($command, '--', 0);
		$firstSpace = strpos($command, ' ', 0);
		$strlen = strlen($command);

		// Check if any flags were supplied and extrat them if yes
		$flagsWithVals = '';
		if ($firstFlag === false) {
			$firstFlag = $strlen;
		} else {
			$flagsWithVals = trim(substr($command, $firstFlag));
		}

		/*
			Spits out list of matched groups, numeric and named.
			[ 0=> ['', '', ...], 'flag' => ['','', ...], ...]
		*/
		$matchResult = [];
		preg_match_all('/--(?<flag>[^=]+)=(?:["])?(?:[\'])?(?<value>(?(2)([^"]|\\")+|(?(3)([^\']|\\\')+|\S+)))/', $flagsWithVals, $matchResult);
		
		/*
			Match flag names back to values. Even if no f/v pairs were passed, we loop
			through what the Command was defined with to check for omitted required flags
			[ 'flag1' => 'value1', ... ]
		*/
		$processedFlagValues = [];
		foreach($this->_flags as $flagName=>$flagProperties) {
			if ($pos = array_search($flagName, $matchResult['flag'], false)) {
				$suppliedValue = $matchResult['value'][$pos];

				// Check for type match
				if (
					empty($flagProperties['types']) // Empty array disables type checking
					|| in_array('mixed', $flagProperties['types'], false)
					|| in_array(gettype($suppliedValue), $flagProperties['types'], false)
				) {
					$processedFlagValues[$flagName] = $flagProperties;
				} else {
					$processedFlagValues[$flagName] = new CommandParseParamsError(CommandParseParamsError::BAD_TYPE);
				}
			} else if ($flagProperties['required'] === true) {
				$processedFlagValues[$flagName] = new CommandParseParamsError(CommandParseParamsError::OMITTED_REQUIRED);
			} 
		}

		// If there was no space, a command with no args was supplied
		if ($firstSpace === false) {
			$commandArgs = [];
		} else {
			// [command] [args ... ...] [flags ... ...]
			// Extract [args ... ...]
			$commandArgs = trim(substr($command, $firstSpace+1, $firstFlag));
			$commandArgs = explode(' ', $commandArgs);
		}

		// Loop through supplied arguemnts and ensure type checks pass
		$numArgs = count($this->_args);
		$processedCommandArgs = [];
		for ($i = 0; $i < $numArgs; $i++) {
			if (array_key_exists($i, $commandArgs)) {
				$argI = $this->_args[$i];
				$suppliedValue = $commandArgs[$i];

				// Check type if an arg was supplied
				if (
					empty($argI['types'])
					|| in_array('mixed', $argI['types'], false)
					|| in_array(gettype($suppliedValue), $argI['types'], false)
				) {
					array_push($processedCommandArgs, $suppliedValue);
				} else {
					array_push($processedCommandArgs, new CommandParseParamsError(CommandParseParamsError::BAD_TYPE));	
				}
			} else {
				array_push($processedCommandArgs, new CommandParseParamsError(CommandParseParamsError::OMITTED_REQUIRED));
			}
		}

		return ['args' => $processedCommandArgs, 'flags' => $processedFlagValues];
	}
}

?>