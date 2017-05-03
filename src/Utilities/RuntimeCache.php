<?php
namespace RemotelyLiving\Doorkeeper\Utilities;

class RuntimeCache
{

    /**
     * @var mixed[]
     */
    private $cache = [];

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

        $result = null;

        if ($fallback) {
            $result = $fallback();
        }

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
    public function set(string $key, $value): void
    {
        $this->cache[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function destroy(string $key): void
    {
        unset($this->cache[$key]);
    }
}
