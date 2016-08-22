<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 13:44
     */


    namespace Gismo\Component\Application\Assets\Renderer;


    interface GoAssetRenderer {

        public function getContentType($filename) : string;

        public function getContent($filename) : string;

    }