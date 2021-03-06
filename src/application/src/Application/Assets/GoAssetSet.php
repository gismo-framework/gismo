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
    use GoAssetContainerTrait;
    
    private $mRootDir;

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
    

    /**
     * @param string|string[] $filter
     * @return $this
     */
    public function include($filter = "*.*") : self {
        $this->mIncludeFilter = $filter;
        return $this;
    }


    public function getFileList() {
        $files = [];
        if ($this->mRootDir !== null)
            $files = goFsDir($this->mRootDir)->scanRecFile($this->mIncludeFilter);
        foreach ($this->mVirtualAssets as $path => $val) {
            if ( ! in_array($path, $files))
                $files[] = $path;
        }
        return $files;
    }

    

    private $mVirtualAssets = [];


    public function setVirtualAsset (string $path, string $contentType, callable $fn) : self {
        $this->mVirtualAssets[$path] = [$contentType, $fn];
        return $this;
    }


    public function getAssetContent(string $path, &$contentType) : string {
        if (strpos($path, "..") !== false || strpos($path, "~") !== false)
            throw new \InvalidArgumentException("Invalid path: '$path'. Security violation was reported.");

        if (isset ($this->mVirtualAssets[$path])) {
            return ($this->mContext)($this->mVirtualAssets[$path][1], ["path"=>$path]);
        }

        if ($this->mRootDir === null)
            throw new \InvalidArgumentException("VirtualAlias '$path' not registered");

        $useRenderer = GoMimeAssetTenderer::class;
        if (preg_match("/(\\.[a-z]+)$/", $path, $matches)) {
            $ext = $matches[1];
            if (isset ($this->typeToRenderer[$ext]))
                $useRenderer = $this->typeToRenderer[$ext];
        }
        
        $renderer = new $useRenderer();

        $contentType = $renderer->getContentType($path);
        return $renderer->getContent($this->mRootDir . "/". $path);
    }
    
}