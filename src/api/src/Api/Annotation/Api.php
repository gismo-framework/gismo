<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:32
     */


    namespace Gismo\Component\Api\Annotation;
    use Doctrine\Common\Annotations\Annotation\Attribute;
    use Doctrine\Common\Annotations\Annotation\Attributes;
    use Doctrine\Common\Annotations\Annotation\Required;
    use Doctrine\Common\Annotations\Annotation\Target;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("METHOD")
     */
    class Api {

        /**
         * Default: auto
         *
         * @var string
         */
        public $bind;

    }


