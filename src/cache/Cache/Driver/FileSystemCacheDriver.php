<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 14:30
     */

    namespace gismo\Cache\Driver;


    use Psr\Cache\CacheItemInterface;

    class FileSystemCacheDriver implements CacheDriver
    {

        public function getItem($zoneId, $key)
        {
            // TODO: Implement getItem() method.
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
            // TODO: Implement clear() method.
        }

        public function deleteItem($zoneId, $key)
        {
            // TODO: Implement deleteItem() method.
        }

        public function deleteItems($zoneId, array $keys)
        {
            // TODO: Implement deleteItems() method.
        }

        public function save($zoneId, CacheItemInterface $item)
        {
            // TODO: Implement save() method.
        }

        public function saveDeferred($zoneId, CacheItemInterface $item)
        {
            // TODO: Implement saveDeferred() method.
        }

        public function commit()
        {
            // TODO: Implement commit() method.
        }
    }