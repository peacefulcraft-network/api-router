<?php
namespace ncsa\phpmvj\enums;

use ReflectionClass;

abstract class Enum {
  /**
   * Takes an enumerated value and attempts to resolve it to an enumeration key.
   * @param value: The value to convert
   * @return null on failure, string or integer on success 
   */
  public static function typeOf($value) {
    foreach (SELF::getTypes() as $type => $enumeration) {
      if ($value === $enumeration) { return $type; }
    }
    return null;
  }

  private static function getTypes() {
    $reflection = new ReflectionClass(static::class);
    return $reflection->getConstants();
  }
}