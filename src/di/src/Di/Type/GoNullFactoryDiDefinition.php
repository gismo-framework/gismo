<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 21:38
     */

    namespace Gismo\Component\Di\Type;

    use Gismo\Component\Di\DiContainer;


    /**
     * Class GoNullFactoryDiDefinition
     *
     * Used whenever a filter is registered before the
     * actual Factory or Service
     *
     * @package Gismo\Component\Di\Type
     */
    class GoNullFactoryDiDefinition extends GoAbstractDiDefinition {

        public function __construct() {

        }

        


        public function __diGetInstance(DiContainer $di, array $params) {
            throw new \InvalidArgumentException("NullFactory!");
        }

    }