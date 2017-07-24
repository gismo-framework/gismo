<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 24.07.17
     * Time: 19:06
     */

    namespace Gismo\Component\Application\Assets;


    use Gismo\Component\Application\Context;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\PhpFoundation\Helper\Mime;

    trait GoAssetContainerTrait {

        private $mContext;
        private $mPath;
        private $mBindName;

        public function __di_set_bindname(string $bindName) {
            $this->mBindName = $bindName;
        }

        protected function __asset_container_init (DiContainer $context, $path) {
            $this->mContext = $context;
            $this->mPath = $path;
        }

        
        protected function _getAssetPath () {
            return $this->mPath;
        }
        
        public function getBindName () {
            return $this->mBindName;
        }
        
        public function getAssetLinkUrl(string $path) : string {
            $req = $this->mContext[Request::class];
            /* @var $req Request */
            return $req->ROUTE_START_PATH . "/assets/{$this->mBindName}/$path?av={$this->mContext->assetRevision}";
        }

        public function getAssetContent(string $path, &$contentType) : string {
            if (strpos($path, "..") !== false || strpos($path, "~") !== false)
                throw new \InvalidArgumentException("Invalid path: '$path'. Security violation was reported.");

            $contentType = Mime::GetMimeType($path);
            return file_get_contents($this->mPath . "/" . $path);
        }
        
        
    }