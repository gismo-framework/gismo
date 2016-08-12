<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 16:33
     */

    namespace Gismo\Component\Route\Annotation;
    use Doctrine\Common\Annotations\Annotation\Attribute;
    use Doctrine\Common\Annotations\Annotation\Attributes;
    use Doctrine\Common\Annotations\Annotation\Enum;
    use Doctrine\Common\Annotations\Annotation\Required;
    use Doctrine\Common\Annotations\Annotation\Target;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("ANNOTATION")
     */
    class Parameter {

        /**
         * @Required()
         * @var string
         */
        public $name;


        /**
         * @Enum("ROUTE", "GET", "BODY", "POST")
         */
        public $source;

        /**
         * @var string
         */
        public $sourceName;



    }
