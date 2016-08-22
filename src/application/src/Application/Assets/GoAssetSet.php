<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.08.16
 * Time: 05:36
 */

namespace Gismo\Component\Application\Assets;


use Gismo\Component\Application\Assets\Renderer\GoAssetRenderer;
use Gismo\Component\Application\Context;
use Gismo\Component\HttpFoundation\Request\Request;

class GoAssetSet implements GoAssetContainer
{

    private $mRootDir;
    private $mBindName;

    /**
     * @var Context
     */
    private $mContext;
    private $mIncludeFilter = "*.*";

    /**
     * @var GoAssetRenderer
     */
    private $assetRenderer;

    public function __construct($bindName, $rootDir, Context $context, GoAssetRenderer $assetRenderer)
    {
        $this->mContext = $context;
        $this->mBindName = $bindName;
        $this->mRootDir = $rootDir;
        $this->assetRenderer = $assetRenderer;
    }


    /**
     * @param string $filter
     * @return $this
     */
    public function include($filter = "*.*") : self {
        $this->mIncludeFilter = $filter;
        return $this;
    }


    public function getFileList() {
        return goFsDir($this->mRootDir)->scanRecFile($this->mIncludeFilter);
    }

    public function getAssetLinkUrl(string $path) : string {
        $req = $this->mContext[Request::class];
        /* @var $req Request */
        return $req->ROUTE_START_PATH . "/assets/{$this->mBindName}/$path?av={$this->mContext->assetRevision}";
    }


    public function getAssetContent(string $path) : string {
        if (strpos($path, "..") !== false || strpos($path, "~") !== false)
            throw new \InvalidArgumentException("Invalid path: '$path'. Security violation was reported.");
        return $this->assetRenderer->getContent($this->mRootDir . "/". $path);
    }

    public function getAssetContentType(string $path = null) : string
    {
        if (strpos($path, "..") !== false || strpos($path, "~") !== false)
            throw new \InvalidArgumentException("Invalid path: '$path'. Security violation was reported.");
        return $this->assetRenderer->getContentType($path);
    }
}