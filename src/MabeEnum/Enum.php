<?php

/**
 * Class to implement enumerations for PHP 5 (without SplEnum)
 *
 * @link http://github.com/marc-mabe/php-enum for the canonical source repository
 * @copyright Copyright (c) 2012 Marc Bennewitz
 * @license http://github.com/marc-mabe/php-enum/blob/master/LICENSE.txt New BSD License
 */
abstract class MabeEnum_Enum
{
    /**
     * The current selected value
     * @var scalar
     */
    protected $value = null;

    /**
     * The ordinal number of the value
     * @var null|int
     */
    private $ordinal = null;

    /**
     * An array of available constants
     * @var array
     */
    private $constants = null;

    /**
     * Constructor
     * 
     * @param mixed $value The value to select
     * @throws InvalidArgumentException
     */
    public function __construct($value = null)
    {
        $reflectionClass = new ReflectionClass($this);
        $constants       = $reflectionClass->getConstants();

        // This is required to make sure that constants of base classes will be the first
        while ( ($reflectionClass = $reflectionClass->getParentClass()) ) {
            $constants = $reflectionClass->getConstants() + $constants;
        }
        $this->constants = $constants;

        // TODO: Check that constant values are equal (non strict comparison)

        // use the default value
        if (func_num_args() == 0) {
            $value = $this->value;
        }

        // find and set the given value
        // set the defined value because of non strict comparison
        $const = array_search($value, $this->constants);
        if ($const === false) {
            throw new InvalidArgumentException("Unknown value '{$value}'");
        }
        $this->value = $this->constants[$const];
    }

    /**
     * Get all available constants
     * @return array
     */
    final public function getConstants()
    {
        return $this->constants;
    }

    /**
     * Get the current selected value
     * @return mixed
     */
    final public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the current selected constant name
     * @return string
     */
    final public function getName()
    {
        return array_search($this->value, $this->constants, true);
    }

    final public function getOrdinal()
    {
        if ($this->ordinal !== null) {
            return $this->ordinal;
        }

        // detect ordinal
        $ordinal = 0;
        $value   = $this->value;
        foreach ($this->constants as $constValue) {
            if ($value === $constValue) {
                $this->ordinal = $ordinal;
                return $ordinal;
            }
            ++$ordinal;
        }

        throw new RuntimeException(
            "Current value '{$value}' isn't defined within this '" . get_class($this) . "'"
        );
    }

    /**
     * Get the current selected constant name
     * @return string
     * @see getName()
     */
    final public function __toString()
    {
        return (string) $this->value;
    }
}