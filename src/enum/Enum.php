<?php
namespace ncsa\phpmvj\enum;

use ReflectionClass;

abstract class Enum {
  private static array $_type_cache = [];

  /**
   * Takes an enumerated value and attempts to resolve it to an enumeration key.
   * @param value: The value to convert
   * @return null on failure, string or integer on success 
   */
  public static function typeOf($value):string|int {
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