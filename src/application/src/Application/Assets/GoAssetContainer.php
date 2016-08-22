<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.08.16
 * Time: 05:59
 */

    namespace Gismo\Component\Application\Assets;


    interface GoAssetContainer {

        public function getAssetContent(string $path) : string;

        public function getAssetContentType(string $path=null) : string;

        public function getAssetLinkUrl (string $path) : string;

    }