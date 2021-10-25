<?php

use net\peacefulcraft\apirouter\config\ImmutableYAMLConfiguration;
use PHPUnit\Framework\TestCase;

class ImmutableYAMLConfigurationTest extends TestCase {

CONST TEST_YAML = <<<EOF
simple_property: simple_value
simple_int: 5
simple_float: 6.0
nest_parent:
  nest_member:
    nest_member_value: 'Hello World!'
EOF;

	public function testInvalidYAMLGeneratesParsingException() {
		$this->expectException(RuntimeException::class);

		$Config = @new ImmutableYAMLConfiguration('	');
	}

	public function testYAMLConfigurationObjectRejectsModificationAttempts() {
		$Config = new ImmutableYAMLConfiguration(SELF::TEST_YAML);

		$this->expectException(RuntimeException::class);
		$Config->setProperty('myproperty', 'foobar');
	}

	public function testYAMLConfigurationReturnsRequestedValues() {
		$Config = new ImmutableYAMLConfiguration(SELF::TEST_YAML);
	
		$this->assertEquals('simple_value', $Config->getProperty('simple_property'));
		$this->assertEquals('Hello World!', $Config->getProperty('nest_parent.nest_member.nest_member_value'));
		$this->assertEquals(['nest_member' => [ 'nest_member_value' => 'Hello World!' ]], $Config->getProperty('nest_parent'));
		
		// DNE Keys
		$this->assertNull($Config->getProperty(''));
		$this->assertNull($Config->getProperty('FAKE'));
	}

	/*
		PHP Arrays are copy-on-write so theoretically attempting to modify
		any values in the array will just clone the array that was returned
		and not impact the internal array. This check exists to prevent any
		accidental change to the read function(s) that may result in
		object-like, pass-by-reference behavior for arrays.

		At present Object serialization is not supported so there should be no
		caviots to this copy-on-write behavior. (Object properties).
	*/
	public function testCopyOnWriteSanity() {
		$Config = new ImmutableYAMLConfiguration(SELF::TEST_YAML);

		$value = $Config->getProperty('nest_parent');
		$value['nest_member_value'] = 'Violated Object boundry.';

		$this->assertEquals(['nest_member' => [ 'nest_member_value' => 'Hello World!' ]], $Config->getProperty('nest_parent'));
	}
}

?>