# php-enum

This is a native PHP implementation to add enumarable support to PHP.
It's an abstract class that can be extended to emulate enumerables.


# Why not ```SplEnum```

* It's not build-in PHP and requires pecl/extension
* SplEnum is too much magic under the hod
* SplEnum hasn't strict comparison


# API

    Enum
    {
        protected $value = null;
        final public function __construct($value = null);
        final public function getConstants();
        final public function setValue($value);
        final public function getValue()
        final public function setName($name);
        final public function getName();
        final public function __toString();
    }

## Examples:

    // Enum with ONE as default value
    class MyEnumWithDefaultValue
    {
        const ONE = 1;
        const TWO = 2;
        protected $value = 1;
    }

    // Enum without a default value
    // No argument on constructor results in InvalidArgumentException
    class MyEnumWithoutDefaultValue
    {
        const ONE = 1;
        const TWO = 2;
    }

    // Because the $value object property is defined as NULL
    // a constant with a NULL value gets the default value automatically
    class MyEnumWithNullAsDefaultValue
    {
        const NONE = null;
        const ONE  = 1;
        const TWO  = 2;
    }

    // To disable the last behavior it's possible to set the default value to an unknown value
    class MyEnumWithoutNullAsDefaultValue
    {
        const NONE = null;
        const ONE  = 1;
        const TWO  = 2;
        protected $value = -1;
    }


# Class constants vs. php-enum

## The way of class constants

    class User
    {
        const INACTIVE = 0;
        const ACTIVE   = 1;
        const DELETED  = 2;

        protected $status = 0;

        public function setStatus($status)
        {
            $intStatus = (int)$status;
            if (!in_array($intStatus, array(self::INACTIVE, self::ACTIVE, self::DELETED))) {
                throw new InvalidArgumentException("Invalid status {$status}");
            }
            $this->status = $intStatus;
        }

        public function getStatus()
        {
            return $this->status;
        }
    }

    $user = new User();
    echo 'Default user status: ' . $user->getStatus() . PHP_EOL;
    $user->setStatus(User::ACTIVE);
    echo 'Changed user status: ' . $user->getStatus() . PHP_EOL;

    PRINTS:
    Default user status: 0
    Changed user status: 1

* Requires validation on every use
* Hard to extend the list of possible values
* Hard to get a human readable name of a value

## The way of php-enum:

    class UserStatusEnum extends Enum
    {
        const INACTIVE = 0;
        const ACTIVE   = 1;
        const DELETED  = 2;

        // default value
        protected $value = self::INACTIVE;
    }

    class User
    {
        protected $status;

        public function setStatus(UserStatusEnum $status)
        {
            $this->status = $status;
        }
 
        public function getStatus()
        {
            if (!$this->status) {
                // init default status
                $this->status = new UserStatusEnum();
            }
            return $this->status;
        }
    }

    $user = new User();
    echo 'Default user status: ' . $user->getStatus() . '(' . $user->getStatus()->getValue() . ')' . PHP_EOL;
    $user->setStatus(new UserStatusEnum(UserStatusEnum::ACTIVE));
    echo 'Changed user status: ' . $user->getStatus() . '(' . $user->getStatus()->getValue() . ')' . PHP_EOL;

    PRINTS:
    Default user status: INACTIVE (0)
    Changed user status: ACTIVE (1)

* Validation already done on basic class ```Enum```
* Using type-hint makes arguments save
* Human readable name of a value is simple accessable


# New BSD License

The files in this archive are released under the New BSD License.
You can find a copy of this license in LICENSE.txt file.
