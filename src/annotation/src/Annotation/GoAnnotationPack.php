<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:39
     */

    namespace Gismo\Component\Annotation;


    interface GoAnnotationPack {

        /**
         * Return a Array of Annotation Classnames to be loaded
         *
         * @return string[]
         */
        public function getAnnotationClassNames ();

    }