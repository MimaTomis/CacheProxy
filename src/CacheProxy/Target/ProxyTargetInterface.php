<?php
namespace CacheProxy\Target;

interface ProxyTargetInterface
{
    /**
     * Callable of target function or method
     *
     * @return callable
     */
    public function getCallable();

    /**
     * List of arguments for calling method
     *
     * @return array
     */
    public function getArguments();
}