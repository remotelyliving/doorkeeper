<?php
namespace RemotelyLiving\Doorkeeper\Tests\Features;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use RemotelyLiving\Doorkeeper\Features\Feature;
use RemotelyLiving\Doorkeeper\Features\HydratableFallbackInterface;
use RemotelyLiving\Doorkeeper\Features\Set;
use RemotelyLiving\Doorkeeper\Features\SetProviderInterface;
use RemotelyLiving\Doorkeeper\Features\SetRepository;
use PHPUnit\Framework\TestCase;

class SetRepositoryTest extends TestCase
{
    /**
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var \RemotelyLiving\Doorkeeper\Features\SetRepository
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->cache = $this->createMock(CacheItemPoolInterface::class);
        $this->sut   = new SetRepository($this->cache);
    }

    /**
     * @test
     */
    public function saveFeatureSet()
    {
        $cache_item = $this->createMock(CacheItemInterface::class);
        $feature_set = $this->createMock(Set::class);

        $cache_item->expects($this->once())
          ->method('set')
          ->with($feature_set);

        $this->cache->method('getItem')
            ->with(SetRepository::generateFeatureSetCacheKey())
            ->willReturn($cache_item);

        $this->cache->expects($this->once())
          ->method('save')
          ->with($cache_item);

        $this->sut->saveFeatureSet($feature_set);
    }

    /**
     * @test
     */
    public function getFeatureSet()
    {
        $cache_item  = $this->createMock(CacheItemInterface::class);
        $feature_set = $this->createMock(Set::class);

        $cache_item->method('get')
          ->willReturn($feature_set);

        $this->cache->method('getItem')
          ->with(SetRepository::generateFeatureSetCacheKey())
          ->willReturn($cache_item);

        $this->assertEquals($feature_set, $this->sut->getFeatureSet());
    }

    /**
     * @test
     */
    public function getFeatureSetWithCallback()
    {
        $cache_item = $this->createMock(CacheItemInterface::class);

        $cache_item->method('get')
          ->willReturn(null);

        $this->cache->method('getItem')
          ->with(SetRepository::generateFeatureSetCacheKey())
          ->willReturn($cache_item);

        $other_provider = new class implements SetProviderInterface {
            public function getFeatureSet(): Set
            {
                return new Set([new Feature('lioi', true)]);
            }
        };

        $this->assertEquals(new Set([new Feature('lioi', true)]), $this->sut->getFeatureSet($other_provider));
    }

    /**
     * @test
     */
    public function deleteFeatureSet()
    {
        $this->cache->expects($this->once())
            ->method('deleteItem')
            ->with(SetRepository::generateFeatureSetCacheKey());

        $this->sut->deleteFeatureSet();
    }
}
