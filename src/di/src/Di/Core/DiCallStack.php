<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 21:06
     */

    namespace Gismo\Component\Di\Core;

    class DiCallStack {

        /**
         * @var DiCallStack
         */
        public $parent = null;

        public $description = null;



        public function __construct(DiCallStack $parent = null, $description=null) {
            $this->parent = $parent;
            $this->description = $description;
        }



        public function __onDiCall(callable $fn) : DiCallStack {

        }

    }