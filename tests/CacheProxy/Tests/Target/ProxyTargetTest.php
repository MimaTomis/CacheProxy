<?php
namespace CacheProxy\Tests\Target;

use CacheProxy\Target\ProxyTarget;
use CacheProxy\Tests\CacheProxyTarget;

class ProxyTargetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider callableProviders
     *
     * @param callable $fn
     * @param array $arguments
     */
    public function testConstructAndGetters(callable $fn, array $arguments)
    {
        $target = new ProxyTarget($fn, $arguments);

        $this->assertEquals($fn, $target->getCallable());
        $this->assertEquals($arguments, $target->getArguments());
    }

    public function callableProviders()
    {
        $target = new CacheProxyTarget();

        return [
            [[$target, 'a'], [1, 2, 3]],
            [CacheProxyTarget::class.'::b', [4, 5]],
            [$target, [6, 7, 8]]
        ];
    }
}