<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 05.08.16
 * Time: 21:48
 */


    namespace Gismo\Component\Partial\Page;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Partial\Renderable;

    abstract class AbstractPage implements Renderable{


        /**
         * @var DiContainer
         */
        protected $mDi;
        
        protected $mSections = [];


        protected $mData = [];

        
        private final function __construct(DiContainer $container) {
            $this->mDi = $container;
            $this->mSections = $this->__get_sections();
        }

        abstract public function __get_sections() : array ;


        public function __get($name) {
            if ( ! isset ($this->mSections[$name]))
                throw new \InvalidArgumentException("Partial '$name' not existing.");
            if ( ! isset ($this->mData[$name]))
                $this->mData[$name] = new $this->mSections[$name]($this->mDi);
            return $this->mData[$name];
        }

        public function __set($name, $value) {
            throw new \InvalidArgumentException("Invalid getter call.");
        }

        public function __isset($name) {
            return isset ($this->mData[$name]);
        }

    }