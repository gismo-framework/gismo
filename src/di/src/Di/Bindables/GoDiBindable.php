<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 04.08.17
 * Time: 10:21
 */

namespace Gismo\Component\Di\Bindables;


interface GoDiBindable
{

    public function __di_set_bindname (string $bindName);
}