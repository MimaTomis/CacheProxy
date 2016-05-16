<?php
namespace CacheProxy\Target;

class ProxyTarget implements ProxyTargetInterface
{
    /**
     * @var array
     */
    private $arguments;
    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $callable, $arguments)
    {
        $this->arguments = $arguments;
        $this->callable = $callable;
    }

    /**
     * List of arguments for calling method
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Callable of target function or method
     *
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }
}