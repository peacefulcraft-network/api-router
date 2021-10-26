<?php

use net\peacefulcraft\apirouter\test\enums\TestEnum;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase {
	public function testEnumDelcaration() {
		$testEnum = new TestEnum(TestEnum::VAL1);
		$this->assertEquals(TestEnum::VAL1, $testEnum->_value);

		$this->expectException(RuntimeException::class);
		$testEnum = new TestEnum("FAKE VALUE");
	}

	public function testEnumStringable() {
		$testEnum = new TestEnum(TestEnum::VAL1);
		$this->assertEquals(TestEnum::VAL1, (string) $testEnum);
	}

	public function testEnumJsonSerializable() {
		$testEnum = new TestEnum(TestEnum::VAL1);
		$this->assertEquals('"' . TestEnum::VAL1 . '"', json_encode($testEnum));
	}

	public function testEnumKeyOf() {
		$this->assertEquals("VAL1", TestEnum::keyOf("value_1"));
		$this->assertEquals("VAL2", TestEnum::keyOf("value_2"));
		$this->assertEquals("VAL3", TestEnum::keyOf("value_3"));
		$this->assertEquals("VAL4", TestEnum::keyOf("value_4"));
	}

	public function testEnumValueOf() {
		$this->assertEquals("value_1", TestEnum::valueOf("VAL1"));
		$this->assertEquals("value_2", TestEnum::valueOf("VAL2"));
		$this->assertEquals("value_3", TestEnum::valueOf("VAL3"));
		$this->assertEquals("value_4", TestEnum::valueOf("VAL4"));
	}

	public function testEnumSerializationAndUnserialization() {
		$testEnum = new TestEnum(TestEnum::VAL1);
		$serializedEnum = serialize($testEnum);
		// Serialize PHP Assoc Array w/ 1 k/v pair
		$this->assertEquals('O:47:"net\peacefulcraft\apirouter\test\enums\TestEnum":1:{s:6:"_value";s:7:"value_1";}', $serializedEnum);

		$newEnum = unserialize($serializedEnum);
		$this->assertEquals(strval($newEnum), TestEnum::VAL1);
	}
}
?>