<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 22.08.16
     * Time: 12:41
     */

    namespace Gismo\Component\Application\Assets\Handler;


    use Gismo\Component\Application\Assets\GoAssetSetList;
    use Gismo\Component\PhpFoundation\Type\OrderedList;
    use Html5\FHtml\FHtml;

    class GoCssAssetHandler implements GoAssetHandler
    {


        private $compact;

        public function __construct($compact = true)
        {
            $this->compact = $compact;
        }


        public function renderOneBigFileInclude (GoAssetSetList $list) {
            $t = new FHtml();
            $t->elem(["link @href=? @rel=stylesheet @type=text/css", $list->getAssetLinkUrl("combined.css")]);
            return $t->render([]);



        }

        public function renderSingleInclude (GoAssetSetList $list) {
            $t = new FHtml();
            foreach ($list->__getAllAssetSets() as $assetSet) {
                foreach ($assetSet->getFileList() as $curFile) {
                     $t->elem(["link @href=? @rel=stylesheet @type=text/css", $assetSet->getAssetLinkUrl($curFile)]);
                }
            }
            return $t->render([]);

        }

        public function getCombinedContent (GoAssetSetList $list) {
            $content = "";
            foreach ($list->__getAllAssetSets() as $assetSet) {
                foreach ($assetSet->getFileList() as $curFile) {
                     $content .= "\n" . $assetSet->getAssetContent($curFile);
                }
            }

            if ($this->compact) {
                $content = preg_replace("/(\\s+)/", " ", $content);
            }

            return $content;
        }


        public function getContentType(GoAssetSetList $list)
        {
            return "text/css";
        }
    }