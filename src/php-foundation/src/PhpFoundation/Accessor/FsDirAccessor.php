<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 18:18
     */

    namespace Gismo\Component\PhpFoundation\Accessor;


    class FsDirAccessor extends AbstractAccessor{



        public function __construct($dir)
        {
            parent::__construct($dir);
        }


        /**
         *
         * Calls Function in parameter 2 for every file
         *
         * Prototype of callback:
         *
         * function($file, $relativePath, $absolutePath);
         *
         * @param int $sortingOrder
         * @param callable $callback
         * @return bool
         */
        public function scanRecCallback(callable $callback, $sortingOrder=SCANDIR_SORT_ASCENDING) : bool {
             $scanFn = function ($rootDir, $subDir) use ($sortingOrder, &$result, $callback, &$scanFn) {
                 if ($subDir == "") {
                     $absPath = $rootDir;
                 } else {
                     $absPath = $rootDir . "/" . $subDir;
                 }
                 foreach (scandir($absPath, $sortingOrder) as $file) {
                     if ($file === "." || $file === "..")
                         continue;

                     $relPath = $subDir . "/" . $file;

                     if (is_dir($absPath . "/" . $file)) {
                         if (false === $scanFn($rootDir, $relPath))
                             return false;
                     }

                     if ($callback($file, $relPath, $absPath . "/" . $file) === false)
                         return false;
                 }
                 return true;
            };
            return $scanFn($this->reference, "");
        }


        /**
         * Return list of relative Filenames to rootDir matching $filter
         *
         * @param int $sortingOrder
         * @param null|string|string[] $filter
         * @return string[]
         */
        public function scanRecFile($filter=null, $sortingOrder=SCANDIR_SORT_ASCENDING) : array {
            $result = [];
            if ($filter !== null && ! is_array($filter))
                $filter = [$filter];

            $this->scanRecCallback(function ($file, $relPath, $absolutePath) use (&$result, $filter) {
                if ( ! is_file($absolutePath))
                    return;
                if ($filter !== null) {
                    $match = false;
                    foreach ($filter as $curFilter) {
                        if (fnmatch($curFilter, $file)) {
                            $match = true;
                            break;
                        }
                    }

                    if ( ! $match)
                        return;
                }
                $result[] = $relPath;
            }, $sortingOrder);

            return $result;
        }


    }