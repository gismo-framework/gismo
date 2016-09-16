<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 16.09.16
     * Time: 12:50
     */

    namespace Gismo\Component\Application\Assets\Renderer;


    use Gismo\Component\PhpFoundation\Helper\Mime;

    class GoMimeAssetTenderer implements GoAssetRenderer
    {
        public function getContentType($filename) : string
        {
            return Mime::GetMimeType($filename);
        }

        public function getContent($filename) : string
        {
            return file_get_contents($filename);
        }
    }