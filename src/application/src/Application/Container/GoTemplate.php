<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.08.16
 * Time: 20:56
 */

namespace Gismo\Component\Application\Container;


use Gismo\Component\Application\Assets\GoAssetContainer;
use Gismo\Component\Di\DiCallChain;
use Gismo\Component\Di\DiContainer;
use Gismo\Component\HttpFoundation\Request\Request;
use Html5\Template\HtmlTemplate;

class GoTemplate extends DiCallChain implements GoAssetContainer
{

    protected $mTemplateFile;
    protected $bindName;

    public function __construct(DiContainer $di, string $filename, string $bindName)
    {
        parent::__construct($di, false);
        $this->mTemplateFile = $filename;
        $this->bindName = $bindName;

        $this[0] = function ($§§parameters, HtmlTemplate $parser) {

            $parser->getExecBag()->expressionEvaluator->register("asset", function (array $arguments, $path) {
                return $this->getAssetLinkUrl($path);
            });

            return $parser->renderHtmlFile($this->mTemplateFile, $§§parameters);
        };
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
        return $req->ROUTE_START_PATH . "/assets/{$this->bindName}/$path?av={$this->mDi->assetRevision}";
    }
}