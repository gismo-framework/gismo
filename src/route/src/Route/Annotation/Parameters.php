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
     * @Target("METHOD")
     */
    class Parameters {

        /**
         * @Required()
         * @var array<Parameter>
         */
        public $params;


    }
