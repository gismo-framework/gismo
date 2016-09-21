<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 14:30
     */

    namespace  Gismo\Component\Cache\Driver;


    use  Gismo\Component\Cache\CacheItem;


    class FileSystemCacheDriver implements CacheDriver
    {

        private $storeDir;

        private $purgeTime = null;

        public function __construct($rootDir = "/tmp")
        {
            $this->storeDir = $rootDir;

        }


        protected function getCacheKey (string $zoneId, string $key) {
            return $zoneId . "_" . $key;
        }

        protected function getFileName ($zoneId, $key) {
            return $this->storeDir . "/" . $this->getCacheKey($zoneId, $key);
        }


        protected function getPurgeTime ($zoneId) {
            $purgeFile = $this->storeDir . "/purge.{$zoneId}";
            if (file_exists($purgeFile))
                return (int)file_get_contents($purgeFile);
            return 0;
        }


        public function getItem($zoneId, $key)
        {
            $file = $this->getFileName($zoneId, $key);
            if ( ! file_exists($file)) {
                return null;
            }
            $item = unserialize(file_get_contents($file));
            if ( ! $item instanceof CacheItem)
                return null;

            if ($this->getPurgeTime($zoneId) > $item->__getData("created")) {
                return null;
            }
            return $item;
        }

        public function getItems($zoneId, array $keys = array())
        {
            // TODO: Implement getItems() method.
        }

        public function hasItem($zoneId, $key)
        {
            // TODO: Implement hasItem() method.
        }

        public function clear($zoneId)
        {
            file_put_contents($this->storeDir . "/purge.{$zoneId}", time());
        }

        public function deleteItem($zoneId, $key)
        {
            // TODO: Implement deleteItem() method.
        }

        public function deleteItems($zoneId, array $keys)
        {
            // TODO: Implement deleteItems() method.
        }

        public function save($zoneId, CacheItem $item)
        {
            $file = $this->getFileName($zoneId, $item->getKey());
            file_put_contents($file, serialize($item));
        }

        public function saveDeferred($zoneId, CacheItem $item)
        {
            // TODO: Implement saveDeferred() method.
        }

        public function commit()
        {
            // TODO: Implement commit() method.
        }
    }