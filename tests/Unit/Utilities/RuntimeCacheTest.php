<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Utilities;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Utilities;

class RuntimeCacheTest extends TestCase
{
    private Utilities\RuntimeCache $cache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = new Utilities\RuntimeCache();
    }

    public function testGetNotCached(): void
    {
        $this->assertNull($this->cache->get('boop'));
    }

    public function testGetNotCachedUsesCallback(): void
    {
        $fallback = function () {
            return 'beep';
        };

        $this->assertEquals('beep', $this->cache->get('boop', $fallback));
    }

    public function testSetWorksAndGetIgnoresFallback(): void
    {
        $this->cache->set('herp', ['derp']);

        $this->assertEquals(['derp'], $this->cache->get(
            'herp',
            function () {
                return 'wrong';
            }
        ));
    }

    public function testDestroy(): void
    {
        $this->cache->set('boop', 'beep');
        $this->cache->set('herp', 'derp');
        $this->cache->destroy('boop');

        $this->assertEquals('derp', $this->cache->get('herp'));
        $this->assertNull($this->cache->get('boop'));
    }

    public function testSetsCacheLimit(): void
    {
        $cache = new Utilities\RuntimeCache(2);

        $cache->set('first', 1);

        $this->assertEquals(1, $cache->get('first'));

        $cache->set('second', 2);
        $cache->set('third', 3);

        $this->assertNull($cache->get('first'));
        $this->assertEquals(3, $cache->get('third'));
        $this->assertEquals(2, $cache->get('second'));
    }
}
