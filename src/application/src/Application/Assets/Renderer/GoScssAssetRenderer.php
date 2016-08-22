<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 17:22
     */

    namespace Gismo\Component\Application\Assets\Renderer;


    use Leafo\ScssPhp\Server;

    class GoScssAssetRenderer implements GoAssetRenderer
    {

        public function getContentType($filename) : string
        {
            return "text/css";
        }

        public function getContent($filename) : string
        {
            $s = new Server(dirname($filename), "/tmp/scss_temp");
            return $s->compileFile($filename);
        }
    }