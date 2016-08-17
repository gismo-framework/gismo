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
    use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Route\GoDiService_Route_Property;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("METHOD")
     */
    class Filter implements GoApplicationMethodAnnotation{

        /**
         * @Required()
         * @var string
         */
        public $target;

        /**
         * @var int
         */
        public $priority = 0;


        public function registerClass($myClassName, $myMethodName, Context $context, array &$builderScope) {
            $context[$this->target] = $context->filter(function ($§§input) use ($myClassName, $myMethodName, $context) {
                return $context([$context[$myClassName], $myMethodName], ["§§input"=>$§§input]);
            }, $this->priority);
        }
    }


