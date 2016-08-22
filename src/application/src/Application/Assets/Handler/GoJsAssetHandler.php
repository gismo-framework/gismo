<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 16:17
     */

    namespace Gismo\Component\Application\Assets\Handler;


    use Gismo\Component\Application\Assets\GoAssetSetList;
    use Html5\FHtml\FHtml;

    class GoJsAssetHandler implements GoAssetHandler
    {
        public function renderOneBigFileInclude (GoAssetSetList $list) {
            $t = new FHtml();
            $t->elem(["script @src=? @type=text/javascript", $list->getAssetLinkUrl(null)]);
            return $t->render([]);



        }

        public function renderSingleInclude (GoAssetSetList $list) {
            $t = new FHtml();
            foreach ($list->__getAllAssetSets() as $assetSet) {
                foreach ($assetSet->getFileList() as $curFile) {
                     $t->elem(["script @src=? @type=text/javascript", $assetSet->getAssetLinkUrl($curFile)]);
                }
            }
            return $t->render([]);

        }

        public function getCombinedContent (GoAssetSetList $list) {
            $content = [];
            foreach ($list->__getAllAssetSets() as $assetSet) {
                foreach ($assetSet->getFileList() as $curFile) {
                     $content .= "\n" . $assetSet->getAssetContent($curFile);
                }
            }
            return $content;
        }


        public function getContentType(GoAssetSetList $list)
        {
            return "text/javascript";
        }
    }