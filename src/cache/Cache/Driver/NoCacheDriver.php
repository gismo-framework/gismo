<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 15:22
     */

    namespace  Gismo\Component\Cache\Driver;


    use  Gismo\Component\Cache\CacheItem;

    class NoCacheDriver implements CacheDriver
    {

        public function getItem($zoneId, $key)
        {
            return null;
        }

        public function getItems($zoneId, array $keys = array())
        {
            return null;
        }

        public function hasItem($zoneId, $key)
        {
            return false;
        }

        public function clear($zoneId)
        {

        }

        public function deleteItem($zoneId, $key)
        {
        }

        public function deleteItems($zoneId, array $keys)
        {
        }

        public function save($zoneId, CacheItem $item)
        {
            return true;
        }

        public function saveDeferred($zoneId, CacheItem $item)
        {
        }

        public function commit()
        {
        }
    }