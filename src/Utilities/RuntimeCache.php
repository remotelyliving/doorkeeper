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

    /**
     * @param string        $key
     * @param callable|null $fallback
     *
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

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value)
    {
        if ((int)$this->max_cache_items > 0 && count($this->cache) >= $this->max_cache_items) {
            array_shift($this->cache);
        }

        $this->cache[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function destroy(string $key)
    {
        unset($this->cache[$key]);
    }

    public function flush()
    {
        $this->cache = [];
    }
}
