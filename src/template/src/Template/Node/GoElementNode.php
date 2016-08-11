<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 21:12
 */


    namespace Gismo\Component\Template\Node;

  

    use Gismo\Component\Template\Directive\GoDirective;
    use Gismo\Component\Template\Directive\GoDirectiveExecBag;

    class GoElementNode implements GoNode {
        public $parent = null;

        public $name;
        public $lineNo;
        public $attributes = [];
        public $preWhiteSpace = "";
        public $postWhiteSpace = "";

        public $isEmptyElement = false;

        /**
         * @var GoDirective[]
         */
        public $useDirectives = [];

        public $childs = [];


        public function useDirective(GoDirective $directive) {
            $this->useDirectives[$directive->getPriority()] = $directive;
        }

        public function postInit () {
            krsort($this->useDirectives, SORT_NUMERIC);
        }


        public function render(array $scope, GoDirectiveExecBag $execBag) {
            $ret = "{$this->preWhiteSpace}<{$this->name}";
            $attrs = [];
            foreach ($this->attributes as $name=>$val) {
                $attrs[] = "{$name}=\"{$val}\"";
            }
            if (count ($attrs) > 0)
                $ret .= " " . implode(" ", $attrs);

            if ($this->isEmptyElement) {
                $ret .= "/>";
                return $ret;
            }

            $ret .= ">";

            foreach ($this->childs as $child) {
                $ret .= $child->run($scope, $execBag);
            }
            $ret .= "{$this->postWhiteSpace}</{$this->name}>";
            return $ret;
        }

        public function run(array $scope, GoDirectiveExecBag $execBag) {
            $output = null;

            $curNode = clone $this;
            foreach ($this->useDirectives as $curDirective) {
                $curNode = $curDirective->exec($curNode, $scope, $output, $execBag);
                if ($curNode === false) {
                    return null;
                }
                if ($curNode === true) {
                    return $output;
                }
                if (is_string($curNode)) {
                    return $curNode;
                }
            }
            if ($curNode instanceof GoElementNode)
                $output = $curNode->render($scope, $execBag);

            return $output;
        }

    }