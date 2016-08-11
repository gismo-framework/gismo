<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 22:42
 */

    namespace Gismo\Component\Template\Directive;
    

    use Gismo\Component\Template\Node\GoElementNode;
    use Gismo\Component\Template\GoTemplateDirectiveBag;

    interface GoDirective {

        public function register(GoTemplateDirectiveBag $bag);
        
        public function getPriority () : int;

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag);
    }