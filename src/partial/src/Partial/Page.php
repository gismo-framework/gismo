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
    use Gismo\Component\PhpFoundation\Helper\Mime;
    use Html5\Template\HtmlTemplate;
    use Html5\Template\Node\GoDocumentNode;

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


        /**
         * @var GoDocumentNode
         */
        private $mParsedTemplate = null;

        public function __invoke($params = [])
        {
            /* @var $parser HtmlTemplate */
            $parser = $this->mDi[HtmlTemplate::class];
            $parser->getExecBag()->expressionEvaluator->register("asset", function (array $arguments, $path, $template=null) {
                if ($template !== null) {
                    $tpl = $this->mDi[$template];
                    if ( ! $tpl instanceof GoAssetContainer)
                        throw new \InvalidArgumentException("asset('$path', '$template'): Template '$template' is not a AssetContianer.");
                    return $tpl->getAssetLinkUrl($path);
                }
                return $this->getAssetLinkUrl($path);
            });
            if ( $this->mParsedTemplate === null) {
                $this->mParsedTemplate = $parser->buildFile($this->mTemplateFile);
            }
            return $this->mParsedTemplate->run($params);
        }

        public function getAssetContent(string $path) : string
        {
            if (strpos($path, "..") !== false || strpos($path, "~") !== false)
                throw new \InvalidArgumentException("Invalid path: '$path'. Security violation was reported.");
            $filename = dirname($this->mTemplateFile) . "/" . $path;
            if ( ! file_exists($filename)) {
                throw new \Exception("File not existing on local disk: $filename");
            }

            return file_get_contents($filename);
        }

        public function getAssetContentType(string $path = null) : string
        {
            return Mime::GetMimeType($path);
        }

        public function getAssetLinkUrl(string $path) : string
        {
            $req = $this->mDi[Request::class];
            /* @var $req Request */
            return $req->ROUTE_START_PATH . "/assets/{$this->mBindName}/$path?av={$this->mDi->assetRevision}";
        }
    }