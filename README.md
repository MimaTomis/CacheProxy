# PHP Cache Proxy

## About library

Proxying calls to any functions to retrieve data from the cache.

* [Installation](#installation)
* [Using](#using)

### Installation

Run command:

    composer require mima/cache-proxy

Add dependency on your composer.json:

```json
{
    "require": {
        "mima/cache-proxy": "~1.0"
    }
}
```

### Using

Create new instance of `CacheProxy\Proxy` class:

```php
use CacheProxy\Proxy;
use CacheProxy\Adapter\DoctrinCacheAdapter;

$adapter = new DoctrinCacheAdapter($doctrinCacheInstance);
$cacheProxy = new Proxy($adapter);
```

The adapter should implement the interface `CacheProxy\Adapter\CacheAdapterInterface`.
It is used to adapt the interface to caching in your framework.

You can use one of several prepared adapters or create your own:

* `CacheProxy\Adapter\DoctrinCacheAdapter` implementation for doctrine cache
* `CacheProxy\Adapter\YiiCacheAdapter` implementation for yii2

Use instance of `CacheProxy\Proxy` class in your code as it is:

```php
use MyNamespace\AnyClass;
use CacheProxy\Target\ProxyTarger;

$object = new AnyClass();
$target = new ProxyTarget([$object, 'anyMethod'], ['arg1', 'arg2']);

$data = $cacheProxy->proxyTarget($target);
```

Or you may create decorator for your class and depend him from `CacheProxy\Proxy` class:

```php
namespace MyNamespace;

use CacheProxy\Proxy

class AnyDecorator
{
    /**
     * @var AnyClass
     */
    protected $class;
    /**
     * @var Proxy
     */
    protected $proxy;

    public function __construct(AnyClass $class, Proxy $cacheProxy)
    {
        $this->class = $class;
        $this->cacheProxy = $proxy;
    }

    public function anyMethod($arg1, $arg2)
    {
        $target = new ProxyTarget([$this->class, 'anyMethod'], func_get_args());

        return $this->cacheProxy->proxyTarget($target);
    }
}
```

Key for caching data generated on runtime in `CacheProxy\Proxy` class. If you want specify any suffix for cache key, pass second argument to `CacheProxy\Proxy::proxyTarget` method.

```php
$cityId = 1;
$cacheProxy->proxyTarget($target, $cityId);
```

To set the *ttl* for storing data in cache, pass third argument to `CacheProxy\Proxy::proxyTarget` method.

```php
$ttl = 3600;
$cacheProxy->proxyTarget($target, null, $ttl);
```
