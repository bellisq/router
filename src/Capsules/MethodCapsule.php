<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Request\Request;
use DomainException;


/**
 * [Class] Method Capsule (Immutable)
 *
 * Encapsulate the http method name.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class MethodCapsule
{
    public const METHOD_GET    = Request::METHOD_GET;
    public const METHOD_POST   = Request::METHOD_POST;
    public const METHOD_PUT    = Request::METHOD_PUT;
    public const METHOD_DELETE = Request::METHOD_DELETE;

    private const METHOD_CANDIDATES = [
        self::METHOD_GET    => true,
        self::METHOD_POST   => true,
        self::METHOD_PUT    => true,
        self::METHOD_DELETE => true
    ];

    /**
     * MethodCapsule constructor.
     *
     * @param string $method
     */
    public function __construct(string $method)
    {
        $method = strtoupper($method);
        if (!isset(self::METHOD_CANDIDATES[$method])) {
            throw new DomainException;
        }
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /** @var string */
    private $method;
}