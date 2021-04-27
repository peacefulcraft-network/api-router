<?php
namespace net\peacefulcraft\apirouter\console;

use \net\peacefulcraft\apirouter\util\Validator;
/**
 * Interactive console
 * Text styling credit: https://gist.github.com/sallar/5257396
 */
class Console {
	
	private $_interactive;
		public function isInteractive():bool { return $this->_interactive; }

	private $_OS;
		public function getOS():string { return $this->_OS; }
		public function isUnix():bool { return $this->_OS === 'UNIX'; }
		public function isWindows():bool { return $this->_OS === 'WINDOWS'; }

	private $_active_directives = [];
		public function getActiveDirectives():array { return $this->_active_directives; }

	private $_running = true;
	private $_last_input = "";
	private $_last_command = "";
	private $_last_args = [];

	private static $_text_colors = array(
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
	private static $_background_colors = array(
		'[black]'        => '40',   '[red]'          => '41',
		'[green]'        => '42',   '[yellow]'       => '43',
		'[blue]'         => '44',   '[magenta]'      => '45',
		'[cyan]'         => '46',   '[light_gray]'   => '47',
	);
	private static $_text_decoration = array(
		'[underline]'    => '4',    '[blink]'         => '5', 
		'[reverse]'      => '7',    '[hidden]'        => '8',
	);
	private static $_text_format = array(
		'[tab]' => '  '
	);

	public function __construct(bool $interactive = true) {
		$this->_interactive = $interactive;

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$this->_OS = 'WINDOWS';
		} else {
			$this->_OS = 'UNIX';
		}
		$this->_parseDirectives();
		if ($interactive === true) {
			$this->_run();
		} else {
			$this->_runOnce();
		}
		$this->_cleanup();
	}

	/**
	 * Populate array of directives from all classes in the  \tsa\director\Directive namespace
	 */
	private function _parseDirectives() {
		$classes = get_declared_classes();
		foreach($classes as $class) {
			$meta = new \ReflectionClass($class);
			if ($meta->implementsInterface('\\ncsa\\phpmcj\\console\\Directive')) {
				$directive = new $class;
				$this->_active_directives[$directive->getName()] = $directive;
			}
		}
	}

	/**
	 * Non-interactive console. Executes command from CLI arguments and exist.
	 */
	private function _runOnce() {
		GLOBAL $argc, $argv;
		if ($argc > 0) {
			$this->_last_command = array_shift($argv);
			$this->_last_args = $argv;
			$this->_last_input = $this->_last_command . " " . implode(" ", $this->_last_args);
			
			$executed = false;
			foreach($this->_active_directives as $directive) {
				if (strcasecmp($this->_last_command, $directive->getName()) === 0) {
					$executed = true;
					$directive->execute($this, $this->_last_args);
					break;
				}
			}

			if (!$executed) { SELF::printLine('[red]Unknown command! [normal]Use [light_purple]help [normal]to see available commands.'); }
		}
	}

	/**
	 * Launch interactive console
	 */
	private function _run() {
		SELF::printLine("[yellow]NCSA [normal]Interactive Application Console");
		$stdin = fopen("php://stdin", "r");
		if ($stdin === false) {
			SELF::printLine('[red]Error opening input stream - exiting.');
			$this->_running = false;
		}

		while($this->_running) {
			SELF::printLine("[yellow]> ", false);
			$this->_last_input = trim(fgets($stdin, 1024));
			$this->_last_args = explode(" ", $this->_last_input);
			$this->_last_command = array_shift($this->_last_args);

			if ($this->_last_input === 'exit') {
				$this->_running = false;

			} else {
				$executed = false;
				foreach($this->_active_directives as $directive) {
					if (strcasecmp($this->_last_command, $directive->getName()) === 0) {
						$executed = true;
						$directive->execute($this, $this->_last_args);
						break;
					}
				}

				if (!$executed) { SELF::printLine('[red]Unknown command! [normal]Use [yellow]help [normal]to see available commands.'); }
			}
		}

		fclose($stdin);
	}

	private function _cleanup() {
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