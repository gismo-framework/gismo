<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 16.08.16
     * Time: 09:47
     */

    namespace Gismo\Component\Partial;


    use Gismo\Component\Application\Assets\GoAssetContainer;
    use Gismo\Component\Application\Assets\GoAssetContainerTrait;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\PhpFoundation\Helper\Mime;
    use Html5\Template\HtmlTemplate;
    use Html5\Template\Node\GoDocumentNode;

    class Page extends Partial implements GoAssetContainer {

        use GoAssetContainerTrait;
        
        private $mTemplateFile;


        public function __construct(DiContainer $di)
        {
            parent::__construct($di, false);
        }

       

        public function setTemplate($filename) {
            $this->mTemplateFile = $filename;
            $this->__asset_container_init($this->mDi, dirname($filename));
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
        
    }