<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 17:58
     */

    namespace Gismo\Component\PhpFoundation\Exception;


    class PfException extends \Exception {

        private $mTemplate = [];

        public function __construct(array $template, $code=null, \Exception $previous=null) {
            $this->mTemplate = $template;
            $message = (string)goString(array_shift($template))->apply($template);
            parent::__construct($message, $code, $previous);
        }

    }