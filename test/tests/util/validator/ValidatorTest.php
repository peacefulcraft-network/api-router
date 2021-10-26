<?php
use PHPUnit\Framework\TestCase;
use net\peacefulcraft\apirouter\util\Validator;

class ValidatorTest extends TestCase {
  public function testMeaningfullyExistsValidatorWithOneValue() {
    $this->assertFalse(Validator::meaningfullyExists(@$undefined));
    $this->assertFalse(Validator::meaningfullyExists(null));
    $this->assertFalse(Validator::meaningfullyExists("  "));
    $this->assertFalse(Validator::meaningfullyExists(""));
    $this->assertTrue(Validator::meaningfullyExists(0));
    $this->assertFalse(Validator::meaningfullyExists([""]));
    $this->assertTrue(Validator::meaningfullyExists(["aasdf"]));
    $this->assertTrue(Validator::meaningfullyExists("When it's something real, you just know."));
  }

  public function testMeaningfullyExistsValidatorWithMultipleValues() {
    $this->assertFalse(Validator::meaningfullyExists(@$undefined, @$undefinedAgain, @$undefinedAgainAgain));
    $this->assertFalse(Validator::meaningfullyExists(null, null, null));
    $this->assertFalse(Validator::meaningfullyExists("  ", ""));
    $this->assertFalse(Validator::meaningfullyExists("", "", "", []));
  	$this->assertFalse(Validator::meaningfullyExists([""], []));
    $this->assertTrue(Validator::meaningfullyExists(["aasdf", "538ios"], ["aasdf", "538ios"]));
    $this->assertFalse(Validator::meaningfullyExists(["aasdf", "538ios"], []));
    $this->assertTrue(Validator::meaningfullyExists(0, 2, -1, [5, 6]));
    $this->assertTrue(
      Validator::meaningfullyExists(
        "When it's something real, you just know.",
        "Thinking something does not make it true. Wanting something does not make it real"
      )
    );
  }

  public function testEmailValidatorWithOneValue() {
    $this->assertFalse(Validator::isEmail(null));
    $this->assertFalse(Validator::isEmail(@$undefined));
    $this->assertFalse(Validator::isEmail(''));
    $this->assertFalse(Validator::isEmail(13));
    $this->assertFalse(Validator::isEmail('this.is_mostly@real'));
    $this->assertTrue(Validator::isEmail('this.is_actually@real.tld'));
  }

  public function testEmailValidatorWithMultipleValues() {
    $this->assertFalse(Validator::isEmail(null, null, null));
    $this->assertFalse(Validator::isEmail(@$undefined, @$undefinedAgain, @$undefined3));
    $this->assertFalse(Validator::isEmail('', '', ''));
    $this->assertFalse(Validator::isEmail(13, 13, 13));
    $this->assertFalse(Validator::isEmail('this.is_mostly@real', 'this.is_mostly@real', 'this.is_mostly@real'));
    $this->assertTrue(Validator::isEmail('this.is_actually@real.tld', 'this.is_actually@real.tld', 'this.is_actually@real.tld'));
  }

  public function testPasswordValidatorWithOneValue() {
    $this->assertFalse(Validator::couldBePassword(null));
    $this->assertFalse(Validator::couldBePassword(@$undefined));
    $this->assertFalse(Validator::couldBePassword(''));
    $this->assertFalse(Validator::couldBePassword('asdf'));
    $this->assertFalse(Validator::couldBePassword(13));
    $this->assertTrue(Validator::couldBePassword('th1s1s@suff1c13nt1yc0mp13xstr1ng'));
  }

  public function testPasswordValidatorWithMultipleValues() {
    $this->assertFalse(Validator::couldBePassword(null, null, null));
    $this->assertFalse(Validator::couldBePassword(@$undefined, @$undefinedAgain, @$undefined3));
    $this->assertFalse(Validator::couldBePassword('', '', ''));
    $this->assertFalse(Validator::couldBePassword('asdf', 'sdfgdsf', '35gawr'));
    $this->assertFalse(Validator::couldBePassword(13, 17, 109503454));
    $this->assertTrue(Validator::couldBePassword('th1s1s@suff1c13nt1yc0mp13xstr1ng', 'th1s1s@suff1c13nt1yc0mp13xstr1ng', 'th1s1s@suff1c13nt1yc0mp13xstr1ng'));
  }

  public function testMultiValidatorWithOneInvalidValueOfTypeExist() {
    $this->assertFalse(
      Validator::meetTypeRequirements(
        array('type'=>'exist', 'value'=>'')
      )
    );
  }

  public function testMultiValidatorWithOneValidValueOfTypeExist() {
    $this->assertTrue(
      Validator::meetTypeRequirements(
        array('type'=>'exist', 'value'=>'I exist!')
      )
    );
  }

  public function testMultiValidatorWithOneInvalidValueOfTypeEmail() {
    $this->assertFalse(
      Validator::meetTypeRequirements(
        array('type'=>'email', 'value'=>'not.a_valid@email')
      )
    );
  }

  public function testMultiValidatorWithOneValidValueOfTypeEmail() {
    $this->assertTrue(
      Validator::meetTypeRequirements(
        array('type'=>'email', 'value'=>'a_valid@email.tld')
      )
    );
  }

  public function testMultiValidatorWithOneInvalidValueOfTypePassword() {
    $this->assertFalse(
      Validator::meetTypeRequirements(
        array('type'=>'password', 'value'=>'invalid')
      )
    );
  }

  public function testMultiValidatorWithOneValidValueOfTypePassword() {
    $this->assertTrue(
      Validator::meetTypeRequirements(
        array('type'=>'password', 'value'=>'nowvalid!')
      )
    );
  }

  public function testMultiValidatorWithMultipleInvalidValuesOfVariousTypes() {
    $this->assertFalse(
      Validator::meetTypeRequirements(
        array('type'=>'exist', 'value'=>''),
        array('type'=>'email', 'value'=>''),
        array('type'=>'email', 'value'=>'asdf'),
        array('type'=>'password', 'value'=>'hrfidp')
      )
    );
  }

  public function testMultiValidatorWithMultipleValidValuesOfVariousTypes() {
    $this->assertTrue(
      Validator::meetTypeRequirements(
        array('type'=>'exist', 'value'=>'I exist!'),
        array('type'=>'email', 'value'=>'a_valid@email.tld'),
        array('type'=>'email', 'value'=>'a_valid@email.tld'),
        array('type'=>'password', 'value'=>'hrfidphrfidp')
      )
    );
  }
}