<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 20.08.16
 * Time: 05:36
 */

namespace Gismo\Component\Application\Assets;


use Gismo\Component\Application\Assets\Renderer\GoAssetRenderer;
use Gismo\Component\Application\Assets\Renderer\GoLessAssetRenderer;
use Gismo\Component\Application\Assets\Renderer\GoMimeAssetTenderer;
use Gismo\Component\Application\Assets\Renderer\GoScssAssetRenderer;
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


    private $typeToRenderer = [
            ".less" => GoLessAssetRenderer::class,
            ".scss" => GoScssAssetRenderer::class
    ];

    public function __construct($rootDir, Context $context)
    {
        $this->mContext = $context;
        $this->mRootDir = $rootDir;
    }


    public function __di_set_bindname(string $bindName) {
        $this->mBindName = $bindName;
    }


    /**
     * @param string|string[] $filter
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

        $useRenderer = GoMimeAssetTenderer::class;
        if (preg_match("/(\\.[a-z]+)$/", $path, $matches)) {
            $ext = $matches[1];
            if (isset ($this->typeToRenderer[$ext]))
                $useRenderer = $this->typeToRenderer[$ext];
        }

        $renderer = new $useRenderer();

        return $renderer->getContent($this->mRootDir . "/". $path);
    }

    public function getAssetContentType(string $path = null) : string
    {
        if (strpos($path, "..") !== false || strpos($path, "~") !== false)
            throw new \InvalidArgumentException("Invalid path: '$path'. Security violation was reported.");

        $useRenderer = GoMimeAssetTenderer::class;
        if (preg_match("/(\\.[a-z]+)$/", $path, $matches)) {
            $ext = $matches[1];
            if (isset ($this->typeToRenderer[$ext]))
                $useRenderer = $this->typeToRenderer[$ext];
        }

        $renderer = new $useRenderer();

        return $renderer->getContentType($path);
    }
}