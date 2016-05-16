<?php
namespace CacheProxy;

use CacheProxy\Adapter\CacheAdapterInterface;
use CacheProxy\Target\ProxyTargetInterface;

class Proxy implements ProxyInterface
{
    /**
     * @var CacheAdapterInterface
     */
    private $cacheAdapter;

    public function __construct(CacheAdapterInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * Call target function, check cache and update it before
     *
     * @param ProxyTargetInterface $target
     * @param string|null $key
     * @param int $ttl
     *
     * @return mixed
     */
    public function proxyTarget(ProxyTargetInterface $target, $key = null, $ttl = 0)
    {
        $key = $this->generateCacheKey($target, $key);
        $data = $this->cacheAdapter->fetch($key);

        if (!$data) {
            $data = $this->callTargetMethod($target);
            $this->cacheAdapter->save($key, $data, $ttl);
        }

        return $data;
    }

    /**
     * Call method in target context
     *
     * @param ProxyTargetInterface $target
     *
     * @return mixed
     */
    protected function callTargetMethod(ProxyTargetInterface $target)
    {
        return call_user_func_array($target->getCallable(), $target->getArguments());
    }

    /**
     * Generate cache key
     *
     * @param ProxyTargetInterface $target
     * @param string $key
     *
     * @return string
     */
    protected function generateCacheKey(ProxyTargetInterface $target, $key)
    {
        $callable = $target->getCallable();

        if (!($callable instanceof \Closure)) {
            if (is_object($callable))
                $callable = [$callable];
            else if (!is_array($callable))
                $callable = explode('::', $callable);

            $className = is_object($callable[0]) ? get_class($callable[0]) : $callable[0];
            $methodName = isset($callable[1]) ? $callable[1] : '__invoke';

            $key = $className.$methodName.$key;
        }

        return md5($key);
    }
}