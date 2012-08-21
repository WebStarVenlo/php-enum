<?php
/**
 * TODO: description
 *
 * @link http://github.com/marc-mabe/php-enum for the canonical source repository
 * @copyright Copyright (c) 2012 Marc Bennewitz
 * @license http://github.com/marc-mabe/php-enum/blob/master/LICENSE.txt New BSD License
 */

error_reporting(E_ALL | E_STRICT);

// init PHPUnit
require_once 'PHPUnit/Framework/TestCase.php';
if ('@package_version@' !== PHPUnit_Runner_Version::id() && version_compare(PHPUnit_Runner_Version::id(), '3.6.0', '<')) {
	echo 'This version of PHPUnit (' . PHPUnit_Runner_Version::id() . ') is not supported.' . PHP_EOL;
	exit(1);
}

require_once dirname(__FILE__) . '/../src/Enum.php';

class EnumTest extends PHPUnit_Framework_TestCase
{
	
	public function testEnumWithDefaultValue()
	{
		$enum = new EnumWithDefaultValue();
		
		$this->assertSame(array(
			'ONE' => 1,
			'TWO' => 2,
		), $enum->getConstants());
		
		$this->assertSame(1, $enum->getValue());
		$this->assertSame(1, $enum->__invoke());
		
		$this->assertSame('ONE', $enum->getName());
		$this->assertSame('ONE', $enum->__toString());
	}
	
	public function testEnumWithNullAsDefaultValue()
	{
		$enum = new EnumWithNullAsDefaultValue();
	
		$this->assertSame(array(
			'NONE' => null,
			'ONE'  => 1,
			'TWO'  => 2,
		), $enum->getConstants());
	
		$this->assertNull($enum->getValue());
		$this->assertNull($enum->__invoke());
	
		$this->assertSame('NONE', $enum->getName());
		$this->assertSame('NONE', $enum->__toString());
	}
	
	public function testEnumWithoutDefaultValue()
	{
		$this->setExpectedException('InvalidArgumentException');
		new EnumWithoutDefaultValue();
	}
	
	public function testChangeValueOnConstructor()
	{
		$enum = new EnumWithoutDefaultValue(1);
		
		$this->assertSame(1, $enum->getValue());
		$this->assertSame(1, $enum->__invoke());
		
		$this->assertSame('ONE', $enum->getName());
		$this->assertSame('ONE', $enum->__toString());
	}
	
	public function testChangeValueOnConstructorThrowsInvalidArgumentExceptionOnStrictComparison()
	{
		$this->setExpectedException('InvalidArgumentException');
		$enum = new EnumWithoutDefaultValue('1');
	}
	
	public function testSetValue()
	{
		$enum = new EnumWithDefaultValue();
		$enum->setValue(2);
	
		$this->assertSame(2, $enum->getValue());
		$this->assertSame(2, $enum->__invoke());
	
		$this->assertSame('TWO', $enum->getName());
		$this->assertSame('TWO', $enum->__toString());
	}
	
	public function testSetValueThrowsInvalidArgumentExceptionOnStrictComparison()
	{
		$this->setExpectedException('InvalidArgumentException');
		$enum = new EnumWithDefaultValue();
		$enum->setValue('2');
	}
	
}

class EnumWithDefaultValue extends Enum
{
	const ONE = 1;
	const TWO = 2;
	protected $value = 1;
}

class EnumWithNullAsDefaultValue extends Enum
{
	const NONE = null;
	const ONE  = 1;
	const TWO  = 2;
}


class EnumWithoutDefaultValue extends Enum
{
	const ONE = 1;
	const TWO = 2;
}

class EmptyEnum extends Enum
{}
