<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:32
     */


    namespace Gismo\Component\Application\Builder\Annotation\App;
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
     * @Target("METHOD|CLASS")
     */
    class Requires {

        /**
         *
         * @var string
         */
        public $role;

        /**
         *
         *
         * @var array<string>
         */
        public $permission;

        /**
         * Classname of a method used to check Access
         *
         * @var string
         */
        public $checker;

        /**
         * @Enum("RESTRICT", "ALLOW")
         */
        public $default = "RESTRICT";

    }


