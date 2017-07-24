<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 19.08.16
 * Time: 20:56
 */

namespace Gismo\Component\Application\Container;


use Gismo\Component\Application\Assets\GoAssetContainer;
use Gismo\Component\Application\Assets\GoAssetContainerTrait;
use Gismo\Component\Di\DiCallChain;
use Gismo\Component\Di\DiContainer;
use Gismo\Component\HttpFoundation\Request\Request;
use Gismo\Component\PhpFoundation\Helper\Mime;
use Html5\Template\HtmlTemplate;

class GoTemplate extends DiCallChain implements GoAssetContainer
{
    use GoAssetContainerTrait;
    
    protected $mTemplateFile;
    protected $bindName;

    public function __construct(DiContainer $di, string $filename, string $bindName)
    {
        parent::__construct($di, false);
        $this->mTemplateFile = $filename;
        $this->bindName = $bindName;

        $this->__di_set_bindname($bindName);
        $this->__asset_container_init($di, dirname($filename));

        $this[0] = function ($§§parameters, HtmlTemplate $parser) {

            $parser->getExecBag()->expressionEvaluator->register("asset", function (array $arguments, $path) {
                return $this->getAssetLinkUrl($path);
            });

            return $parser->renderHtmlFile($this->mTemplateFile, $§§parameters);
        };
    }

    
}