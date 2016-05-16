<?php
namespace CacheProxy\Tests;

class CacheProxyTarget
{
    public function a()
    {
        return 'a';
    }

    public static function b()
    {
        return 'b';
    }

    public function __invoke()
    {
        return 'c';
    }
}