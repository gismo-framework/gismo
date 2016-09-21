<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 16:14
     */

    namespace Gismo\Component\Partial;


    use Gismo\Component\Di\Core\Invokeable;
    use Gismo\Component\Di\DiContainer;

    class Event
    {

        private $listeners = [];

        public function addListener (callable $fn) {
            $this->listeners[] = $fn;
        }


        public function trigger(DiContainer $container) {

            foreach ($this->listeners as $cur) {
                $container($cur, [Event::class => $this, get_class($this) => $this]);
            }
        }
    }