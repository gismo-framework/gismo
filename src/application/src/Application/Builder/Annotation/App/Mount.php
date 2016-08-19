<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:44
     */

    namespace Gismo\Component\Application\Builder\Annotation\App;
    use Doctrine\Common\Annotations\Annotation\Attribute;
    use Doctrine\Common\Annotations\Annotation\Attributes;
    use Doctrine\Common\Annotations\Annotation\Required;
    use Doctrine\Common\Annotations\Annotation\Target;
    use Gismo\Component\Application\Builder\GoApplicationClassAnnotation;
    use Gismo\Component\Application\Context;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("CLASS")
     */
    class Mount implements GoApplicationClassAnnotation {

        /**
         * @var string
         */
        public $route;

        /**
         * @var string
         */
        public $api;


        public function registerClass($myClassName, Context $context, array &$builderScope)
        {
            $builderScope["mount_{$myClassName}"] = [
                "route" => $this->route,
                "api"   => $this->api
            ];
        }
    }
