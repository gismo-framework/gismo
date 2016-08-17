<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 16:33
     */

    namespace Gismo\Component\Application\Builder\Annotation\App;
    use Doctrine\Common\Annotations\Annotation\Attribute;
    use Doctrine\Common\Annotations\Annotation\Attributes;
    use Doctrine\Common\Annotations\Annotation\Enum;
    use Doctrine\Common\Annotations\Annotation\Required;
    use Doctrine\Common\Annotations\Annotation\Target;
    use Gismo\Component\Annotation\GoAnnotations;
    use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Di\DiCallChain;
    use Gismo\Component\HttpFoundation\Request\Request;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("ALL")
     */
    class Parameter implements GoApplicationMethodAnnotation{

        /**
         * @Required()
         * @var string
         */
        public $name;


        /**
         * @Required()
         * @Enum({"ROUTE", "GET", "BODY", "POST", "FILE", "COOKIE"})
         */
        public $source;

        /**
         * @var string
         */
        public $sourceName;


        public function registerClass($myClassName, $myMethodName, Context $context, array &$builderScope) {
            if (isset ($builderScope["PARAMS_{$myClassName}::{$myMethodName}"]))
                return; // Already parsed
            $builderScope["PARAMS_{$myClassName}::{$myMethodName}"] = true;

            $route = GoAnnotations::ForMethod($myClassName, $myMethodName, Route::class);
            if ( ! $route instanceof Route)
                throw new \InvalidArgumentException("Annotation @Parameter requires @Action.");
            $routeBindName = $route->getBindName($myClassName, $myMethodName);

            $params = [];
            $annos = GoAnnotations::ForMethod($myClassName, $myMethodName);
            foreach ($annos as $anno) {
                if ( ! $anno instanceof Parameter) {
                    continue;
                }
                if ($this->sourceName === null)
                    $this->sourceName = $this->name;
                $params[] = $anno;
            }

            $context[$routeBindName] = $context->filter(function (DiCallChain $§§input) use ($context, $params)  {
                $§§input[9000] = function ($§§parameters, Request $request) use ($params) {
                      foreach ($params as $param) {
                          /* @var $param \Gismo\Component\Application\Builder\Annotation\App\Parameter */
                          $value = null;
                          $sourceName = $param->sourceName;
                          if ($sourceName === null)
                              $sourceName = $param->name;
                          switch ($param->source) {
                              case "GET":
                                  $value = (string)$request->GET[$sourceName]->getValue();
                                  break;
                              case "POST":
                                  $value = (string)$request->POST[$sourceName]->getValue();
                                  break;
                              case "COOKIE":
                                  $value = (string)$request->COOKIE[$sourceName]->getValue();
                                  break;

                          }
                          $§§parameters[$param->name] = $value;
                      }
                      return $§§parameters;
                };
            });


        }
    }
