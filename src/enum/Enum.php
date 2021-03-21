<?php
namespace ncsa\phpmcj\enum;

use ReflectionClass;
use RuntimeException;

abstract class Enum {
	private static array $_type_cache = [];

	protected string|int $_value;
	/**
	 * Only used to access the $_value of this encapsulated enum type.
	 * Method should not be invoked directly. See PHP __get magic method.
	 * Access with [EnumChildObject]->_value;
	 */
	public function __get(string $name) {
		if ($name !== "_value") {
			throw new RuntimeException("Attempted to access non _value property on enumerated type.");
		}
		return $this->_value;
	}
	/**
	 * Only used to set the $_value of this encapsulated enum type.
	 * Method should not be invoked directly. See PHP __set magic method.
	 * Access with [EnumChildObject]->_value = value;
	 */
	public function __set(string $name, mixed $value) {
		if ($name !== "_value") {
			throw new RuntimeException("Attempted to access non _value property on enumerated type.");
		}

		if (SELF::typeOf($value) === null) {
			throw new RuntimeException("Supplied value failed enumerated type check.");
		}

		$this->_value = $value;
	}

	/**
	 * Should only be called by a child class during instantiation.
	 * @param value The value to be ecapsulated and value enforced
	 * @throws RuntimeException When $value is not a valid representation of this type.
	 */
	public function __construct(string|int $value) {
		SELF::getTypes();

		if (SELF::typeOf($value) === null) {
			throw new RuntimeException("Supplied value failed enumerated type check.");
		}

		$this->_value = $value;
	}

	/**
	 * Takes an enumerated value and attempts to resolve it to an enumeration key.
	 * @param value: The value to convert
	 * @return null on failure, string or integer on success 
	 */
	public static function typeOf($value):string|int|null {
		foreach (SELF::getTypes() as $type => $enumeration) {
			if ($value === $enumeration) { return $type; }
		}
		return null;
	}

	/**
	 * Takes a string key and converts it to the value of the type with that key.
	 * @param key: The string key to convert
	 * @return String Success and the enumerated type is internally represnted by a string
	 * @return Int Success and the enumerated type is internally represented by an integer
	 * @return null Failure, no key by that name exists
	 */
	public static function valueOf(string $key):string|int|null {
		return SELF::getTypes()[$key];
	}

	private static function getTypes() {
		$reflection = new ReflectionClass(static::class);

		// Check if we've already cached these constants
		if (!isset(SELF::$_type_cache[$reflection->getName()])) {
			// Cache the constants so we don't have to generate them everytime.
			SELF::$_type_cache[$reflection->getName()] = $reflection->getConstants();
		}

		return SELF::$_type_cache[$reflection->getName()];
	}
}