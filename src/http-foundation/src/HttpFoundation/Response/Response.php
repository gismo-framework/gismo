<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 13:25
     */


    namespace Gismo\Component\HttpFoundation\Request;

    class Response {

        private $content;
        private $contentType = null;

        public function __construct(string $content)
        {
            $this->content = $content;
        }



        public function cache($ttl) {

        }

        public function contentType($contentType) : self {
            $this->contentType = $contentType;
            return $this;
        }

        public function header() {

        }

        public function cookie() {

        }

        public function send() {
            if ($this->contentType !== null)
                header("Content-Type: {$this->contentType}");
            echo $this->content;
        }

    }