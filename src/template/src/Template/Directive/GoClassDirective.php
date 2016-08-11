<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 17:21
     */

    namespace Gismo\Component\Template\Directive;
    
    use Gismo\Component\Template\Node\GoElementNode;
    use Gismo\Component\Template\GoTemplateDirectiveBag;

    class GoClassDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["go-class"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }
        
        public function getPriority() : int
        {
            return -990;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {
            $stmt = $node->attributes["go-class"];

            $defClasses = [];
            if (isset ($node->attributes["class"])) {
                $defClasses = explode(" ", $node->attributes["class"]);
            }

            $data = $execBag->expressionEvaluator->yaml($stmt, $scope);
            foreach ($data as $key => $value) {
                if ($value == true) {
                    $defClasses[] = $key;
                }
            }
            
            $clone = clone $node;
            $clone->attributes["go-class"] = "";
            $clone->attributes["class"] = implode(" ", $defClasses);
            
            return $clone;
        }
    }
    
