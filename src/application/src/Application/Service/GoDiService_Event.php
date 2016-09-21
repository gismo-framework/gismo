<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 16:20
     */

    namespace Gismo\Component\Application\Service;


    use Gismo\Component\Partial\Event;

    trait GoDiService_Event
    {

        private function __di_init_service_event() {

        }

        public function on ($bindName, callable $fn) : self {
            if (!isset ($this[$bindName]))
            $this[$bindName] = $this->filter(function (Event $§§) use ($fn) {
                $§§->addListener($fn);
            });
            return $this;
        }


        public function trigger($bindName, Event $event = null) : self {
            if ($event === null)
                $event = new Event();
            unset ($this[$bindName]);

            $this[$bindName] = $this->constant($event);
            $this[$bindName]->trigger($this);
            return $this;
        }

    }