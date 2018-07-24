<?php
namespace RemotelyLiving\Doorkeeper\Utilities;

class RuntimeCache
{
    /**
     * @var mixed[]
     */
    private $cache = [];

    /**
     * @var int|null
     */
    private $max_cache_items;

    /**
     * @param int|null $max_cache_items
     */
    public function __construct(int $max_cache_items = null)
    {
        $this->max_cache_items = $max_cache_items;
    }

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

    public function set(string $key, $value)
    {
        if ($this->max_cache_items !== null && count($this->cache) >= $this->max_cache_items) {
            array_shift($this->cache);
        }

        $this->cache[$key] = $value;
    }

    public function destroy(string $key)
    {
        unset($this->cache[$key]);
    }

    public function flush()
    {
        $this->cache = [];
    }
}
