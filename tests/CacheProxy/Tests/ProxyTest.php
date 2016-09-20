<?php
namespace CacheProxy\Tests;

use CacheProxy\Adapter\CacheAdapterInterface;
use CacheProxy\Proxy;
use CacheProxy\Target\ProxyTarget;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Proxy
     */
    protected $cacheProxy;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $adapter;

    public function setUp()
    {
        $this->adapter = $this->getMock(CacheAdapterInterface::class);
        $this->cacheProxy = new Proxy($this->adapter);
    }

    /**
     * @dataProvider callableProviders
     *
     * @param callable $fn
     * @param array $arguments
     * @param mixed $return
     */
    public function testProxyTargetWithExistingCache(callable $fn, array $arguments, $return)
    {
        $method = new ProxyTarget($fn, $arguments);

        $this->adapter
            ->expects($this->once())
            ->method('fetch')
            ->willReturn($return);

        $this->adapter
            ->expects($this->never())
            ->method('save');

        $data = $this->cacheProxy->proxyTarget($method);

        $this->assertEquals($return, $data);
    }

    /**
     * @dataProvider callableProviders
     *
     * @param callable $fn
     * @param array $arguments
     * @param mixed $return
     */
    public function testProxyTargetWithNonExistingCache(callable $fn, array $arguments, $return)
    {
        $ttl = mt_rand(0, 1000);
        $method = new ProxyTarget($fn, $arguments);

        $this->adapter
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(null);

        $this->adapter
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->isType('string'),
                $this->equalTo($return),
                $this->equalTo($ttl)
            );

        $data = $this->cacheProxy->proxyTarget($method, null, $ttl);

        $this->assertEquals($return, $data);
    }

    /**
     * @dataProvider callableProviders
     *
     * @param callable $fn
     * @param array $arguments
     */
    public function testFlush(callable $fn, array $arguments)
    {
        $this->adapter
            ->expects($this->once())
            ->method('delete')
            ->with(
                $this->isType('string')
            );

        $method = new ProxyTarget($fn, $arguments);
        $this->cacheProxy->flushTarget($method);
    }

    public function callableProviders()
    {
        $target = new CacheProxyTarget();

        return [
            [[$target, 'a'], [1, 2, 3], 'a'],
            [CacheProxyTarget::class.'::b', [4, 5], 'b'],
            [$target, [6, 7, 8], 'c']
        ];
    }
}