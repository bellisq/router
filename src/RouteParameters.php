<?php

namespace Bellisq\Router;

use Bellisq\Router\Exceptions\RouteParameters\InvalidKeyException;
use Bellisq\Router\Exceptions\RouteParameters\NonStringValueException;
use stdClass;
use Strict\Property\DisablePropertyInjection;
use Strict\Property\Errors\DisabledPropertyInjectionError;
use Strict\Property\Errors\IndeliblePropertyError;
use Strict\Property\Errors\ReadonlyPropertyError;
use Strict\Property\Errors\UndefinedPropertyError;


/**
 * [Class] Route Parameters (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteParameters
    extends stdClass
{
    use DisablePropertyInjection {
        __set as private traitSet;
        __get as private traitGet;
        __unset as private traitUnset;
    }

    /** @var array */
    private $var = [];

    /**
     * RouteParameters constructor.
     *
     * @param array $params
     *
     * @throws InvalidKeyException
     * @throws NonStringValueException
     */
    public function __construct(array $params)
    {
        foreach ($params as $key => $value) {
            if (!is_string($key)) {
                throw new InvalidKeyException;
            }

            if (is_scalar($value)) {
                $this->var[$key] = (string)$value;
            } else if (is_object($value)) {
                if (method_exists($value, '__toString')) {
                    $this->var[$key] = (string)$value;
                } else {
                    throw new NonStringValueException;
                }
            } else {
                throw new NonStringValueException;
            }
        }
    }

    /**
     * Magic method.
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->var[$name]);
    }

    /**
     * Magic method.
     *
     * @param string $name
     * @return mixed
     *
     * @throws UndefinedPropertyError
     */
    public function __get(string $name)
    {
        if ($this->__isset($name)) {
            return $this->var[$name];
        }
        return $this->traitGet($name);
    }

    /**
     * Magic method.
     *
     * @param string $name
     *
     * @throws IndeliblePropertyError
     * @throws UndefinedPropertyError
     */
    public function __unset(string $name): void
    {
        if ($this->__isset($name)) {
            throw new IndeliblePropertyError(static::class, $name);
        }
        $this->traitUnset($name);
    }

    /**
     * Magic method.
     *
     * @param string $name
     * @param        $value
     *
     * @throws ReadonlyPropertyError
     * @throws DisabledPropertyInjectionError
     */
    public function __set(string $name, $value): void
    {
        if ($this->__isset($name)) {
            throw new ReadonlyPropertyError(static::class, $name);
        }
        $this->traitSet($name, $value);
    }
}