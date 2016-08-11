<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 03.08.16
     * Time: 13:22
     */

    namespace Gismo\Component\Template\Directive;

    
    use Gismo\Component\Template\Directive\Ex\GoContinueLoopException;
    use Gismo\Component\Template\Node\GoElementNode;
    use Gismo\Component\Template\GoTemplateDirectiveBag;

    class GoContinueDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-continue"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return -1;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {
            throw new GoContinueLoopException("Loop continue on line {$node->lineNo}");
        }
    }
