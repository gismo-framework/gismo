<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 12:23
     */

    namespace Gismo\Component\Template\Directive;

    
    use Gismo\Component\Template\Node\GoElementNode;
    use Gismo\Component\Template\GoTemplateDirectiveBag;

    class GoDumpDirective implements GoDirective 
    {

        public function register(GoTemplateDirectiveBag $bag) 
        {
            $bag->elemToDirective["go-dump"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int 
        {
            return 998;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag) 
        {
            $data = $scope;
            if (isset ($node->attributes["name"])) {
                 $data = $execBag->expressionEvaluator->eval($node->attributes["name"], $scope);
            }
            return "<pre><div>Dump:</div><div>" . print_r($data, true) . "</div></pre>";
        }
    }

