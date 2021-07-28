<?php
namespace net\peacefulcraft\apirouter\enum;

use JsonSerializable;
use ReflectionClass;
use RuntimeException;
use Stringable;

/**
 * Type-checked enumerated type.
 * Only fully supports PHP primatives. Can support more complex types, so long as those
 * types implement the 'Stringable' and 'JsonSerializable' interfaces, otherwise
 * __toString() and json_encode() behavior will likley differ from expected.
 */
abstract class Enum implements Stringable, JsonSerializable {
	private static array $_type_cache = [];

	protected mixed $_value;
	/**
	 * Only used to access the $_value of this encapsulated enum type.
	 * Method should not be invoked directly. See PHP __get magic method.
	 * Access with [EnumChildObject]->_value;
	 */
	public function __get(string $name): mixed {
		if ($name !== "_value") {
			throw new RuntimeException("Attempted to access non _value property on enumerated type.");
		}
		return $this->_value;
	}

	/**
	 * Should only be called by a child class during instantiation.
	 * @param value The value to be ecapsulated and value enforced
	 * @throws RuntimeException When $value is not a valid representation of this type.
	 */
	public function __construct(mixed $value) {
		SELF::getTypes();

		if (SELF::keyOf($value) === null) {
			throw new RuntimeException("Supplied value failed enumerated type check.");
		}

		$this->_value = $value;
	}

	/**
	 * Takes an enumerated value and attempts to resolve it to an enumeration key.
	 * @param value: The value to convert
	 * @return Enum An enumerated type wrapper which represents the given $value
	 * @return null on failure
	 */
	public static function keyOf(mixed $value): ?string {
		foreach (SELF::getTypes() as $type => $enumeration) {
			if ($value === $enumeration) { return $type; }
		}
		return null;
	}

	/**
	 * Takes a string key and converts it to the value of the type with that key.
	 * @param key: The string key to convert
	 * @param mixed Success, return the underlying value which the key is represented by
	 * @return null Failure, no key by that name exists
	 */
	public static function valueOf(string $key): mixed {
		return SELF::getTypes()[$key];
	}

	private static function getTypes(): array {
		$reflection = new ReflectionClass(static::class);

		// Check if we've already cached these constants
		if (!isset(SELF::$_type_cache[$reflection->getName()])) {
			// Cache the constants so we don't have to generate them everytime.
			SELF::$_type_cache[$reflection->getName()] = $reflection->getConstants();
		}

		return SELF::$_type_cache[$reflection->getName()];
	}

	/**
	 * Define behavior for casting / coercing enums into strings.
	 * Fallback to underlying value's behavior
	 */
	public function __toString(): string {
		return (string) $this->_value;
	}

	/**
	 * Define behavior for serializing data as json
	 * Return value as a primative
	 */
	public function jsonSerialize(): mixed {
		return $this->_value;
	}

	/**
	 * Support PHP serialize() calls. This method is often called
	 * when using PHP's internal Session system which uses built-in
	 * serialization for storing $_SESSION[] values;
	 */
	public function __serialize(): array {
		return ['_value' => $this->_value];
	}

	/**
	 * Support PHP unserialize() calls. This method is often called
	 * when using PHP's internal Session system which uses built-in
	 * unserialization for populating $_SESSION[] values
	 */
	public function __unserialize(array $data): void {
		$this->__construct($data['_value']);
	}
}