<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.08.16
 * Time: 05:59
 */

    namespace Gismo\Component\Application\Container;


    interface GoAssetContainer {

        public function getAssetContent($path);

    }