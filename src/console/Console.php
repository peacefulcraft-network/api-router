<?php
namespace net\peacefulcraft\apirouter\console;

use net\peacefulcraft\apirouter\api\ApplicationCommandProvider;
use \net\peacefulcraft\apirouter\util\Validator;
use ReflectionClass;

/**
 * Interactive console
 * Text styling credit: https://gist.github.com/sallar/5257396
 */
class Console {
	private array $_config;

	private string $_OS;
		public function getOS():string { return $this->_OS; }
		public function isUnix():bool { return $this->_OS === 'UNIX'; }
		public function isWindows():bool { return $this->_OS === 'WINDOWS'; }

	private array $_active_directives = [];
		public function getActiveDirectives():array { return $this->_active_directives; }
		public function registerCommand(ApplicationCommandProvider $Plugin, Command $Command): void {
			$prefix = (strlen($Plugin->getPrefix() === 0))? strtolower((new ReflectionClass($Plugin))->getShortName()) : $Plugin->getPrefix();
			$prefix = "${prefix}:";
			$this->_active_directives[strtolower($prefix . $Command->getName())] = $Command;
		}

	private bool $_running = true;
	private string $_last_input = "";
	private string $_last_command = "";
	private array $_last_args = [];

	private static array $_text_colors = array(
		'[bold]'         => '1',    '[dim]'          => '2',
		'[black]'        => '0;30', '[dark_gray]'    => '1;30',
		'[blue]'         => '0;34', '[light_blue]'   => '1;34',
		'[green]'        => '0;32', '[light_green]'  => '1;32',
		'[cyan]'         => '0;36', '[light_cyan]'   => '1;36',
		'[red]'          => '0;31', '[light_red]'    => '1;31',
		'[purple]'       => '0;35', '[light_purple]' => '1;35',
		'[brown]'        => '0;33', '[yellow]'       => '1;33',
		'[light_gray]'   => '0;37', '[white]'        => '1;37',
		'[normal]'       => '0;39',
	);
	private static array $_background_colors = array(
		'[black]'        => '40',   '[red]'          => '41',
		'[green]'        => '42',   '[yellow]'       => '43',
		'[blue]'         => '44',   '[magenta]'      => '45',
		'[cyan]'         => '46',   '[light_gray]'   => '47',
	);
	private static array $_text_decoration = array(
		'[underline]'    => '4',    '[blink]'         => '5', 
		'[reverse]'      => '7',    '[hidden]'        => '8',
	);
	private static array $_text_format = array(
		'[tab]' => '	'
	);

	public function __construct(array $config = []) {
		$this->_config = $config;


		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$this->_OS = 'WINDOWS';
		} else {
			$this->_OS = 'UNIX';
		}
	}

	/**
	 * Non-interactive console. Executes command from CLI arguments and exist.
	 */
	public function runCommand(string $command_string): int {
		$this->_last_input = trim($command_string);
		$this->_last_args = explode(" ", $this->_last_input);
		$this->_last_command = array_shift($this->_last_args);
		
		if (array_key_exists($this->_last_command, $this->_active_directives)) {
			return $this->_active_directives[$this->_last_command]->execute($this->_config, $this, $this->_last_args);
		} else {
			SELF::printLine('[red]Unknown command! [normal]Use [light_purple]help [normal]to see available commands.');
			return 1;
		}
	}

	/**
	 * Launch interactive console
	 */
	public function run(): void {
		SELF::printLine("[light_blue]PHP [normal]Interactive Application Console");
		$stdin = fopen("php://stdin", "r");
		if ($stdin === false) {
			SELF::printLine('[red]Error opening input stream - exiting.');
			$this->_running = false;
		}

		while($this->_running) {
			SELF::printLine("[green]> ", false);
			$this->_last_input = trim(fgets($stdin, 1024));
			$this->_last_args = explode(" ", $this->_last_input);
			$this->_last_command = array_shift($this->_last_args);

			// If no prefix was specified, default to STD command prefix
			if (strpos($this->_last_command, ':') === false) { $this->_last_command = 'std:' . $this->_last_command; }

			if ($this->_last_input === 'exit' || $this->_last_input === 'std:exit') {
				$this->_running = false;

			} else {
				if (array_key_exists($this->_last_command, $this->_active_directives)) {
					$this->_active_directives[$this->_last_command]->execute($this->_config, $this, $this->_last_args);
				} else {
					SELF::printLine('[red]Unknown command! [normal]Use [light_purple]help [normal]to see available commands.');
				}
			}
		}

		fclose($stdin);
	}

	/**
	 * Take an array of argument strings that are seperated by a delimeter ["username=tsa", "password=asdfasdf"]. Delimeter '='
	 * Parse into array of argument key => value. [ username=>"tsa", password=>"asdfasdf"]
	 */
	public static function associateArguments(array $args, string $delimiter = null):array {
		if (count($args) === 0) { return []; }

		$associated = [];
		if ($delimiter === null) {
			$key = '';
			foreach($args as $arg) {
				if (strlen($key) > 0) {
					$associated[$key] = $arg;
					$key = '';
				} else {
					$key = $arg;
				}
			}
		} else {
			foreach($args as $arg) {
				$kv = explode($delimiter, $arg);
				
				if (!Validator::meaningfullyExists(@$kv[0])) {
					continue;
				}
				if (!Validator::meaningfullyExists(@$kv[1])) {
					$kv[1] = '';
				}

				$associated[$kv[0]] = $kv[1];
			}
		}

		return $associated;
	}

	public static function printLine(string $line, bool $newLine = true) {
		foreach(SELF::$_text_colors as $color => $code) {
			$line = str_replace($color, "\033[" . $code . "m", $line);
		}

		foreach(SELF::$_text_format as $key => $format) {
			$line = str_replace($key, $format, $line);
		}

		echo $line . "\033[" . SELF::$_text_colors['[normal]'] . "m";
		if ($newLine) { echo PHP_EOL; }
	}
}
?>