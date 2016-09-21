<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 13:16
     */

    namespace  Gismo\Component\Cache;


    use Gismo\Component\Cache\Driver\CacheDriver;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Accessor\CallableAccessor;
    use Psr\Cache\CacheItemInterface;
    use Psr\Cache\CacheItemPoolInterface;
    use Psr\Cache\InvalidArgumentException;

    class Cache implements CacheItemPoolInterface
    {

        private $mDi;

        /**
         * @var CacheDriver
         */
        private $driver;
        private $zoneId;
        private $ttl;
        private $expireAt;

        private $bypass = false;

        public function __construct(CacheDriver $driver, DiContainer $di, string $zoneId, $ttl=3600, $expireAt=null)
        {
            $this->mDi = $di;
            $this->driver = $driver;
            $this->zoneId = $zoneId;
            $this->ttl = $ttl;
            $this->expireAt = $expireAt;
        }

        /**
         * All items will be reloaded
         *
         * @param bool $bypass
         * @return $this
         */
        public function bypass ($bypass = true) {
            $this->bypass = $bypass;
            return $this;
        }


        public function __invoke(callable $fn, array $parameters=[])
        {
            $ref = (new CallableAccessor($fn))->getReflection();

            $cacheKey = sha1(serialize([$ref->getFileName(), $ref->getStartLine(), $ref->getEndLine(), $parameters]));

            $item = $this->getItem($cacheKey);
            if ($item->isHit()) {
                return $item->get();
            }

            $ret = ($this->mDi)($fn, $parameters);

            $item->expiresAfter($this->ttl)->set($ret);
            $this->save($item);
            return $ret;
        }


        /**
         * Returns a Cache Item representing the specified key.
         *
         * This method must always return a CacheItemInterface object, even in case of
         * a cache miss. It MUST NOT return null.
         *
         * @param string $key
         *   The key for which to return the corresponding Cache Item.
         *
         * @throws InvalidArgumentException
         *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
         *   MUST be thrown.
         *
         * @return CacheItemInterface
         *   The corresponding Cache Item.
         */
        public function getItem($key)
        {
            if ($this->bypass)
                return new CacheItem($key, true);

            $item = $this->driver->getItem($this->zoneId, $key);
            if (! $item instanceof CacheItem)
                return new CacheItem($key, false);
            if ($item->__getData("expires") > time())
                return new CacheItem($key, false);
            $item->__setData("isHit", true);
            return $item;
        }

        /**
         * Returns a traversable set of cache items.
         *
         * @param string[] $keys
         *   An indexed array of keys of items to retrieve.
         *
         * @throws InvalidArgumentException
         *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
         *   MUST be thrown.
         *
         * @return array|\Traversable
         *   A traversable collection of Cache Items keyed by the cache keys of
         *   each item. A Cache item will be returned for each key, even if that
         *   key is not found. However, if no keys are specified then an empty
         *   traversable MUST be returned instead.
         */
        public function getItems(array $keys = array())
        {
            return $this->driver->getItems($this->zoneId, $keys);
        }

        /**
         * Confirms if the cache contains specified cache item.
         *
         * Note: This method MAY avoid retrieving the cached value for performance reasons.
         * This could result in a race condition with CacheItemInterface::get(). To avoid
         * such situation use CacheItemInterface::isHit() instead.
         *
         * @param string $key
         *   The key for which to check existence.
         *
         * @throws InvalidArgumentException
         *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
         *   MUST be thrown.
         *
         * @return bool
         *   True if item exists in the cache, false otherwise.
         */
        public function hasItem($key)
        {
            return $this->driver->hasItem($this->zoneId, $key);
        }

        /**
         * Deletes all items in the pool.
         *
         * @return bool
         *   True if the pool was successfully cleared. False if there was an error.
         */
        public function clear()
        {
            $this->driver->clear($this->zoneId);
        }

        /**
         * Removes the item from the pool.
         *
         * @param string $key
         *   The key to delete.
         *
         * @throws InvalidArgumentException
         *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
         *   MUST be thrown.
         *
         * @return bool
         *   True if the item was successfully removed. False if there was an error.
         */
        public function deleteItem($key)
        {
            $this->driver->deleteItem($this->zoneId, $key);
        }

        /**
         * Removes multiple items from the pool.
         *
         * @param string[] $keys
         *   An array of keys that should be removed from the pool.
         * @throws InvalidArgumentException
         *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
         *   MUST be thrown.
         *
         * @return bool
         *   True if the items were successfully removed. False if there was an error.
         */
        public function deleteItems(array $keys)
        {
            $this->driver->deleteItems($this->zoneId, $keys);
        }

        /**
         * Persists a cache item immediately.
         *
         * @param CacheItemInterface $item
         *   The cache item to save.
         *
         * @return bool
         *   True if the item was successfully persisted. False if there was an error.
         */
        public function save(CacheItemInterface $item)
        {
            if ( ! $item instanceof CacheItem)
                throw new \InvalidArgumentException("save() only accepts CacheItem");

            if ($item->__getData("expires") === null)
                $item->expiresAfter($this->ttl);



            $this->driver->save($this->zoneId, $item);
            return true;
        }

        /**
         * Sets a cache item to be persisted later.
         *
         * @param CacheItemInterface $item
         *   The cache item to save.
         *
         * @return bool
         *   False if the item could not be queued or if a commit was attempted and failed. True otherwise.
         */
        public function saveDeferred(CacheItemInterface $item)
        {
            if ( ! $item instanceof CacheItem)
                throw new \InvalidArgumentException("save() only accepts CacheItem");
            $this->driver->saveDeferred($this->zoneId, $item);
        }

        /**
         * Persists any deferred cache items.
         *
         * @return bool
         *   True if all not-yet-saved items were successfully saved or there were none. False otherwise.
         */
        public function commit()
        {
            $this->driver->commit();
        }
    }