<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 22:06
 */

    namespace Gismo\Component\Template\Node;


    use Gismo\Component\Template\Directive\GoDirectiveExecBag;
    use Gismo\Component\Template\Directive\GoInlineTextDirective;

    class GoTextNode implements GoNode {

        public $text;
        public $preWhiteSpace = "";

        /**
         * @var GoInlineTextDirective
         */
        private $mInlineTextDirective;
        
        public function __construct($text, GoInlineTextDirective $inlineTextDirective=null)
        {
            $this->text = $text;
            $this->mInlineTextDirective = $inlineTextDirective;
        }


        public function render (array $scope, GoDirectiveExecBag $execBag) {
            $text = $this->text;
            if ($this->mInlineTextDirective !== null) {
                $text = $this->mInlineTextDirective->execText($this->text, null, $scope, $output, $execBag);
            }

            return $this->preWhiteSpace . $text;
        }

        public function run (array $scope, GoDirectiveExecBag $execBag) {
            return $this->render($scope, $execBag);
        }

    }