<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 04.08.17
 * Time: 10:25
 */

namespace Gismo\Component\Di\Bindables;


use Phore\File\PhoreFile;

class GoDiFile
{

    /**
     * @var PhoreFile
     */
    private $mPath;
    private $mFile;

    private $mData = null;

    private function __construct($uri)
    {
        $this->mFile = \Phore\File\file($uri);
        $this->mPath = $this->mFile->path()->dirname();
    }


    public function getData() {
        if ($this->mData === null)
            $this->mData = $this->mFile->yaml();
        return $this->mData;
    }

    public function xpath($uri) : self {
        $new = $this->mPath->xpath($uri);
        return new self((string)$new);
    }


    public static function Load($uri) : self {
        return new self($uri);
    }


    public function __toString()
    {
        return (string)$this->mFile;
    }

}