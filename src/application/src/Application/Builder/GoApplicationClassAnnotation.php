<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 14:01
     */

    namespace Gismo\Component\Application\Builder;


    use Gismo\Component\Application\Context;

    interface GoApplicationClassAnnotation {

        public function registerClass ($myClassName, Context $context, array &$builderScope);

    }