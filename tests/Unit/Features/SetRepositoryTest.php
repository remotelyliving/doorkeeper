<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Features;

use Psr\Cache as PSRCache;
use RemotelyLiving\Doorkeeper\Features;
use PHPUnit\Framework\TestCase;

class SetRepositoryTest extends TestCase
{
    private PSRCache\CacheItemPoolInterface $cache;

    private Features\SetRepository $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = $this->createMock(PSRCache\CacheItemPoolInterface::class);
        $this->sut = new Features\SetRepository($this->cache);
    }

    public function testSaveFeatureSet(): void
    {
        $cacheItem = $this->createMock(PSRCache\CacheItemInterface::class);
        $featureSet = new Features\Set();

        $cacheItem->expects($this->once())
          ->method('set')
          ->with($featureSet);

        $this->cache->method('getItem')
          ->with(Features\SetRepository::generateFeatureSetCacheKey())
          ->willReturn($cacheItem);

        $this->cache->expects($this->once())
          ->method('save')
          ->with($cacheItem);

        $this->sut->saveFeatureSet($featureSet);
    }

    public function testGetFeatureSet(): void
    {
        $cacheItem = $this->createMock(PSRCache\CacheItemInterface::class);
        $featureSet = new Features\Set();

        $cacheItem->method('get')
          ->willReturn($featureSet);

        $this->cache->method('getItem')
          ->with(Features\SetRepository::generateFeatureSetCacheKey())
          ->willReturn($cacheItem);

        $this->assertEquals($featureSet, $this->sut->getFeatureSet());
    }

    public function testGetFeatureSetWithCallback(): void
    {
        $cacheItem = $this->createMock(PSRCache\CacheItemInterface::class);

        $cacheItem->method('get')
          ->willReturn(null);

        $this->cache->method('getItem')
          ->with(Features\SetRepository::generateFeatureSetCacheKey())
          ->willReturn($cacheItem);

        $otherProvider = new class implements Features\SetProviderInterface {
            public function getFeatureSet(): Features\Set
            {
                return new Features\Set([new Features\Feature('lioi', true)]);
            }
        };

        $this->assertEquals(
            new Features\Set([new Features\Feature('lioi', true)]),
            $this->sut->getFeatureSet($otherProvider)
        );
    }

    public function testDeleteFeatureSet(): void
    {
        $this->cache->expects($this->once())
          ->method('deleteItem')
          ->with(Features\SetRepository::generateFeatureSetCacheKey());

        $this->sut->deleteFeatureSet();
    }
}
