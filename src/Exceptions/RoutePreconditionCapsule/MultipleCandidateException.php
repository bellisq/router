<?php

namespace Bellisq\Router\Exceptions\RoutePreconditionCapsule;

use LogicException;


/**
 * [Exception] Multiple Candidate
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class MultipleCandidateException
    extends LogicException
{
    public function __construct(string $name)
    {
        parent::__construct("Multiple candidate found for {$name}.");
    }
}