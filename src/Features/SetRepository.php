<?php
namespace RemotelyLiving\Doorkeeper\Features;

use Psr\Cache\CacheItemPoolInterface;

class SetRepository
{
    /**
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    private $cache;

    /**
     * @param \Psr\Cache\CacheItemPoolInterface $cache
     */
    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Features\Set $set
     */
    public function saveFeatureSet(Set $set): void
    {
        $cache_item = $this->cache->getItem(self::generateFeatureSetCacheKey());
        $cache_item->set($set);

        $this->cache->save($cache_item);
    }

    /**
     * @param callable|null $fallback
     *
     * @return \RemotelyLiving\Doorkeeper\Features\Set
     */
    public function getFeatureSet(callable $fallback = null): Set
    {
        $result = $this->cache->getItem(self::generateFeatureSetCacheKey())->get();

        if (!$result && $fallback) {
            $result = $fallback();
            $this->saveFeatureSet($result);
        }

        return ($result) ?? new Set();
    }

    public function deleteFeatureSet(): void
    {
        $this->cache->deleteItem(self::generateFeatureSetCacheKey());
    }

    /**
     * @return string
     */
    public static function generateFeatureSetCacheKey(): string
    {
        return md5(self::class);
    }
}
