<?php

namespace Bellisq\Router\Capsules;


/**
 * [Class] HTTP Host Capsule (Immutable)
 *
 * Encapsulate the http host name.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class HostCapsule
{
    /**
     * HostCapsule constructor.
     *
     * @param string $host
     */
    public function __construct(string $host)
    {
        $this->host = strtolower($host);
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /** @var string */
    private $host;
}