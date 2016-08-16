<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 16.08.16
     * Time: 16:51
     */

    namespace Gismo\Component\Di\Core;



    interface Invokeable {

        public function __invoke($params);

    }
