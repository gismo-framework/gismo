<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.08.16
 * Time: 05:28
 */

namespace Gismo\Component\Application\Assets;


use Gismo\Component\Application\Assets\Handler\GoAssetHandler;
use Gismo\Component\Application\Context;
use Gismo\Component\HttpFoundation\Request\Request;
use Gismo\Component\PhpFoundation\Type\OrderedList;
use Html5\FHtml\FHtml;

class GoAssetSetList implements \ArrayAccess, GoAssetContainer
{

    private $mContext;
    private $mList;
    private $bindName;
    private $assetHandler;

    public function __construct($bindName, Context $context, GoAssetHandler $assetHandler)
    {
        $this->bindName = $bindName;
        $this->mContext = $context;
        $this->mList = new OrderedList();
        $this->assetHandler = $assetHandler;
    }


    const RENDERMODE_DEBUG_INCLUDE = "RENDERMODE_DEBUG_INCLUDE";
    const BIGFILE = "BIGFILE";

    private $mRenderMode = self::BIGFILE;


    public function getAssetLinkUrl(string $path) : string
    {
        $req = $this->mContext[Request::class];
        /* @var $req Request */
        return $req->ROUTE_START_PATH . "/assets/{$this->bindName}/$path?av={$this->mContext->assetRevision}";
    }

    /**
     * @return GoAssetSet[]
     */
    public function __getAllAssetSets () :array {
        $sets = [];
        $this->mList->each(function ($what) use (&$sets){
            $sets[] = $this->mContext[$what];
        });
        return $sets;
    }


    public function __invoke()
    {
        if ($this->mContext->debug === true) {
            return $this->assetHandler->renderSingleInclude($this);
        }
        return $this->assetHandler->renderOneBigFileInclude($this);
    }



    public function offsetExists($offset)
    {
        return new \InvalidArgumentException("offsetExists() not available on GoAssetSetList");

    }


    public function offsetGet($offset)
    {
        return new \InvalidArgumentException("offsetGet() not available on GoAssetSetList");
    }


    public function offsetSet($offset, $value)
    {
        if ( ! is_string($value))
            throw new \InvalidArgumentException("Value must be bindName");
        if ( ! isset ($this->mContext[$value]))
            throw new \InvalidArgumentException("AssetList '$value' not registered.");
        $this->mList->add($offset, $value);
    }


    public function offsetUnset($offset)
    {
        return new \InvalidArgumentException("offsetExists() not available on GoAssetSetList");
    }

    public function getAssetContent(string $path) : string
    {
        return $this->assetHandler->getCombinedContent($this);
    }

    public function getAssetContentType(string $path=null) : string
    {
        return $this->assetHandler->getContentType($this);
    }
}