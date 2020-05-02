<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Utilities;

final class RuntimeCache
{
    /**
     * @var mixed[]
     */
    private $cache = [];

    private ?int $maxCacheItems;

    public function __construct(int $maxCacheItems = null)
    {
        $this->maxCacheItems = $maxCacheItems;
    }

    /**
     * @return mixed|null
     */
    public function get(string $key, callable $fallback = null)
    {
        if ($this->has($key)) {
            return $this->cache[$key];
        }

        if (!$fallback) {
            return null;
        }

        $result = $fallback();

        $this->set($key, $result);

        return $result;
    }

    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    /**
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        if ($this->maxCacheItems !== null && count($this->cache) >= $this->maxCacheItems) {
            array_shift($this->cache);
        }

        $this->cache[$key] = $value;
    }

    public function destroy(string $key): void
    {
        unset($this->cache[$key]);
    }

    public function flush(): void
    {
        $this->cache = [];
    }
}
