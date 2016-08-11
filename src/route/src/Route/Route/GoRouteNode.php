<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.08.16
     * Time: 19:10
     */

    namespace Gismo\Component\Route\Route;


    use Gismo\Component\PhpFoundation\Type\PrototypeMap;
    use Gismo\Component\Route\GoAction;

    class GoRouteNode extends PrototypeMap {

        public function __construct() {
            parent::__construct($this);
        }


        /**
         * @var GoAction
         */
        public $action = null;

        /**
         * @var GoRouteComponent
         */
        public $component = null;

    }
