<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 17:22
     */

    namespace Gismo\Component\Application\Assets\Renderer;



    class GoLessAssetRenderer implements GoAssetRenderer
    {

        public function getContentType($filename) : string
        {
            return "text/css";
        }

        public function getContent($filename) : string
        {
            $s = new \Less_Parser();
            return $s->parseFile($filename)->getCss();
        }
    }