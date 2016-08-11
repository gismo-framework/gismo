<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 07.08.16
     * Time: 14:08
     */    
    
    
    namespace Gismo\Component\Partial\Section;

    
    use Gismo\Component\Partial\Renderable;

    class PlainSection implements Section, Renderable {

        private $mData;
        
        public function __construct(string $data) {
            $this->mData = $data;
        }
        
        public function render($return=false) : string {
            if ($return)
                return $this->mData;
            echo $this->mData;
            return null;
        }

    }