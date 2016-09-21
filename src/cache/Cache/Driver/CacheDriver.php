<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 13:18
     */

    namespace gismo\Cache\Driver;


    use gismo\Cache\CacheItem;


    interface CacheDriver
    {

        public function getItem($zoneId, $key);


        public function getItems($zoneId, array $keys = array());


        public function hasItem($zoneId, $key);


        public function clear($zoneId);


        public function deleteItem($zoneId, $key);


        public function deleteItems($zoneId, array $keys);


        public function save($zoneId, CacheItem $item);


        public function saveDeferred($zoneId, CacheItem $item);


        public function commit();
    }