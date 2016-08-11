<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 05.08.16
 * Time: 22:55
 */

    namespace Gismo\Component\Partial;

    interface Renderable {

        /**
         * @param bool $return
         * @return mixed
         */
        public function render($return=false) : string;

    }