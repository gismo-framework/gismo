<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 17:21
     */

    namespace Gismo\Component\JsBridge;


    use Gismo\Component\Application\Assets\GoAssetContainer;
    use Gismo\Component\Application\Assets\GoAssetSet;
    use Gismo\Component\HttpFoundation\Request\Request;

    trait GoDiService_JsBridge
    {

        private function __di_init_service_jsbridge() {
            $this["assets.jsbridge"] = $this->service(
                    function () {
                        $set = new GoAssetSet(null, $this);
                        $set->setVirtualAsset("jsbridge.js", "text/javascript", function (Request $request) {
                            $content = file_get_contents(__DIR__ . "/js/jsbridge.js");
                            $content = str_replace("%%GISMO_ROOT_URL%%", $request->ROUTE_START_PATH, $content);
                            return $content;
                        });
                        return $set;
                    }
            );
        }

    }