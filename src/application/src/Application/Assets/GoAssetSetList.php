<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.08.16
 * Time: 05:28
 */

namespace Gismo\Component\Application\Container;


use Gismo\Component\Application\Context;
use Gismo\Component\HttpFoundation\Request\Request;
use Gismo\Component\PhpFoundation\Type\OrderedList;
use Html5\FHtml\FHtml;

class GoAssetSetList implements \ArrayAccess, GoAssetContainer
{

    private $mContext;
    private $mList;
    private $bindName;

    public function __construct($bindName, Context $context)
    {
        $this->bindName = $bindName;
        $this->mContext = $context;
        $this->mList = new OrderedList();
    }


    const RENDERMODE_DEBUG_INCLUDE = "RENDERMODE_DEBUG_INCLUDE";
    const BIGFILE = "BIGFILE";

    private $mRenderMode = self::BIGFILE;


    private function getAssetLinkUrl($path) {
        $req = $this->mContext[Request::class];
        /* @var $req Request */
        return $req->ROUTE_START_URL . "/assets/{$this->bindName}/$path?av=";
    }


    public function __invoke()
    {
        $tpl = new FHtml();
        if ($this->mRenderMode === self::RENDERMODE_DEBUG_INCLUDE) {
            $tpl->elem(["link @url=? @type=text/css", $this->getAssetLinkUrl("combined.css")]);
        } else {
            $this->mList->each(function (GoAssetSet $assetSet) use ($tpl) {
                foreach ($assetSet->getFileList() as $file) {
                    // Append all Files from AssetSet - but link them to their own bind-url
                    $tpl->elem(["link @url=? @type=text/css", $assetSet->getAssetLinkUrl($file)]);
                }
            });
        }
        return $tpl->render();
    }



    public function offsetExists($offset)
    {
    }


    public function offsetGet($offset)
    {
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
    }

    public function getAssetContent($path)
    {
        $ret = "";
        $this->mList->each(function (GoAssetSet $assetSet) use (&$ret) {
            foreach ($assetSet->getFileList() as $file) {
                $ret .= $assetSet->getAssetContent($file);
            }
        });
        return $ret; // One big file.
    }
}