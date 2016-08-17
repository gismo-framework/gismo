<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 14:03
     */

    namespace Gismo\Component\Application\Builder;


    use Gismo\Component\Application\Context;

    interface GoApplicationMethodAnnotation {

        public function registerClass ($myClassName, $myMethodName, Context $context, array &$builderScope);
    }