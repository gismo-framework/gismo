<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 05.08.16
 * Time: 22:45
 */

    namespace Gismo\Component\Partial\DefaultPartial;


    use Gismo\Component\Partial\Partial;

    class NavigationPartial implements Partial {



        public function __construct()
        {
            echo "Navi Create";
        }

        /**
         * @param bool $return
         * @return mixed
         */
        public function render($return = false) : string
        {
            // TODO: Implement render() method.
        }
    }

