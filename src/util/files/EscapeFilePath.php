<?php
namespace ncsa\phpmcj\util\files;

trait EscapeFilePath {
	/**
	 * Takes a file name or path and escapes it for use in file functions.
	 * Replaces any non alpha-numeric character with an '_', and then
	 * trims and '_'s off the ends to keep the file name neat.
	 * @param path The path or file name to escape
	 * @return string The escape path or file name
	 */
	private function _escapeFilePath(string $path): string {
		return trim(preg_replace('/[^A-Za-z0-9_\-]/', '_', $path), '_');
	}
}
?>