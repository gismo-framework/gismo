<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.08.16
 * Time: 20:56
 */

namespace Gismo\Component\Application\Container;


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

                return $this->getAssetLink($path);
            });
            return $parser->renderHtmlFile($this->mTemplateFile, $§§parameters);
        };
    }


    public function getAssetLink (string $path) {
        $req = $this->mDi[Request::class];
        /* @var $req Request */
        return $req->ROUTE_START_URL . "/assets/{$this->bindName}/$path?av=";
    }


    public function getAssetContent($path)
    {
        return file_get_contents(dirname($this->mTemplateFile) . "/" . implode("/", $path));
    }
}