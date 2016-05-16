<?php
namespace CacheProxy;

use CacheProxy\Target\ProxyTargetInterface;

interface ProxyInterface
{
    /**
     * Call target function, check cache and update it before
     *
     * @param ProxyTargetInterface $target
     * @param string|null $key
     * @param int $ttl
     *
     * @return mixed
     */
    public function proxyTarget(ProxyTargetInterface $target, $key = null, $ttl = 0);
}