<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.08.16
 * Time: 20:12
 */namespace Gismo\Component\Application\Builder\Annotation\App;
use Doctrine\Common\Annotations\Annotation\Target;
use Gismo\Component\Annotation\GoAnnotations;
use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
use Gismo\Component\Application\Container\GoAction;
use Gismo\Component\Application\Context;
use Gismo\Component\Di\Core\DiCallStack;
use Gismo\Component\Di\DiCallChain;

/**
 * Class Route
 * @package Gismo\Component\Route\Annotations
 *
 * @Annotation
 * @Target("METHOD")
 */
class ContextInit implements GoApplicationMethodAnnotation {



    public function registerClass($myClassName, $myMethodName, Context $context, array &$builderScope) {
        $obj = $context[$myClassName];
        $context->__invoke([$obj, $myMethodName]);
    }

}