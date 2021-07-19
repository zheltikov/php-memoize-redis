# php-memoize-redis

A [memoization](https://github.com/zheltikov/php-memoize) cache provider backed by [Redis](https://redis.io/).

## Installation

This library is available via Composer:

```shell
$ composer require zheltikov/php-memoize-redis
```

## Usage

You can create a new instance of `RedisCache`, configure its Redis client and supply it to the memoization wrapper.

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Zheltikov\Memoize\RedisCache;

use function Zheltikov\Memoize\wrap;

// Create a new instance
$cache = new RedisCache();

$cache->setHashName('my_hash_name');    // the hash used to cache the results

// Configure the Redis object
$cache->getRedis()
    // For example, change the serializer to be used.
    // By default, `Redis::SERIALIZER_PHP` will be used by the cache.
    ->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_JSON);

// or you can supply your own Redis object
$my_redis_object = new Redis();
// do some configuration here...
// set it to the cache
$cache->setRedis($my_redis_object);

// finally, use it when memoizing:
function my_expensive_function() { /* ... */ }
$wrapped = wrap('my_expensive_function', $cache);
// enjoy!

```

As you might have noticed, this cache provider works as an inter-process cache, which gives amazing speed-ups!
