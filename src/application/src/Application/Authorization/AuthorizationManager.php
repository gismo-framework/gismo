<?php

/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.08.16
 * Time: 21:54
 */

namespace Gismo\Component\Application\Authorization;

interface AuthorizationManager
{

    public function hasRole($role) : bool;

    public function hasPermission($permission) : bool;

    public function isInternal();

}