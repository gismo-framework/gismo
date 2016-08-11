<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 15:41
     */

    namespace Gismo\Component\Template\Node;


    use Gismo\Component\Template\Directive\GoDirectiveExecBag;

    interface GoNode {

        public function render(array $scope, GoDirectiveExecBag $execBag);

        public function run(array $scope, GoDirectiveExecBag $execBag);

    }