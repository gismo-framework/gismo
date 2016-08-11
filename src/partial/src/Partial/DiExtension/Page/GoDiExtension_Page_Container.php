<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 14.08.16
     * Time: 16:00
     */    
    
    namespace Gismo\Component\Partial\DiExtension\Page;
    
    
    use Gismo\Component\Di\DiContainer;

    class GoDiExtension_Page_Container {

        /**
         * @var DiContainer
         */
        private $mDi;
        
        public function __construct(DiContainer $di) {
            $this->mDi = $di;
        }

    }
