<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 13:47
     */

    namespace Gismo\Component\Application\Assets\Renderer;


    class GoCssAssetRenderer implements GoAssetRenderer {

        public function getContentType($filename) : string
        {
            return "text/css";
        }

        public function getContent($filename) : string
        {
            return file_get_contents($filename);
        }

    }