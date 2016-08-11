<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 12:28
     */


    namespace Gismo\Component\Template\Directive;


    use Gismo\Component\Template\Expression\GoExpressionEvaluator;

    class GoDirectiveExecBag {

        public function __construct(GoExpressionEvaluator $expressionCompiler) {
            $this->expressionEvaluator = $expressionCompiler;
        }


        /**
         * @var GoExpressionEvaluator
         */
        public $expressionEvaluator;
        
        public $macros = [];

        public $scopePrototype = [];

        public $returnScope = [];
    }