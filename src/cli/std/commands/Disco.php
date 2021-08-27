<?php namespace net\peacefulcraft\apirouter\cli\std\commands;

use net\peacefulcraft\apirouter\cli\Command;
use net\peacefulcraft\apirouter\spec\cli\Format;
use net\peacefulcraft\apirouter\spec\cli\Terminal;
use ReflectionClass;

class Disco extends Command {

	public function __construct() {
		parent::__construct('disco');
	}

	public function execute(Terminal $Terminal, array $args, array $flags): int {
		$width = getenv('COLUMNS') ? getenv('COLUMNS') : 50;
		$height = getenv('LINES') ? getenv('LINES') : 80;

		$block = str_repeat(str_repeat("\32", $width) . PHP_EOL, $height);

		$all_format_codes = (new ReflectionClass(Format::class))->getConstants();
		$bg_format_codes = array_filter($all_format_codes, function ($key) {
			return strpos($key, 'BG_') === 0;
 		}, ARRAY_FILTER_USE_KEY);
		$bg_format_keys = array_keys($bg_format_codes);

		$bgi = 2; // skip black and reset
		$bgc = count($bg_format_codes);

		// Not CTRL + D
		// while (ord(stream_get_contents($Terminal->getSTDIn(), 5)) != 4) {
		while (true) {
			$Terminal->print($bg_format_codes[$bg_format_keys[$bgi]]);
			$color_key = str_replace('BG_', 'COLOR_', $bg_format_keys[$bgi]);
			$Terminal->print($all_format_codes[$color_key]);
			$Terminal->print($block);

			if (++$bgi === $bgc) { $bgi = 2; }

			sleep(1);
		}

		$Terminal->printLn(Format::BG_RESET);
		$Terminal->runCommand('std:clear');

		return 0;
	}
}

?>