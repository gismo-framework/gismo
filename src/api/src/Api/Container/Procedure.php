<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 14.09.17
 * Time: 17:49
 */

namespace Gismo\Component\Api\Container;


class Procedure
{


    public function cache($ttl) : self {

    }

    public function param(string $parameterName) : self {

    }


    public function begin (callable $fn) : self {

    }


    public function retrieve (&...$def) {

    }


    public function doc (string $shortDocumentation, string $docFileName) : self {

    }

    public function finally (callable $fn) : self {

    }

    public function __invoke(array $params)
    {
        // TODO: Implement __invoke() method.
    }

}