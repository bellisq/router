<?php

namespace Bellisq\Router\Exceptions\PreconditionCapsule;

use LogicException;


class MultipleCandidateException
    extends LogicException
{
    public function __construct(string $name)
    {
        parent::__construct("Multiple candidate found for {$name}.");
    }
}