<?php
namespace CacheProxy\Tests\Adapter;

use CacheProxy\Adapter\YiiCacheAdapter;

class YiiCacheAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cacheInstance;
    /**
     * @var YiiCacheAdapter
     */
    protected $cacheAdapter;

    public function setUp()
    {
        $this->cacheInstance = $this->getMockBuilder('yii\caching\Cache')
            ->setMethods(['get', 'save', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->cacheAdapter = new YiiCacheAdapter($this->cacheInstance);
    }

    /**
     * @dataProvider cacheDataProvider
     *
     * @param string $key
     * @param mixed $data
     * @param int $ttl
     */
    public function testSave($key, $data, $ttl)
    {
        $this->cacheInstance
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->equalTo($key),
                $this->equalTo($data),
                $this->equalTo($ttl)
            );

        $this->cacheAdapter->save($key, $data, $ttl);
    }

    /**
     * @dataProvider cacheDataProvider
     *
     * @param string $key
     * @param mixed $data
     */
    public function testFetch($key, $data)
    {
        $this->cacheInstance
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo($key)
            )
            ->willReturn($data);

        $fetchedData = $this->cacheAdapter->fetch($key);

        $this->assertEquals($data, $fetchedData);
    }

    /**
     * @dataProvider cacheDataProvider
     *
     * @param string $key
     */
    public function testFetchNoData($key)
    {
        $this->cacheInstance
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo($key)
            );

        $fetchedData = $this->cacheAdapter->fetch($key);

        $this->assertNull($fetchedData);
    }

    /**
     * @dataProvider cacheDataProvider
     *
     * @param string $key
     */
    public function testDelete($key)
    {
        $this->cacheInstance
            ->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo($key)
            );

        $this->cacheAdapter->delete($key);
    }

    public function cacheDataProvider()
    {
        return [
            ['key-1', ['a', 'b', 'c' => 'e'], 100],
            ['key-1', 'string', null],
            ['key-1', (object)['a' => 'c', 'b' => 'e'], 100]
        ];
    }
}