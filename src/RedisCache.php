<?php

namespace Zheltikov\Memoize;

use Redis;

/**
 * Class RedisCache
 * @package Zheltikov\Memoize
 */
class RedisCache implements Cache
{
    /**
     * @var \Redis
     */
    private Redis $redis;

    /**
     * @var string
     */
    private string $hash_name;

    public function __construct(?string $hash_name = null, ?string $host = null, int $port = 6379)
    {
        if ($hash_name !== null) {
            $this->setHashName($hash_name);
        }

        $redis = new Redis();
        $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

        if ($host !== null) {
            $redis->connect($host, $port);
        }

        $this->setRedis($redis);
    }

    // -------------------------------------------------------------------------

    /**
     * @return $this
     */
    public function clear(): self
    {
        $this->getRedis()->del($this->getHashName());
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value): self
    {
        $this->getRedis()->hSet($this->getHashName(), $key, $value);
        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->getRedis()->hGet($this->getHashName(), $key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool
    {
        return $this->getRedis()->hExists($this->getHashName(), $key);
    }

    // -------------------------------------------------------------------------

    /**
     * @return \Redis
     */
    public function getRedis(): Redis
    {
        return $this->redis;
    }

    /**
     * @param \Redis $redis
     * @return $this
     */
    public function setRedis(Redis $redis): self
    {
        $this->redis = $redis;
        return $this;
    }

    /**
     * @return string
     */
    public function getHashName(): string
    {
        return $this->hash_name;
    }

    /**
     * @param string $hash_name
     * @return $this
     */
    public function setHashName(string $hash_name): self
    {
        $this->hash_name = $hash_name;
        return $this;
    }
}
