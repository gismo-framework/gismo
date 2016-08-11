<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.08.16
     * Time: 21:59
     */


    namespace Gismo\Component\Plugin;


    use Gismo\Component\Application\Context;

    interface Plugin {

        public function onContextInit(Context $context);

    }