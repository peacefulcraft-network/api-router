<?php
namespace ncsa\phpmcj\util;

abstract class Validator {
	/**
	 * Check that the values meet their requested type constraints.
	 * No criteria defaults to meaningfullyExists() check
	 * @param array...$values array('type'=>'exist, email, password', 'value'=>'the value'),...
	 * @return bool true/false Values met the specified criteria
	 */
	public static function meetTypeRequirements(array ...$values): bool {
		foreach($values as $value) {
			if ($value['type'] === 'exist') {
				if (!SELF::meaningfullyExists($value['value'])) { return false; }
			} elseif ($value['type'] === 'email') {
				if (!SELF::isEmail($value['value'])) { return false; }
			} elseif ($value['type'] === 'password') {
				if (!SELF::couldBePassword($value['value'])) { return false; }
			} else {
				if (!SELF::meaningfullyExists($value['value'])) { return false; }
			}
		}
		return true;
	}

	/**
	 * Check that values are set, non-null, and non-empty
	 * @param any...$values The values to check
	 * @return bool true/false value(s) were meaningful and exist
	 */
	public static function meaningfullyExists(string|int|float|bool|array|null ...$values): bool {
		foreach($values as $value) {
			// Check for general existence
			if (!isset($value) || $value === null) {
				return false;
			}

			// Check for significant string
			if (is_string($value) && strlen(trim($value)) === 0) {
				return false;
			}

			if (is_array($value)) {
				if (count($value) === 0) {
					return false;
				} else {
					foreach($value as $i) {
						if (!SELF::meaningfullyExists($i)) { return false; }
					}
				}
			}
		}
		return true;
	}

	/**
	 * Check that the provide values are email addreses
	 * @param any...$values The values to check
	 * @return bool true/false value(s) were emails
	 */
	public static function isEmail(?string ...$values): bool {
		foreach($values as $value) {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Check that the provided values meet password requirements
	 * @param any...$values The values to check
	 * @return bool ture/false value(s) meet password requirements
	 */
	public static function couldBePassword(string|int|float|bool|null ...$values): bool {
		foreach($values as $value) {
			if (strlen($value) < 8) { return false; }
		}
		return true;
	}
}

?>
