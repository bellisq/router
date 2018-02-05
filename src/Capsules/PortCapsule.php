<?php

namespace Bellisq\Router\Capsules;

use DomainException;


/**
 * [Class] Port Capsule (Immutable)
 *
 * Encapsulate the port number.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class PortCapsule
{
    /**
     * PortCapsule constructor.
     *
     * @param int $port
     */
    public function __construct(int $port)
    {
        if ($port < 0 || 65535 < $port) {
            throw new DomainException;
        }
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /** @var int */
    private $port;
}