<?php
namespace CacheProxy\Adapter;

use Doctrine\Common\Cache\Cache;

class DoctrineCacheAdapter implements CacheAdapterInterface
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
     * Load data from cache for cached method
     *
     * @param string $key
     *
     * @return mixed
     */
    public function fetch($key)
    {
        return $this->cache->contains($key) ?
            $this->cache->fetch($key) :
            null;
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
}