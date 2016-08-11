<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 14.08.16
     * Time: 15:57
     */


    namespace Gismo\Component\Partial\DiExtension;


    use Gismo\Component\Partial\DiExtension\Page\GoDiExtension_Page_Container;

    /**
     * Class GoDiExtension_Page_Trait
     * @package Gismo\Component\Partial\DiExtension
     *
     * @property GoDiExtension_Page_Container $page
     */
    trait GoDiExtension_Page_Trait {


        
        private function __enable_page () {
            $this->page = $this->constant(new GoDiExtension_Page_Container($this));
        }
        

    }
