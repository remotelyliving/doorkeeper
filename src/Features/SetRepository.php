<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Features;

use Psr\Cache;

final class SetRepository
{
    private Cache\CacheItemPoolInterface $cache;

    public function __construct(Cache\CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function saveFeatureSet(Set $set): void
    {
        $cacheItem = $this->cache->getItem(self::generateFeatureSetCacheKey());
        $cacheItem->set($set);

        $this->cache->save($cacheItem);
    }

    public function getFeatureSet(SetProviderInterface $fallback = null): Set
    {
        $result = $this->cache->getItem(self::generateFeatureSetCacheKey())->get();

        if (!$result && $fallback) {
            $result = $fallback->getFeatureSet();
            $this->saveFeatureSet($fallback->getFeatureSet());
        }

        return ($result instanceof Set) ? $result : new Set();
    }

    public function deleteFeatureSet(): void
    {
        $this->cache->deleteItem(self::generateFeatureSetCacheKey());
    }

    public static function generateFeatureSetCacheKey(): string
    {
        return md5(self::class);
    }
}
