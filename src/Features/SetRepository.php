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
    public function saveFeatureSet(Set $set)
    {
        $cache_item = $this->cache->getItem(self::generateFeatureSetCacheKey());
        $cache_item->set($set);

        $this->cache->save($cache_item);
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Features\SetProviderInterface|null $fallback
     *
     * @return \RemotelyLiving\Doorkeeper\Features\Set
     */
    public function getFeatureSet(SetProviderInterface $fallback = null): Set
    {
        $result = $this->cache->getItem(self::generateFeatureSetCacheKey())->get();

        if (!$result && $fallback) {
            $result = $fallback->getFeatureSet();
            $this->saveFeatureSet($fallback->getFeatureSet());
        }

        return ($result) ?? new Set();
    }

    public function deleteFeatureSet()
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
