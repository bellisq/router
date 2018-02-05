<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Request\Request;
use DomainException;


/**
 * [Class] Scheme Capsule (Immutable)
 *
 * Encapsulate the http scheme name.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class SchemeCapsule
{
    public const SCHEME_HTTP  = Request::SCHEME_HTTP;
    public const SCHEME_HTTPS = Request::SCHEME_HTTPS;

    private const SCHEME_CANDIDATES = [
        self::SCHEME_HTTP  => true,
        self::SCHEME_HTTPS => true
    ];

    /**
     * SchemeCapsule constructor.
     *
     * @param string $scheme
     */
    public function __construct(string $scheme)
    {
        $scheme = strtolower($scheme);
        if (!isset(self::SCHEME_CANDIDATES[$scheme])) {
            throw new DomainException;
        }
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function isHttp(): bool
    {
        return $this->scheme === self::SCHEME_HTTP;
    }

    public function isHttps(): bool
    {
        return $this->scheme === self::SCHEME_HTTPS;
    }

    /** @var string */
    private $scheme;
}