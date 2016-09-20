<?php
namespace CacheProxy\Adapter;

interface CacheAdapterInterface
{
    /**
     * Load data from cache for cached method.
     * Return data from cache or null, if no any records found.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function fetch($key);

    /**
     * Save data into cache for cached method
     *
     * @param string $key
     * @param mixed $data
     * @param int $ttl
     */
    public function save($key, $data, $ttl = 0);

    /**
     * Delete data from cache
     *
     * @param string $key
     */
    public function delete($key);
}