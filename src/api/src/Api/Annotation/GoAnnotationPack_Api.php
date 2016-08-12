<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:24
     */

    namespace Gismo\Component\Api\Annotation;




    use Gismo\Component\Annotation\GoAnnotationPack;

    class GoAnnotationPack_Api implements GoAnnotationPack {


        /**
         * Return a Array of Annotation Classnames to be loaded
         *
         * @return string[]
         */
        public function getAnnotationClassNames() {
            return [
                Api::class,
                Requires::class
            ];
        }
    }