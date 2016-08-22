<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 12:25
     */

    namespace Gismo\Component\Application\Assets\Handler;

    use Gismo\Component\Application\Assets\GoAssetSetList;

    interface GoAssetHandler {


        public function renderOneBigFileInclude (GoAssetSetList $list);

        public function renderSingleInclude (GoAssetSetList $list);

        public function getCombinedContent (GoAssetSetList $list);

        public function getContentType(GoAssetSetList $list);

    }
