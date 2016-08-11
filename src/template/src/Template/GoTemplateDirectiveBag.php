<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 23:56
     */

    namespace Gismo\Component\Template;
    
    use Gismo\Component\Template\Directive\GoDirective;
    use Gismo\Component\Template\Directive\GoInlineTextDirective;

    class GoTemplateDirectiveBag {

        /**
         * @var GoDirective[]
         */
        public $elemToDirective = [];

        /**
         * @var GoDirective[]
         */
        public $attrToDirective = [];

        /**
         * @var GoInlineTextDirective
         */
        public $textDirective = null;

        /**
         * @var GoDirective[]
         */
        public $directiveClassNameMap = [];

    }