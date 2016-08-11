<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 17:43
     */


    namespace Gismo\Component\Template\Directive;

    

    use Gismo\Component\Template\Node\GoElementNode;
    use Gismo\Component\Template\GoTemplateDirectiveBag;

    class GoDefineDirective implements GoDirective {

        public function register(GoTemplateDirectiveBag $bag) {
            $bag->elemToDirective["go-define"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int {
            return 1;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag) {
            
        }
    }

