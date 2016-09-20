<?php
namespace CacheProxy\Adapter;

use yii\caching\Cache;

class YiiCacheAdapter implements CacheAdapterInterface
{
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Load data from cache for cached method.
     * Return data from cache or null, if no any records found.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function fetch($key)
    {
        $data = $this->cache->get($key);

        return $data ?: null;
    }

    /**
     * Save data into cache for cached method
     *
     * @param string $key
     * @param mixed $data
     * @param int $ttl
     */
    public function save($key, $data, $ttl = 0)
    {
        $this->cache->save($key, $data, $ttl);
    }

    /**
     * Delete data from cache
     *
     * @param string $key
     */
    public function delete($key)
    {
        $this->cache->delete($key);
    }
}