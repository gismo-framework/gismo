<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 23.08.16
     * Time: 10:33
     */

    namespace Gismo\Component\Application\Container;


    interface GoLinkable
    {

        public function link($params, $getParams = null) : string;

        public function linkAbs($params, $getParams = null) : string;
    }