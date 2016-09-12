<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 16.08.16
     * Time: 09:47
     */

    namespace Gismo\Component\Partial;


    use Gismo\Component\Application\Assets\GoAssetContainer;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Html5\Template\HtmlTemplate;

    class Page extends Partial implements GoAssetContainer {

        private $mTemplateFile;

        private $mBindName;

        public function __construct(DiContainer $di)
        {
            parent::__construct($di, false);
        }

        public function __di_set_bindname(string $name) {
            $this->mBindName = $name;
        }

        public function setTemplate($filename) {
            $this->mTemplateFile = $filename;
        }


        public function __invoke($params = [])
        {
            /* @var $parser HtmlTemplate */
            $parser = $this->mDi[HtmlTemplate::class];
            $parser->getExecBag()->expressionEvaluator->register("asset", function (array $arguments, $path) {
                return $this->getAssetLinkUrl($path);
            });
            return $parser->renderHtmlFile($this->mTemplateFile, $params);
        }

        public function getAssetContent(string $path) : string
        {
            if (strpos($path, "..") !== false || strpos($path, "~") !== false)
                throw new \InvalidArgumentException("Invalid path: '$path'. Security violation was reported.");
            return file_get_contents(dirname($this->mTemplateFile) . "/" . $path);
        }

        public function getAssetContentType(string $path = null) : string
        {
            return mime_content_type(dirname($this->mTemplateFile) . "/" . $path);
        }

        public function getAssetLinkUrl(string $path) : string
        {
            $req = $this->mDi[Request::class];
            /* @var $req Request */
            return $req->ROUTE_START_PATH . "/assets/{$this->mBindName}/$path?av={$this->mDi->assetRevision}";
        }
    }